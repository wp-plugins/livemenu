<?php

/**
 * Create Assets Files ( CSS/JS )
 * @since 1.0.0
 */
class livemenu_create_assets {


	/**
	 * Create CSS Style File
	 * @since 1.0
	**/
	static function css( $menu_id, $config, $filename ){

		// Style String
		$style_string = '

			/**
			 * Menu Container Style
			**/
			.'.$menu_id.'.livemenu-wrap{

				/* z-index */
				z-index:'.$config['lm-op-menu-zindex'].';

				/* Layout */
				'.self::layout( $config['lm-op-menuWideOrBoxed'], $config['lm-op-menuWidth'] ).'

				/* Background */
				'.self::background(array(
					'type' 				=> $config['lm-op-menuBackground-type'],
					'color' 			=> $config['lm-op-menuBackground-color'],
    				'image' 			=> $config['lm-op-menuBackground-image'],
    				'image-repeat' 		=> $config['lm-op-menuBackground-image-repeat'],
    				'image-attachment' 	=> $config['lm-op-menuBackground-image-attachment'],
    				'image-position' 	=> $config['lm-op-menuBackground-image-position'],
    				'gradient-dir' 		=> $config['lm-op-menuBackground-gradient-dir'],
    				'gradient-color1' 	=> $config['lm-op-menuBackground-gradient-color1'],
    				'gradient-color2' 	=> $config['lm-op-menuBackground-gradient-color2'],
				)).'

				border:'.$config['lm-op-menuBorder-size'].' solid '.$config['lm-op-menuBorder-color'].';

                /* CSS3 Box Shadow */
                -moz-box-shadow   : 0px 0px '.$config['lm-op-menuBoxShadow-blur'].' '.$config['lm-op-menuBoxShadow-size'].' '.$config['lm-op-menuBoxShadow-color'].';
                -webkit-box-shadow: 0px 0px '.$config['lm-op-menuBoxShadow-blur'].' '.$config['lm-op-menuBoxShadow-size'].' '.$config['lm-op-menuBoxShadow-color'].';
                box-shadow        : 0px 0px '.$config['lm-op-menuBoxShadow-blur'].' '.$config['lm-op-menuBoxShadow-size'].' '.$config['lm-op-menuBoxShadow-color'].';

