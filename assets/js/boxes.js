/**
 * Livemenu: Boxes
 *
 * @author sabri taieb
 * @url http://vamospace.com
 * @since 1.0
**/
;(function( $ ){


   /**
    * Ajax: Get Box by Type
    * @since 1.0
   **/
   $( document ).on( 'click', '.lm-controls a', function( event ){

		event.preventDefault();
		event.stopPropagation();

		var $self      = jQuery(this),
			$data      = $self.data();

		$data.action    = 'lm_customizer_box';
		$data.nonce     = lm_obj.nonce;

		// AJAX
		jQuery.ajax({

			url: lm_obj.ajaxurl,
			type: 'POST',
			data: $data,
			
			success:function(data) {

				jQuery('.era-box.lm-box-edit-item, .era-box.lm-box-menu-settings, .era-box.lm-box-menu-style, .era-box.lm-box-item-style, .era-box.lm-box-item-submenu-style').remove();
				jQuery('body').prepend(data);

				// Draggable Box
				jQuery('.era-box').draggable({
					cursor: 'move',
					handle: '.lm-btn-drag',
				});

				// set the height of the content;
				$EraAPI.set_box_content_height('.era-box');

				// Enable ColorPicker Plugin
				jQuery('.era-op-colorpicker input').spectrum({
					showInput: true,
					showAlpha: true,
					preferredFormat: "rgb",
				});

			},
			error: function(error) {
			
				console.log(error);
			
			}

    	});

	});


	/**
	 * Close Current Box
	 * @since 1.0
	**/
	$(document).on('click','.lm-btn-cancel',function(event){
	
		event.preventDefault();
		jQuery(this).parents('.era-box').fadeOut(250);
	
	});


   /**
    * Ajax: Save menu style, menu settings, item style
    * @since 1.0
   **/
   $(document).on('click','.lm-btn-save-menu-style, .lm-btn-save-menu-settings, .lm-btn-save-item-style',function( event ){

        event.preventDefault();
        event.stopPropagation();

        var $self       = jQuery(this),
            $data       = $self.parents('.era-box').data(),
            $old_text   = $self.find('span').text();

        // Set All Data
        $data.action 		= 'lm_save_config';
        $data.nonce         = lm_obj.nonce;
        $data.data_string   = $EraAPI.set_ajax_data_string( $self.parents('.era-box') ); // get all options
        $data.uiDraggable   = ''; // dont remove it, its a conflict fix with jquery ui

        // Disable the Button
        $self.attr('disabled','disabled');

        // Display AJAX LOAD Img
        $self.append('<img src="'+lm_obj.loadimg+'" >');

        // Hide text
        $self.find('span').text('');

        // AJAX
        jQuery.ajax({

            url: lm_obj.ajaxurl,
            type: 'POST',
            data: $data,
            success:function(response) {

                // Saved Text         
                $self.find('span').text( response );
                $self.addClass('lm-btn-save-done');

                // Remove AJAX LOAD Img
                $self.find('img').remove();

                // remove Done Class & Enable Button Again
                setTimeout(function(){
                    $self.removeAttr('disabled');
                    $self.removeClass('lm-btn-save-done');
                    $self.find('span').text( $old_text );
                },2000);
                         
                if( $data.box_id == 'menu_settings' ){
                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });

   });


	/**
	 * Box: Reset Item Style
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-btn-reset-items-style',function(event){
		
		event.preventDefault();
		event.stopPropagation();

		var $self = jQuery(this);
		var $data = $self.parents('.era-box').data();

		$data.action 	= 'lm_reset_custom_style';
		$data.nonce 	= lm_obj.nonce;
		$data.uiDraggable 	= ''; 

		// Disable the Button
		$self.attr('disabled','disabled');

        // Display AJAX LOAD Img
        $self.append('<img src="'+lm_obj.loadimg+'" >');

		// Ajax : Send it
		jQuery.post( lm_obj.ajaxurl, $data, function( response ){
			
			setTimeout(function(){
			
				location.reload();
			
			},1500);

		});

	});


	/**
	 * Box: Page Change
	 * @since 1.0
	**/
	$(document).on('change','.lm-op-page-selector',function(){
		var $self = jQuery(this);
		var $page = $self.find(':selected').val();
		jQuery('.lm-box-content-page').slideUp(100);
		jQuery('.lm-box-content-page[data-page="'+$page+'"]').slideDown(200);
	});


	/**
	 * Box : Edit Item : Navigation
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-item-box-nav li',function(event){
		event.preventDefault();

		// hide all nav 
		jQuery('.lm-item-box-nav li.current').removeClass('current');

		var $self 	= jQuery(this);
		var $target = $self.attr('class').split('-item-box-nav-')[1];

		// Display Target
		$self.addClass('current');

		// Display Page
		jQuery('.lm-item-box-page').hide();
		jQuery('.lm-item-box-page-'+$target).show();
	});


	/**
	 * Box : Edit Item : Type change
	 * @since 1.0
	**/
	$(document).on('click','.lm-op-submenu-wrap label',function(event){
		
		// Display Target
		var $self 		= $(this);
		var $target 	= $self.find('input:checked').val();
		var $old_type 	= $self.parents('.era-box').attr('data-item_type');

		// Set Box Type, help show/hide options by type
		$self.parents('.era-box').attr('data-item_type',$target);
		$self.parents('.era-box')
			.removeClass('lm-box-item-type-'+$old_type)
			.addClass('lm-box-item-type-'+$target);
		$self.parents('.era-box').find('.era-box-header > h3 span').text( ' [ '+$target+' ] ');
	
	});


	/**
	 * Box : Edit Item : Column Type change, display nav link for this type
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-op-column-type-wrap input',function(event){
		
		// Display Target
		var $self 		= jQuery(this);
		var $target 	= $self.val();
		var $old_type	= $self.parents('.era-box').attr('data-column_type');

		jQuery('.lm-item-box-nav li').hide();
		jQuery('.lm-item-box-nav-'+$target).show();

		// Set Box Type, help show/hide options by type
		$self.parents('.era-box').attr('data-column_type',$target);
		$self.parents('.era-box')
			.removeClass('lm-box-item-column-'+$old_type)
			.addClass('lm-box-item-column-'+$target);
		$self.parents('.era-box').find('.era-box-header > h3 span').text( ' [ '+$target+' ] ');
	
	});


	/**
	 * Box : Edit Item : Mega Posts - Select Items
	 * @since 1.0
	**/
	jQuery(document).on('change','.lm_op_item_mega_posts_selector',function(event){
		
		event.preventDefault();
		
		var $data = '';
		
		jQuery(this).find(':selected').each(function(){
			var $self = jQuery(this);
			// Post Type
			if( $self.attr('data-type') == 'post_type' ){
				// don't make the separator same as the helpers get data_string separators
				$data = $data + '-__lmtype__-' + $self.val();
			}
			// Category
			else{
				// don't make the separator same as the helpers get data_string separators
				$data = $data + '-__lmtype__-' + $self.attr('data-taxonomy') + '-__lmcat__-' + $self.val();
			}
		});

		$data = $data.substring(12);
		jQuery(this).prev('.lm-op').val( $data );
	
	});


	/**
	 * Box : Add Item : Open Select Item
	 * @since 1.0
	**/
	$(document).on('click','.lm-live-items-box > h3',function( event ){

	    var $self = $(this);

	    if( $self.find('> .era-icon').is('.era-icon-triangle-down') ){
	        $self.parent().find('>div').slideDown(200);
	        $self.find('> .era-icon')
	            .removeClass('era-icon-triangle-down')
	            .addClass('era-icon-triangle-up');
	    }
	    else{
	        $self.parent().find('>div').slideUp(200);       
	        $self.find('> .era-icon')
	            .removeClass('era-icon-triangle-up')
	            .addClass('era-icon-triangle-down');     
	    }

	});


	/**
	 * Box : Add Item : Save or Add
	 * @since 1.0
	**/
	$(document).on('click','.lm-live-add-btn',function( event ){

	    event.preventDefault();
	    event.stopPropagation();

	    var $self = $(this);
	    var $data = $self.parents('.era-box').data();

	    $data.action        = 'lm_customizer_add_menu_item';
	    $data.nonce         = lm_obj.nonce;

	    $data.order         = $('.'+$data.menu_class+'.livemenu-wrap .lm-item.lm-item-reorder').length;
	    $data.order 		= $data.order + $('.'+$data.menu_class+'.livemenu-wrap .lm-item > .lm-sub > .lm-sub-item.lm-item-reorder').length;
	    $data.order 		= $data.order + $('.'+$data.menu_class+'.livemenu-wrap .lm-item > .lm-sub > .lm-sub-item.lm-item-reorder > .lm-sub > .lm-sub-item.lm-item-reorder').length;
	    $data.order 		= $data.order + $('.'+$data.menu_class+'.livemenu-wrap .lm-item > .lm-sub > .lm-sub-item.lm-item-reorder > .lm-sub > .lm-sub-item.lm-item-reorder > .lm-sub > .lm-sub-item.lm-item-reorder').length;
	    $data.order 		= $data.order + 1;

	    $data.uiDraggable   = ''; // dont remove it, its a conflict fix with jquery ui

        // Display AJAX LOAD Img
        $self.append('<img src="'+lm_obj.loadimg+'" >');

	    // Dynamic
	    if( $self.parents('.lm-live-items-box-content').find('> select').length ){

	        $data.id     = $self.parent('.lm-live-items-box-content').find('> select option:selected').val();
	        $data.type   = $self.parent('.lm-live-items-box-content').data('type');
	        $data.object = $self.parent('.lm-live-items-box-content').find('> select option:selected').data('object');
	        $data.title  = '';
	        $data.url    = '';
	    }

	    // Custom
	    else{

	        $data.type   = 'custom';
	        $data.object = 'custom';
	        $data.url    = $self.parent('.lm-live-items-box-content').find('#lm-live-custom-menu-item-url').val();
	        $data.title  = $self.parent('.lm-live-items-box-content').find('#lm-live-custom-menu-item-text').val();
	        $data.id     = $data.title.toLowerCase();

	    }


	    // AJAX
	    jQuery.ajax({

	        url     : lm_obj.ajaxurl,
	        type    : 'POST',
	        data    : $data,

	        success :function(data) {

	        	if( data.indexOf('#lmerror#') != -1 ){
	        		alert( data.replace('#lmerror#','') );
	        		// Remove AJAX LOAD Img
			        $self.find('img').remove();
	        	}
	        	else{

		            // Add Menu Content
		            $('.'+$data.menu_class+'.livemenu-wrap').html( data ); 
		            $('.'+$data.menu_class+'.livemenu-wrap .livemenu').show(); 
		        
	        		// Remove AJAX LOAD Img
			        $self.find('img').remove();

		            // Open Submenus & re-sortable the items
		            setTimeout( function(){

		                var $target     = $('#lm-item-'+$data.parent_id);
		                var $allParents = $('#lm-item-'+$data.parent_id).parents();

		                $target.addClass('lm-submenu-active');    

		                for( var $parent in $allParents ){
		                    
		                    var obj = $allParents[ $parent ];

		                    if( typeof obj == "object" && typeof obj.className != 'undefined'
		                        && ( obj.className.indexOf("lm-item") != -1 || obj.className.indexOf("lm-sub-item") != -1 ) 
		                    ) {
		                        obj.className = obj.className + ' lm-submenu-active';                        
		                    }

		                }

		                // Current Menu
		                var $menu = $( '#livemenu-id-' + $data.menu_class );

						// Remove Left/Right Border When Menu is bigger than window
						lm_remove_menu_border( $menu );

						// Remove Arrow From Search Link
						lm_remove_arrow_from_search( $menu );

						// Handle WPML
						lm_handle_wpml( $menu );

						// Handle RTL
						lm_handle_rtl( $menu );

						// Handle Sticky
						lm_handle_sticky( $menu, $livemenu_global_obj[$data.menu_class].config );

						// Lazy Load the Images
						lm_handle_lazy_load( $menu );   

						// Mega Posts Re-set the width of posts container
						lm_mega_posts_set_size( $menu );

		                // save the new items order
		                lm_items_sortable();

		            }, 150);

	        	}

	        },
	        error: function(error) {

	            console.log(error);
	        
	        }
	    });

	});


	/**
	 * Admin : Box : Open Edit Item Box 
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-open-item-box',function(event){

		event.preventDefault();
		event.stopPropagation();

		var $self      = jQuery(this),
			$data      = {};

		$data.action    	= 'livemenu_admin_box_edit_item';
		$data.nonce     	= lm_obj.nonce;
		$data.item_id 		= $self.data('item_id');
		
		// Item Type, Column & Depth
		if( $self.parents('.menu-item').is('.menu-item-depth-0') ){

			$data.item_depth 	= 0;
			$data.item_type		= $self.data('item_type');
			$data.item_column	= '';

		}
		else if( $self.parents('.menu-item').is('.menu-item-depth-1') ){

			var $parent_id		= $self.parents('.menu-item').find('.menu-item-data-parent-id').val();

			$data.item_depth 	= 1;
			$data.item_type		= $('#menu-item-'+$parent_id).find('.lm-open-item-box').attr('data-item_type');
			$data.item_column	= $self.attr('data-item_column');

		}
		else{
			$data.item_depth = 2;
		}

		$data.item_column	= $self.data('item_column');
		$data.menu_class	= '';


		// AJAX
		jQuery.ajax({

			url: lm_obj.ajaxurl,
			type: 'POST',
			data: $data,
			
			success:function(data) {

				jQuery('.era-box.lm-box-edit-item').remove();
				jQuery('body').prepend(data);

				// Draggable Box
				jQuery('.era-box').draggable({
					cursor: 'move',
					handle: '.lm-btn-drag',
				});

				// set the height of the content;
				$EraAPI.set_box_content_height('.era-box');

				// Enable ColorPicker Plugin
				jQuery('.era-op-colorpicker input').spectrum({
					showInput: true,
					showAlpha: true,
					preferredFormat: "rgb",
				});

			},
			error: function(error) {
			
				console.log(error);
			
			}

    	});

	});


	/**
	 * Box : Edit Item : Save Item Options
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-btn-editbox-save',function(event){
		
		event.preventDefault();
		event.stopPropagation();

		// Set
		var $self 		= jQuery(this),
			$data 		= $self.parents('.era-box').data();

		// Fix for Conflict with jQuery ui
		$data.uiDraggable = "";

		// Add Loading image
		$self.append('<img src="'+lm_obj.loadimg+'" >');

		// Data
		$data.action 		= 'lm_update_item_options';
		$data.nonce 		= lm_obj.nonce;
		$data.data_string 	= $EraAPI.set_ajax_data_string('.era-box.lm-box-edit-item');

		// AJAX
		jQuery.post( lm_obj.ajaxurl, $data, function( data ){

			// We are in admin (nav-menus.php) so display a message only
			if( location.href.indexOf('nav-menus.php') != -1 ){
				
				$self.find('img').remove();

				// Change menu item type text
				if( jQuery( '#menu-item-'+$data.item_id ).is('.menu-item-depth-0') ){

					var $new_type = $self.parents('.era-box').find('input[name="lm-op-submenu"]:checked').val();

					jQuery( '#menu-item-'+$data.item_id+' .item-controls .item-type').text( ' [ '+$new_type+' ] ' );
					jQuery( '#menu-item-'+$data.item_id+' .lm-open-item-box' ).attr('data-item_type', $new_type );
				}

				// Change menu item type text
				if( jQuery( '#menu-item-'+$data.item_id ).is('.menu-item-depth-1') ){

					var $new_column = $self.parents('.era-box').find('input[name="lm-op-submenu"]:checked').val();

					jQuery( '#menu-item-'+$data.item_id+' .item-controls .item-type').text( ' [ '+$new_column+' ] ' );
					jQuery( '#menu-item-'+$data.item_id+' .lm-open-item-box' ).attr('data-item_column', $new_column );
				}

			}

			// Live Builder
			else{
	
	        	if( data.indexOf('#lmerror#') != -1 ){
	        		alert( data.replace('#lmerror#','') );
	        		// Remove AJAX LOAD Img
			        $self.find('img').remove();
	        	}
	        	else{

		            // Add Menu Content
		            $('.'+$data.menu_class+'.livemenu-wrap').html( data ); 
		            $('.'+$data.menu_class+'.livemenu-wrap .livemenu').show(); 
		        
		        	$self.find('img').remove();

		            // Open Submenus
		            setTimeout( function(){

		                var $target     = $('#lm-item-'+$data.parent_id);
		                var $allParents = $('#lm-item-'+$data.parent_id).parents();

		                $target.addClass('lm-submenu-active');    

		                for( var $parent in $allParents ){
		                    
		                    var obj = $allParents[ $parent ];

		                    if( typeof obj == "object" && typeof obj.className != 'undefined'
		                        && ( obj.className.indexOf("lm-item") != -1 || obj.className.indexOf("lm-sub-item") != -1 ) 
		                    ) {
		                        obj.className = obj.className + ' lm-submenu-active';                        
		                    }

		                }

		                // Current Menu
		                var $menu = $( '#livemenu-id-' + $data.menu_class );

						// Remove Left/Right Border When Menu is bigger than window
						lm_remove_menu_border( $menu );

						// Remove Arrow From Search Link
						lm_remove_arrow_from_search( $menu );

						// Handle WPML
						lm_handle_wpml( $menu );

						// Handle RTL
						lm_handle_rtl( $menu );

						// Handle Sticky
						lm_handle_sticky( $menu, $livemenu_global_obj[$data.menu_class].config );

						// Lazy Load the Images
						lm_handle_lazy_load( $menu );   

						// Mega Posts Re-set the width of posts container
						lm_mega_posts_set_size( $menu );

		                // save the new items order
		                lm_items_sortable();

		            }, 150);

	        	}


			}

		});


	});


	/**
	 * Box : Remove Item
	 * @since 2.0
	**/
	jQuery(document).on('click','.lm-btn-item-remove',function(event){
		
		event.preventDefault();
		event.stopPropagation();

		// Set
		var $self 		= jQuery(this),
			$data 		= $self.parents('.era-box').data(),
			$old_text	= $self.text();

		// Fix for Conflict with jQuery ui
		$data.uiDraggable = "";

		// Add Loading image
		$self.html('&nbsp;&nbsp;<img src="'+lm_obj.loadimg+'" >&nbsp;&nbsp;');

		// Data
		$data.action 		= 'lm_remove_item';
		$data.nonce 		= lm_obj.nonce;

		// AJAX
		jQuery.post( lm_obj.ajaxurl, $data, function( data ){

        	if( data.indexOf('#lmerror#') != -1 ){
        		alert( data.replace('#lmerror#','') );
        		// Remove AJAX LOAD Img
		        $self.find('img').remove();
		        $self.parents('.era-box').hide();
        	}
        	else{
	            // Add Menu Content
	            $('.'+$data.menu_class+'.livemenu-wrap').html( data ); 
	            $('.'+$data.menu_class+'.livemenu-wrap .livemenu').show(); 
	        
	        	$self.text( $old_text );
	        	$self.find('img').hide();

	            $self.parents('.era-box').fadeOut(250);

	            // save the new items order
	            lm_items_sortable();
	        }	


		});

	});


	/**
	 * Re-order menu items
	 * @since 2.0
	**/
	var lm_items_sortable = function(){

		$('.livemenu-wrap .livemenu, .livemenu-wrap .livemenu .lm-sub').sortable({

	  		stop: function( event, ui ) {

	  			var $counter 		= 0;
	  			var $data_string 	= '';
	  			
	  			// Display Ajax Loading Box
	  			$('.lm-box-ajaxloading').fadeIn(250);

	  			// depth 0
	  			$( '#' + ui.item.context.id ).parents('.livemenu').find('.lm-item.lm-item-reorder').each(function(){

	  				$counter += 1;
	  				$data_string = $data_string + '//_-LMSEP-_//' + $(this).attr('id').substring(8) + '//_-LMORD-_//' + $counter;
	  				$(this).attr('data-uiorder',$counter);

	  				// depth 1
	  				$(this).find('> .lm-sub > .lm-sub-item.lm-item-reorder').each(function(){

	  					$counter += 1;
	  					$data_string = $data_string + '//_-LMSEP-_//' + $(this).attr('id').substring(8) + '//_-LMORD-_//' + $counter;
	  					$(this).attr('data-uiorder',$counter);

		  				// depth 2
		  				$(this).find('> .lm-sub > .lm-sub-item.lm-item-reorder').each(function(){
	  					
	  						$counter += 1;
							$data_string = $data_string + '//_-LMSEP-_//' + $(this).attr('id').substring(8) + '//_-LMORD-_//' + $counter;
	  						$(this).attr('data-uiorder',$counter);

		  					// depth 3
		  					$(this).find('> .lm-sub > .lm-sub-item.lm-item-reorder').each(function(){
		  						$counter += 1;
			  					$data_string = $data_string + '//_-LMSEP-_//' + $(this).attr('id').substring(8) + '//_-LMORD-_//' + $counter;
		  						$(this).attr('data-uiorder',$counter);
		  					});
		  					
		  				});

	  				});
	  			});

				$data_string = $data_string.substring(13);

				// AJAX
				jQuery.post( lm_obj.ajaxurl, {

					action 		: 'lm_reorder_items',
					nonce 		: lm_obj.nonce,
					data_string : $data_string,

				}, function( data ){

		        	if( data.indexOf('#lmerror#') != -1 ){
		        		alert( data.replace('#lmerror#','') );
		        	}

	  				$('.lm-box-ajaxloading').fadeOut(250);

				});
	  		
	  		}

		});

	};
	lm_items_sortable();


	/**
	 * Box : Open Icons Box
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-btn-open-icons-box',function(event){
		event.preventDefault();
		event.stopPropagation();
		$('.lm-box-icons').fadeIn(200);
	});


	/**
	 * Select Icon from Icons Box
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-box-icons-icon',function(event){
		event.preventDefault();

		var $self = $(this);
		jQuery('#lm-op-icon').val( $self.data('icon') );
		jQuery('#lm-icon-placeholder').html( '<span class="era-icon '+$self.data('icon')+'"></span>');
		$self.parents('.era-box').fadeOut(250);
	});


	/**
	 * Remove Icon from the hidden input
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-btn-remove-icon',function(event){
		event.preventDefault();
		jQuery('#lm-op-icon').val('');
		jQuery('#lm-icon-placeholder').html('');
	});


	/**
	 * Box : Open WP Editor Box & Add Content
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-open-wp-editor-box',function(event){
	
		event.preventDefault();
		event.stopPropagation();

		// display the WP Editor Box
		jQuery('.lm-box-wpeditor').fadeIn(250);

		var $DOM = tinymce.DOM, //DOMUtils outside the editor iframe
			$content = jQuery('#lm-op-shortcodes-content').val(), // content of the textarea of the option
			$editor = tinymce.get( 'lm_wp_editor' ), // Visual > Editor
			$txtarea_el = $DOM.get( 'lm_wp_editor' ), // Text > Editor
			$editor_mode = 'text'; // mode of the editor Text or Visual

			// if Visual mode is displayed
			if ( $editor && ! $editor.isHidden() ) {
				$editor_mode = 'visual';
				$editor.setContent( $content, {format:'raw'} );
			}
			// Else Text mode is displayed
			else {
				$editor_mode = 'text';
				$txtarea_el.value = $content;
			}

			// change the visual editor, height
			jQuery('#lm_wp_editor_ifr').height(270);
	});


	/**
	 * Box : WP Editor : Get content from WP Editor and paste it to the hidden textarea
	 * @since 1.0
	**/
	jQuery(document).on('click','.lm-btn-wpeditor-get-text',function(event){
		event.preventDefault();

		var $DOM = tinymce.DOM, //DOMUtils outside the editor iframe
			$content = '',
			$editor = tinymce.get( 'lm_wp_editor' ), // Visual > Editor
			$txtarea_el = $DOM.get( 'lm_wp_editor' ), // Text > Editor
			$editor_mode = 'text'; // mode of the editor Text or Visual

		// if Visual mode is displayed
		if ( $editor && ! $editor.isHidden() ) {
			$editor_mode = 'visual';
			$content = $editor.getContent( {format:'raw'} );
		}
		// Else Text mode is displayed
		else {
			$editor_mode = 'text';
			$content = $txtarea_el.value;
		}

		jQuery('#lm-op-shortcodes-content').val( $content );
		jQuery('.lm-box-wpeditor').fadeOut(250);
	});

	
	/**
	 * Hide Icons/WP Editor Boxes ( dont remove them )
	 * @since 2.0
	**/
	jQuery(document).on('click','.lm-btn-hide',function(event){
		event.preventDefault();
		event.stopPropagation();
		jQuery(this).parents('.era-box').fadeOut(250);
	});


})(jQuery);