<?php
/**
 * Plugin Name: TinyMCE Custom Class
 * Plugin URI: http://sitepoint.com
 * Version: 1.0
 * Author: Tim Carr
 * Author URI: http://www.n7studios.co.uk
 * Description: TinyMCE Plugin to wrap selected text in a custom CSS class, within the Visual Editor
 * License: GPL2
 */
 
class TinyMCE_Custom_Class {
 
    /**
    * Constructor. Called when the plugin is initialised.
    */
    function __construct() {
 
 		if ( is_admin() ) {
		    add_action( 'init', array( &$this, 'setup_tinymce_plugin' ) );
		    add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts_css' ) );
		    add_action( 'admin_print_footer_scripts', array( &$this, 'admin_footer_scripts' ) );
		}
		$this->options = get_option('naf_gmap_option_name', 'default text');

    }

    /**
	* Check if the current user can edit Posts or Pages, and is using the Visual Editor
	* If so, add some filters so we can register our plugin
	*/
	function setup_tinymce_plugin() {
	 
	    // Check if the logged in WordPress User can edit Posts or Pages
	    // If not, don't register our TinyMCE plugin
	    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
	        return;
	    }
	 
	    // Check if the logged in WordPress User has the Visual Editor enabled
	    // If not, don't register our TinyMCE plugin
	    if ( get_user_option( 'rich_editing' ) !== 'true' ) {
	        return;
	    }
	 
	    // Setup some filters
	    add_filter( 'mce_external_plugins', array( &$this, 'add_tinymce_plugin' ) );
	    add_filter( 'mce_buttons', array( &$this, 'add_tinymce_toolbar_button' ) );
	 
	}

	/**
	 * Adds a TinyMCE plugin compatible JS file to the TinyMCE / Visual Editor instance
	 *
	 * @param array $plugin_array Array of registered TinyMCE Plugins
	 * @return array Modified array of registered TinyMCE Plugins
	 */
	function add_tinymce_plugin( $plugin_array ) {
		// Check if the logged in WordPress User can edit Posts or Pages
	    
	 $display_prebuilt = (is_array($this->options) && isset($this->options['naf_gmap_post_type_required']) and $this->options['naf_gmap_post_type_required']==1)?true:false;
	    // Check if the logged in WordPress User has the Visual Editor enabled
	    // If not, don't register our TinyMCE plugin
	    if ( get_user_option( 'rich_editing' ) == 'true' && current_user_can( 'edit_posts' ) &&current_user_can( 'edit_pages' )) {
	 add_thickbox();
	 echo '<table id="map-insert-info" style="height:0; display:block;"><tbody><tr><td>	<div id="my-content-id" style="display:none;">
	 <div class="wrap">
	 <div id="tabs-container">
    <ul class="tabs-menu">';
     echo '   	<li class="current"><a href="#tab-1">Insert New</a></li>';
	if($display_prebuilt):
     echo '       <li><a href="#tab-2">Insert from pre-built</a></li>';
     echo '   	<li><a href="#tab-3">Insert Category</a></li>';
	 endif;
     echo '   	</ul>
		 	';
			echo '<div class="tab"><div id="tab-1" class="tab-content">';
			echo $current_maps = $this->getForm();
			echo '</div>';
			if($display_prebuilt):
			echo '<div id="tab-2" class="tab-content">';
			$current_maps = $this->getMaps();
			if($current_maps):
			echo '<p>You can select from the maps, whih has alredy been created.</p>';
			echo $current_maps;
			else:
			echo '<h4>System cannot find any content of type <em>map</em>.</h4>
			<ul>
			<li>Please create a map and then reload this window.</li>
			</ul>';
			endif;
			echo '</div>';
			echo '<div id="tab-3" class="tab-content">';
			$current_maps = $this->getMapCats();
			if($current_maps):
			echo '<p>You can select a category, and all locations in that category will then be ploted in a single map.</p>';
			echo $current_maps;
			echo '</div>';
			else:
			echo '<h4>System cannot find any category, which is being assigned to a map.</h4>
			<ul>
			<li>Please make sure that, you have a category under the contet type <em>map</em></li>
			<li>Category will appear, only when it is being assigned to any content of type <em>map</em></li>
			</ul>';
			endif;
			endif;
			echo '</div>';
   echo '
	</div>
		 </div></div></td></tr><tr><td>';

	echo '<a title="Create a new map/ Select from pre-built" style="width:0; height:0; font-size:0; color:transparent;	" href="#TB_inline?width=600&height=550&inlineId=my-content-id" class="thickbox" id="nafs_gmap_popuptrigger">View my inline content!</a></td></tr></tbody></table>';
	    $plugin_array['custom_class'] = plugin_dir_url( __FILE__ ) . 'tinymce-custom-class.js';
	    return $plugin_array;
		}
	}