                /* CSS3 Border Radius */
                -webkit-border-radius: '.$config['lm-op-menuBorderRadius-topLeft'].' '.$config['lm-op-menuBorderRadius-topRight'].' '.$config['lm-op-menuBorderRadius-bottomRight'].' '.$config['lm-op-menuBorderRadius-bottomLeft'].';
                -moz-border-radius: '.$config['lm-op-menuBorderRadius-topLeft'].' '.$config['lm-op-menuBorderRadius-topRight'].' '.$config['lm-op-menuBorderRadius-bottomRight'].' '.$config['lm-op-menuBorderRadius-bottomLeft'].';
                border-radius: '.$config['lm-op-menuBorderRadius-topLeft'].' '.$config['lm-op-menuBorderRadius-topRight'].' '.$config['lm-op-menuBorderRadius-bottomRight'].' '.$config['lm-op-menuBorderRadius-bottomLeft'].';

			}



			/**
			 * Sticky Menu
			**/
			.'.$menu_id.'.livemenu-sticky{
				background-color: '.self::get_sticky_true_color( $config['lm-op-menuBackground-color'] ) .';
			}



			/**
			 * Menu Style
			**/
			.'.$menu_id.'.livemenu-horizontal .livemenu{
				width: '.$config['lm-op-menuWidth'].';
				height: '.$config['lm-op-menuHeight'].';
			}

			/* general text inside the menu */
			.'.$menu_id.' .livemenu .lm-sub{
				/* Text Inside */
                color: '.$config['lm-op-submenuText-fontColor'].';
                font-size:'.(int)$config['lm-op-submenuText-fontSize'].'px;
			    font-family:'.$config['lm-op-submenuText-fontFamily'].';
                '.$config['lm-op-submenuText-fontStyle'].'
			}



			/**
			 * Logo
			**/
			.'.$menu_id.' .lm-logo-left{
				padding-right: '.$config['lm-op-topLinksPadding'].';
			}
			.'.$menu_id.' .lm-logo-right{
				padding-left: '.$config['lm-op-topLinksPadding'].';
			}
			.'.$menu_id.' .lm-logo img{
				max-height: '.$config['lm-op-menuHeight'].';
			}



			/**
			 * Top Links
			**/
			.'.$menu_id.' .lm-item > a{
				height: '.$config['lm-op-menuHeight'].';
				line-height: '.$config['lm-op-menuHeight'].';
				padding:0 '.$config['lm-op-topLinksPadding'].';
                color: '.$config['lm-op-topLinksFont-fontColor'].';
                font-size:'.$config['lm-op-topLinksFont-fontSize'].';
                border-color:'.$config['lm-op-topLinksBorderColor'].';
			}

			/* Title */
			.'.$menu_id.' .lm-item > a .lm-item-title{
                font-family:'.$config['lm-op-topLinksFont-fontFamily'].';
                '.$config['lm-op-topLinksFont-fontStyle'].'
            }

			/* Icons + Arrows */
			.'.$menu_id.' .lm-item > a .era-icon:before{
				font-size:'.$config['lm-op-topLinksIconSize'].';
				padding-right: '.$config['lm-op-topLinksIconPadding'].';
			}
			.'.$menu_id.' .lm-item > a .era-icon:after{
				padding-left: '.$config['lm-op-topLinksIconPadding'].';
			}

			/* ++RTL Icons + Arrows */
			.'.$menu_id.'.livemenu-rtl .lm-item > a .era-icon:before{
				padding-left: '.$config['lm-op-topLinksIconPadding'].';
				padding-right: 0px;
			}
			.'.$menu_id.'.livemenu-rtl .lm-item > a .era-icon:after{
				padding-right: '.$config['lm-op-topLinksIconPadding'].';
				padding-left: 0px;
			}

			/* Social Media ( mini item ) */
			.'.$menu_id.' .lm-item.lm-item-socialmedia > a .era-icon:before{
				padding-right: '.$config['lm-op-topLinksIconPadding'] .';
				padding-left:  '.$config['lm-op-topLinksIconPadding'] .';
			}

			/* Hover */
			.'.$menu_id.' .lm-item:hover > a,
			.'.$menu_id.' .lm-item.lm-submenu-active > a{
                color: '.$config['lm-op-topLinksColorHover'].';
                background-color: '.$config['lm-op-topLinksBgColorHover'].';
			}

			/* Current Link */
			.'.$menu_id.' .lm-item.lm-item-current > a {
				color:'.$config['lm-op-current-link-color'].';
				background-color:'.$config['lm-op-current-link-bgcolor'].';
			}



			/**
			 * Sub Links ( Links & Widget links, Column Title, General Links inside submenu  )
			**/

            /* General Links inside submenus */
            .'.$menu_id.' .lm-sub a{
                color: '.$config['lm-op-subLinksFont-fontColor'].';
            }

			/* Main Sub Links ( Column Title, Main Links ) */
			.'.$menu_id.' .lm-sub-item > a {
                color: '.$config['lm-op-subLinksFont-fontColor'].';
                font-size:'.$config['lm-op-subLinksFont-fontSize'].';
				padding:'.(int)$config['lm-op-subLinksPadding'].'px;
			}
			.'.$menu_id.' .lm-col > a{
                border-color:'.$config['lm-op-subLinksBorderColor'].';
                margin-bottom:'.(int)$config['lm-op-subLinksPadding'].'px;
			}
			.'.$menu_id.' .lm-item-dropdowns .lm-sub-item > a,
			.'.$menu_id.' .lm-item-posts .lm-sub-item > a{
                border-color:'.$config['lm-op-subLinksBorderColor'].';
			}

			/* Title */
			.'.$menu_id.' .lm-sub-item > a .lm-item-title{
			    font-family:'.$config['lm-op-subLinksFont-fontFamily'].';
                '.$config['lm-op-subLinksFont-fontStyle'].'
			}

			/* Description */
			.'.$menu_id.' .lm-sub-item > a .lm-item-desc{
                color: '.$config['lm-op-subLinksDescFont-fontColor'].';
                font-family:'.$config['lm-op-subLinksDescFont-fontFamily'].';
                '.$config['lm-op-subLinksDescFont-fontStyle'].'
                font-size:'.$config['lm-op-subLinksDescFont-fontSize'].';
			}

			/* Icons + Arrows */
			.'.$menu_id.' .lm-sub-item > a .era-icon:before{
				font-size:'.$config['lm-op-subLinksIconSize'].';
				padding-right: '.(int)$config['lm-op-subLinksIconPadding'].'px;
			}
			.'.$menu_id.' .lm-sub-item > a .era-icon:after{
				padding-left: '.(int)$config['lm-op-subLinksIconPadding'].'px;
			}

			/* ++RTL Icons + Arrows */
			.'.$menu_id.'.livemenu-rtl .lm-sub-item > a .era-icon:before {
				padding-left: '.$config['lm-op-subLinksIconPadding'].';
				padding-right: 0px;
			}
			.'.$menu_id.'.livemenu-rtl .lm-sub-item > a .era-icon:after {
				padding-right: '.$config['lm-op-subLinksIconPadding'].';
				padding-left: 0px;
			}

			/* Hover & Selected */
			.'.$menu_id.' .lm-col > a:hover,
			.'.$menu_id.' .lm-col .lm-sub-item:hover > a,
			.'.$menu_id.' .lm-item-posts .lm-sub-item:hover > a,
			.'.$menu_id.' .lm-item-dropdowns .lm-sub-item:hover > a,
			.'.$menu_id.' .lm-sub .lm-submenu-active > a{
                color: '.$config['lm-op-subLinksColorHover'].';
                background-color: '.$config['lm-op-subLinksBgColorHover'].';
			}

			/* only color for general links inside */
			.'.$menu_id.' .lm-sub a:hover{
                color: '.$config['lm-op-subLinksColorHover'].';
			}

			/* Current Link */
			.'.$menu_id.' .lm-sub-item.lm-item-current  > a{
				color:'.$config['lm-op-current-link-color'].';
				background-color:'.$config['lm-op-current-link-bgcolor'].';
			}



			/**
			 * Submenu
			**/
			.'.$menu_id.' .lm-sub{

				'.self::background(array(
					'type' 				=> $config['lm-op-submenuBackground-type'],
					'color' 			=> $config['lm-op-submenuBackground-color'],
    				'image' 			=> $config['lm-op-submenuBackground-image'],
    				'image-repeat' 		=> $config['lm-op-submenuBackground-image-repeat'],
    				'image-attachment' 	=> $config['lm-op-submenuBackground-image-attachment'],
    				'image-position' 	=> $config['lm-op-submenuBackground-image-position'],
    				'gradient-dir' 		=> $config['lm-op-submenuBackground-gradient-dir'],
    				'gradient-color1' 	=> $config['lm-op-submenuBackground-gradient-color1'],
    				'gradient-color2' 	=> $config['lm-op-submenuBackground-gradient-color2'],
				)).'

				border:'.$config['lm-op-submenuBorder-size'].' solid '.$config['lm-op-submenuBorder-color'].';

                /* CSS3 Box Shadow */
                -moz-box-shadow   : 0px 0px '.$config['lm-op-submenuBoxShadow-blur'].' '.$config['lm-op-submenuBoxShadow-size'].' '.$config['lm-op-submenuBoxShadow-color'].';
                -webkit-box-shadow: 0px 0px '.$config['lm-op-submenuBoxShadow-blur'].' '.$config['lm-op-submenuBoxShadow-size'].' '.$config['lm-op-submenuBoxShadow-color'].';
                box-shadow        : 0px 0px '.$config['lm-op-submenuBoxShadow-blur'].' '.$config['lm-op-submenuBoxShadow-size'].' '.$config['lm-op-submenuBoxShadow-color'].';

                /* CSS3 Border Radius */
                -webkit-border-radius: '.$config['lm-op-submenuBorderRadius-topLeft'].' '.$config['lm-op-submenuBorderRadius-topRight'].' '.$config['lm-op-submenuBorderRadius-bottomRight'].' '.$config['lm-op-submenuBorderRadius-bottomLeft'].';
                -moz-border-radius: '.$config['lm-op-submenuBorderRadius-topLeft'].' '.$config['lm-op-submenuBorderRadius-topRight'].' '.$config['lm-op-submenuBorderRadius-bottomRight'].' '.$config['lm-op-submenuBorderRadius-bottomLeft'].';
                border-radius: '.$config['lm-op-submenuBorderRadius-topLeft'].' '.$config['lm-op-submenuBorderRadius-topRight'].' '.$config['lm-op-submenuBorderRadius-bottomRight'].' '.$config['lm-op-submenuBorderRadius-bottomLeft'].';

			}

			/* Padding */
			.'.$menu_id.' .lm-item-posts > .lm-sub,
			.'.$menu_id.' .lm-item-mega > .lm-sub{
				padding-top:'.$config['lm-op-submenuPadding-topbottom'].';
				padding-bottom:'.$config['lm-op-submenuPadding-topbottom'].';
				padding-left: '.$config['lm-op-submenuPadding-rightleft'].';
				padding-right: '.$config['lm-op-submenuPadding-rightleft'].';
			}

			/* Top Level Sub */
			.'.$menu_id.' .lm-item > .lm-sub{
				top:'.(int)$config['lm-op-menuHeight'].'px;
			}



			/**
			 * Posts
			**/
			.'.$menu_id.' .lm-item-posts > .lm-sub > li > .lm-sub a,
			.'.$menu_id.' .lm-item-posts > .lm-sub > li > .lm-sub img{
				width:'.$config['lm-op-megapostsThumbWidth'].';
			}
			.'.$menu_id.' .lm-item-posts > .lm-sub > li > .lm-sub a{
				min-height:'.$config['lm-op-megapostsThumbHeight'].';
			}
			.'.$menu_id.' .lm-item-posts > .lm-sub > li > .lm-sub img{
				height:'.$config['lm-op-megapostsThumbHeight'].';
			}
			.'.$menu_id.' .lm-item-posts > .lm-sub > li > .lm-sub a{
                color:'.$config['lm-op-megapostsLinksFont-fontColor'].';
                font-size:'.$config['lm-op-megapostsLinksFont-fontSize'].';
                font-family:'.$config['lm-op-megapostsLinksFont-fontFamily'].';
                '.$config['lm-op-megapostsLinksFont-fontStyle'].'
			}


			/* Widget : Search */
			.'.$menu_id.' .livemenu-searchinput{
				height:'.$config['lm-op-searchInputHeight'].';
				line-height:'.$config['lm-op-searchInputHeight'].';
				background:'.$config['lm-op-searchInputBgColor'].';
                padding-left:'.$config['lm-op-searchTextPadding'].';

                font-size:'.$config['lm-op-searchTextFont-fontSize'].';
                font-family:'.$config['lm-op-searchTextFont-fontFamily'].';
                '.$config['lm-op-searchTextFont-fontStyle'].'

                color:'.$config['lm-op-searchTextFont-fontColor'].';
                border-color:'.$config['lm-op-search-input-border-Color'].';
			}
            .'.$menu_id.' .livemenu-searchform ::-webkit-input-placeholder { /* WebKit browsers */
                color:'.$config['lm-op-searchTextFont-fontColor'].';
            }
            .'.$menu_id.' .livemenu-searchform :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
                color:'.$config['lm-op-searchTextFont-fontColor'].';
                opacity:  1;
            }
            .'.$menu_id.' .livemenu-searchform ::-moz-placeholder { /* Mozilla Firefox 19+ */
                color:'.$config['lm-op-searchTextFont-fontColor'].';
                opacity:  1;
            }
            .'.$menu_id.' .livemenu-searchform :-ms-input-placeholder { /* Internet Explorer 10+ */
                color:'.$config['lm-op-searchTextFont-fontColor'].';
            }

			/* Icon */
			.'.$menu_id.' .livemenu-searchsubmit{
				height:'.$config['lm-op-searchInputHeight'].';
				line-height:'.$config['lm-op-searchInputHeight'].';
                font-size:'.$config['lm-op-searchIconSize'].';
                color:'.$config['lm-op-searchTextFont-fontColor'].';
			}



			/**
			 * Responsive
			**/
			.'.$menu_id.'.livemenu-responsive .livemenu{
				top:'.$config['lm-op-menuHeight'].';
				background-color:'.$config['lm-op-menuBackground-color'].';
			}
			.'.$menu_id.'.livemenu-responsive .livemenu-responsive-controls {
				height:'.$config['lm-op-menuHeight'].';
			}
			.'.$menu_id.'.livemenu-responsive .livemenu-responsive-controls .era-icon-menu{
				font-size:'.$config['lm-op-responsiveIcon-size'].';
				color:'.$config['lm-op-responsiveIcon-color'].';
				height:'.$config['lm-op-menuHeight'].';
				line-height:'.$config['lm-op-menuHeight'].';
			}

			/** Main Links **/
			.'.$menu_id.'.livemenu-responsive .lm-item > a,
			.'.$menu_id.'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a,
			.'.$menu_id.'.livemenu-responsive .lm-item-posts .lm-sub-item > a{

				padding:0 '.$config['lm-op-responsiveLinks-links-padding'].';

				height:'.$config['lm-op-responsiveLinks-height'].';
				line-height:'.$config['lm-op-responsiveLinks-height'].';
                font-size:'.$config['lm-op-topLinksFont-fontSize'].';

			}

			/* borders */
			.'.$menu_id.'.livemenu-responsive .lm-item > a{
				border-color:'.$config['lm-op-topLinksBorderColor'].';
			}
			.'.$menu_id.'.livemenu-responsive .lm-item-posts .lm-sub-item > a,
			.'.$menu_id.'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a {
				border-color:'.$config['lm-op-subLinksBorderColor'].';
			}

			/* Title */
			.'.$menu_id.'.livemenu-responsive .lm-item > a .lm-item-title,
			.'.$menu_id.'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a .lm-item-title,
			.'.$menu_id.'.livemenu-responsive .lm-item-posts .lm-sub-item > a .lm-item-title{
                font-family:'.$config['lm-op-topLinksFont-fontFamily'].';
                '.$config['lm-op-topLinksFont-fontStyle'].'
			}

			/* Icon */
			.'.$menu_id.'.livemenu-responsive .lm-item > a .lm-responsive-links-arrow,
			.'.$menu_id.'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a .lm-responsive-links-arrow,
			.'.$menu_id.'.livemenu-responsive .lm-item-posts .lm-sub-item > a .lm-responsive-links-arrow{
				height:'.$config['lm-op-responsiveLinks-height'].';
				line-height:'.$config['lm-op-responsiveLinks-height'].';
			}
			.'.$menu_id.'.livemenu-responsive .lm-item > a .lm-responsive-links-arrow.lm-arrow-up{
			    color: '.$config['lm-op-topLinksFont-fontColor'].';
				background-color:'.$config['lm-op-menuBackground-color'].';
			}
			.'.$menu_id.'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a .lm-responsive-links-arrow.lm-arrow-up,
			.'.$menu_id.'.livemenu-responsive .lm-item-posts .lm-sub-item > a .lm-responsive-links-arrow.lm-arrow-up{
			    color: '.$config['lm-op-subLinksFont-fontColor'].';
				background-color:'.$config['lm-op-submenuBackground-color'].';
			}

			/** Posts **/
			.'.$menu_id.'.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > .lm-sub{
				padding-top:'.$config['lm-op-submenuPadding-topbottom'].';
				padding-bottom:'.$config['lm-op-submenuPadding-topbottom'].';
				padding-left: '.$config['lm-op-submenuPadding-rightleft'].';
				padding-right: '.$config['lm-op-submenuPadding-rightleft'].';
			}
			.'.$menu_id.'.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > a{
				padding:0 '.$config['lm-op-responsiveLinks-links-padding'].' 0 '.( (int)$config['lm-op-responsiveLinks-links-padding'] * 2 ).'px;
			}
			/** ++RTL **/
			.'.$menu_id.'.livemenu-rtl.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > a{
				padding:0 '.( (int)$config['lm-op-responsiveLinks-links-padding'] * 2 ).'px 0 '.$config['lm-op-responsiveLinks-links-padding'].';
			}

			/** Dropdowns **/
			.'.$menu_id.'.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > a{
				padding:0 '.$config['lm-op-responsiveLinks-links-padding'].' 0  '.( (int)$config['lm-op-responsiveLinks-links-padding'] * 2 ).'px;
			}
			.'.$menu_id.'.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > a{
				padding:0 '.$config['lm-op-responsiveLinks-links-padding'].' 0  '.( (int)$config['lm-op-responsiveLinks-links-padding'] * 4 ).'px;
			}
			.'.$menu_id.'.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > a{
				padding:0 '.$config['lm-op-responsiveLinks-links-padding'].' 0  '.( (int)$config['lm-op-responsiveLinks-links-padding'] * 6 ).'px;
			}
			/** ++RTL **/
			.'.$menu_id.'.livemenu-rtl.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > a{
				padding:0 '.( (int)$config['lm-op-responsiveLinks-links-padding'] * 2 ).'px 0  '.$config['lm-op-responsiveLinks-links-padding'].';
			}
			.'.$menu_id.'.livemenu-rtl.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > a{
				padding:0 '.( (int)$config['lm-op-responsiveLinks-links-padding'] * 4 ).'px 0  '.$config['lm-op-responsiveLinks-links-padding'].';
			}
			.'.$menu_id.'.livemenu-rtl.livemenu-responsive .lm-item-dropdowns > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > .lm-sub > .lm-sub-item > a{
				padding:0 '.( (int)$config['lm-op-responsiveLinks-links-padding'] * 6 ).'px 0  '.$config['lm-op-responsiveLinks-links-padding'].';
			}

			/** Social Media **/
			.'.$menu_id.'.livemenu-responsive .lm-item-responsive-socialmedia{
                border-color:'.$config['lm-op-topLinksBorderColor'].';
			}
			.'.$menu_id.'.livemenu-responsive .lm-item-responsive-socialmedia:hover > a{
                color: '.$config['lm-op-topLinksFont-fontColor'].';
                background-color:transparent;
			}
			.'.$menu_id.'.livemenu-responsive .lm-item-responsive-socialmedia > a:hover{
                color: '.$config['lm-op-topLinksColorHover'].';
                background-color: '.$config['lm-op-topLinksBgColorHover'].';
			}

		';

		// Create the file
		$upload_dir = 	wp_upload_dir();
		$my_file 	= 	$upload_dir['basedir'].'/livemenu/css/'.$filename;

		$handle 	= 	@fopen($my_file, 'w');
						@fwrite($handle, $style_string);
						@fclose($handle);

		// Update Cache Version
		do_action('livemenu_update_cache');

	}// End Func


	/**
	 * Create Javascript Style File
	 * @since 1.0
	**/
	static function javascript(){

		$output 			= ' $livemenu_global_obj = {};'."\n\n\n";
		$site_url 			= get_bloginfo('url');
		$wp_used_locations 	= get_option( 'livemenu_registered_menus' );

		foreach( $wp_used_locations as $location => $menu_id ){

			$settings = get_option('livemenu_'.$location.'_config');
			$settings = $settings['settings'];

			$output .= ' $livemenu_global_obj["'.$location.'"] = {};'."\n";
			$output .= ' $livemenu_global_obj["'.$location.'"].config = {};'."\n";

			$output .= ' $livemenu_global_obj["'.$location.'"].config.site_url 				= "'.$site_url.'";'."\n";
			$output .= ' $livemenu_global_obj["'.$location.'"].config.responsive 				= "'.$settings['lm-op-responsive'].'";'."\n";
			$output .= ' $livemenu_global_obj["'.$location.'"].config.responsive_breakpoint 	= "'.$settings['lm-op-responsive-start-width'].'";'."\n";
			$output .= ' $livemenu_global_obj["'.$location.'"].config.sticky 					= "'.$settings['lm-op-sticky'].'";'."\n";
			$output .= ' $livemenu_global_obj["'.$location.'"].config.jquery_trigger 			= "'.$settings['lm-op-jqueryTrigger'].'";'."\n";
			$output .= ' $livemenu_global_obj["'.$location.'"].config.jquery_animation 		= "'.$settings['lm-op-jqueryAnimation'].'";'."\n";
			$output .= ' $livemenu_global_obj["'.$location.'"].config.jquery_time 				= "'.$settings['lm-op-jqueryTime'].'";'."\n\n";

			$output .= ' $livemenu_global_obj["'.$location.'"].plugin = jQuery("#livemenu-id-'.$location.'").livemenu( $livemenu_global_obj["'.$location.'"].config );'."\n\n\n";

		}

		// Create the file
		$upload_dir = 	wp_upload_dir();
		$my_file 	= 	$upload_dir['basedir'].'/livemenu/js/livemenu_global_obj.js';

		$handle 	= 	@fopen($my_file, 'w');
						@fwrite($handle, $output);
						@fclose($handle);

		// Update Cache Version
		do_action('livemenu_update_cache');
	}


	/**
	 * Create Custom CSS Style File
	 * @since 1.0
	**/
	static function custom_items_css( $menu_id ){

		// get data from db
		if( ! $data = get_option('livemenu_'.$menu_id.'_config') ) return;
		if( ! isset( $data['item_style'] ) ) $data['item_style'] = array();


		$style_string 	= '';
		$items_style 	= $data['item_style'];

		foreach( $items_style as $item_id => $array ){

			$style_string .= '
				/* Item */
				#lm-item-'.$item_id.' > a {
					color:'.$array['lm-op-custom-color'].';
					background-color:'.$array['lm-op-custom-background-color'].';
				}
				#lm-item-'.$item_id.':hover > a{
					color:'.$array['lm-op-custom-color-hover'].';
					background-color:'.$array['lm-op-custom-background-color-hover'].';
				}
			';

			$style_string .= '

				/* Submenu */
				.livemenu-horizontal #lm-item-' . $item_id .' > .lm-sub{

					'.self::background(array(
						'type' 				=> $array['lm-op-custom-submenuBackground-type'],
						'color' 			=> $array['lm-op-custom-submenuBackground-color'],
	    				'image' 			=> $array['lm-op-custom-submenuBackground-image'],
	    				'image-repeat' 		=> $array['lm-op-custom-submenuBackground-image-repeat'],
	    				'image-attachment' 	=> $array['lm-op-custom-submenuBackground-image-attachment'],
	    				'image-position' 	=> $array['lm-op-custom-submenuBackground-image-position'],
	    				'gradient-dir' 		=> $array['lm-op-custom-submenuBackground-gradient-dir'],
	    				'gradient-color1' 	=> $array['lm-op-custom-submenuBackground-gradient-color1'],
	    				'gradient-color2' 	=> $array['lm-op-custom-submenuBackground-gradient-color2'],
					)).'

					border:'.$array['lm-op-custom-submenuBorder-size'].' solid '.$array['lm-op-custom-submenuBorder-color'].';

	                /* CSS3 Box Shadow */
	                -moz-box-shadow   : 0px 0px '.$array['lm-op-custom-submenuBoxShadow-blur'].' '.$array['lm-op-custom-submenuBoxShadow-size'].' '.$array['lm-op-custom-submenuBoxShadow-color'].';
	                -webkit-box-shadow: 0px 0px '.$array['lm-op-custom-submenuBoxShadow-blur'].' '.$array['lm-op-custom-submenuBoxShadow-size'].' '.$array['lm-op-custom-submenuBoxShadow-color'].';
	                box-shadow        : 0px 0px '.$array['lm-op-custom-submenuBoxShadow-blur'].' '.$array['lm-op-custom-submenuBoxShadow-size'].' '.$array['lm-op-custom-submenuBoxShadow-color'].';

	                /* CSS3 Border Radius */
	                -webkit-border-radius: '.$array['lm-op-custom-submenuBorderRadius-topLeft'].' '.$array['lm-op-custom-submenuBorderRadius-topRight'].' '.$array['lm-op-custom-submenuBorderRadius-bottomRight'].' '.$array['lm-op-custom-submenuBorderRadius-bottomLeft'].';
	                -moz-border-radius: '.$array['lm-op-custom-submenuBorderRadius-topLeft'].' '.$array['lm-op-custom-submenuBorderRadius-topRight'].' '.$array['lm-op-custom-submenuBorderRadius-bottomRight'].' '.$array['lm-op-custom-submenuBorderRadius-bottomLeft'].';
	                border-radius: '.$array['lm-op-custom-submenuBorderRadius-topLeft'].' '.$array['lm-op-custom-submenuBorderRadius-topRight'].' '.$array['lm-op-custom-submenuBorderRadius-bottomRight'].' '.$array['lm-op-custom-submenuBorderRadius-bottomLeft'].';
	            }

			';

		}// End Foreach

		// Create the file
		$upload_dir = 	wp_upload_dir();
		$upload_dir = 	$upload_dir['basedir'].'/livemenu/css/';

		$my_file 	= 	$upload_dir . $menu_id . '-custom.css';
		$handle 	= 	@fopen($my_file, 'w');
						@fwrite($handle, $style_string);
						@fclose($handle);

		// Update Cache Version
		do_action('livemenu_update_cache');
	}


	/**
	 * Create Global Custom CSs File
	 * @since 2.0
	**/
	static function global_custom_css(){

		// check if the option exists first
		if( $custom_css = get_option( 'livemenu_custom_css' ) ){

			// Create the file
			$upload_dir = 	wp_upload_dir();
			$my_file 	= 	$upload_dir['basedir'].'/livemenu/css/livemenu-global-css.css';

			$handle 	= 	@fopen($my_file, 'w');
							@fwrite($handle, $custom_css);
							@fclose($handle);

		}

		// Update Cache Version
		do_action('livemenu_update_cache');
	}


	/**
	 * Create Export File
	 * @since 1.0
	**/
	static function export_file( $config ){

		// Create the file
		$upload_dir = 	wp_upload_dir();
		$file_url 	= 	$upload_dir['baseurl'].'/livemenu/export.txt';
		$file_path 	= 	$upload_dir['basedir'].'/livemenu/export.txt';

		$handle 	= 	@fopen($file_path, 'w');
						@fwrite($handle, json_encode($config) );
						@fclose($handle);

		return $file_url;

	}// End Func



	/**
	 * Remove Opacity From background color
	 * @since 1.0
	**/
	static function get_sticky_true_color( $color ){

		// Opacity Exists
		// the best why to search for "gba" because rgba is the first word and will strpos will return 0 and 0=false
		if( strpos($color, 'gba') != false ){

			$color = str_replace('rgba(', '', $color);
			$color = str_replace(')', '', $color);

			$arr = explode( ',', $color );
			return 'rgb('.$arr[0].','.$arr[1].','.$arr[2].')';
		}

	}// End Func



	/**
	 * Prepare Background CSS Prop
	 * @since 1.0
	**/
	static function background( $config ){

		// Only Color
		if( $config['type'] == 'color' ){
			return 'background-color:'.$config['color'].';';
		}

		// Image
		elseif( $config['type'] == 'image' ){
			return '
				background-color:'.$config['color'].';
				background-image:url(\''. $config['image'].'\');
                background-repeat:'.$config['image-repeat'].';
                background-attachment:'.$config['image-attachment'].';
                background-position:'.$config['image-position'].';
            ';
		}

		// gradient
		elseif( $config['type'] == 'gradient' ){
			return '
				background-color: '.$config['color'].';
                background-image: -webkit-linear-gradient('.$config['gradient-dir'].', '.$config['gradient-color1'].', '.$config['gradient-color2'].') ;
                background-image: -moz-linear-gradient('.$config['gradient-dir'].', '.$config['gradient-color1'].', '.$config['gradient-color2'].') ;
                background-image: -o-linear-gradient('.$config['gradient-dir'].', '.$config['gradient-color1'].', '.$config['gradient-color2'].') ;
                background-image: -ms-linear-gradient('.$config['gradient-dir'].', '.$config['gradient-color1'].', '.$config['gradient-color2'].') ;
                background-image: linear-gradient('.$config['gradient-dir'].', '.$config['gradient-color1'].', '.$config['gradient-color2'].') ;
			';
		}

	}


	/**
	 * Prepare Menu Layout
	 * @since 1.0
	**/
	static function layout( $layout, $width ){

		// boxed
		if( $layout == 'boxed' ){
			return 'width:'.$width.';';
		}

		// wide
		else if( $layout == 'wide' ){
			return 'width:100%;';
		}

	}// End Func

}// End Class