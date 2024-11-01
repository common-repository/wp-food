<?php 
get_header();
wp_enqueue_style( 'ionicon' );?>
<div class="ex-food-single">
    <div class="ex-content-food ex-fdlist">
      <?php if (have_posts()) : while (have_posts()) : the_post(); 
        $id_food = get_the_ID();
        $protein = get_post_meta( $id_food, 'exfood_protein', true );
        $calo = get_post_meta( $id_food, 'exfood_calo', true );
        $choles = get_post_meta( $id_food, 'exfood_choles', true );
        $fibel = get_post_meta( $id_food, 'exfood_fibel', true );
        $sodium = get_post_meta( $id_food, 'exfood_sodium', true );
        $carbo = get_post_meta( $id_food, 'exfood_carbo', true );
        $fat = get_post_meta( $id_food, 'exfood_fat', true );
        $gallery = get_post_meta( $id_food, 'exfood_gallery', true );
        $custom_data = get_post_meta( $id_food, 'exfood_custom_data_gr', true );
        $price = get_post_meta( $id_food, 'exfood_price', true );
        $saleprice = get_post_meta( $id_food, 'exfood_sale_price', true );
        $currency = exfood_get_option('exfood_currency');
        $num_decimal = exfood_get_option('exfood_num_decimal');
        $decimal_sep = exfood_get_option('exfood_decimal_sep');
        if ($currency=='') {
          $currency ='$';
        }
        $position = exfood_get_option('exfood_position');
        $price = exfood_price_with_currency($price);
        if ($saleprice > 0 && is_numeric($saleprice)){
          $saleprice = exfood_price_with_currency($saleprice);
        }
        echo '<input type="hidden"  name="ajax_url" value="'.esc_url(admin_url( 'admin-ajax.php' )).'">';?>
  		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
  			<?php if(has_post_thumbnail()){?>
          <div class="food-img">
          	<div class="first-img">
    	        <?php the_post_thumbnail('full');?>
            </div>
          </div>
        <?php } ?>
        <div class="food-description">
          <h3><?php echo get_the_title( $id_food ); ?></h3>
            <div class="exfd_nutrition">
              <ul>
                <?php if($protein!=''){ ?>
                  <li>
                    <span><?php esc_html_e('Protein','wp-food'); ?></span><?php echo wp_kses_post($protein);?>
                  </li>
                <?php }if($calo!=''){ ?>
                  <li><span><?php esc_html_e('Calories','wp-food'); ?></span><?php echo wp_kses_post($calo);?></li>
                <?php }if($choles!=''){ ?>
                  <li><span><?php esc_html_e('Cholesterol','wp-food'); ?></span><?php echo wp_kses_post($choles);?></li>
                <?php }if($fibel!=''){ ?>
                  <li><span><?php esc_html_e('Dietary fibre','wp-food'); ?></span><?php echo wp_kses_post($fibel);?></li>
                <?php }if($sodium!=''){ ?>
                  <li><span><?php esc_html_e('Sodium','wp-food'); ?></span><?php echo wp_kses_post($sodium);?></li>
                <?php }if($carbo!=''){ ?>
                  <li><span><?php esc_html_e('Carbohydrates','wp-food'); ?></span><?php echo wp_kses_post($carbo);?></li>
                <?php }if($fat!=''){ ?>
                  <li><span><?php esc_html_e('Fat total','wp-food'); ?></span><?php echo wp_kses_post($fat);?></li>
                <?php }
                ?>
                <div class="exfd_clearfix"></div>
              </ul>
            </div>
            <h5>
            <?php if ($saleprice !='') {?>
              <del><?php echo wp_kses_post($price); ?></del> <ins><?php echo wp_kses_post($saleprice); ?></ins>
            <?php }else{
              echo wp_kses_post($price);
            } ?>
            </h5>
            <p><?php the_content();?></p>
          </div>

  		</div>
		<?php endwhile; 
		endif; ?>
    </div><!--end post-->
    <div class="exfood-sidebar">
      <?php 
      if(is_active_sidebar('exfood-sidebar')){
        dynamic_sidebar( 'exfood-sidebar' );
      }
      ?>
    </div>
    <div class="exfd_clearfix"></div>
</div><!--end main-content-->
<?php 
get_footer(); ?>