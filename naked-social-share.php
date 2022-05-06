<?php
/**
 * Plugin Name: Naked Social Share
 * Plugin URI: https://shop.nosegraze.com/product/naked-social-share/
 * Description: Simple, unstyled social share icons for theme designers.
 * Version: 1.5.2
 * Author: Nose Graze
 * Author URI: https://www.nosegraze.com
 * License: GPL2
 * Text Domain: naked-social-share
 * Domain Path: lang
 *
 * Requires at least: 3.0
 * Requires PHP: 7.4
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Exit if accessed directly
use Ashleyfae\NakedSocialShare\Plugin;

if (! defined('ABSPATH')) {
    exit;
}

if (version_compare(phpversion(), '7.4', '<')) {
    return;
}

// Plugin version.
const NSS_VERSION = '1.5.2';

// Plugin Folder Path.
define('NSS_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Plugin Folder URL.
define('NSS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Plugin Root File.
const NSS_PLUGIN_FILE = __FILE__;

require_once __DIR__.'/vendor/autoload.php';

/**
 * Loads the whole plugin.
 *
 * @since 1.0.0
 * @return Plugin
 */
function Naked_Social_Share()
{
    return Plugin::instance();
}

Naked_Social_Share()->boot();
