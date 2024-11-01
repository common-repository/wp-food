<?php
//shortcode
include plugin_dir_path(__FILE__).'shortcodes/wp-food-list.php';
include plugin_dir_path(__FILE__).'shortcodes/wp-food-grid.php';
include plugin_dir_path(__FILE__).'shortcodes/wp-food-carousel.php';
include plugin_dir_path(__FILE__).'shortcodes/wp-food-mini-cart.php';
//widget
include plugin_dir_path(__FILE__).'widgets/wp-food.php';

// include plugin_dir_path(__FILE__).'payments/class-paypal-payment.php';

if(!function_exists('exfood_startsWith')){
	function exfood_startsWith($haystack, $needle)
	{
		return !strncmp($haystack, $needle, strlen($needle));
	}
} 
if(!function_exists('exfood_get_google_fonts_url')){
	function exfood_get_google_fonts_url ($font_names) {
	
		$font_url = '';
	
		$font_url = add_query_arg( 'family', urlencode(implode('|', $font_names)) , "//fonts.googleapis.com/css" );
		return $font_url;
	} 
}
if(!function_exists('exfood_get_google_font_name')){
	function exfood_get_google_font_name($family_name){
		$name = $family_name;
		if(exfood_startsWith($family_name, 'http')){
			// $family_name is a full link, so first, we need to cut off the link
			$idx = strpos($name,'=');
			if($idx > -1){
				$name = substr($name, $idx);
			}
		}
		$idx = strpos($name,':');
		if($idx > -1){
			$name = substr($name, 0, $idx);
			$name = str_replace('+',' ', $name);
		}
		return $name;
	}
}
if(!function_exists('exfood_template_plugin')){
	function exfood_template_plugin($pageName,$shortcode=false){
		if(isset($shortcode) && $shortcode== true){
			if (locate_template('wp-food/content-shortcodes/content-' . $pageName . '.php') != '') {
				get_template_part('wp-food/content-shortcodes/content', $pageName);
			} else {
				include exfood_get_plugin_url().'templates/content-shortcodes/content-' . $pageName . '.php';
			}
		}else{
			if (locate_template('wp-food/' . $pageName . '.php') != '') {
				get_template_part('wp-food/'.$pageName);
			} else {
				include exfood_get_plugin_url().'templates/' . $pageName . '.php';
			}
		}
	}
}

if(!function_exists('EX_WPFood_query')){
    function EX_WPFood_query($posttype, $count, $order, $orderby, $cat, $tag, $taxonomy, $meta_key, $ids, $on_sale, $meta_value=false,$page=false,$mult=false){
		$posttype = 'ex_food';
		if($orderby == 'sale'){
			$meta_key = 'exfood_sale_price';
			$orderby = 'meta_value_num';
		}
		if($posttype == 'ex_food' && $taxonomy == ''){
			$taxonomy = 'exfood_cat';
		}
		$posttype = explode(",", $posttype);
		if($ids!=''){ //specify IDs
			$ids = explode(",", $ids);
			$args = array(
				'post_type' => $posttype,
				'posts_per_page' => $count,
				'post_status' => array( 'publish', 'future' ),
				'post__in' =>  $ids,
				'order' => $order,
				'orderby' => $orderby,
				'ignore_sticky_posts' => 1,
			);
		}elseif($ids==''){
			$args = array(
				'post_type' => $posttype,
				'posts_per_page' => $count,
				'post_status' => array( 'publish', 'future' ),
				'tag' => $tag,
				'order' => $order,
				'orderby' => $orderby,
				'meta_key' => $meta_key,
				'ignore_sticky_posts' => 1,
			);
			if(!is_array($cat) && $taxonomy =='') {
				$cats = explode(",",$cat);
				if(is_numeric($cats[0])){
					$args['category__in'] = $cats;
				}else{			 
					$args['category_name'] = $cat;
				}
			}elseif( is_array($cat) && count($cat) > 0 && $taxonomy ==''){
				$args['category__in'] = $cat;
			}
			if($taxonomy !='' && $tag!=''){
				$tags = explode(",",$tag);
				if(is_numeric($tags[0])){$field_tag = 'term_id'; }
				else{ $field_tag = 'slug'; }
				if(count($tags)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($tags as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field_tag,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field_tag,
								  'terms' => $tags,
							  )
					  );
				}
			}
			//cats
			if($taxonomy !='' && $cat!=''){
				$cats = explode(",",$cat);
				if(is_numeric($cats[0])){$field = 'term_id'; }
				else{ $field = 'slug'; }
				if(count($cats)>1){
					  $texo = array(
						  'relation' => 'OR',
					  );
					  foreach($cats as $iterm) {
						  $texo[] = 
							  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field,
								  'terms' => $iterm,
							  );
					  }
				  }else{
					  $texo = array(
						  array(
								  'taxonomy' => $taxonomy,
								  'field' => $field,
								  'terms' => $cats,
							  )
					  );
				}
			}
			if(isset($mult) && $mult!=''){
				$texo['relation'] = 'AND';
				$texo[] = 
					array(
						'taxonomy' => 'wpex_category',
						'field' => 'term_id',
						'terms' => $mult,
					);
			}
			
			if(isset($texo)){
				$args += array('tax_query' => $texo);
			}
		}
		if(isset($meta_value) && $meta_value!='' && $meta_key!=''){
			if(!empty($args['meta_query'])){
				$args['meta_query']['relation'] = 'AND';
			}
			$args['meta_query'][] = array(
				'key'  => $meta_key,
				'value' => $meta_value,
				'compare' => '='
			);
		}
		if(isset($on_sale) && $on_sale =='yes'){
			if(!empty($args['meta_query'])){
				$args['meta_query']['relation'] = 'AND';
			}
			$args['meta_query'][] = array(
				'key'  => 'exfood_sale_price',
				'value' => '0',
				'compare' => '>',
				'type'    => 'NUMERIC',
			);
		}	
		if(isset($page) && $page!=''){
			$args['paged'] = $page;
		}
		return apply_filters( 'exfood_query', $args );
	}
}


