<?php

/**
 * Plugin Name: Latest Posts for Elementor
 * Description: Adds a Latest Posts widget for Elementor page builder
 * Plugin URI:  https://github.com/amm10090/amo/releases/
 * Version:     3.6.0
 * Author:      HuaYangTian
 * Author URI:  https://blog.amoze.cc/
 * Text Domain: latest-posts-for-elementor
 * Domain Path: /languages
 *
 * Requires Plugins: elementor
 * Elementor tested up to: 3.21.0
 * Elementor Pro tested up to: 3.21.0
 */

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path(__FILE__) . 'includes/class-lpfe-i18n.php';

/**
 * Main Latest Posts for Elementor Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Latest_Posts_For_Elementor
{

    const VERSION = '3.6.0';
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION = '7.0';

    private static $_instance = null;
    private $plugin_i18n;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->load_dependencies();
        $this->set_locale();
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
        add_action('init', [$this, 'setup_updater']);
    }

    private function load_dependencies()
    {
        $this->plugin_i18n = new LPFE_i18n();
    }

    private function set_locale()
    {
        add_action('plugins_loaded', array($this->plugin_i18n, 'load_plugin_textdomain'));
    }

    public function on_plugins_loaded()
    {
        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    public function is_compatible()
    {
        // 检查 Elementor 是否已安装和激活
        if (! did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return false;
        }

        // 检查所需的 Elementor 版本
        if (! version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        // 检查所需的 PHP 版本
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        return true;
    }

    public function init()
    {
        // 加载控件文件
        require_once(__DIR__ . '/controls/latest-posts-control.php');

        // 添加插件操作
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);
    }

    public function register_widgets($widgets_manager)
    {
        require_once(__DIR__ . '/widgets/latest-posts-widget.php');
        $widgets_manager->register(new \Latest_Posts_For_Elementor\Widgets\Latest_Posts_Widget());
    }

    public function widget_styles()
    {
        wp_register_style('latest-posts-widget-style', plugins_url('assets/css/latest-posts-widget.css', __FILE__));
        wp_enqueue_style('latest-posts-widget-style');
    }

    public function register_scripts()
    {
        wp_register_script('latest-posts-youtube', plugins_url('assets/js/youtube-widget.js', __FILE__), ['jquery'], self::VERSION, true);
    }

    public function admin_notice_missing_main_plugin()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'latest-posts-for-elementor'),
            '<strong>' . esc_html__('Latest Posts for Elementor', 'latest-posts-for-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'latest-posts-for-elementor') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'latest-posts-for-elementor'),
            '<strong>' . esc_html__('Latest Posts for Elementor', 'latest-posts-for-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'latest-posts-for-elementor') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_php_version()
    {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'latest-posts-for-elementor'),
            '<strong>' . esc_html__('Latest Posts for Elementor', 'latest-posts-for-elementor') . '</strong>',
            '<strong>' . esc_html__('PHP', 'latest-posts-for-elementor') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function setup_updater()
    {
        if (file_exists(__DIR__ . '/plugin-update-checker/plugin-update-checker.php')) {
            require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';

            $myUpdateChecker = PucFactory::buildUpdateChecker(
                'https://github.com/amm10090/amo/',
                __FILE__,
                'latest-posts-for-elementor'
            );

            // 设置包含稳定版本的分支
            $myUpdateChecker->setBranch('main');

            // 启用发布资产
            $myUpdateChecker->getVcsApi()->enableReleaseAssets();
        }
    }
}

// 实例化 Latest_Posts_For_Elementor
Latest_Posts_For_Elementor::instance();
