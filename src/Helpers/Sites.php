<?php
/**
 * Sites.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 * @since     2.0
 */

namespace Ashleyfae\NakedSocialShare\Helpers;

use Ashleyfae\NakedSocialShare\Contracts\SocialSite;
use Ashleyfae\NakedSocialShare\Plugin;
use Ashleyfae\NakedSocialShare\SocialSites\Facebook;
use Ashleyfae\NakedSocialShare\SocialSites\LinkedIn;
use Ashleyfae\NakedSocialShare\SocialSites\Pinterest;
use Ashleyfae\NakedSocialShare\SocialSites\StumbleUpon;
use Ashleyfae\NakedSocialShare\SocialSites\Twitter;

class Sites
{
    protected array $sites = [
        Twitter::class,
        Facebook::class,
        Pinterest::class,
        StumbleUpon::class,
        LinkedIn::class,
    ];

    public function getAvailable(): array
    {
        // @todo filter this
        return $this->injectLegacyThirdPartySites(
            $this->makeAvailableSites()
        );
    }

    /**
     * Creates the classes for each available site and returns the array.
     *
     * @since 2.0.0
     *
     * @return SocialSite[] Sites, keyed by ID.
     */
    protected function makeAvailableSites(): array
    {
        $sites = [];

        foreach ($this->sites as $siteClass) {
            /** @var SocialSite $site */
            $site = Plugin::instance()->make($siteClass);

            $sites[$site->getId()] = $site;
        }

        return $sites;
    }

    /**
     * Executes the legacy filter to inject any new third party sites.
     *
     * @param  SocialSite[]  $sites
     *
     * @return SocialSite
     */
    protected function injectLegacyThirdPartySites(array $sites): array
    {
        $backwardsCompatSites = [];
        foreach ($sites as $site) {
            $backwardsCompatSites[$site->getId()] = $site->getName();
        }

        /**
         * Filters the sites so third parties can hook in to add their own.
         */
        $backwardsCompatSites = apply_filters('naked-social-share/available-sites', $backwardsCompatSites);

        // Removes any sites that have been removed.
        $sites = array_intersect_key($sites, $backwardsCompatSites);
    }
}
