<?php
/**
 * SocialSite.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 * @since     2.0
 */

namespace Ashleyfae\NakedSocialShare\Contracts;

interface SocialSite {

    /**
     * Unique ID (slug) for the site.
     *
     * @since 2.0
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Display name for the site.
     *
     * @since 2.0
     *
     * @return string
     */
    public function getName(): string;

}
