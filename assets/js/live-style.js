/**
 * Livemenu: Detect Live Changes & Apply callbacks 
 *
 * @author sabri taieb
 * @url http://vamospace.com
 * @since 1.0
**/
;(function( $ ){


	/**
	 * Add Style to "HEAD" of the page
	 * @since 1.0
	**/
	var addStyle = function( $new_style ){
	
		var $obj = $('head').find('#livemenu_custom_style_tag');

		if( $obj.length == 0 ){
	
			$('head').append('<style id="livemenu_custom_style_tag"></style>');
			$obj = $('#livemenu_custom_style_tag');
	
		}

		var $oldstyle = $obj.text();
		$obj.text( $oldstyle + $new_style );

	};


	// Layout And Logo Changer
	var lm_logo_position = function(){

		var $self      = $('#lm-op-layoutAndLogo');
		var $val       = $self.find('option:selected').val();
		var $css_class = $self.data('css_class');
		var $logo_op   = $('#lm-op-logo');

		// remove old logo
		$( $css_class ).parent().find('.lm-logo').remove();

		// no logo 
		if( $val == 'no-logo' ){
			$logo_op.parents('.lm-op-wrap').addClass('hidden');
		}

		else{
			$logo_op.parents('.lm-op-wrap').removeClass('hidden');

			// Logo Output
			$output = '<li class="lm-logo lm-'+$val+'">';
			$output = $output + '<a href="#"><img src="'+$logo_op.val()+'" alt="" ></a>';
			$output = $output + '</li>';

			// Responsive Logo
			$resp_output = '<a class="lm-logo lm-'+$val+'" href="#"><img src="'+$logo_op.val()+'" alt="" ></a>';

			// Add Logo
			if( $logo_op.val() != '' ){

				$( $css_class ).prepend( $output );

				$( $css_class ).parent()
					.find('.livemenu-responsive-controls')
					.prepend( $resp_output );

				// Responsive Icon
				if( $val == 'logo-right' ){
					$( $css_class ).parent()
						.find('.livemenu-responsive-controls .era-icon')
						.css('float','left');
				}
				else{
					$( $css_class ).parent()
						.find('.livemenu-responsive-controls .era-icon')
						.css('float','right');
				}

			}

		}// End Else
	};


	// Load Google Font
	var lm_load_google_font = function( $font_name ){

		var $cross_fonts = [
			'Arial',
			'Arial Black',
			'Verdana',
			'Impact',
			'Helvetica',
			'Georgia',
			'Tahoma',
			'Courier New'
		];

		if( $.inArray( $font_name, $cross_fonts ) != -1 ) return false;

		$font_name = $font_name.replace(' ','%20');
		$("head").append("<link href='https://fonts.googleapis.com/css?family=" + $font_name + "' rel='stylesheet' type='text/css'>");
	};


	// if we add a background image & select gradient, gradient will not work ( Vice Versa )
	var clean_css_background = function( $css_class ){

		var $style_tag  = $('head #livemenu_custom_style_tag');
		var $old_style  = $style_tag.text();
		var $pattern    = new RegExp( $css_class + "\{ background-image.+?\}", "g" );

		$style_tag.text( $old_style.replace( $pattern, '') );

	};


	/** 
	 * Prepare The Style Line 
	 * @since 1.0
	**/
	var lm_prepare_style_lines = function( $self, $val, $allData, event ){

		for( var key in $allData ){
			
			var $data = $allData[key];
			
			if( typeof $data == 'string' && $data.search("#livemenuProp#") > -1 ){ // sometime with some inputs return integer, thats a problem
			
				$data = $data.split("#livemenuProp#");
				addStyle( $data[0] + '{' + $data[1] + ':' + $val + '; }' );
			
			}

		}// End For


		// Box Shadow
		if( $self.parents('.era-op-boxshadow').length ){

			var $parent    = $self.closest('.era-op-boxshadow');
			var $css_class = $parent.attr('data-css');

			var $size      = $parent.find('.era-op-boxshadow-size').val(); // Size
			var $blur      = $parent.find('.era-op-boxshadow-blur').val(); // Blur
			var $color     = $parent.find('.era-op-boxshadow-color').val(); // Color

			addStyle( $css_class + '{ -webkit-box-shadow: ' + '0px 0px ' + $blur + ' ' + $size + ' ' + $color + '; }' );
			addStyle( $css_class + '{ -moz-box-shadow: ' + '0px 0px ' + $blur + ' ' + $size + ' ' + $color + '; }' );
			addStyle( $css_class + '{ box-shadow: ' + '0px 0px ' + $blur + ' ' + $size + ' ' + $color + '; }' );

		}

		// Border Radius
		else if( $self.parents('.era-op-borderradius').length ){

			var $parent       = $self.closest('.era-op-borderradius');
			var $css_class    = $parent.attr('data-css');

			var $topLeft      = $parent.find('.era-op-borderRadius-topLeft').val(); // top left
			var $topRight     = $parent.find('.era-op-borderRadius-topRight').val(); // top right
			var $bottomLeft   = $parent.find('.era-op-borderRadius-bottomLeft').val(); // bottom left
			var $bottomRight  = $parent.find('.era-op-borderRadius-bottomRight').val(); // bottom right

			addStyle( $css_class + '{ -webkit-border-radius: '+$topLeft + ' ' + $topRight + ' ' + $bottomRight + ' ' + $bottomLeft+'; }' );
			addStyle( $css_class + '{ -moz-border-radius: '+$topLeft + ' ' + $topRight + ' ' + $bottomRight + ' ' + $bottomLeft+'; }' );
			addStyle( $css_class + '{ border-radius: '+$topLeft + ' ' + $topRight + ' ' + $bottomRight + ' ' + $bottomLeft+'; }' );

		}

		// Background
		else if( $self.parents('.era-op-background').length ){

			var $parent    = $self.closest('.era-op-background');
			var $type      = $parent.find('.era-op-background-type option:selected').val();
			var $css_class = $parent.find('.era-op-background-type').attr('data-css'); 

			var $listener;
			clearInterval( $listener );

			// First Clean it to prevent issues
			clean_css_background( $css_class );

			// Type: Color
			if( $type == 'color' ){

				// Set Background Color
				addStyle( $css_class + '{ background-color:' +$parent.find('.era-op-background-color').val()+ '; }' );

			} 

			// Type: Image
			else if( $type == 'image' ){

				// Set Background Color
				addStyle( $css_class + '{ background-color:' +$parent.find('.era-op-background-color').val()+ '; }' );

				// Set Background Image
				addStyle( $css_class + '{ background-image:url('+$parent.find('.era-op-background-image').val()+'); }' );
				addStyle( $css_class + '{ background-repeat:'+$parent.find('.era-op-background-image-repeat option:selected').val()+'; }' );
				addStyle( $css_class + '{ background-attachment:'+$parent.find('.era-op-background-image-attachment option:selected').val()+'; }' );
				addStyle( $css_class + '{ background-position:'+$parent.find('.era-op-background-image-position option:selected').val()+'; }' );

				$listener = setInterval(function(){

					if( $parent.find('.era-op-background-image').val().length == 0 ){
					
						addStyle( $css_class + '{ background-image:url('+$parent.find('.era-op-background-image').val()+'); }' );
					
					}
					else{
						
						addStyle( $css_class + '{ background-image:url('+$parent.find('.era-op-background-image').val()+'); }' );
						clearInterval( $listener );
				
					}           

				}, 500);

			}

			// Gradient
			else if( $type == "gradient" ){

				var $direction = $parent.find('.era-op-gradient-dir option:selected').val();
				var $color1    = $parent.find('.era-op-gradient-color1').val();
				var $color2    = $parent.find('.era-op-gradient-color2').val();

				// Set Background Color
				addStyle( $css_class + '{ background-color:' +$parent.find('.era-op-background-color').val()+ '; }' );

				// Set Background Image
				addStyle( $css_class + '{ background-image:-webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, '+$color1+'), color-stop(100%, '+$color2+')); }' );
				addStyle( $css_class + '{ background-image:-webkit-linear-gradient('+$direction+', '+$color1+' 0%,'+$color2+' 100%); }' );
				addStyle( $css_class + '{ background-image:-moz-linear-gradient('+$direction+', '+$color1+' 0%,'+$color2+' 100%) }' );
				addStyle( $css_class + '{ background-image:-o-linear-gradient('+$direction+', '+$color1+' 0%,'+$color2+' 100%) }' );
				addStyle( $css_class + '{ background-image:linear-gradient('+$direction+', '+$color1+' 0%,'+$color2+' 100%) }' );

			}

		}

		// Font change Event
		else if( $self.parents('.era-op-font').length ){

			// Google Fonts
			if( $self.is('.era-op-fontFamily') ){
			
				lm_load_google_font( $self.find(':selected').text() );
			
			}

			// Font Style
			else if( $self.is('.era-op-fontStyle') ){

				for( var key in $allData ){

					var $data = $allData[key];

					if( typeof $data == 'string' && $data.search("#livemenuProp#") > -1 ){ // sometime some inputs return integer, thats a problem

						$data = $data.split("#livemenuProp#");
						addStyle( $data[0] + '{ '+$val+' }' );

					}

				}// End For
			}
		
		}


		// Event: Layout And Logo
		if( $self.is('#lm-op-layoutAndLogo') ){
		
			lm_logo_position();
		
		}
		  
		// Event: Layout Boxed or Wide
		else if( $self.is('#lm-op-menuWideOrBoxed') ){

			var $css_class = $self.data('css_class');

			// wide
			if( $val == 'wide' ){
	
				$( $css_class ).css({ 'width' : '100%' });
	
			}

			// boxed
			else{
	
				$( $css_class ).width( $( $css_class ).children('.livemenu').outerWidth() );
	
			}

		}// End IF


		// Event: Skin or Custom
		else if( $self.is('#lm-op-skinOrCustom') ){

			var $type = $self.find('option:selected').val();

			if( $type == 'skin' ){
			
				$('#lm-op-page-selector').addClass('no_visible');
				$('#lm-op-skin').closest('.lm-op-wrap').removeClass('hidden');
			
			}
			else{
			
				$('#lm-op-page-selector').removeClass('no_visible');
				$('#livemenu-frontend-preview-css').remove();
				$('#lm-op-skin').closest('.lm-op-wrap').addClass('hidden'); 
			
			}   

		}// End IF


		// Event: Mega Posts Categories Container
		else if( $self.is('#lm-op-megaposts-cat-container-size') ){

			var $css_class = $self.data('css_class');

			if( $val == 'lm-col-20' ){
				
				$( $css_class ).removeClass('lm-col lm-col-20 lm-col-25 lm-col-33')
					.addClass('lm-col lm-col-20');
				
				$( $css_class ).parent().find('> li').removeClass('lm-col lm-col-80 lm-col-75 lm-col-66')
					.addClass('lm-col lm-col-80');
			
			}
			else if( $val == 'lm-col-25' ){
			
				$( $css_class ).removeClass('lm-col lm-col-20 lm-col-25 lm-col-33')
					.addClass('lm-col lm-col-25');
			
				$( $css_class ).parent().find('> li').removeClass('lm-col lm-col-80 lm-col-75 lm-col-66')
					.addClass('lm-col lm-col-75');
			
			}
			else{
			
				$( $css_class ).removeClass('lm-col lm-col-20 lm-col-25 lm-col-33')
					.addClass('lm-col lm-col-33');
				$( $css_class ).parent().find('> li').removeClass('lm-col lm-col-80 lm-col-75 lm-col-66')
					.addClass('lm-col lm-col-66');
			
			}

		}// End IF


		// Event: Padding of responssive links
		else if( $self.is('#lm-op-responsiveLinks-links-padding') ){

			var $css_class = $self.data('css_class');

			addStyle( $css_class + '.livemenu-responsive .lm-item > a ' + '{ padding: 0 '+$val+'; }' );
			addStyle( $css_class + '.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a ' + '{ padding: 0 '+$val+'; }' );
			addStyle( $css_class + '.livemenu-responsive .lm-item-posts .lm-sub-item > a ' + '{ padding: 0 '+$val+'; }' );
			addStyle( $css_class + '.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > a ' + '{ padding: 0 '+$val+' 0 '+(parseInt($val)*2)+'px ; }' );
			addStyle( $css_class + '.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > a ' + '{ padding: 0 '+$val+' 0 '+(parseInt($val)*2)+'px ; }' );
			addStyle( $css_class + '.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > a ' + '{ padding: 0 '+$val+' 0 '+(parseInt($val)*4)+'px ; }' );
			addStyle( $css_class + '.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > a ' + '{ padding: 0 '+$val+' 0 '+(parseInt($val)*6)+'px ; }' );

			// RTL 
			addStyle( $css_class + '.livemenu-rtl.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > a ' + '{ padding: 0 '+(parseInt($val)*2)+'px 0 '+$val+' ; }' );
			addStyle( $css_class + '.livemenu-rtl.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > a ' + '{ padding: 0 '+(parseInt($val)*2)+'px 0 '+$val+' ; }' );
			addStyle( $css_class + '.livemenu-rtl.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > a ' + '{ padding: 0 '+(parseInt($val)*4)+'px 0 '+$val+' ; }' );
			addStyle( $css_class + '.livemenu-rtl.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > a ' + '{ padding: 0 '+(parseInt($val)*6)+'px 0 '+$val+' ; }' );

		}
  
	};


	// Event: Inputs change 
	// dont remove change event, best is 'input change' to prevent issues with hidden inputs ( colorpicker )
	$(document).on('input change','.lm-box-menu-settings .era-box-content input, .lm-box-menu-settings .era-box-content input, .lm-box-menu-item-style .era-box-content input, .lm-box-menu-style .era-box-content input, .lm-box-menu-item-style .era-box-content textarea, .lm-box-menu-style .era-box-content textarea',function( event ){
	
		lm_prepare_style_lines( $(this), $(this).val(), $(this).data(), event );
	
	});
	$(document).on('change','.lm-box-menu-settings .era-box-content select, .lm-box-menu-item-style .era-box-content select, .lm-box-menu-style .era-box-content select',function( event ){
	
		lm_prepare_style_lines( $(this), $(this).find(':selected').val(), $(this).data(), event );
	
	});


	// Event: Layout And Logo "Input Change"
	jQuery(document).on('click','.lm-op-wrap-logo-upload button',function(){
	
		var $self = $(this);
		var $timer;

		$timer = setInterval(function(){
			if( $self.parents('.lm-op-wrap').find('#lm-op-logo').val() != '' ){
				lm_logo_position();
				clearInterval( $timer );
			}
		},700);

	});


	// Event: Preview Skin
	jQuery(document).on('click','.lm-btn-preview-skin',function(event){
	
		event.preventDefault();

		var $self      = $(this);
		var $url       = $self.data('url');
		var $menu_id   = $self.parents('.era-box').data('menu_class');
		var $skin      = $('#lm-op-skin option:selected').val();

		$url = $url+$menu_id+'-prev-'+$skin +'.css';

		$('#livemenu-frontend-preview-css').remove();
		$('head').append('<link rel="stylesheet" id="livemenu-frontend-preview-css" href="'+$url+'" type="text/css" media="all">');

	});


	/**
	 * Box : Item Edit : Open Widget Options by Widget Type
	**/
	$(document).on('change','#lm-op-widget',function(event){
		var $self 	= $(this);
		var $val 	= $self.find(':selected').val();

		$('.lm-item-op-content-widget').addClass('hidden');
		$('.lm-item-op-content-widget-'+$val).removeClass('hidden');
	});


})(jQuery);