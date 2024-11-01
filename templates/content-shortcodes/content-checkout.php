<?php
$userfood = isset($_SESSION['ex_userfood']) && !empty($_SESSION['ex_userfood']) ? $_SESSION['ex_userfood'] : array();
if(empty($userfood)){
  echo
  '<div class="exfood-warning">'.esc_html__('Oops, There is no item in your Cart!','wp-food').'</div>';
  return;
}
$checkout_page = exfood_get_option('exfood_checkout_page','exfood_advanced_options');

$fname = exfood_get_option('exfood_ck_fname','exfood_advanced_options');
$lname = exfood_get_option('exfood_ck_lname','exfood_advanced_options');
$order_type = exfood_get_option('exfood_ck_order_type','exfood_advanced_options');
$date_require = exfood_get_option('exfood_ck_date','exfood_advanced_options');
$time = exfood_get_option('exfood_ck_time','exfood_advanced_options');
$location = exfood_get_option('exfood_ck_location','exfood_advanced_options');
$store = exfood_get_option('exfood_ck_store','exfood_advanced_options');
$address = exfood_get_option('exfood_ck_address','exfood_advanced_options');
$phone = exfood_get_option('exfood_ck_phone','exfood_advanced_options');
$email = exfood_get_option('exfood_ck_email','exfood_advanced_options');
$note = exfood_get_option('exfood_ck_note','exfood_advanced_options');
$captcha = exfood_get_option('exfood_ck_captcha','exfood_advanced_options');
$captcha_key = exfood_get_option('exfood_captcha_key','exfood_advanced_options');

?>
<div class="exfd_clearfix"></div>
<div class="exfood-buildin-checkout">
  <input type="hidden"  name="ajax_url" value="<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>">
  <form class="exform-checkout excheckout" method="post" action="<?php echo esc_url(get_the_permalink($checkout_page)); ?>">
    <div class="exfood-cart-shortcode exfd-cart-content exfood-builling-details">
      <div class="exfood-mulit-steps">
        <div class="exfood-cart-step active"><?php esc_html_e('Your cart','wp-food');?></div>
        <div class="exfood-checount-step active"><?php esc_html_e('Checkout','wp-food');?></div>
        <!-- <div class="exfood-confirm-step"><?php esc_html_e('Confirm your order','wp-food');?></div> -->
      </div>
      <p class="<?php echo esc_attr($fname!='no' ? 'ex-required' : '');?>">
        <label><?php esc_html_e('First name','wp-food');?></label>
        <input type="text" name="_fname" value="">
      </p>
      <p class="<?php echo esc_attr($lname!='no' ? 'ex-required' : '');?>">
        <label><?php esc_html_e('Last name','wp-food');?></label>
        <input type="text" name="_lname" value="">
      </p>
      <p>
        <label class="exfd-container exfd-inline"><?php esc_html_e('Order and  wait delivery','wp-food');?>
          <input class="exfd-choice-order" type="radio" checked="checked" name="_type" value="order-delivery">
          <span class="exfd-checkmark"></span>
        </label>
      </p>
      <p class="<?php echo esc_attr($date_require!='no' ? 'ex-required' : '');?>">
        <label><?php esc_html_e('Date Delivery','wp-food');?></label>
        <select class="ex-ck-select" name="_date">
        <?php
          $cure_time =  strtotime("now");
          $gmt_offset = get_option('gmt_offset');
          if($gmt_offset!=''){
            $cure_time = $cure_time + ($gmt_offset*3600);
          }
          $date = strtotime(date('Y-m-d', $cure_time));
          $dis_date = exfood_get_option('exfood_ck_disdate','exfood_advanced_options');
          $maxl = 10;
          for ($i = 0 ; $i<= $maxl; $i ++ ) {
            $date_un = strtotime("+$i day", $date);
            if(!empty($dis_date) && in_array(date('Y-m-d',$date_un), $dis_date )){
              $maxl = $maxl +1;
            }else{
              $date_fm = date_i18n(get_option('date_format'), $date_un);
              echo '<option value="'. esc_attr($date_un) .'" >'. $date_fm .'</option>';
            }
          }  
        ?>
        </select>
      </p>
      <p class="<?php echo esc_attr($time!='no' ? 'ex-required' : '');?>">
        <label><?php esc_html_e('Time Delivery','wp-food');?></label>
      <?php 
        $array_time = array();
        $array_time = exfood_get_option('exfood_ck_times','exfood_advanced_options');
        if (empty($array_time)) {
          echo '<input type="text" name="_time" value="">';
        }else{
          echo '<select class="ex-ck-select" name="_time">';
            $count_time = 3;
            foreach ($array_time as $time_option) {
              $count_time = $count_time -1;
              if ($count_time < 0) {
                break;
              }
              echo '<option value="'. $time_option .'">'. $time_option .'</option>';
            }
          echo '</select>';
        }
      ?>
      </p>
      <p class="exfd-hide-order <?php echo esc_attr($address!='no' ? 'ex-required' : '');?>">
        <label><?php esc_html_e('Address','wp-food');?></label>
        <input type="text" name="_address" value="">
      </p>
      <p class="<?php echo esc_attr($phone!='no' ? 'ex-required' : '');?>">
        <label><?php esc_html_e('Phone number','wp-food');?></label>
        <input type="text" name="_phone" value="">
      </p>
      <p class="<?php echo esc_attr($email!='no' ? 'ex-required' : '');?>">
        <label><?php esc_html_e('Email','wp-food');?></label>
        <input type="email" name="_email" value="">
      </p>
      <p class="<?php echo esc_attr($note!='no' ? 'ex-required' : '');?>">
        <label><?php esc_html_e('Order notes','wp-food');?></label>
        <textarea name="_note" value=""></textarea>
      </p>
    </div>
    <div class="exfood-cart-shortcode exfd-cart-content exfood-buildin-cart">
      <?php 
      global $checkout;
      $checkout = 1;
      exfood_template_plugin('cart',1);?>
    </div>
    <div class="excheckout-submit exfood-cart-shortcode exfd-cart-content">
      <?php if($captcha!='no' && $captcha_key!=''){
        wp_enqueue_script( 'ex-google-recaptcha')?>
        <input type="hidden" class="captcha_mes" name="captcha_mes" value="<?php esc_html_e( 'Please vefiry captcha', 'wp-food' )?>">
        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($captcha_key);?>"></div>
      <?php }?>

      <!-- Paypal -->
      <!-- <input type="hidden" name="payment_method" value="paypal">   -->

      <button class="exstyle-button-bin">
        <span class="ex-order"><?php esc_html_e( 'Place order', 'wp-food' );?></span>
      </button>
    </div>
  </form>
</div>