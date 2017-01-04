<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       nafsin.info/
 * @since      1.0.0
 *
 * @package    Nafs_Gmap
 * @subpackage Nafs_Gmap/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nafs_Gmap
 * @subpackage Nafs_Gmap/public
 * @author     Nafsin Vattakandy <nafsinvk@gmail.com>
 */
class Nafs_Gmap_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	private $map_script;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode( 'nafs_gmap', array($this, 'gmap_shortcode') );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nafs_Gmap_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nafs_Gmap_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nafs-gmap-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nafs_Gmap_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nafs_Gmap_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nafs-gmap-public.js', array( 'jquery' ), $this->version, false ); 
		$jsapi = $this->getGmapAPICode();
		if($jsapi)
		{
			wp_enqueue_script( 'google-map', '//maps.googleapis.com/maps/api/js?key='.$jsapi.'', array( 'jquery' ), $this->version, false );
		}
	}
	function getGmapAPICode()
	{
		$options = get_option('naf_gmap_option_name', 'default text');
		return  $option = $options['naf_gmap_api_key'];
	}
	
	
	function gmap_shortcode( $atts, $content = null ) {
	$a = shortcode_atts( array(
		'wrapper' => 'nafgmap_wrapper',
		'id' => '',
		'lat' => '26.228611',
		'lng' => '50.589220',
		'width' => '100%',
		'height' => '350px',
		'zoom' => '13',
		'center' => '26.228239, 50.583331'
	), $atts );
	$wrap_id=esc_attr($a['wrapper']).rand ();
	$this->map_script = "function initMap".$wrap_id."() {
        var uluru = {lat: ".esc_attr($a['lat']).", lng: ".esc_attr($a['lng'])."};
        var map = new google.maps.Map(document.getElementById('".$wrap_id."'), {
          zoom: ".esc_attr($a['zoom']).",
          center: uluru,
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }function initialize() {initMap".$wrap_id."();}google.maps.event.addDomListener(window, 'load', initialize);";
	echo '<script>';
	echo $this->map_script;
	echo '</script>';
	echo '<style>';
	echo '.' . esc_attr($a['wrapper']) . "{
		  height: ".esc_attr($a['height']).";
		  width : ".esc_attr($a['width'])."}";
	echo '</style>';
	return       '<div class="' . esc_attr($a['wrapper']) . '" id="'.$wrap_id.'">' . $content . '</div>';
	}
	

}