if(!function_exists('EX_WPFood_customlink')){
	function EX_WPFood_customlink($id){
		if ( exfood_get_option('exfood_disable_single') =='yes' ) {
			return 'javascript:;';
		}
		return get_the_permalink($id);
	}
}


if(!function_exists('exfood_ajax_navigate_html')){
	function exfood_ajax_navigate_html($ID,$atts,$num_pg,$args,$arr_ids){
		echo '
			<div class="ex-loadmore">
				<input type="hidden"  name="id_grid" value="'.esc_attr($ID).'">
				<input type="hidden"  name="num_page" value="'.esc_attr($num_pg).'">
				<input type="hidden"  name="num_page_uu" value="1">
				<input type="hidden"  name="current_page" value="1">
				<input type="hidden"  name="ajax_url" value="'.esc_url(admin_url( 'admin-ajax.php' )).'">
				<input type="hidden"  name="param_query" value="'.esc_html(str_replace('\/', '/', json_encode($args))).'">
				<input type="hidden"  name="param_ids" value="'.esc_html(str_replace('\/', '/', json_encode($arr_ids))).'">
				<input type="hidden" id="param_shortcode" name="param_shortcode" value="'.esc_html(str_replace('\/', '/', json_encode($atts))).'">';
				if($num_pg > 1){
					echo '
					<a  href="javascript:void(0)" class="loadmore-exfood" data-id="'.esc_attr($ID).'">
						<span class="load-text">'.esc_html__('Load more','wp-food').'</span><span></span>&nbsp;<span></span>&nbsp;<span></span>
					</a>';
				}
				echo '
		</div>';
	}
}

