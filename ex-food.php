<?php
/*
Plugin Name: WP-Food
Plugin URI: https://exthemes.net/wp-food-lite
Description: Food ordering and Restaurant Menu
Version: 1.1
Author: Ex-Themes
Author URI: https://exthemes.net
Text Domain: ex-food
License: Envato Split Licence
Domain Path: /languages/
*/
define( 'EXFOOD_PATH', plugin_dir_url( __FILE__ ) );
// Make sure we don't expose any info if called directly
if ( !defined('EXFOOD_PATH') ){
	die('-1');
}
if(!function_exists('exfood_get_plugin_url')){
	function exfood_get_plugin_url(){
		return plugin_dir_path(__FILE__);
	}
}
class EX_WPFood{
	public $template_url;
	public $plugin_path;
	public function __construct(){
		$this->includes();
		add_action( 'wp_enqueue_scripts', array($this, 'frontend_scripts') );
		add_filter( 'template_include', array( $this, 'template_loader' ),99 );
		add_action('wp_enqueue_scripts', array($this, 'frontend_style'),99 );
		add_action('wp_enqueue_scripts',array( $this, 'custom_css'),100);
		add_action('plugins_loaded',array( $this, 'load_textdomain'));
		add_action( 'after_setup_theme', array(&$this, 'calthumb_register') );
		add_action( 'widgets_init', array( &$this,'widgets_init') );
    }
    function widgets_init() {
		register_sidebar( array(
			'name' => esc_html__('Single Food','wp-food'),
			'id' => 'exfood-sidebar',
			'description' => esc_html__('Sidebar for single food','wp-timeline'),
			'before_widget' => '<div id="%1$s" class="exfood-sidebar widget %2$s">',
			'after_widget' => '<div class="clear"></div></div></div></div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3><div class="ex-sidebar"><div class="tl-wrapper">',
		) );
	}
	function load_textdomain() {
		$textdomain = 'wp-food';
		$locale = '';
		if ( empty( $locale ) ) {
			if ( is_textdomain_loaded( $textdomain ) ) {
				return true;
			} else {
				return load_plugin_textdomain( $textdomain, false, plugin_basename( dirname( __FILE__ ) ) . '/language' );
			}
		} else {
			return load_textdomain( $textdomain, plugin_basename( dirname( __FILE__ ) ) . '/' . $textdomain . '-' . $locale . '.mo' );
		}
	}
	//thumbnails register
	function calthumb_register(){
		add_image_size('exfood_80x80',120,120, true);
		add_image_size('exfood_400x400',400,400, true);
	}
	function custom_css(){
		
	}

	function template_loader($template){
		$find = array('archive-food.php');
		$file = '';			
		if(is_post_type_archive( 'ex_food' ) || is_tax('exfood_cat') || is_tax('exfood_loc')){
			wp_redirect( get_template_part( '404' ) ); exit;
		}
		if(is_singular('ex_food')){
			$exfood_disable_single = exfood_get_option('exfood_disable_single');
			if($exfood_disable_single=='yes'){
				wp_redirect( get_template_part( '404' ) ); exit;
			}
			$file = 'single-food.php';
			$find[] = $file;
			$find[] = $this->template_url . $file;
			if ( $file ) {
				$template = locate_template( $find );
				if ( ! $template ){
					$file = 'wp-food/single-food.php';
					$find[] = $file;
					$find[] = $this->template_url . $file;
					$template = locate_template( $find );
					if ( ! $template ){
						$template = $this->plugin_path() . '/templates/single-food.php';
					}
				}
			}
		}
		return $template;		
	}
	public function plugin_path() {
		if ( $this->plugin_path ) return $this->plugin_path;
		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	function includes(){
		include_once exfood_get_plugin_url().'admin/functions.php';
		include_once exfood_get_plugin_url().'inc/functions.php';
		include_once exfood_get_plugin_url().'inc/class-buildin-ordering-food.php';
	}
	// Load js and css
	function frontend_scripts(){
		$main_font_default='Source Sans Pro';
		$g_fonts = array($main_font_default);
		$exfood_font_family = exfood_get_option('exfood_font_family');
		if($exfood_font_family!=''){
			$exfood_font_family = exfood_get_google_font_name($exfood_font_family);
			array_push($g_fonts, $exfood_font_family);
		}
		$exfood_headingfont_family = exfood_get_option('wt_hfont');
		if($exfood_headingfont_family!=''){
			$exfood_headingfont_family = exfood_get_google_font_name($exfood_headingfont_family);
			array_push($g_fonts, $exfood_headingfont_family);
		}
		$wt_googlefont_js = exfood_get_option('exfood_disable_ggfont','exfood_js_css_file_options');
		if($wt_googlefont_js!='yes'){
			wp_enqueue_style( 'ex-google-fonts', exfood_get_google_fonts_url($g_fonts), array(), '1.0.0' );
		}
	}
	function frontend_style(){
		
		wp_enqueue_script( 'ex-wp-food',plugins_url('/js/food.js', __FILE__) , array( 'jquery' ),'2.0' );
		$exfood_custom_js = exfood_get_option('exfood_custom_js','exfood_custom_code_options');
   		wp_add_inline_script( 'ex-wp-food', $exfood_custom_js );
		if(exfood_get_option('exfood_rating')=='yes'){
			wp_register_style('ionicon', EXFOOD_PATH.'css/ionicon/css/ionicons.min.css','1.0');
		}
		wp_enqueue_style('ex-wp-food', EXFOOD_PATH.'css/style.css','1.0');
		wp_enqueue_style('ex-wp-food-list', EXFOOD_PATH.'css/style-list.css','1.0');
		wp_enqueue_style('ex-wp-food-modal', EXFOOD_PATH.'css/modal.css','1.0');
		wp_enqueue_style( 'ex-wp-s_lick', EXFOOD_PATH.'js/ex_s_lick/ex_s_lick.css');
		wp_enqueue_style( 'ex_wp_s_lick-theme', EXFOOD_PATH.'js/ex_s_lick/ex_s_lick-theme.css');
		wp_enqueue_script( 'ex_wp_s_lick', EXFOOD_PATH.'js/ex_s_lick/ex_s_lick.js', array( 'jquery' ),'1.0' );
		wp_enqueue_style(
	        'exfood-custom-css',
	        EXFOOD_PATH.'js/ex_s_lick/ex_s_lick.css'
	    );
		if(is_singular('ex_food')){
			wp_enqueue_style('extp-single-member', EXFOOD_PATH.'css/single-food.css','1.0');
		}
		require exfood_get_plugin_url(). 'css/custom.css.php';
		$ctcss = exfood_custom_css();
		wp_add_inline_style( 'exfood-custom-css', $ctcss );
	}
}
$EX_WPFood = new EX_WPFood();