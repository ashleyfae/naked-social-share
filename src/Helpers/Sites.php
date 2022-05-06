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
use Ashleyfae\NakedSocialShare\Exceptions\InvalidSiteException;
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

    /**
     * Returns the list of registered site class names.
     *
     * @since 2.0.0
     *
     * @return array
     */
    protected function getSiteClassNames(): array
    {
        /**
         * Filters the sites.
         *
         * @since 2.0.0
         */
        return apply_filters('naked-social-share/sites', $this->sites);
    }

    /**
     * Returns the objects of all available sites.
     *
     * @since 2.0.0
     *
     * @return SocialSite[]
     */
    public function getAvailable(): array
    {
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

        foreach ($this->getSiteClassNames() as $siteClass) {
            try {
                $site = $this->makeSiteFromClassName($siteClass);

                $sites[$site->getId()] = $site;
            } catch (InvalidSiteException $e) {
                error_log($e->getMessage());
            }
        }

        return $sites;
    }

    /**
     * Creates a SocialSite object from a class name.
     *
     * @since 2.0.0
     *
     * @param  string  $siteClass
     *
     * @return SocialSite
     * @throws InvalidSiteException
     */
    protected function makeSiteFromClassName(string $siteClass): SocialSite
    {
        if (! class_exists($siteClass)) {
            throw new InvalidSiteException("The {$siteClass} class does not exist.");
        }

        $site = Plugin::instance()->make($siteClass);

        if (! $site instanceof SocialSite) {
            throw new InvalidSiteException(sprintf(
                "The {$siteClass} class must implement the %s interface.",
                SocialSite::class
            ));
        }

        return $site;
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

        // Add any extra sites.

        return $sites;
    }

    protected function makeThirdPartySocialSite(string $siteId, string $siteName): SocialSite
    {

    }
}