add_action( 'wp_ajax_exfood_loadmore', 'ajax_exfood_loadmore' );
add_action( 'wp_ajax_nopriv_exfood_loadmore', 'ajax_exfood_loadmore' );
function ajax_exfood_loadmore(){
	global $columns,$number_excerpt,$show_time,$orderby,$img_size,$ID;
	global $ID,$number_excerpt,$img_size;
	$food_nounce = sanitize_text_field($_POST['food_nounce']);
	if ( ! wp_verify_nonce( $food_nounce, 'food_nounce_secure' ) ) {
	    // This nonce is not valid.
	    die( 'Security check' ); 
	}
	$atts = json_decode( stripslashes( sanitize_text_field($_POST['param_shortcode'] )), true );
	$ID = sanitize_text_field(isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'ex-'.rand(10,9999));
	$style = sanitize_text_field(isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1');
	$column = intval(isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '2');
	$posttype   = 'ex_food';
	$ids   = sanitize_text_field(isset($atts['ids']) ? $atts['ids'] : '');
	$taxonomy  = sanitize_text_field(isset($atts['taxonomy']) ? $atts['taxonomy'] : '');
	$cat   = sanitize_text_field(isset($atts['cat']) ? $atts['cat'] : '');
	$tag  = sanitize_text_field(isset($atts['tag']) ? $atts['tag'] : '');
	$count   = intval(isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9');
	if($count < 0){
		$count = 9;
	}
	$posts_per_page   = intval(isset($atts['posts_per_page']) && $atts['posts_per_page'] !=''? $atts['posts_per_page'] : '3');
	if($posts_per_page < 0){
		$posts_per_page = 3;
	}
	$order  = sanitize_text_field(isset($atts['order']) ? $atts['order'] : '');
	$orderby  = sanitize_text_field(isset($atts['orderby']) ? $atts['orderby'] : '');
	$meta_key  = sanitize_text_field(isset($atts['meta_key']) ? $atts['meta_key'] : '');
	$meta_value  = sanitize_text_field(isset($atts['meta_value']) ? $atts['meta_value'] : '');
	$class  = sanitize_text_field(isset($atts['class']) ? $atts['class'] : '');
	$img_size =  sanitize_text_field(isset($atts['img_size']) ? $atts['img_size'] :'');
	$number_excerpt =  intval(isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10');
	$page = sanitize_text_field($_POST['page']);
	$layout = sanitize_text_field(isset($_POST['layout']) ? $_POST['layout'] : '');
	if ($layout == 'grid') {
		if ($style == '2') {
			$style = '3';
		}else{
			$style = '2';
		}
	}
	if ($layout == 'list') {
		$style = '1';
	}
	$param_query = json_decode( stripslashes( sanitize_text_field($_POST['param_query']) ), true );
	$param_ids = '';
	if(isset($_POST['param_ids']) && $_POST['param_ids']!=''){
		$param_ids =  json_decode( stripslashes( sanitize_text_field($_POST['param_ids']) ), true )!='' ? json_decode( stripslashes( sanitize_text_field($_POST['param_ids']) ), true ) : explode(",",sanitize_text_field($_POST['param_ids']));
	}
	$end_it_nb ='';
	if($page!=''){ 
		$param_query['paged'] = $page;
		$count_check = $page*$posts_per_page;
		if(($count_check > $count) && (($count_check - $count)< $posts_per_page)){$end_it_nb = $count - (($page - 1)*$posts_per_page);}
		else if(($count_check > $count)) {die;}
	}
	if($orderby =='rand' && is_array($param_ids)){
		$param_query['post__not_in'] = $param_ids;
		$param_query['paged'] = 1;
	}
	global $wpdb;
	
	$the_query = new WP_Query( $param_query );
	$it = $the_query->post_count;
	ob_start();
	if($the_query->have_posts()){
		$i =0;
		$arr_ids = array();
		$html_modal = '';
		while($the_query->have_posts()){ $the_query->the_post();
			$i++;
			$arr_ids[] = get_the_ID();
			if($layout=='table'){
				exfood_template_plugin('table-'.$style,1);
			}else if($layout=='list'){
				echo '<div class="fditem-list item-grid" data-id="ex_id-'.esc_attr($ID).'-'.get_the_ID().'" data-id_food="'.get_the_ID().'"> ';
						?>
					<div class="exp-arrow" >
						<?php 
						exfood_template_plugin('list-'.$style,1);
						?>
					<div class="exfd_clearfix"></div>
					</div>
					<?php
				echo '</div>';
			}else{
				echo '<div class="item-grid" data-id="ex_id-'.esc_attr($ID).'-'.get_the_ID().'" data-id_food="'.get_the_ID().'"> ';
					?>
					<div class="exp-arrow">
						<?php 
						exfood_template_plugin('grid-'.$style,1);
						?>
					<div class="exfd_clearfix"></div>
					</div>
					<?php
				echo '</div>';
			}
			if($end_it_nb!='' && $end_it_nb == $i){break;}
		}
		wp_reset_postdata();
		
		if($orderby =='rand' && is_array($param_ids)){
		
		}?>
        </div>
        <?php
	}
	$html = ob_get_clean();
	$output =  array('html_content'=>$html,'html_modal'=> $html_modal);
	echo str_replace('\/', '/', json_encode($output));
	die;
}


function exfood_convert_color($color){
	if ($color == '') {
		return;
	}
	$hex  = str_replace("#", "", $color);
	if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
	}
	$rgb = $r.','. $g.','.$b;
	return $rgb;
}

if(!function_exists('exfood_sale_badge')){
	function exfood_sale_badge($sale_price){
		if($sale_price !='' ){ ?>
			<div class="exfd-ribbon"><span>Sale</span></div>
		<?php }
	}
}


add_action( 'wp_ajax_exfood_booking_info', 'ajax_exfood_booking_info' );
add_action( 'wp_ajax_nopriv_exfood_booking_info', 'ajax_exfood_booking_info' );

function ajax_exfood_booking_info(){
	$food_nounce = sanitize_text_field($_POST['food_nounce']);
	if ( ! wp_verify_nonce( $food_nounce, 'food_nounce_secure' ) ) {
	    exit;
	}
	if(isset($_POST['id_food']) && $_POST['id_food']!=''){
		global $atts,$id_food;
		$id_food = intval($_POST['id_food']);
		exfood_template_plugin('modal',true);
	}else{
		echo 'error';
	}
	exit;	
}

/*--- Booking button ---*/
if(!function_exists('exfood_booking_button_html')){
	function exfood_booking_button_html($style) {
		$html = '<a href="'.get_the_permalink(get_the_ID()).'" class="exstyle-'.esc_attr($style).'-button">'.esc_html__( 'Order', 'wp-food' ).'</a>';
		$exfood_enable_order = exfood_get_option('exfood_booking');
		if ($exfood_enable_order !='disable') {
			global $id_food;
			$id_food = get_the_ID();
			$html = do_shortcode( '[ex_food_wooform id="" hide_pm="1"]');
		}
		//inline button
		// $options = get_post_meta( get_the_ID(), 'exfood_addition_data', true );
		// if(empty($options)){
			
		// }
		echo '<div class="exbt-inline">'.$html.'</div>';
		
	}
}

if(!function_exists('exfood_add_to_cart_form_shortcode')){
	function exfood_add_to_cart_form_shortcode( $atts ) {
		$hide_pm = isset( $atts['hide_pm']) ? $atts['hide_pm'] : '';
		ob_start();
		exfood_build_in_cart_form($hide_pm);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		return '<div class="exfood-woocommerce woocommerce">' . ob_get_clean() . '</div>';
	}
}
add_shortcode( 'ex_food_wooform', 'exfood_add_to_cart_form_shortcode' );

function exfood_build_in_cart_form($hide_pm){
	global $attr,$id_food;
	?>
	<div class="exfood-buildin-cart">
		<form class="exform" method="post" action="<?php esc_url(home_url())?>">
			<input type="hidden" name="food_id" value="<?php echo esc_attr($id_food); ?>">
			<div class="exfood-sm">
				<div class="exfood-quantity">
					<?php if($hide_pm!='1'){?>
						<input type="button" class="minus_food" value="-">
						<input type="number" min="1" name="food_qty" class="food_qty" value="1">
						<input type="button" class="plus_food" value="+">
					<?php }else{?>
						<input type="hidden" min="1" name="food_qty" class="food_qty" value="1">
					<?php }?>
				</div>
				<button class="exstyle-button-bin ex-cart">
					<span class="ex-order"><?php esc_html_e( 'Order', 'wp-food' );?></span>
					<span class="ex-added exhidden"><?php esc_html_e( 'Added to cart', 'wp-food' );?></span>
				</button>
			</div>
		</form>
	</div>
	<?php
}

function register_exfood_session()
{
  if( !session_id() )
  {
    session_start();
  }
  $user_ID= get_current_user_id(); 
  $_SESSION['ex_userid'] = $user_ID;
}
add_action('init', 'register_exfood_session');

// exfood price
function exfood_price_with_currency($price,$id_food=false){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if(isset($id_food) && is_numeric($id_food)){
		$id = $id_food;
	}else{ $id = get_the_ID();}
	$product_exist = get_post_meta( $id, 'exfood_product', true );
    if($price=='' && exfood_get_option('exfood_booking') =='woo' && is_plugin_active( 'woocommerce/woocommerce.php' ) && $product_exist!='' && is_numeric($product_exist)){
    	$product = wc_get_product ($product_exist);
    	if($product!==false) {
    		return $product->get_price_html();
    	}
    }
	if($price=='' || !is_numeric($price)){ return;}
	$num_decimal = exfood_get_option('exfood_num_decimal');
	$decimal_sep = exfood_get_option('exfood_decimal_sep');
	$thousand_sep = exfood_get_option('exfood_thousand_sep');
	if ($num_decimal > 0) {
	    $price = number_format((float)$price, $num_decimal, $decimal_sep, $thousand_sep);
	}
	$currency = exfood_get_option('exfood_currency');
	if ($currency=='') {
		$currency ='$';
	}
	$position = exfood_get_option('exfood_position');
	if($position==0){
		$price = $price.$currency;
	}else{
		$price = $currency.$price;
	}
	return $price;
}


function exfood_update_total_price($data,$no_cur=false){
	$total_price = 0;
 	foreach ($data as $key => $value) {
 		$food_id = $value['food_id'];
 		$price_food = get_post_meta( $food_id, 'exfood_price', true );
 		$saleprice = get_post_meta( $food_id, 'exfood_sale_price', true );
    	$price_food = $saleprice!='' && is_numeric($saleprice) ? $saleprice : $price_food;
    	$price_food = is_numeric($price_food) ? $price_food : 0;
    	foreach ($value as $key_it => $item_meta) {
    		if(is_array($item_meta)){
				foreach ($item_meta as $val) {
					$val = explode("|",$val);
					$price = isset ($val[2]) ? $val[2] : 0;
					$price_food = $price_food + $price*1;
				}
			}
    	}

    	$total_price = $total_price + $price_food * $value['food_qty'];
 	}
 	if(isset($no_cur) && $no_cur==1){
 		return $total_price;
 	}else{
	 	return exfood_price_with_currency($total_price);
	 }
}

function exfood_woo_cart_icon_html(){
	global $cart_icon;
	if(!isset($cart_icon) || $cart_icon!='on'){
		$cart_icon = 'on';
	}else if($cart_icon =='on'){
		return;
	}
	if(is_admin() || exfood_get_option('exfood_booking') =='woo' && !function_exists('wc_get_cart_url')){ return;}
	?>
	<div class="exfd-shopping-cart">
    	<div class="exfd-cart-parent">
    		<a href="javascript:;">
				<img src="<?php echo EXFOOD_PATH.'css/exfdcart2.svg';?>" alt="image">
				<?php if ( exfood_get_option('exfood_booking') !='woo' ) { ?>
					<span class="exfd-cart-count"><?php echo !empty($_SESSION['ex_userfood']) ? wp_kses_post(count($_SESSION['ex_userfood'])) : 0; ?></span>
				<?php }else{?>
					<span class="exfd-cart-num"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
				<?php }?>
			</a>
		</div>
    </div>
    <div class="exfd-overlay"></div>
    <div class="exfd-cart-content">
    	<span class="exfd-close-cart">&times;</span>
    	<?php exfood_template_plugin('cart-mini',1);?>
	</div>
	<?php
}

add_action( 'wp_ajax_exfood_add_cart_item', 'ajax_exfood_add_cart_item' );
add_action( 'wp_ajax_nopriv_exfood_add_cart_item', 'ajax_exfood_add_cart_item' );

function ajax_exfood_add_cart_item(){
	$data_food = array();
	parse_str($_POST['data'], $data_food);
	if (!$_SESSION['ex_userfood'] || $_SESSION['ex_userfood']=='' || !is_array($_SESSION['ex_userfood'])) {
		$_SESSION['ex_userfood'] = array();
	}
	$_SESSION['ex_userfood'][] = $data_food;
	ob_start();
	exfood_template_plugin('cart-mini',1);
	$cart_update = ob_get_contents();
	ob_end_clean();
	$output =  array('status'=>1,'cart_content'=> $cart_update);
	echo str_replace('\/', '/', json_encode($output));
	exit;	
}

add_action( 'wp_ajax_exfood_remove_cart_item', 'ajax_exfood_remove_cart_item' );
add_action( 'wp_ajax_nopriv_exfood_remove_cart_item', 'ajax_exfood_remove_cart_item' );

function ajax_exfood_remove_cart_item(){

	$key = $_POST['it_remove'];
	if(is_numeric($key)){
		unset($_SESSION['ex_userfood'][$key]);
		$avari = 1;
	}else{
		$avari = 0;
	}
	$total_price = exfood_update_total_price($_SESSION['ex_userfood'],1);
	$mes = '';
	if(empty($_SESSION['ex_userfood'])){
		$mes = '<div class="exfood-warning">'.esc_html__('Your cart is currently empty.','wp-food').'</div>';
	}
	$output =  array('status'=>$avari,'update_total'=> exfood_price_with_currency($total_price), 'message'=> $mes, 'total'=> $total_price);
	echo str_replace('\/', '/', json_encode($output));
	exit;	
}

function exfood_cart_shortcode(){
	ob_start();?>
	<div class="exfood-cart-shortcode exfd-cart-content exfood-buildin-cart">
    	<?php exfood_template_plugin('cart',1);?>
	</div>
	<?php
	$cart_content = ob_get_contents();
	ob_end_clean();
	return $cart_content;
}

add_shortcode( 'exfood_cart', 'exfood_cart_shortcode' );

function exfood_checkout_shortcode(){
	ob_start();?>
	<div class="exfood-checkout-shortcode">
    	<?php exfood_template_plugin('checkout',1);?>
	</div>
	<?php
	$cart_content = ob_get_contents();
	ob_end_clean();
	return $cart_content;
}

add_shortcode( 'exfood_checkout', 'exfood_checkout_shortcode' );

function exfood_location_field_html(){

	ob_start();
	?>
	<select class="ex-ck-select exfd-choice-locate" name="_location">
		<?php 
	        	echo '<option disabled selected value>'.esc_html__( '-- Select --', 'wp-food' ) .'</option>';
	    ?>
	</select>
	<?php
	$loca = ob_get_contents();
	ob_end_clean();
	return $loca;
}

add_action( 'wp_ajax_exfood_update_cart_item', 'ajax_exfood_update_cart_item' );
add_action( 'wp_ajax_nopriv_exfood_update_cart_item', 'ajax_exfood_update_cart_item' );

function ajax_exfood_update_cart_item(){
	session_start();
	$key = $_POST['it_update'];
	$qty = $_POST['qty'];
	if(is_numeric($key) && isset($_SESSION['ex_userfood'][$key])){
		$_SESSION['ex_userfood'][$key]['food_qty'] = $qty;
		$avari = 1;
	}else{
		$avari = 0;
		$number_item = count($_SESSION['ex_userfood']);
		$output =  array('status'=>$avari,'info_text'=> esc_html__("This item does not exist in cart","wp-food"),'number_item'=>$number_item);
		echo str_replace('\/', '/', json_encode($output));
		exit;
	}
	$total_price = exfood_update_total_price($_SESSION['ex_userfood']);

	$food_id = $_SESSION['ex_userfood'][$key]['food_id'];
	$price_food = get_post_meta( $food_id, 'exfood_price', true );
	$saleprice = get_post_meta( $food_id, 'exfood_sale_price', true );
	$price_food = $saleprice!='' && is_numeric($saleprice) ? $saleprice : $price_food;
	$price_food = is_numeric($price_food) ? $price_food : 0;
	foreach ($_SESSION['ex_userfood'][$key] as $key_it => $item_meta) {
		if(is_array($item_meta)){
			foreach ($item_meta as $val) {
				$val = explode("|",$val);
				$price = isset ($val[2]) ? $val[2] : 0;
				$price_food = $price_food + $price*1;
			}
		}
	}

	$total_price = $price_food * $_SESSION['ex_userfood'][$key]['food_qty'];
	$total_cart = exfood_update_total_price($_SESSION['ex_userfood'],1);
	$output =  array('status'=>$avari,'update_price'=> exfood_price_with_currency($total_price),'update_total'=> exfood_price_with_currency($total_cart), 'total'=> $total_price);
	echo str_replace('\/', '/', json_encode($output));
	exit;	
}

function exfood_checkemail($email) {
	if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}else{
		return false;
	}
}