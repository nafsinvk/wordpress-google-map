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
		
		$this->options = get_option('naf_gmap_option_name', 'default text');
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
		if(is_array($this->options))
		return  $option = isset($this->options['naf_gmap_api_key'])?$this->options['naf_gmap_api_key']:false;
		else
		return false;
	}
	function setScriptDefaults()
	{
		$this->script_params = array(
		'wrapper' => 'nafgmap_wrapper',
		'id' => '',
		'cat_id' =>'',
		'lat' => '26.228611',
		'lng' => '50.589220',
		'width' => '100%',
		'height' => '350px',
		'zoom' => '13',
		'center' => '26.228239, 50.583331',
		'icon'   => plugin_dir_url(__FILE__).'img/marker.png'
		);
		if(isset($this->options['naf_gmap_lat_long']))
		{
		$default_lat_lng = explode(',',($this->options['naf_gmap_lat_long']));
		if(is_array($default_lat_lng) and isset($default_lat_lng[0]) and  isset($default_lat_lng[1]) )
		{
			$this->script_params['lat'] = $default_lat_lng[0];
			$this->script_params['lng'] = $default_lat_lng[1];
			$this->script_params['center'] = $this->options['naf_gmap_lat_long'];
		}
		}
	}
	
	function gmap_shortcode( $atts, $content = null ) {
		
	$this->setScriptDefaults();		
	$wrap_id=esc_attr($atts['wrapper']).rand ();
	$a = shortcode_atts( $this->script_params, $atts ); 
	if(isset($atts['cat_id']) and $atts['cat_id'])
	{
	echo '<script>';
	echo $this->createFromCat($atts['cat_id'], $wrap_id);
	echo '</script>';
	}
	else
	{
	if(isset($atts['id']) and $atts['id'])
	{
	$this->createFromPost($atts['id']);
	$a = shortcode_atts( $this->script_params, $atts ); 
	}
	$this->setMapScript($wrap_id, $a);
	echo '<script>';
	echo $this->map_script;
	echo '</script>';
	}
	echo '<style>
	.nafs_map_item{min-width:100px;min-height:100px;}
	';
	echo '.' . esc_attr($a['wrapper']) .$wrap_id. "{
		  height: ".esc_attr($a['height']).";
		  width : ".esc_attr($a['width'])."}";
	echo '</style>';
	return       '<div class="nafs_map_item ' . esc_attr($a['wrapper']) .$wrap_id. '" id="'.$wrap_id.'">' . $content . '</div>';
	}
	function createFromCat($id, $wrapper)
	{
		$maps = get_posts(array('post_type'=> 'nafs_gmap_item',  'tax_query' => array( array( 'taxonomy' => 'nafs_gmap_cat', 'field' => 'id', 'terms' => $id )) )); //print_r($maps);
		$marker ="// Multiple Markers
    	var markers = [";
		$infowindow ="var infoWindowContent = [";
		foreach($maps as $map)
		{
			$lat_lng = explode(',',(get_post_meta( $map->ID, 'lat_long_value', true ))); 
			$marker.="['".get_the_title($map->ID)."', ".$lat_lng [0].",".$lat_lng [1].", '".get_the_post_thumbnail_url($map->ID)."'],";
			$infowindow .="[".json_encode($map->post_content)."],";
		}
		$marker .="];\n";
		$infowindow .="];\n";
		$totalScript ="function initialize() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the page
    map = new google.maps.Map(document.getElementById(\"".$wrapper."\"), mapOptions);
    map.setTilt(45);";
		$totalScript .= $marker.$infowindow;
		$totalScript .=" // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Loop through our array of markers & place each one on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0],
			icon : markers[i][3]
        });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });}google.maps.event.addDomListener(window, 'load', initialize);";
	return $totalScript;
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
