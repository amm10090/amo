<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://blog.amoze.cc/
 * @since      3.0.0
 *
 * @package    Latest_Posts_For_Elementor
 * @subpackage Latest_Posts_For_Elementor/includes
 */

class LPFE_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    3.0.0
     */
    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'latest-posts-for-elementor',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }

}