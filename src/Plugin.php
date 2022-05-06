<?php
/**
 * Plugin.php
 *
 * @package   naked-social-share
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2
 */

namespace Ashleyfae\NakedSocialShare;

use Ashleyfae\LaravelContainer\Container;

/**
 * @mixin Container
 *
 * @since 2.0 Renamed from `Naked_Social_Share`
 */
class Plugin
{
    /**
     * Single instance of the plugin.
     *
     * @var Plugin|null
     * @since 1.0.0
     */
    protected static ?Plugin $instance = null;

    /**
     * @var Container
     * @since 2.0.0
     */
    protected Container $container;

    /**
     * Constructor
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * Retrieves the single instance of this class.
     *
     * @since  1.0.0
     * @return static
     */
    public static function instance(): Plugin
    {
        if (! isset(static::$instance) && ! static::$instance instanceof Plugin) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Throw error on object clone.
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @since  1.0.0
     * @return void
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
    }

    /**
     * Disable unserializing of the class.
     *
     * @since  1.0.0
     * @return void
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
    }

    /**
     * Forwards method calls to the container.
     *
     * @since 2.0.0
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->container, $name], $arguments);
    }

    /**
     * Boots the plugin.
     *
     * @since 2.0.0
     */
    public function boot(): void
    {
        $this->includes();

        // Backwards compatibility for renamed classes.
        class_alias(Plugin::class, 'Naked_Social_Share');

        add_action('plugins_loaded', [static::$instance, 'load_textdomain']);
    }

    /**
     * Include Required Files
     *
     * @since  1.0.0
     * @return void
     */
    private function includes()
    {
        global $nss_options;

        // Settings.
        require_once NSS_PLUGIN_DIR.'includes/admin/settings/register-settings.php';
        if (empty($nss_options)) {
            $nss_options = nss_get_settings();
        }

        require_once NSS_PLUGIN_DIR.'includes/class-naked-social-share-buttons.php';
        require_once NSS_PLUGIN_DIR.'includes/functions.php';

        if (is_admin()) {
            require_once NSS_PLUGIN_DIR.'includes/admin/admin-pages.php';
            require_once NSS_PLUGIN_DIR.'includes/admin/upgrades.php';
            require_once NSS_PLUGIN_DIR.'includes/admin/settings/display-settings.php';
        }

        require_once NSS_PLUGIN_DIR.'includes/install.php';
    }

    /**
     * Loads the plugin language files.
     *
     * @since  1.0.0
     * @return void
     */
    public function load_textdomain()
    {
        $lang_dir = dirname(plugin_basename(NSS_PLUGIN_FILE)).'/lang/';
        $lang_dir = apply_filters('naked-social-share/languages-directory', $lang_dir);
        load_plugin_textdomain('naked-social-share', false, $lang_dir);
    }
}
