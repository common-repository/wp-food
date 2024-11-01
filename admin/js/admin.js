;(function($){
	'use strict';
	$(document).ready(function() {
		/*-ready-*/
		if(jQuery('.post-type-exfood_scbd .cmb2-metabox select[name="sc_type"]').length>0){
			var $val = jQuery('.post-type-exfood_scbd .cmb2-metabox select[name="sc_type"]').val();
			if($val==''){
				$val ='grid';
			}
			if($val =='carousel'){
				jQuery('.cmb2-id-style select#style option').attr('disabled','disabled');
			}
			$('body').removeClass (function (index, className) {
				return (className.match (/(^|\s)ex-layout\S+/g) || []).join(' ');
			});
			$('body').addClass('ex-layout-'+$val);
			//$('.show-in'+$val).fadeIn();
			//$('.hide-in'+$val).fadeOut();
		}
		/*-on change-*/
		jQuery('.post-type-exfood_scbd .cmb2-metabox select[name="sc_type"]').on('change',function() {
			var $this = $(this);
			var $val = $this.val();
			if($val==''){
				$val ='grid';
			}
			if($val =='list'){
				jQuery('.post-type-exfood_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-exfood_scbd select#style option[value="1"], .post-type-exfood_scbd select#style option[value="2"], .post-type-exfood_scbd select#style option[value="3"]').removeAttr("disabled");
			}else if($val =='table'){
				jQuery('.post-type-exfood_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-exfood_scbd select#style option[value="1"]').removeAttr("disabled");
			}else{
				jQuery('.post-type-exfood_scbd select#style option').removeAttr('disabled','disabled');
			}
			$('body').removeClass (function (index, className) {
				return (className.match (/(^|\s)ex-layout\S+/g) || []).join(' ');
			});
			$('body').addClass('ex-layout-'+$val);
			//$('.show-in'+$val).fadeIn();
			//$('.hide-in'+$val).fadeOut();
			
		});
		/*-ajax save meta-*/
		jQuery('input[name="exfood_sort"]').on('change',function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var valu = $this.val();
           	var param = {
	   			action: 'exfood_change_sort_mb',
	   			post_id: post_id,
				value: valu,
				food_admin: exfood_ajax.food_admin,
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: exfood_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});
		jQuery('.post-type-exfood_order input[name="exorder_food_id"]').on('change paste keyup',function() {
			$('.exfd-order-items').addClass('loading');
			var $this = $(this);
			var post_id = $('#post_ID').val();
			var food_id = $this.val();
			var param = {
	   			action: 'exfood_admin_add_order_item',
	   			post_id: post_id,
	   			food_id: food_id,
	   			food_admin: exfood_ajax.food_admin,
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: exfood_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				if(data!=0){
		   				$('.exfd-order-items').removeClass('loading');
		   				$('.exfd-order-items table, .exfd-order-items .exfood-total').fadeOut(300, function() {
		                    $(this).remove();
		                });
		                $('.exfd-order-items').append(data);
		   				return true;
		   			}
	   			}	
	   		});
		});
		$('.exfd-order-items').on('click', '.exfood-close', function(event) {
			var $this_click = $(this);
			var it_remove  		= $this_click.data('remove');
			var post_id = $('#post_ID').val();
			$this_click.closest('tr').addClass('loading');
	   		var param = {
				action: 'adm_exfood_remove_cart_item',
				it_remove: it_remove,
				post_id: post_id,
				food_admin: exfood_ajax.food_admin,
			};
			$.ajax({
				type: "post",
				url: exfood_ajax.ajaxurl,
				dataType: 'json',
				data: (param),
				success: function(data){
					if(data != '0')
					{
						if(data.status == '0'){ 
						}
						else{
							$this_click.closest('tr').fadeOut(300, function() {
								$this_click.closest('tr').remove();
								$('.exfood-total > span').html(data.update_total);
							});
						}
					}else{ alert('error');}
				}
			});
			return false;
		});

		jQuery('.post-type-exfood_order').on('change paste keyup', 'input[name="food_qty"]',function() {
			var $this_click = $(this);
			$this_click.closest("tr").addClass('loading');
			var it_update  		= $this_click.data('update');
			var $value = $this_click.val();
			var post_id = $('#post_ID').val();
			var param = {
				action: 'adm_exfood_update_cart_item',
				it_update: it_update,
				qty: $value,
				post_id: post_id,
				food_admin: exfood_ajax.food_admin,
			};
			$.ajax({
				type: "post",
				url: exfood_ajax.ajaxurl,
				dataType: 'json',
				data: (param),
				success: function(data){
					if(data != '0')
					{
						if(data.status == '0'){
							alert(data.info_text);
							$this_click.closest("tr").find('.exfood-close').trigger('click');
							if (data.number_item < 1) {	}
						}
						else{
							$this_click.closest("tr").removeClass('loading');
							$this_click.closest("tr").find('.exfood-cart-price').html(data.update_price);
							$('.exfood-total > span').html(data.update_total);
						}
					}else{ alert('error');}
				}
			});
			return false;
		});
		$('.exfd-order-items').on('click', '.remove-order-meta', function(event) {
			$(this).closest(".exfood-container").remove();
			return false;
		});
		$('.exfd-order-items').on('click', '.save-order-meta', function(event) {
			
			var order_meta = new Array();
			$('input[name="exfodd-order-meta[]"]').each(function() {
			   order_meta.push($(this).val());
			});
			var $this_click = $(this);
			$this_click.closest("tr").addClass('loading');
			var it_update  		= $this_click.data('update');
			var post_id = $('#post_ID').val();
			
			var param = {
				action: 'adm_exfood_add_order_meta',
				metas: order_meta,
				it_update: it_update,
				post_id: post_id,
				food_admin: exfood_ajax.food_admin,
			};
			$.ajax({
				type: "post",
				url: exfood_ajax.ajaxurl,
				dataType: 'json',
				data: (param),
				success: function(data){
					if(data != '0')
					{
						if(data.status == '0'){
							
						}
						else{
							$this_click.closest("tr").removeClass('loading');
							$this_click.closest("tr").find('.exfood-container').remove();
							$this_click.closest("tr").find('.exfood-add-order-item-meta').before(data.html_add);
							$this_click.closest("tr").find('.exfood-cart-price').html(data.update_price);
							$('.exfood-total > span').html(data.update_total);
						}
					}else{ alert('error');}
				}
			});
			return false;
		});

		function ex_add_title($box){
			$box.find( '.cmb-group-title' ).each( function() {
				var $this = $( this );
				var txt = $this.next().find( '[id$="_name"]' ).val();
				var rowindex;
				if ( ! txt ) {
					txt = $box.find( '[data-grouptitle]' ).data( 'grouptitle' );
					if ( txt ) {
						rowindex = $this.parents( '[data-iterator]' ).data( 'iterator' );
						txt = txt.replace( '{#}', ( rowindex + 1 ) );
					}
				}
				if ( txt ) {
					$this.text( txt );
				}
			});
		}
		function ex_replace_title(evt){
			var $this = $( evt.target );
			var id = 'name';
			if ( evt.target.id.indexOf(id, evt.target.id.length - id.length) !== -1 ) {
				$this.parents( '.cmb-row.cmb-repeatable-grouping' ).find( '.cmb-group-title' ).text( $this.val() );
			}
		}
		jQuery('#exfood_addition_options,#exfood_custom_data').on( 'cmb2_add_row cmb2_shift_rows_complete', ex_add_title )
				.on( 'keyup', ex_replace_title );
		ex_add_title(jQuery('#exfood_addition_options,#exfood_custom_data'));

		jQuery('.cmb2-id-exorder-store input[name="exorder_store"]').on('change paste keyup',function(e) {
			e.preventDefault();
			var $this = $(this);
			var store_id = $this.val();
			var param = {
	   			action: 'exfood_admin_show_store',
	   			store_id: store_id,
	   			food_admin: exfood_ajax.food_admin,
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: exfood_ajax.ajaxurl,
	   			dataType: 'json',
	   			data: (param),
	   			success: function(data){
	   				if(data!=0){
		   				$('.cmb2-id-exorder-store .cmb2-metabox-description').empty();
		   				$('.cmb2-id-exorder-store .cmb2-metabox-description').append(data.store_name);
		   			}
	   			}	
	   		});
		});

		// change sort menu
		jQuery('input[name="exfd_sort_menu"]').on('change',function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var value = $this.val();
           	var param = {
	   			action: 'exfd_change_sort_menu',
	   			post_id: post_id,
				value: value,
				food_admin: exfood_ajax.food_admin,
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: exfood_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});

		if( jQuery(".exfd-toggle .exfd-toggle-title").hasClass('active') ){
			jQuery(".exfd-toggle .exfd-toggle-title.active").closest('.toggle').find('.toggle-inner').show();
		}
		jQuery(".exfd-toggle .exfd-toggle-title").click(function(){
			if( jQuery(this).hasClass('active') ){
				jQuery(this).removeClass("active").closest('.exfd-toggle').find('.exfd-toggle-inner').slideUp(200);
			}
			else{	jQuery(this).addClass("active").closest('.exfd-toggle').find('.exfd-toggle-inner').slideDown(200);
			}
		});

	});
}(jQuery));