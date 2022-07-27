<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wpfaqs
 * @subpackage Wpfaqs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpfaqs
 * @subpackage Wpfaqs/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Wpfaqs_Admin {

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
		 * defined in Wpfaqs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpfaqs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpfaqs-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wpfaqs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpfaqs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpfaqs-admin.js', array( 'jquery' ), $this->version, false );

	}

	/*
	* Creating a function to create our FAQs CPT
	*/
	function wpfaqs_post_type() {
		$labels = array(
			'name'                => _x( 'FAQs', 'Post Type General Name', 'wpfaqs' ),
			'singular_name'       => _x( 'Faq', 'Post Type Singular Name', 'wpfaqs' ),
			'menu_name'           => __( 'FAQs', 'wpfaqs' ),
			'parent_item_colon'   => __( 'Parent Faq', 'wpfaqs' ),
			'all_items'           => __( 'All FAQs', 'wpfaqs' ),
			'view_item'           => __( 'View Faq', 'wpfaqs' ),
			'add_new_item'        => __( 'Add New Faq', 'wpfaqs' ),
			'add_new'             => __( 'Add New', 'wpfaqs' ),
			'edit_item'           => __( 'Edit Faq', 'wpfaqs' ),
			'update_item'         => __( 'Update Faq', 'wpfaqs' ),
			'search_items'        => __( 'Search Faq', 'wpfaqs' ),
			'not_found'           => __( 'Not Found', 'wpfaqs' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'wpfaqs' ),
		);
		
		$args = array(
			'label'               => __( 'help', 'wpfaqs' ),
			'description'         => __( 'Faq news and reviews', 'wpfaqs' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies'          => array( 'helpc' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'       => 'dashicons-search',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest' => true
		);
		  
		// Registering your Custom Post Type
		register_post_type( 'help', $args );

		$labels = array(
			'name' => _x( 'Categories', 'taxonomy general name' ),
			'singular_name' => _x( 'Category', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Categories', 'wpfaqs' ),
			'all_items' => __( 'All Categories', 'wpfaqs' ),
			'parent_item' => __( 'Parent Category', 'wpfaqs' ),
			'parent_item_colon' => __( 'Parent Category:', 'wpfaqs' ),
			'edit_item' => __( 'Edit Category', 'wpfaqs' ), 
			'update_item' => __( 'Update Category', 'wpfaqs' ),
			'add_new_item' => __( 'Add New Category', 'wpfaqs' ),
			'new_item_name' => __( 'New Category Name', 'wpfaqs' ),
			'menu_name' => __( 'Categories', 'wpfaqs' ),
		  );    
		 
		// Now register the taxonomy
		register_taxonomy('helpc',array('help'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_rest' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'helpc' ),
		));

		if(get_option( 'wpfaqs_permalinks_flush' ) !== $this->version ){
			flush_rewrite_rules(false);
			update_option( 'wpfaqs_permalinks_flush', $this->version );
		}
	}

	function wpfaqs_meta_boxes(){
		add_meta_box( "faq_external_url", "External URL", [$this, "faq_external_url_cb"], "help", "side" );
	}

	function faq_external_url_cb($post){
		$extLink = get_post_meta($post->ID, 'faq_external_url', true);
		echo '<input class="widefat" type="url" name="faq_external_url" value="'.$extLink.'" id="faq_external_url">';
	}

	function wpfaqs_save_post_help($post_id){
		$extlink = ((isset($_POST['faq_external_url']))?$_POST['faq_external_url']: '');
		update_post_meta($post_id, 'faq_external_url', $extlink);
	}

	/*
	* Add a form field in the new category page
	* @since 1.0.0
	*/
	public function add_category_image ( $taxonomy ) { ?>
		<div class="form-field term-group">
		<label for="category-icon-id"><?php _e('Icon', 'hero-theme'); ?></label>
		<div id="category-icon-wrapper"></div>
			<p>
				<input type="text" style="width: 100%" name="faq_cat_icon" id="category-icon-id">
			</p>
			<p>Use fontawesome icon</p>
		</div>
	<?php
	}
	
	/*
	* Save the form field
	* @since 1.0.0
	*/
	public function save_category_image ( $term_id, $tt_id ) {
		if( isset( $_POST['faq_cat_icon'] ) && '' !== $_POST['faq_cat_icon'] ){
			$icon = $_POST['faq_cat_icon'];
			$icon = base64_encode( $icon );
			add_term_meta( $term_id, 'faq_cat_icon', $icon, true );
		}
	}
	
	/*
	* Edit the form field
	* @since 1.0.0
	*/
	public function update_category_image ( $term, $taxonomy ) { ?>
		<tr class="form-field term-group-wrap">
		<th scope="row">
			<label for="category-icon-id"><?php _e( 'Icon', 'hero-theme' ); ?></label>
		</th>
		<td>
			<?php
			$icon = get_term_meta ( $term->term_id, 'faq_cat_icon', true ); 
			$icon = base64_decode($icon);
			$icon = stripslashes($icon);
			?>
			<p>
				<input type="text" style="width: 100%" name="faq_cat_icon" value='<?php echo $icon ?>' id="category-icon-id">
			</p>
			<p>Use fontawesome icon</p>
		</td>
		</tr>
		<?php
	}
	
	/*
	* Update the form field value
	* @since 1.0.0
	*/
	public function updated_category_image ( $term_id, $tt_id ) {
		if( isset( $_POST['faq_cat_icon'] ) && '' !== $_POST['faq_cat_icon'] ){
			$icon = $_POST['faq_cat_icon'];
			$icon = base64_encode( $icon );
			update_term_meta ( $term_id, 'faq_cat_icon', $icon );
		} else {
			update_term_meta ( $term_id, 'faq_cat_icon', '' );
		}
	}
}
