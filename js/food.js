!function(x){"use strict";x(document).ready(function(){var l;function t(t,a){t.closest("li").addClass("loading");var e=x(".exfd-cart-buildin input[name=ajax_url]").val(),o={action:"exfood_update_cart_item",it_update:t.data("update"),qty:a};return x.ajax({type:"post",url:e,dataType:"json",data:o,success:function(e){"0"!=e?"0"==e.status?(alert(e.info_text),t.closest("li").find(".exfood-close").trigger("click"),e.number_item):(t.closest("li").removeClass("loading"),t.closest(".exfood-quantity").find(".food_qty").val(a),t.closest("li").find(".exfood-cart-price").html(e.update_price),x(".exfood-total > span").html(e.update_total),x(".exfood-total > input[name=total_price]").val(e.total)):alert("error")}}),!1}l=x("html").width(),x(window).resize(function(){x("html").css("max-width",""),l=x("html").width(),"block"==x(".ex_modal.exfd-modal-active").css("display")&&x("html").css("max-width",l)}),x(".ex-fdlist.ex-food-plug .parent_grid .ctgrid, .ex-fdlist .ctlist").on("click","a",function(e){e.preventDefault();var o,t=x(this).closest(".ex-fdlist ").attr("id"),a=x("#"+t).hasClass("table-layout")?"table":"";if(x("#"+t).hasClass("ex-fdcarousel")&&(a="Carousel"),!(o="table"!=a?x(this).closest(".item-grid"):x(this).closest("tr")).hasClass("ex-loading")){o.addClass("ex-loading");var d=o.data("id_food"),i=x("#"+t+" input[name=ajax_url]").val(),s={action:"exfood_booking_info",id_food:d,id_crsc:t,food_nounce:x("#"+t+" input[name=food_nounce]").val()};return x.ajax({type:"post",url:i,dataType:"html",data:s,success:function(e){if("0"!=e)if(""==e)x(".row.loadmore").html("error");else{x("#food_modal").empty(),x("#food_modal").append(e);var t=x("#food_modal .modal-content").find(".variations_form");t.each(function(){x(this).wc_variation_form()}),t.trigger("check_variations"),t.trigger("reset_image"),"function"==typeof x.fn.init_addon_totals&&x("body").find(".cart:not(.cart_group)").each(function(){x(this).init_addon_totals()}),o.removeClass("ex-loading"),x("html").css("max-width",l),x("html").fadeIn("slow",function(){x(this).addClass("exfd-hidden-scroll")}),x("#food_modal").css("display","block"),x("#food_modal").addClass("exfd-modal-active");var a=x("#food_modal .exfd-modal-carousel").attr("rtl_mode");x("#food_modal .exfd-modal-carousel").EX_ex_s_lick({dots:!0,slidesToShow:1,infinite:!0,speed:500,fade:!0,cssEase:"linear",arrows:!1,rtl:"yes"==a})}else x(".row.loadmore").html("error")}}),!1}}),x(".ex-food-plug #food_modal").on("click",".ex_close",function(e){e.preventDefault();x(this);x("#food_modal").css("display","none"),x("html").removeClass("exfd-hidden-scroll"),x("#food_modal").removeClass("exfd-modal-active"),x("html").css("max-width","")}),x(".ex-food-plug .ex_modal").on("click",function(e){"ex_modal exfd-modal-active"==e.target.className&&(e.preventDefault(),x(this).css("display","none"),x("html").removeClass("exfd-hidden-scroll"),x(this).removeClass("exfd-modal-active"),x("html").css("max-width",""))}),x(".exfd-shopping-cart").on("click",function(e){return e.preventDefault(),x(".exfd-cart-content").addClass("excart-active"),x(".exfd-overlay").addClass("exfd-overlay-active"),!1}),x(".exfd-cart-content .exfd-close-cart, .exfd-overlay").on("click",function(e){return x(".exfd-cart-content").removeClass("excart-active"),x(".exfd-overlay").removeClass("exfd-overlay-active"),!1}),x(".ex-fdlist.ex-food-plug").on("click",".exfd-choice",function(e){return e.preventDefault(),x(this).prev(".ex-hidden").find("form").length?(x(this).addClass("loading"),x(this).prev(".ex-hidden").find("form button").trigger("click")):x(this).prev(".ex-hidden").find("a").trigger("click"),!1}),x(".ex-food-plug .loadmore-exfood").on("click",function(){if(!x(this).hasClass("disable-click")){var e=x(this),t=e.closest(".ex-fdlist").attr("id");!function(a,o,d,e){"loadmore"!=a&&x("#"+d+" .page-numbers").removeClass("disable-click"),o.addClass("disable-click");var i=x("#"+d+" input[name=num_page_uu]").val();"loadmore"==a?x("#"+d+" .loadmore-exfood").addClass("loading"):x("#"+d).addClass("loading");var s=x("#"+d).hasClass("table-layout")?"table":"grid";x("#"+d).hasClass("list-layout")&&(s="list");var t=x("#"+d+" input[name=param_query]").val(),l=x("#"+d+" input[name=param_ids]").val(),n=x("#"+d+" input[name=current_page]").val(),r=x("#"+d+" input[name=num_page]").val(),c=x("#"+d+" input[name=ajax_url]").val(),f=x("#"+d+" input[name=food_nounce]").val(),u=x("#"+d+" input[name=param_shortcode]").val(),m={action:"exfood_loadmore",param_query:t,param_ids:l,id_crsc:d,page:""!=e?e:1*n+1,param_shortcode:u,layout:s,food_nounce:f};x.ajax({type:"post",url:c,dataType:"json",data:m,success:function(e){if("0"!=e){if("loadmore"==a)i=1*i+1,x("#"+d+" input[name=num_page_uu]").val(i),""==e.html_content?x("#"+d+" .loadmore-exfood").remove():(x("#"+d+" input[name=current_page]").val(1*n+1),"table"==s?x("#"+d+" table tbody").append(e.html_content):"list"==s?x("#"+d+" .ctlist").append(e.html_content):(x("#"+d+" .ctgrid").append(e.html_content),setTimeout(function(){x("#"+d+" .item-grid").addClass("active")},200)),x("#"+d+" .loadmore-exfood").removeClass("loading"),o.removeClass("disable-click")),i==r&&x("#"+d+" .loadmore-exfood").remove();else{var t="";t=x("table"==s?"#"+d+" table tbody":"list"==s?"#"+d+" .ctlist":"#"+d+" .ctgrid"),x(t).fadeOut({duration:0,complete:function(){x(this).empty()}}),x("#"+d).removeClass("loading"),t.append(e.html_content).fadeIn()}""!=e.html_modal&&x("#"+d+" .ex-hidden .exp-mdcontaner").append(e.html_modal).fadeIn(),x("#"+d).hasClass("extp-masonry")&&!x("#"+d).hasClass("column-1")&&"function"==typeof imagesLoaded&&x("#"+d+".extp-masonry .ctgrid").imagesLoaded(function(){x("#"+d+".extp-masonry .ctgrid").masonry("reloadItems"),x("#"+d+".extp-masonry .ctgrid").masonry({isInitLayout:!1,horizontalOrder:!0,itemSelector:".item-grid"})})}else x("#"+d+" .loadmore-exfood").html("error")}})}("loadmore",e,t,"")}}),x(".exfd-cart-content").on("click",".exfood-close",function(e){e.preventDefault();var t=x(this),a=x(".exfd-cart-buildin input[name=ajax_url]").val(),o=t.data("remove");t.closest("li").addClass("loading");var d={action:"exfood_remove_cart_item",it_remove:o};return x.ajax({type:"post",url:a,dataType:"json",data:d,success:function(e){"0"!=e?(""!=e.message&&(x(".exfd-cart-content").append(e.message),x(".exfd-cart-content .exfd-cart-buildin").fadeOut(300,function(){x(this).remove()})),"0"==e.status||t.closest("li").fadeOut(300,function(){t.closest("li").remove(),x(".exfd-cart-count").html(x(".exfd-cart-buildin > ul > li").length),x(".exfood-total > span").html(e.update_total),x(".exfood-total > input[name=total_price]").val(e.total)})):alert("error")}}),!1}),jQuery("#food_modal").on("click",".minus_food",function(){var e=parseInt(jQuery(this).closest(".exfood-quantity").find(".food_qty").val())-1;0<e&&jQuery(this).closest(".exfood-quantity").find(".food_qty").val(e)}),jQuery("#food_modal").on("click",".plus_food",function(){var e=parseInt(jQuery(this).closest(".exfood-quantity").find(".food_qty").val())+1;jQuery(this).closest(".exfood-quantity").find(".food_qty").val(e)}),jQuery(".exfood-cart-shortcode").on("click",".minus_food",function(){var e=parseInt(jQuery(this).closest(".exfood-quantity").find(".food_qty").val())-1;0<e?t(jQuery(this),e):jQuery(this).closest("li").find(".exfood-close").trigger("click")}),jQuery(".exfood-cart-shortcode").on("click",".plus_food",function(){var e=parseInt(jQuery(this).closest(".exfood-quantity").find(".food_qty").val())+1;t(jQuery(this),e)}),jQuery(".ex-fdcarousel").each(function(){var e=jQuery(this),t=e.attr("id"),a=e.data("slidesshow"),o=e.data("slidesscroll");""==a&&(a=3),""==o&&(o=a);0<e.data("startit")&&e.data("startit");var d=e.data("autoplay"),i=e.data("speed"),s=e.data("rtl"),l=0<e.data("start_on")?e.data("start_on"):0;if("0"==e.data("infinite"))var n=0;else n="yes"==e.data("infinite")||"1"==e.data("infinite");!function(e,t,a,o,d,i,s,l){jQuery(e).EX_ex_s_lick({infinite:t,initialSlide:a,rtl:"yes"==o,prevArrow:'<button type="button" class="ex_s_lick-prev"></button>',nextArrow:'<button type="button" class="ex_s_lick-next"></button>',slidesToShow:d,slidesToScroll:i,dots:!0,autoplay:1==s,autoplaySpeed:""!=l?l:3e3,arrows:!0,centerMode:!1,focusOnSelect:!1,ariableWidth:!0,adaptiveHeight:!0,responsive:[{breakpoint:1024,settings:{slidesToShow:d,slidesToScroll:i}},{breakpoint:768,settings:{slidesToShow:2,slidesToScroll:1}},{breakpoint:480,settings:{slidesToShow:1,slidesToScroll:1}}]})}("#"+t+" .ctgrid",n,l,s,a,o,d,i)}),jQuery(window).load(function(e){jQuery(".ex-fdcarousel.ld-screen").each(function(){jQuery(this).addClass("at-childdiv")})}),setTimeout(function(){jQuery(".ex-fdcarousel.ld-screen").each(function(){jQuery(this).addClass("at-childdiv")})},7e3),x("body").on("submit",".exform",function(e){e.preventDefault();var a=x(this);x(this).addClass("loading");var t={action:"exfood_add_cart_item",data:x(e.target).serialize()},o=x(".ex-fdlist input[name=ajax_url]").val();return jQuery.ajax({type:"post",url:o,dataType:"json",data:t,success:function(e){if(0!=e){""!=e.cart_content&&(x(".exfd-cart-content .exfd-cart-buildin").fadeOut(300,function(){x(this).remove()}),x(".exfd-cart-content .exfood-warning").fadeOut(300,function(){x(this).remove()}),x(".exfd-cart-content").append(e.cart_content),setTimeout(function(){x(".exfd-cart-count").html(x(".exfd-cart-buildin > ul > li").length)},500),a.find(".ex-order").addClass("exhidden"),a.find(".ex-added").removeClass("exhidden")),a.removeClass("loading"),a.closest(".ex-hidden").next().hasClass("exfd-choice")&&a.closest(".ex-hidden").next().removeClass("loading");var t=a.closest(".ex-fdlist ").attr("id");x("#"+t).hasClass("table-layout")}}}),!1}),x("body").on("submit",".exform-checkout",function(e){if(!x(this).hasClass("loading")){e.preventDefault();var a=x(this);x(this).addClass("loading");var t={action:"exfood_user_checkout",data:x(e.target).serialize()},o=x(".exfood-buildin-checkout input[name=ajax_url]").val();return jQuery.ajax({type:"post",url:o,dataType:"json",data:t,success:function(e){if(0!=e){a.removeClass("loading"),"2"==e.status?(x(".exfood-validate-warning").remove(),x(e.html_content).insertAfter(".exfood-mulit-steps"),"undefined"!=typeof grecaptcha&&grecaptcha.reset()):(x(".exform-checkout").fadeOut(300,function(){x(this).remove()}),x(".exfood-checkout-shortcode .exfood-buildin-checkout").append(e.html_content));var t=x(window).height();x("html,body").animate({scrollTop:x(".exfood-mulit-steps").offset().top-.2*t},"slow")}}}),!1}}),x(".exfd-list-order.ex-fdlist.list-layout").on("click",".exfd-list-name,.exf-img img",function(e){return e.stopPropagation(),x(this).closest("figure").find("a.exfd_modal_click").trigger("click"),!1})})}(jQuery);