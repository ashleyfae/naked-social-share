<?php
/**
 * Facebook.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 * @since     2.0
 */

namespace Ashleyfae\NakedSocialShare\SocialSites;

use Ashleyfae\NakedSocialShare\Contracts\SocialSite;

class Facebook implements SocialSite
{

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return 'facebook';
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return __('Facebook', 'naked-social-share');
    }
}
