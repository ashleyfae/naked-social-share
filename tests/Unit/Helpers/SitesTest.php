<?php
/**
 * SitesTest.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 */

namespace Ashleyfae\NakedSocialShare\Tests\Unit\Helpers;

use Ashleyfae\NakedSocialShare\Helpers\Sites;
use Ashleyfae\NakedSocialShare\Tests\TestCase;

/**
 * @covers \Ashleyfae\NakedSocialShare\Helpers\Sites
 */
class SitesTest extends TestCase
{
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
}
