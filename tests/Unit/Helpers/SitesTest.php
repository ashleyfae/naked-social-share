<?php
/**
 * SitesTest.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 */

namespace Ashleyfae\NakedSocialShare\Tests\Unit\Helpers;

use Ashleyfae\NakedSocialShare\Contracts\SocialSite;
use Ashleyfae\NakedSocialShare\Exceptions\InvalidSiteException;
use Ashleyfae\NakedSocialShare\Helpers\Sites;
use Ashleyfae\NakedSocialShare\Plugin;
use Ashleyfae\NakedSocialShare\SocialSites\Twitter;
use Ashleyfae\NakedSocialShare\Tests\TestCase;
use Mockery;
use ReflectionException;
use WP_Mock;

/**
 * @covers \Ashleyfae\NakedSocialShare\Helpers\Sites
 */
class SitesTest extends TestCase
{
    /**
     * @covers \Ashleyfae\NakedSocialShare\Helpers\Sites::getSiteClassNames()
     * @throws ReflectionException
     */
    public function testCanGetSiteClassNames(): void
    {
        $helper = new Sites();
        $sites  = ['twitter', 'facebook'];

        $this->setInaccessibleProperty($helper, 'sites', $sites);

        WP_Mock::expectFilter('naked-social-share/sites', $sites);

        $this->assertSame($sites, $this->invokeInaccessibleMethod($helper, 'getSiteClassNames'));
    }

    /**
     * @covers \Ashleyfae\NakedSocialShare\Helpers\Sites::getAvailable()
     */
    public function testCanGetAvailable(): void
    {
        $helper = $this->createPartialMock(Sites::class, ['injectLegacyThirdPartySites', 'makeAvailableSites']);

        $helper->expects($this->once())
            ->method('makeAvailableSites')
            ->willReturn(['twitter', 'facebook']);

        $helper->expects($this->once())
            ->method('injectLegacyThirdPartySites')
            ->with(['twitter', 'facebook'])
            ->willReturn(['twitter', 'facebook', 'custom']);

        $this->assertSame(['twitter', 'facebook', 'custom'], $helper->getAvailable());
    }

    /**
     * @covers \Ashleyfae\NakedSocialShare\Helpers\Sites::makeAvailableSites()
     * @throws ReflectionException
     */
    public function testCanMakeAvailableSites(): void
    {
        $helper = $this->createPartialMock(Sites::class, ['getSiteClassNames', 'makeSiteFromClassName']);

        $site = Mockery::mock(SocialSite::class);
        $site->expects('getId')
            ->once()
            ->andReturn('twitter');

        $helper->expects($this->once())
            ->method('getSiteClassNames')
            ->willReturn(['Twitter']);

        $helper->expects($this->once())
            ->method('makeSiteFromClassName')
            ->with('Twitter')
            ->willReturn($site);

        $this->assertSame(['twitter' => $site], $this->invokeInaccessibleMethod($helper, 'makeAvailableSites'));
    }

    /**
     * @covers       \Ashleyfae\NakedSocialShare\Helpers\Sites::makeSiteFromClassName()
     * @dataProvider providerCanMakeSiteFromClassName
     */
    public function canMakeSiteFromClassName(string $className, ?string $expectedException): void
    {
        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $result = $this->invokeInaccessibleMethod(
            Plugin::instance()->make(Sites::class),
            'makeSiteFromClassName',
            $className
        );

        $this->assertInstanceOf(SocialSite::class, $result);
        $this->assertSame($className, get_class($result));
    }

    /** @see canMakeSiteFromClassName */
    public function providerCanMakeSiteFromClassName(): \Generator
    {
        yield 'valid class' => [Twitter::class, null];

        yield 'class does not exist' => [
            '\\Ashleyfae\\NakedSocialShare\\TotallyInvalidClassName',
            InvalidSiteException::class,
        ];

        yield 'class is not instance of SocialSite interface' => [
            Plugin::class,
            InvalidSiteException::class,
        ];
    }

    public function testInjectLegacyThirdPartySitesReturnsSameArrayIfNotFiltered()
    {
        $helper = $this->createPartialMock(Sites::class, []);

        $site = Mockery::mock(SocialSite::class);
        $site->expects('getId')
            ->once()
            ->andReturn('twitter');
        $site->expects('getName')
            ->once()
            ->andReturn('Twitter');

        WP_Mock::expectFilter('naked-social-share/available-filters', ['twitter' => 'Twitter']);

        $this->assertSame(['twitter' => $site], $this->invokeInaccessibleMethod($helper, 'injectLegacyThirdPartySites', ['twitter' => $site]));
    }
}
