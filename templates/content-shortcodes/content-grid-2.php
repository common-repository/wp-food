<?php
  $customlink = EX_WPFood_customlink(get_the_ID());
  global $number_excerpt,$img_size;
  if($img_size==''){$img_size = 'exfood_400x400';}
  $price = get_post_meta( get_the_ID(), 'exfood_price', true );
  $saleprice = get_post_meta( get_the_ID(), 'exfood_sale_price', true );
  $price = exfood_price_with_currency($price);
  if ($saleprice > 0 && is_numeric($saleprice)){
    $saleprice = exfood_price_with_currency($saleprice);
  }
?>
<figure class="exstyle-2 tppost-<?php the_ID();?>">
  <div class="exstyle-2-image">
    <a class="exfd_modal_click" href="<?php echo esc_url($customlink); ?>">
      <?php the_post_thumbnail($img_size); ?>
    </a>
    <?php exfood_sale_badge($saleprice); ?>
  </div><figcaption>
    <h3><a class="exfd_modal_click" href="<?php echo esc_url($customlink); ?>"><?php the_title(); ?></a></h3>
    <h5><span>
        <?php if ($saleprice !='') {?>
        <del><?php echo wp_kses_post($price); ?></del> <ins><?php echo wp_kses_post($saleprice); ?></ins>
      <?php }else{
        echo wp_kses_post($price);
      } ?>
    </span>
    </h5>
    <?php 
      if(has_excerpt(get_the_ID())){?>
          <p><?php echo wp_trim_words(get_the_excerpt(),$number_excerpt,'...'); ?></p>
      <?php }
      
    ?>
    <?php $exfood_enable_order = exfood_get_option('exfood_booking');
      if ($exfood_enable_order != 'disable') {
        exfood_booking_button_html(2);
      }
     ?>
  </figcaption>
</figure>