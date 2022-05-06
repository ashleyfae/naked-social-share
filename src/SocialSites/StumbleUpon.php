<?php
/**
 * StumbleUpon.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 * @since     2.0
 */

namespace Ashleyfae\NakedSocialShare\SocialSites;

use Ashleyfae\NakedSocialShare\Contracts\DeprecatedSocialSite;
use Ashleyfae\NakedSocialShare\Contracts\SocialSite;

/**
 * @depecated 2.0
 */
class StumbleUpon implements SocialSite, DeprecatedSocialSite
{

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return 'stumbleupon';
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return __( 'StumbleUpon', 'naked-social-share' );
    }
}
