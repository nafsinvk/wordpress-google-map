<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/nafsinvk
 * @since             1.0.0
 * @package           Nafs_Gmap
 *
 * @wordpress-plugin
 * Plugin Name:       Google map Widget
 * Plugin URI:        https://github.com/nafsinvk/wordpress-google-map
 * Description:       Simple Google Map plugin, can be used free across the web. 
 * Version:           2.2.1
 * Author:            Nafsin Vattakandy
 * Author URI:        nafsi.in/
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
function nafs_gmap_cat_init() {
	// create a new taxonomy
	register_taxonomy(
		'nafs_gmap_cat',
		'nafs_gmap_item',
		array(
			'label' => __( 'Map Category' ),
			'rewrite' => array( 'slug' => 'nafs_gmap_cat' ),
			'show_ui' => true
		)
	);
}
function nafs_gmap_custom_post_type()
{
	
    register_post_type('nafs_gmap_item',
                       [
                           'labels'      => [
                               'name'          => __('Maps'),
                               'singular_name' => __('Map'),
							   'add_new_item'       => __( 'Add New Map', 'textdomain' ),
									'new_item'           => __( 'New Map', 'textdomain' ),
									'edit_item'          => __( 'Edit Map', 'textdomain' ),
									'view_item'          => __( 'View Map', 'textdomain' ),
									'all_items'          => __( 'All Maps', 'textdomain' ),
									'search_items'       => __( 'Search Map', 'textdomain' ),
							   'featured_image'        => _x( 'Book Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain' ),
        					'featured_image'        => _x( 'Map Marker image', 'Overrides default marker image', 'textdomain' ),
							'set_featured_image'    => _x( 'Set Map Marker image', '', 'textdomain' ),
        					'remove_featured_image' => _x( 'Remove Map Marker image', '', 'textdomain' ),
        					'use_featured_image'    => _x( 'Use as Map Marker image', '', 'textdomain' ),
                           ],
                           'public'      => true,
                           'has_archive' => true,
                           'rewrite'     => ['slug' => 'nafs_g_maps'], // my custom slug
						   'supports' => array( 'title', 'editor', 'thumbnail' ),
						   'menu_icon' => 'dashicons-location',
						   'taxonomies'  => array( 'Location Categories')
                       ]
    );
}

function nafs_gmap_add_taxonomy_filters() {
	global $typenow;
 
	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array('nafs_gmap_cat');
 
	// must set this to the post type you want the filter(s) displayed on
	if( $typenow == 'nafs_gmap_item' ){
 
		foreach ($taxonomies as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			if(count($terms) > 0) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Show All $tax_name</option>";
				foreach ($terms as $term) { 
					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
				}
				echo "</select>";
			}
		}
	}
}




function run_nafs_gmap() {
	$plugin = new Nafs_Gmap();
	$plugin->run();
	$options = get_option('naf_gmap_option_name', 'default text');
	if(is_array($options) && isset($options['naf_gmap_post_type_required']) and $options['naf_gmap_post_type_required']==1)
	{
	add_action('init', 'nafs_gmap_custom_post_type');
	add_action( 'init', 'nafs_gmap_cat_init' );
	add_action( 'restrict_manage_posts', 'nafs_gmap_add_taxonomy_filters' );
	}
	add_action( 'add_meta_boxes', array($plugin, 'lat_long_box') );
	add_action( 'save_post', array($plugin,'lat_long_box_save'));
}
run_nafs_gmap();


