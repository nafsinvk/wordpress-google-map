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
	private $map_script_additional;
	private $error_message;
	
	private $script_params;

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
		$this->map_script_additional='';
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
	function setScriptDefaults()
	{
		$this->script_params = array(
		'wrapper' => 'nafgmap_wrapper',
		'id' => '',
		'lat' => '26.228611',
		'lng' => '50.589220',
		'width' => '100%',
		'height' => '350px',
		'zoom' => '13',
		'center' => '26.228239, 50.583331',
		'icon'   => plugin_dir_url(__FILE__).'img/marker.png'
		);
	}
	
	function gmap_shortcode( $atts, $content = null ) {
	$this->setScriptDefaults();	
	if(isset($atts['id']) and $atts['id'])
	{
	$this->createFromPost($atts['id']);
	}
	$a = shortcode_atts( $this->script_params, $atts ); 
	$wrap_id=esc_attr($a['wrapper']).rand ();
	$this->setMapScript($wrap_id, $a);
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
	
	function createFromPost($id)
	{
		$thePostRet = get_post($id); 
		if(!empty($thePostRet) and $thePostRet->post_type == 'nafs_gmap_item')
		{
		$this->setInfoWindow($thePostRet->post_content);	
		$lat_lng = explode(',',(get_post_meta( $id, 'lat_long_value', true ))); 
		$this->script_params['lat'] = trim($lat_lng [0]);
		$this->script_params['lng'] = trim($lat_lng [1]);
		
		if(has_post_thumbnail($thePostRet))
		{
			$this->script_params['icon'] = get_the_post_thumbnail_url($thePostRet);
		}
		}
		else
		{
		if($thePostRet->post_type != 'nafs_gmap_item')
			$this->error_message = 'The mentioned post id is not of type Google map item';
		if(empty($thePostRet->post_type))
			$this->error_message = 'The post with id '.$id.'was not found';
		return false;
		}

		
	}
	function setInfoWindow($content)
	{
		$this->map_script_additional .= '
		var contentString = '.json_encode($content).'
		var infowindow = new google.maps.InfoWindow({
										  content: contentString
										});';
		$this->map_script_additional .= "marker.addListener('click', function() {
										  infowindow.open(map, marker);
										});";
	}
	function setMapScript($wrap, $a, $html='')
	{
		$this->map_script = "function initMap".$wrap."() {
        var uluru = {lat: ".esc_attr($a['lat']).", lng: ".esc_attr($a['lng'])."};
        var map = new google.maps.Map(document.getElementById('".$wrap."'), {
          zoom: ".esc_attr($a['zoom']).",
          center: uluru,
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map,
		  icon : '".$a['icon']."'
        });
		".$this->map_script_additional."
      }function initialize() {initMap".$wrap."();}google.maps.event.addDomListener(window, 'load', initialize);";
	}

	

}
