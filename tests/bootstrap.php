<?php
/**
 * bootstrap.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 * @since     2.0
 */

const ABSPATH = 'foo/bar';

require_once dirname(__DIR__).'/vendor/autoload.php';

WP_Mock::setUsePatchwork( true);
WP_Mock::bootstrap();
