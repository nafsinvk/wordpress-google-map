<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       nafsin.info/
 * @since      1.0.0
 *
 * @package    Nafs_Gmap
 * @subpackage Nafs_Gmap/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nafs_Gmap
 * @subpackage Nafs_Gmap/admin
 * @author     Nafsin Vattakandy <nafsinvk@gmail.com>
 */
 

class Nafs_Gmap_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

	}
   /**
     * Add options page
     */
 public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Gmap Settings', 
            'manage_options', 
            'nafs-gmap-admin', 
            array( $this, 'create_admin_page' )
        );
    }
	
	
	  /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'naf_gmap_option_name' );
       echo  '<div class="wrap">
            <form method="post" action="options.php">';
                // This prints out all hidden setting fields
                settings_fields( 'naf_gmap_option_group' );
                do_settings_sections( 'nafs-gmap-admin' );
                submit_button();
        echo  '</form>
        </div>';
    }


public function page_init()
    {        
        register_setting(
            'naf_gmap_option_group', // Option group
            'naf_gmap_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Google Map Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'nafs-gmap-admin' // Page
        );  

        add_settings_field(
            'naf_gmap_api_key', // ID
            'JS API Key', // Title 
            array( $this, 'naf_gmap_api_key_callback' ), // Callback
            'nafs-gmap-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'naf_gmap_lat_long', 
            'Latitude and Longitude', 
            array( $this, 'naf_gmap_lat_long_callback' ), 
            'nafs-gmap-admin', 
            'setting_section_id'
        );     
        add_settings_field(
            'naf_post_type_required', 
            'Do you require a post type', 
            array( $this, 'naf_gmap_post_type_required_callback' ), 
            'nafs-gmap-admin', 
            'setting_section_id'
        );   
    }
	
	/**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['naf_gmap_api_key'] ) )
            $new_input['naf_gmap_api_key'] = sanitize_text_field( $input['naf_gmap_api_key'] );

        if( isset( $input['naf_gmap_lat_long'] ) )
            $new_input['naf_gmap_lat_long'] = sanitize_text_field( $input['naf_gmap_lat_long'] );
        if( isset( $input['naf_gmap_post_type_required'] ) )
            $new_input['naf_gmap_post_type_required'] = sanitize_text_field( $input['naf_gmap_post_type_required'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function naf_gmap_api_key_callback()
    {
        printf(
            '<input type="text" id="naf_gmap_api_key" name="naf_gmap_option_name[naf_gmap_api_key]" value="%s" />',
            isset( $this->options['naf_gmap_api_key'] ) ? esc_attr( $this->options['naf_gmap_api_key']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function naf_gmap_lat_long_callback()
    {
        printf(
            '<input type="text" id="naf_gmap_lat_long" name="naf_gmap_option_name[naf_gmap_lat_long]" value="%s" />',
            isset( $this->options['naf_gmap_lat_long'] ) ? esc_attr( $this->options['naf_gmap_lat_long']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function naf_gmap_post_type_required_callback()
    {
        printf(
            '<input type="checkbox" id="naf_gmap_post_type_required" name="naf_gmap_option_name[naf_gmap_post_type_required]" value="1" %s/>',
            isset( $this->options['naf_gmap_post_type_required'] ) ?'checked' : ''
        );
    }
	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nafs-gmap-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nafs-gmap-admin.js', array( 'jquery' ), $this->version, false );
	
	
		$jsapi = $this->getGmapAPICode();
		if($jsapi)
		{
			
			wp_enqueue_script( ($this->plugin_name.'_google'), '//maps.googleapis.com/maps/api/js?key='.$jsapi.'&callback=initMap', array(), $this->version, true );
			echo '<script type="text/javascript">function initMap(){return ;}</script>';
		}
		else
		{
			$url = admin_url('admin.php?page=nafs-gmap-admin');
				echo '<div class="error notice">
					<p>Please setup a google map API key <a href="'.$url.'">here</a>.</p>
					</div>';
		}

	}
function getGmapAPICode()
	{
		$options = get_option('naf_gmap_option_name', 'default text');
		return  $option = (is_array($options) and isset($options['naf_gmap_api_key']))?$options['naf_gmap_api_key']:'';
	}
}
