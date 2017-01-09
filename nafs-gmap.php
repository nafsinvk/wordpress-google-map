<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              nafsin.info/
 * @since             1.0.0
 * @package           Nafs_Gmap
 *
 * @wordpress-plugin
 * Plugin Name:       Google map Widget
 * Plugin URI:        nafsin.info/wp/plugins/nafs-gmap
 * Description:       Simple Google Map plugin, can be used free across the web. 
 * Version:           1.0.0
 * Author:            Nafsin Vattakandy
 * Author URI:        nafsin.info/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nafs-gmap
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nafs-gmap-activator.php
 */
function activate_nafs_gmap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nafs-gmap-activator.php';
	Nafs_Gmap_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nafs-gmap-deactivator.php
 */
function deactivate_nafs_gmap() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nafs-gmap-deactivator.php';
	Nafs_Gmap_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nafs_gmap' );
register_deactivation_hook( __FILE__, 'deactivate_nafs_gmap' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nafs-gmap.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function nafs_gmap_custom_post_type()
{
    register_post_type('nafs_gmap_item',
                       [
                           'labels'      => [
                               'name'          => __('Maps'),
                               'singular_name' => __('Map'),'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        					'featured_image'        => _x( 'Map Marker image', 'Overrides default marker image', 'textdomain' ),
							'set_featured_image'    => _x( 'Set Map Marker image', '', 'textdomain' ),
        					'remove_featured_image' => _x( 'Remove Map Marker image', '', 'textdomain' ),
        					'use_featured_image'    => _x( 'Use as Map Marker image', '', 'textdomain' ),
                           ],
                           'public'      => true,
                           'has_archive' => true,
                           'rewrite'     => ['slug' => 'nafs_g_maps'], // my custom slug
						   'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail' ),
						   'menu_icon' => 'dashicons-location'
                       ]
    );
}



function run_nafs_gmap() {
	$plugin = new Nafs_Gmap();
	$plugin->run();
	add_action('init', 'nafs_gmap_custom_post_type');
	add_action( 'add_meta_boxes', array($plugin, 'lat_long_box') );
	add_action( 'save_post', array($plugin,'lat_long_box_save'));
}
run_nafs_gmap();


