<?php
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Plugin Name: Latest Posts for Elementor
 * Description: Adds a Latest Posts widget for Elementor page builder
 * Plugin URI:  https://github.com/amm10090/amo/releases/
 * Version:     1.1.0
 * Author:      HuaYangTian
 * Author URI:  https://blog.amoze.cc/
 * Text Domain: latest-posts-for-elementor
 *
 * Requires Plugins: elementor
 * Elementor tested up to: 3.21.0
 * Elementor Pro tested up to: 3.21.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Main Latest Posts for Elementor Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Latest_Posts_For_Elementor {

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '1.1.0';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Latest_Posts_For_Elementor The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return Latest_Posts_For_Elementor An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
        add_action( 'init', [ $this, 'setup_updater' ] );
    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'latest-posts-for-elementor' );
    }

    /**
     * On Plugins Loaded
     *
     * Checks if Elementor has loaded, and performs some compatibility checks.
     * If All checks pass, inits the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function on_plugins_loaded() {

        if ( $this->is_compatible() ) {
            add_action( 'elementor/init', [ $this, 'init' ] );
        }

    }

    /**
     * Compatibility Checks
     *
     * Checks if the installed version of Elementor meets the plugin's minimum requirement.
     * Checks if the installed PHP version meets the plugin's minimum requirement.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function is_compatible() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return false;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return false;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return false;
        }

        return true;

    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init() {
    
        $this->load_textdomain();

        // Add Plugin actions
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'latest-posts-for-elementor' ),
            '<strong>' . esc_html__( 'Latest Posts for Elementor', 'latest-posts-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'latest-posts-for-elementor' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'latest-posts-for-elementor' ),
            '<strong>' . esc_html__( 'Latest Posts for Elementor', 'latest-posts-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'latest-posts-for-elementor' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'latest-posts-for-elementor' ),
            '<strong>' . esc_html__( 'Latest Posts for Elementor', 'latest-posts-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'latest-posts-for-elementor' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Register Widgets
     *
     * Load widgets files and register new Elementor widgets.
     *
     * Fired by `elementor/widgets/register` action hook.
     *
     * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets( $widgets_manager ) {
        require_once( __DIR__ . '/widgets/latest-posts-widget.php' );
        $widgets_manager->register( new \Latest_Posts_For_Elementor\Widgets\Latest_Posts_Widget() );
    }

    /**
     * Widget Styles
     *
     * Load required plugin core files.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function widget_styles() {
        wp_register_style( 'latest-posts-widget-style', plugins_url( 'assets/css/latest-posts-widget.css', __FILE__ ) );
        wp_enqueue_style( 'latest-posts-widget-style' );
    }

    /**
     * Setup Updater
     *
     * Configure the plugin updater.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function setup_updater() {
        if (file_exists(__DIR__ . '/plugin-update-checker/plugin-update-checker.php')) {
            require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';

            $myUpdateChecker = PucFactory::buildUpdateChecker(
                'https://github.com/amm10090/amo/',
                __FILE__,
                'latest-posts-for-elementor'
            );

            // Set the branch that contains the stable release.
            $myUpdateChecker->setBranch('main');

            // Enable release assets
            $myUpdateChecker->getVcsApi()->enableReleaseAssets();
        }
    }
}

// Instantiate Latest_Posts_For_Elementor.
Latest_Posts_For_Elementor::instance();