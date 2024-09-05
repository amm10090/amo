<?php

/**
 * Plugin Name: Latest Posts for Elementor
 * Description: Adds a Latest Posts widget for Elementor page builder
 * Plugin URI:  https://github.com/amm10090/amo/releases/
 * Version:     5.5.2
 * Author:      HuaYangTian
 * Author URI:  https://blog.amoze.cc/
 * Text Domain: latest-posts-for-elementor
 * Domain Path: /languages
 *
 * Requires Plugins: elementor
 * Elementor tested up to: 3.21.0
 * Elementor Pro tested up to: 3.21.0
 */

if (! defined('ABSPATH')) {
    exit; // 禁止直接访问
}

// 自动加载函数
spl_autoload_register(function ($class) {
    // 项目特定的命名空间前缀
    $prefix = 'Latest_Posts_For_Elementor\\';

    // 命名空间前缀的基本目录
    $base_dir = __DIR__ . '/';

    // 如果类使用命名空间前缀
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // 不是我们的类，移动到下一个注册的自动加载器
        return;
    }

    // 获取相对类名
    $relative_class = substr($class, $len);

    // 将命名空间前缀替换为基本目录，用目录分隔符替换命名空间分隔符，并在文件名中附加.php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // 如果文件存在，需要它
    if (file_exists($file)) {
        require $file;
    }
});

require_once plugin_dir_path(__FILE__) . 'includes/class-lpfe-i18n.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Main Latest Posts for Elementor Class
 *
 * 初始化并运行插件的主类。
 *
 * @since 1.0.0
 */
final class Latest_Posts_For_Elementor
{
    // 插件版本
    const VERSION = '5.5.2';
    // 最低要求的Elementor版本
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    // 最低要求的PHP版本
    const MINIMUM_PHP_VERSION = '7.0';

    // 单例实例
    private static $_instance = null;
    // 国际化对象
    private $plugin_i18n;
    // 更新检查器对象
    private $myUpdateChecker;

    /**
     * 获取单例实例
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->load_dependencies();
        $this->set_locale();
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
        add_action('init', [$this, 'setup_updater']);
    }

    /**
     * 加载依赖
     */
    private function load_dependencies()
    {
        $this->plugin_i18n = new LPFE_i18n();
    }

    /**
     * 设置语言环境
     */
    private function set_locale()
    {
        add_action('plugins_loaded', array($this->plugin_i18n, 'load_plugin_textdomain'));
    }

    /**
     * 插件加载完成后的操作
     */
    public function on_plugins_loaded()
    {
        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    /**
     * 检查兼容性
     */
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
            add_action('admin_notice

s', [$this, 'admin_notice_minimum_php_version']);
            return false;
        }

        return true;
    }

    /**
     * 初始化插件
     */
    public function init()
    {
        // 加载控件文件
        require_once(__DIR__ . '/controls/latest-posts-control.php');

        // 加载小部件文件
        require_once(__DIR__ . '/widgets/latest-posts-widget.php');

        // 添加插件操作
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts']);

        // 注册AJAX动作
        add_action('wp_ajax_latest_posts_load_more', ['Latest_Posts_For_Elementor\Widgets\Latest_Posts_Widget', 'ajax_load_more']);
        add_action('wp_ajax_nopriv_latest_posts_load_more', ['Latest_Posts_For_Elementor\Widgets\Latest_Posts_Widget', 'ajax_load_more']);
    }

    /**
     * 注册小部件
     */
    public function register_widgets($widgets_manager)
    {
        $widgets_manager->register(new \Latest_Posts_For_Elementor\Widgets\Latest_Posts_Widget());
    }

    /**
     * 注册小部件样式
     */
    public function widget_styles()
    {
        wp_register_style('latest-posts-widget-style', plugins_url('assets/css/latest-posts-widget.css', __FILE__));
        wp_enqueue_style('latest-posts-widget-style');
    }

    /**
     * 注册脚本
     */
    public function register_scripts()
    {
        wp_register_script('latest-posts-youtube', plugins_url('assets/js/youtube-widget.js', __FILE__), ['jquery'], self::VERSION, true);
        wp_register_script('latest-posts-ajax', plugins_url('assets/js/latest-posts-ajax.js', __FILE__), ['jquery'], self::VERSION, true);
        wp_enqueue_script('latest-posts-ajax');
        wp_localize_script('latest-posts-ajax', 'latest_posts_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('latest_posts_load_more')
        ]);
    }

    /**
     * 缺少主插件的管理通知
     */
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

    /**
     * Elementor 版本过低的管理通知
     */
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

    /**
     * PHP 版本过低的管理通知
     */
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

    /**
     * 设置更新检查器
     */
    public function setup_updater()
    {
        if (file_exists(__DIR__ . '/plugin-update-checker/plugin-update-checker.php')) {
            require 'plugin-update-checker/plugin-update-checker.php';

            $this->myUpdateChecker = PucFactory::buildUpdateChecker(
                'https://github.com/amm10090/amo/',
                __FILE__,
                'latest-posts-for-elementor'
            );

            // 设置包含稳定版本的分支
            $this->myUpdateChecker->setBranch('main');

            // 启用发布资产
            $this->myUpdateChecker->getVcsApi()->enableReleaseAssets();
        }
    }
}

// 实例化 Latest_Posts_For_Elementor
Latest_Posts_For_Elementor::instance();
