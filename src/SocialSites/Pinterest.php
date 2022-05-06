<?php
/**
 * Pinterest.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 * @since     2.0
 */

namespace Ashleyfae\NakedSocialShare\SocialSites;

use Ashleyfae\NakedSocialShare\Contracts\SocialSite;

class Pinterest implements SocialSite
{

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return 'pinterest';
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return __('Pinterest', 'naked-social-share');
    }
}
