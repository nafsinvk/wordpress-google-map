<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       nafsin.info/
 * @since      1.0.0
 *
 * @package    Nafs_Gmap
 * @subpackage Nafs_Gmap/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Nafs_Gmap
 * @subpackage Nafs_Gmap/includes
 * @author     Nafsin Vattakandy <nafsinvk@gmail.com>
 */
class Nafs_Gmap_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'nafs-gmap',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
