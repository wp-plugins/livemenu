/**
 * Admin script for Livemenu
 *
 * Table of Content:
 * 1) Export Settings / Style ( config )
 * 2) Import Menus or Settings / Style
 * 3) Get Menu item box
 * 4) Close Box
 * 5) Save Item settings
 * 6) 
 * 7)
 * 8)
 *
**/

;(function($){


	/**
	 * Enable Demo Site
	 * @since 2.0
	**/
	$('#lm-panel-enable-demo').on( 'click', function( event ){

		event.preventDefault();

		var $self 		= $(this);
		var $onOrOff	= 'off';

		if( $self.parent().find(':checked').length ){
			$onOrOff = 'on';
		}

		// Show Loading Icon
		$self.find('img').show();

		// AJAX
		jQuery.post( lm_obj.ajaxurl, {
				action 			: 'lm_admin_save_enable_mode',
				nonce 			: lm_obj.nonce,
				enable_demo 	: $onOrOff,
			},
			function( response ){

				$self.find('img').hide();
				
			}// End Func
		);

	});

	
	/**
	 * Save how many menus to add
	 * @since 2.0
	**/
	$('#lm-panel-save-menus-count').on( 'click', function( event ){

		event.preventDefault();

		var $self = $(this);

		// Show Loading Icon
		$self.find('img').show();

		// AJAX
		jQuery.post( lm_obj.ajaxurl, {
				action 			: 'lm_admin_save_menus_to_add',
				nonce 			: lm_obj.nonce,
				custom_css 		: $self.parent().find('#lm-panel-menus-count :selected').val(),
			},
			function( response ){

				$self.find('img').hide();
				
			}// End Func
		);

	});


	/**
	 * Save custom css
	 * @since 2.0
	**/
	$('#lm-panel-save-custom-css').on( 'click', function( event ){

		event.preventDefault();

		var $self = $(this);

		// Show Loading Icon
		$self.find('img').show();

		// AJAX
		jQuery.post( lm_obj.ajaxurl, {
				action 			: 'lm_admin_save_custom_css',
				nonce 			: lm_obj.nonce,
				custom_css 		: $self.parent().find('#lm-panel-custom-css').val(),
			},
			function( response ){

				$self.find('img').hide();
				
			}// End Func
		);

	});


	/**
	 * Export Settings / Style ( config )
	**/
	$('#lm-btn-export-config').on( 'click', function( event ){

		event.preventDefault();

		var $self = $(this);

		// Show Loading Icon
		$self.find('img').show();

		// AJAX
		jQuery.post( lm_obj.ajaxurl, {
				action 			: 'lm_export_config',
				nonce 			: lm_obj.nonce,
			},
			function( response ){

				// Error
				if( response == '#error#no_active_menus_found' ){
					alert( response );
				}

				else{
					document.location = response;
				}
				
			}// End Func
		);

	});


	/**
	 * Import Menus or Settings / Style
	**/
	$('#lm-btn-import-config, #lm-btn-import-menus').on( 'click', function( event ){
		
		event.preventDefault();

		var $self = $(this);
		
		// Show Loading Icon
		$self.find('img').show();

		// Media Uploader Function
		$EraAPI.wp_media_upload({
			frame_title       : 'Upload Import File',
			button_title      : 'Import',
			multiple_upload   : false,
			library_type      : 'text',
			callback          : function( $obj ){

				// AJAX
				jQuery.post( lm_obj.ajaxurl, {
						action 			: 'lm_import_config',
						nonce 			: lm_obj.nonce,
						file_url		: $obj.url,
					},
					function( response ){

						alert( response );

						// Hide Loading Icon
						$self.find('img').hide();

					}// End Func
				);

			}// End Callback
		});

	});


	/**
	 * Check menu status when page load & add Buttons
	**/

	var $menu_theme_locations 			= $('.menu-settings .menu-theme-locations :checked');
	var $menu_theme_locations_string 	= '';

	$menu_theme_locations.each(function(){
		$menu_theme_locations_string = $menu_theme_locations_string + '#LMSEP#' + $(this).attr('id').replace('locations-','');		
	});
	$menu_theme_locations_string = $menu_theme_locations_string.substring(7);

	jQuery.post( lm_obj.ajaxurl, {
			action 			: 'livemenu_menu_status',
			nonce 			: lm_obj.nonce,
			menu_name		: jQuery('#menu-name').val(),
			theme_locations : $menu_theme_locations_string
		},
		function( response ){
			jQuery('.major-publishing-actions .publishing-action')
				.prepend( response );
		}
	);


	/**
	 * Enable Livemenu
	**/
	jQuery(document).on('click','.lm-btn-enable',function(event){
		
		event.preventDefault();

		var $self = jQuery(this);

		var $menu_theme_locations 			= $('.menu-settings .menu-theme-locations :checked');
		var $menu_theme_locations_string 	= '';

		$menu_theme_locations.each(function(){
			$menu_theme_locations_string = $menu_theme_locations_string + '#LMSEP#' + $(this).attr('id').replace('locations-','');		
		});
		$menu_theme_locations_string = $menu_theme_locations_string.substring(7);


		// Show Loading Icon
		$self.find('img').show();

		// AJAX
		jQuery.post( lm_obj.ajaxurl, {
				action 			: 'livemenu_enable_menu',
				nonce 			: lm_obj.nonce,
				menu_name		: jQuery('#menu-name').val(),
				theme_locations : $menu_theme_locations_string
			},
			function( response ){

				// menu is not registered yet, show an error message
				if( response.search('#error#') != -1 ){
					alert( response );
				}

				// menu is registered
				else{
					// remove old buttons
					jQuery('body').find('.lm-btn-enable, .lm-btn-disable')
						.remove();
					// add new ones
					jQuery('.major-publishing-actions .publishing-action')
						.prepend( response );
				}

				// Hide Loading Icon
				$self.find('img').hide();
			}
		);

	});


	/**
	 * Disable Livemenu
	**/
	jQuery(document).on('click','.lm-btn-disable',function(event){

		event.preventDefault();

		var $self = jQuery(this);

		// Show Loading Icon
		$self.find('img').show();

		// AJAX
		jQuery.post( lm_obj.ajaxurl, {
				action 			: 'livemenu_disable_menu',
				nonce 			: lm_obj.nonce,
				menu_name		: jQuery('#menu-name').val(),
			},
			function( response ){

				// menu is not registered yet, show an error message
				if( response.search('#error#') != -1 ){
					alert( response );
				}

				// menu is registered
				else{
					// remove old buttons
					jQuery('body').find('.lm-btn-enable, .lm-btn-disable')
						.remove();
					// add new ones
					jQuery('.major-publishing-actions .publishing-action')
						.prepend( response );
				}

				// Show Loading Icon
				$self.find('img').hide();

			}
		);

	});


	/**
	 * Delete Menu
	**/
	jQuery(document).on('click','#nav-menu-footer a.menu-delete',function(){

		jQuery.ajax({
			type 	: "POST",
			url		: lm_obj.ajaxurl,
			data 	: {
				action 		: 'livemenu_disable_menu',
				nonce 		: lm_obj.nonce,
				menu_name	: jQuery('#menu-name').val(),
			},
			success : function( response ){
				alert( response );
			},
		});

	});


})(jQuery);