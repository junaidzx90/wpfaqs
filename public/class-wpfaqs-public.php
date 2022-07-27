<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Wpfaqs
 * @subpackage Wpfaqs/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpfaqs
 * @subpackage Wpfaqs/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Wpfaqs_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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
		 * defined in Wpfaqs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpfaqs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpfaqs-public.css', array(), $this->version, 'all' );

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
		 * defined in Wpfaqs_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpfaqs_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		wp_enqueue_script( "wpfaqs_vue", 'https://cdn.jsdelivr.net/npm/vue@2.7.4', array(  ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpfaqs-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'wfqsajax', array(
			'ajaxurl' => admin_url( "admin-ajax.php" ),
			'nonce'	=> wp_create_nonce( "wfqsnonce" )
		) );

	}

	function wpfaqs_template_include($template){
		if ( is_post_type_archive( 'help' ) ) {
			$theme_files = array('wpfaqs-archive.php', plugin_dir_path( __FILE__ ).'partials/wpfaqs-archive.php');
			$exists_in_theme = locate_template($theme_files, false);
			if ( $exists_in_theme != '' ) {
				$template = $exists_in_theme;
			} else {
				$template = plugin_dir_path( __FILE__ ). 'partials/wpfaqs-archive.php';
			}
		}

		if ($template == '') {
			throw new \Exception('No template found');
		}

		return $template;
	}

	function get_initial_results(){
		if(!wp_verify_nonce( $_GET['nonce'], 'wfqsnonce' )){
			die("Invalid request!");
		}

		$which = ((isset($_GET['which'])) ? $_GET['which']: 'initial');

		if($which === 'initial'){
			$categories = [];
			$taxonomies = get_terms( array(
				'taxonomy' => 'helpc',
				'hide_empty' => false
			) );
				
			if ( !empty($taxonomies) ) :
				foreach( $taxonomies as $category ) {
					$arr = [
						'id' => $category->term_id,
						'label' => $category->name,
						'counts' => $category->count
					];
	
					$categories[] = $arr;
				}
			endif;
		}
		

		$faqs_data = [];
		if(isset($_GET['category']) && !empty($_GET['category'])){
			$category = (($_GET['category'] === 'all')? 'all': intval($_GET['category']));
			$perpage = ((isset($_GET['perpage']))? intval($_GET['perpage']): 10);
			$page = ((isset($_GET['page']))? intval($_GET['page']): 1);

			$args = array(
				'numberposts' => $perpage,
				'post_type'  => 'help',
				'paged'  => $page,
				'orderby' => 'date',
				'order' => 'DESC',
			);

			if($category !== 'all'){
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'helpc',
						'field' => 'term_id',
						'terms' => $category,
					)
				);
			}
			
			$faqs = get_posts( $args );

			if($faqs){
				foreach($faqs as $faq){
					$extLink = get_post_meta($faq->ID, 'faq_external_url', true);
					$extLink = ((!empty($extLink))?$extLink:get_the_permalink( $faq->ID ));

					$sfaq = [
						'title' => $faq->post_title,
						'link' => $extLink,
						'excerpt' => wp_trim_words(get_the_excerpt( $faq->ID ), 30)
					];
					$faqs_data[] = $sfaq;
				}
			}
		}

		$args2 = array(
			'numberposts' => -1,
			'post_type'  => 'help',
			'orderby' => 'date',
			'order' => 'DESC',
		);

		if($category !== 'all'){
			$args2['tax_query'] = array(
				array(
					'taxonomy' => 'helpc',
					'field' => 'term_id',
					'terms' => $category,
				)
			);
		}
		
		$faqs2 = get_posts( $args2 );
		$total_counts = count($faqs2);

		if($which === 'initial'){
			echo json_encode(array(
				'categories' => $categories,
				'faqs' => $faqs_data,
				'counts' => $total_counts
			));
		}
		if($which === 'filtered'){
			echo json_encode(array(
				'faqs' => $faqs_data,
				'counts' => $total_counts
			));
		}
		die();
	}

	function get_search_category(){
		if(!wp_verify_nonce( $_GET['nonce'], 'wfqsnonce' )){
			die("Invalid request!");
		}

		$categories = [];
		$taxonomies = get_terms( array(
			'taxonomy' => 'helpc',
			'hide_empty' => false
		) );
			
		if ( !empty($taxonomies) ) :
			foreach( $taxonomies as $category ) {
				$arr = [
					'id' => $category->term_id,
					'label' => $category->name
				];

				$categories[] = $arr;
			}
		endif;

		echo json_encode(array("categories" => $categories));
		die;
	}

	function get_faq_by_search(){
		global $wpdb;
		
		if(!wp_verify_nonce( $_GET['nonce'], 'wfqsnonce' )){
			die("Invalid request!");
		}

		if(isset($_GET['search'])){
			$faqs_data = [];

			$title = sanitize_text_field( $_GET['search'] );
			$faqs = $wpdb->get_results("SELECT post_title, ID FROM {$wpdb->prefix}posts WHERE post_type = 'help' AND post_status = 'publish' AND post_title LIKE '%$title%' ORDER BY post_date DESC");

			if($faqs){
				foreach($faqs as $faq){
					$extLink = get_post_meta($faq->ID, 'faq_external_url', true);
					$extLink = ((!empty($extLink))?$extLink:get_the_permalink( $faq->ID ));

					$sfaq = [
						'id' => $faq->ID,
						'link' => $extLink,
						'title' => $faq->post_title,
					];
					$faqs_data[] = $sfaq;
				}
			}

			echo json_encode(array(
				'faqs' => $faqs_data
			));
		}

		die();
	}
}
