<?php
function exfood_custom_css(){
    ob_start();
    $exfood_color = exfood_get_option('exfood_color');

    $hex  = str_replace("#", "", $exfood_color);
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
    if($exfood_color!=''){
    	?>

        .ex-fdlist .exstyle-2 figcaption .exstyle-2-button,
        .ex-fdlist .exstyle-3 figcaption .exstyle-3-button,
        .exstyle-button-bin,
        .ex-loadmore .loadmore-exfood:hover,
        .ex_close{background:<?php echo esc_attr($exfood_color);?>;}

        .fdstyle-list-1 .fdlist_1_des button{
            border-color: <?php echo esc_attr($exfood_color);?>;
        }
        .ex-fdlist .exstyle-2 figcaption h5,
        .ex-fdlist .exstyle-3 figcaption h5,
        .fdstyle-list-1 .fdlist_1_title .fdlist_1_price,
        .ex-fdlist.ex-fdcarousel .ex_s_lick-dots li.ex_s_lick-active button:before,
        .exfd-admin-review > span > i.icon,
        .ex-fdlist.ex-fdcarousel .ex_s_lick-dots li button:before{
            color: <?php echo esc_attr($exfood_color);?>;
        }
        .ex-loadmore .loadmore-exfood{
            border-color: <?php echo esc_attr($exfood_color);?>;
            color: <?php echo esc_attr($exfood_color);?>;
        }
        .ex-loadmore .loadmore-exfood span:not(.load-text),
        .fdstyle-list-1 .exfd-icon-plus:before,
        .fdstyle-list-1 .exfd-icon-plus:after{
            background-color: <?php echo esc_attr($exfood_color);?>;
        }
        @media screen and (max-width: 768px){

        }
        @media screen and (max-width: 992px) and (min-width: 769px){

        }
        <?php
    }
    $exfood_font_family = exfood_get_option('exfood_font_family');
    $main_font_family = explode(":", $exfood_font_family);
    $main_font_family = $main_font_family[0];
    if($exfood_font_family!=''){?>
        .ex-fdlist,
        .exfood-thankyou,
        .exfood-cart-shortcode, .exfood-checkout-shortcode{font-family: "<?php echo esc_html($main_font_family);?>", sans-serif;}
        <?php
    }
    $exfood_font_size = exfood_get_option('exfood_font_size');
    if($exfood_font_size!=''){?>
        .ex-fdlist,
        .exfood-thankyou,
        .exfood-cart-shortcode, .exfood-checkout-shortcode{font-size: <?php echo esc_html($exfood_font_size);?>;}
        <?php
    }
    $exfood_ctcolor = exfood_get_option('exfood_ctcolor');
    if($exfood_ctcolor!=''){?>
    	.ex-fdlist,
        .exfood-cart-shortcode, .exfood-checkout-shortcode,
        .exfd-table-1 td{color: <?php echo esc_html($exfood_ctcolor);?>;}
        <?php
    } ?>

    <?php

    $exfood_custom_css = exfood_get_option('exfood_custom_css','exfood_custom_code_options');
    if($exfood_custom_css!=''){
    	echo wp_kses_post($exfood_custom_css);
    }
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}