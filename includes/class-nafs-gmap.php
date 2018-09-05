<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       nafsin.info/
 * @since      1.0.0
 *
 * @package    Nafs_Gmap
 * @subpackage Nafs_Gmap/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Nafs_Gmap
 * @subpackage Nafs_Gmap/includes
 * @author     Nafsin Vattakandy <nafsinvk@gmail.com>
 */
class Nafs_Gmap extends WP_Widget{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Nafs_Gmap_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'nafs-gmap';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		/*
		parent::__construct(
			'Nafs_Gmap_Widget', // Base ID
			__('Google Map Weiget by Nafs', 'text_domain'), // Name
			array('description' => __( 'To create a google map widget', 'text_domain' ),) // Args
		);
		*/
		 parent::__construct(
            'nafs_gmap_widget',
            __( 'Google Map Weiget by Nafs', 'nafs_gmaptextdomain' ),
            array(
                'classname'   => 'nafs_gmap',
                'description' => __( 'Add GoogleMap to your web pages.', 'nafs_gmaptextdomain' )
			)
        );
		if(is_array($options) && isset($options['naf_gmap_post_type_required']) and $options['naf_gmap_post_type_required']==1)
		{
		add_action( 'widgets_init', function(){
    	register_widget( $this );
		});
		}
		load_plugin_textdomain( 'nafs_gmaptextdomain', false, basename( dirname( __FILE__ ) ) . '/languages' );
		
		
		

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Nafs_Gmap_Loader. Orchestrates the hooks of the plugin.
	 * - Nafs_Gmap_i18n. Defines internationalization functionality.
	 * - Nafs_Gmap_Admin. Defines all hooks for the admin area.
	 * - Nafs_Gmap_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nafs-gmap-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nafs-gmap-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'tinymce-custom-class-master/tinymce-custom-class.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nafs-gmap-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nafs-gmap-public.php';

		$this->loader = new Nafs_Gmap_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Nafs_Gmap_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Nafs_Gmap_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Nafs_Gmap_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Nafs_Gmap_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

public function widget( $args, $instance ) {
	
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo esc_html__( 'Hello, World!', 'text_domain' );
		echo $args['after_widget'];
	}
	
	
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		echo '<p>
		<label for="'.esc_attr( $this->get_field_id( 'title' ) ).'">'.esc_attr_e( 'Title:', 'text_domain' ).'</label> 
		<input class="widefat" id="'.esc_attr( $this->get_field_id( 'title' ) ).'" name="'.esc_attr( $this->get_field_name( 'title' ) ).'" type="text" value="'.esc_attr( $title ).'">
		</p>';
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Nafs_Gmap_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	public function lat_long_box()
	{
    add_meta_box( 
        'lat_long_box',
        __( 'Latitude and longitude', 'nafs_gmaptextdomain' ),
        array($this,'lat_long_box_content'),
        'nafs_gmap_item',
        'side',
        'high'
    );
	}
	
	function lat_long_box_content($post)
	{
  $custom = get_post_custom($post->ID);
  $lat_long_value = isset($custom["lat_long_value"])?$custom["lat_long_value"][0]:'';
		wp_nonce_field( plugin_basename( __FILE__ ), 'lat_long_box_content_nonce' );
  		echo '<label for="lat_long_value">Lat, Long</label>';
  		echo '<input required type="text" id="lat_long_value" name="lat_long_value" placeholder="enter Lat, Long" value="'.$lat_long_value.'" />';

	}
	
	function lat_long_box_save( $post_id ) {
	if(!isset($_POST['lat_long_box_content_nonce']))
	{
	return ;
	}
	  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	  return;
	
	  if ( !wp_verify_nonce( $_POST['lat_long_box_content_nonce'], plugin_basename( __FILE__ ) ) )
	  return;
	
	  if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
		return;
	  } else {
		if ( !current_user_can( 'edit_post', $post_id ) )
		return;
	  }
	  $lat_long_value = $_POST['lat_long_value'];
	  update_post_meta( $post_id, 'lat_long_value', $lat_long_value );
	}
	
	
//can add a control later to define	

}
/* Register the widget
add_action( 'widgets_init', function(){
     register_widget( 'Nafs_Gmap' );
}); */