function getForm()
{
	return '<div class="form-wrap-nafs_gmap">Please fill in, all the boxes with appropriate values.
	<div class="nafs-form-el-wrap">
	<label for="nafs-lat">Latitude</label>
	<input type="text" name="nafs-lat" id="nafs-lat" class="form-input-tip">
	</div>
	<div class="nafs-form-el-wrap">
	<label for="nafs-lng">Longitude</label>
	<input type="text" name="nafs-lng" id="nafs-lng" class="form-input-tip">
	</div>
	<div class="nafs-form-el-wrap">
	<label for="nafs-width">Width</label>
	<input type="text" name="nafs-width" id="nafs-width" class="form-input-tip" width="" placeholder="100%">
	<div class="help">Mention number and unit (800px/100%/5em etc.)</div>
	</div>
	<div class="nafs-form-el-wrap">
	<label for="nafs-height">Height</label>
	<input type="text" name="nafs-height" id="nafs-height" class="form-input-tip" placeholder="300px">
	<div class="help">Mention number and unit (300px/5em etc.) <em>Please don\'t use "%" for height</em></div>
	</div>
	<div class="nafs-form-el-wrap">
	<label for="nafs-zoom">Zoom</label>
	<input type="number" name="nafs-zoom" id="nafs-zoom" class="form-input-tip" min="3" max="21">
	<div class="help">Min is 3 and Max is 21</em></div>
	</div>
	<div class="nafs-form-el-wrap">
	<label for="nafs-center">Center</label>
	<input type="text" name="nafs-center" id="nafs-center" class="form-input-tip" placeholder="26.217908, 50.553557">
	<div class="help">Enter a lat, lng value to keep it as center of the map</em></div>
	</div>
	<div class="nafs-form-el-wrap">
	<label for="nafs-insert-custom">&nbsp;</label>
	<button class="button custom-lat-lng" id="nafs-insert-custom">INSERT</button>
	</div>
	</div>';
}
function getMapCats()
{
	$row_count=0;
	$map_cats = get_categories( array(
							'taxonomy' => 'nafs_gmap_cat'	
							) );
	$ret='<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class="wp-list-table widefat fixed striped cats">
  			<tbody>
    		<tr>
			  <th>ID</th>
			  <th>Map Category</th>
			  <th>Insert</th>
    		</tr>';
    foreach ( $map_cats as $map_cat ) :
	$ret .=  '<tr>
			  <td>'.$map_cat->term_id.'</td>
			  <td>'.$map_cat->name.'</td>
			  <td><a class="nafs_gmap_insertlink" data-attr-item="cat_id" data-attr-id="'.$map_cat->term_id.'" >Insert</a></td>
    		  </tr>';
			  $row_count++;
	endforeach;
   	$ret .='	</tbody>
			</table>';
	return $row_count?$ret:'';
}
function getMaps()
{
	
	$mymaps = get_posts(array('post_type'        => 'nafs_gmap_item'));
	$row_count =0;
	if ( $mymaps ) {
	$ret='<table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" class="wp-list-table widefat fixed striped posts">
  			<tbody>
    		<tr>
			  <th>ID</th>
			  <th>Map Title</th>
			  <th>Insert</th>
    		</tr>';
    foreach ( $mymaps as $map ) :
	$ret .=  '<tr>
			  <td>'.$map->ID.'</td>
			  <td>'.$map->post_title.'</td>
			  <td><a class="nafs_gmap_insertlink"  data-attr-item="id"  data-attr-id="'.$map->ID.'" >Insert</a></td>
    		  </tr>';
			  $row_count++;
	endforeach;
   	$ret .='	</tbody>
			</table>';
	return $ret;
	}
	else
	{
	return false;
	}
}
	/**
	 * Adds a button to the TinyMCE / Visual Editor which the user can click
	 * to insert a custom CSS class.
	 *
	 * @param array $buttons Array of registered TinyMCE Buttons
	 * @return array Modified array of registered TinyMCE Buttons
	 */
	function add_tinymce_toolbar_button( $buttons ) {
	 
	    array_push( $buttons, 'custom_class' );
	    return $buttons;
	 
	}

	/**
	* Enqueues CSS for TinyMCE Dashicons
	*/
	function admin_scripts_css() {

		wp_enqueue_style( 'tinymce-custom-class', plugins_url( 'tinymce-custom-class.css', __FILE__ ) );

	}

/**
* Adds the Custom Class button to the Quicktags (Text) toolbar of the content editor
*/
function admin_footer_scripts() {

	// Check the Quicktags script is in use
	if ( ! wp_script_is( 'quicktags' ) ) {
		return;
	}
	?>
	<script type="text/javascript">
		QTags.addButton( 'custom_class', 'Insert Custom Class', insert_custom_class );
		function insert_custom_class() {
		    // Ask the user to enter a CSS class
		    var result = prompt('Enter the CSS class');
		    if ( !result ) {
		        // User cancelled - exit
		        return;
		    }
		    if (result.length === 0) {
		        // User didn't enter anything - exit
		        return;
		    }

		    // Insert
		    QTags.insertContent('<span class="' + result +'"></span>');
		}
	</script>
	<?php

}
 
}
 
$tinymce_custom_class = new TinyMCE_Custom_Class;