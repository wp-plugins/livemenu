/**
 * Livemenu: Customizer Plugin
 *
 * @author sabri taieb
 * @url http://vamospace.com
 * @since 1.0
**/
;(function( $ ){


    /* Default Config */
    var default_config = {
        logo_type               : "no-logo",
        logo_src                : "",
        responsive              : "off",
        responsive_breakpoint   : 959,
        sticky                  : "off",
        jquery_trigger          : "hover",
        jquery_animation        : "none",
        jquery_time             : 350,
        debug                   : false, // Display Debug Log
        click_outside           : true,
    };


    // Remove Left/Right Border When Menu is bigger than window
    lm_remove_menu_border = function( $menu ){

        if( $menu.outerWidth() > $(window).width() && $menu.css('border-left-width') == '1px' ) {
            $menu.css({ 'border-left-width':'0px', 'border-right-width':'0px' });
        }

    };

    // Remove Arrow From Search Link
    lm_remove_arrow_from_search = function( $menu ){

        $menu.find('.lm-item.lm-item-disable-text > a .era-icon-magnifying-glass').addClass('lm-remove-arrow-from-search');

    };

    // Handle WPML [ Before init() ]
    lm_handle_wpml = function( $menu ){

        var $self = $menu.find('.menu-item-language');

        $self.attr('class','lm-item lm-item-depth-0 lm-has-submenu lm-item-dropdowns lm-item-wpml');
        $self.find('ul').attr('class','lm-sub')
        $self.find('li').attr('class','lm-sub-item lm-item-depth-1');
        $self.find('a').wrapInner('<div class="era-icon"><span class="lm-item-title"></span></div>');

        $self.find('> a .era-icon')
            .addClass('lm-arrow-down')
            .append('<span class="lm-responsive-links-arrow era-icon lm-arrow-down"></span>');

    };

    // Handle RTL [ Before init() ]
    lm_handle_rtl = function( $menu ){

        if( ! $menu.is('.livemenu-rtl') ) return;

        $menu.find('.lm-arrow-right')
            .removeClass('lm-arrow-right')
            .addClass('lm-arrow-left');

    };

    // window scroll [Must be Called from here to prevent re-call when resizing] required
    lm_handle_sticky = function( $menu, $config ){

        if( $config.sticky == 'on' ){

            var $old_offset_top = ( $menu.length ) ? $menu.offset().top : 0;

            $(window).on('scroll',function(){

                if( $(window).scrollTop() > $old_offset_top && $(window).width() > $config.responsive_breakpoint ){

                    $menu.addClass('livemenu-sticky');

                    if( ! $menu.prev().is('.livemenu-sticky-holder') ){
                        $menu.before('<div class="livemenu-sticky-holder" style="height:'+$menu.css('height')+';width:100%;background:transparent !important;border:none !important;"></div>');
                    }

                }
                else{

                    $menu.removeClass('livemenu-sticky');
                    $menu.prev('.livemenu-sticky-holder').remove();

                }

            });

        }

    };

    // Lazy Load Images
    lm_handle_lazy_load = function( $menu ){

        setTimeout(function(){
            $( '.lm-lazy-load', $menu ).each(function(){
                var $self = $(this);
                $self.attr( 'src', $self.data('src') );
            });
        },100);

    };

    // Mega Posts Re-set the width of posts container
    lm_mega_posts_set_size = function( $menu ){

        $( '.lm-item-posts', $menu ).each(function(){

            var $self       = $(this);
            var $cats_col   = $self.find('.lm-posts-categories').attr('class').split(' ')[2];

            if( $cats_col == 'lm-col-20' ){
                $self.find('> .lm-sub > li').addClass('lm-col lm-col-80');
            }
            else if( $cats_col == 'lm-col-25' ){
                $self.find('> .lm-sub > li').addClass('lm-col lm-col-75');
            }
            else{
                $self.find('> .lm-sub > li').addClass('lm-col lm-col-66');
            }

        });

    };


    /* Class: Livemenu */
    var ClassLivemenu = function( menu, config ){

        this.livemenu               = menu;
        this.config                 = $.extend( {}, default_config, config );
        this.click_outside_started  = false;

        // Detect Touch Devices
        // more info here : https://hacks.mozilla.org/2013/04/detecting-touch-its-the-why-not-the-how/
        this.touch_device = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0);

        if( window.navigator.pointerEnabled ){
            this.touch_start = 'pointerdown';
        }
        else if( window.navigator.msPointerEnabled ){
            this.touch_start = 'MSPointerDown';
        }
        else{
            this.touch_start = 'touchstart';
        }

        // if device is not a touch one, than use click
        if( ! this.touch_device ){
            this.touch_start = 'click';
        }

        // Remove Left/Right Border When Menu is bigger than window
        lm_remove_menu_border( this.livemenu );

        // Remove Arrow From Search Link
        lm_remove_arrow_from_search( this.livemenu );

        // Handle WPML
        lm_handle_wpml( this.livemenu );

        // Handle RTL
        lm_handle_rtl( this.livemenu );

        // Initialise
        this.init();

        // Window Resize [Must be Called from here to prevent re-call when resizing] required
        this.window_resize();

        lm_handle_sticky( this.livemenu, this.config );

        // Lazy Load the Images
        lm_handle_lazy_load( this.livemenu );

    };


    /* Prototype */
    ClassLivemenu.prototype = {

        // Init
        init : function(){

            var Class = this;

            // Log the device type
            Class.log( (this.touch_device) ? 'Touch Device' : 'Standard Device' );

            // Clean First
            Class.clean();

            // Responsive or Horizontal
            Class.responsive = ( Class.config.responsive == 'on' && $(window).width() <= Class.config.responsive_breakpoint );

            // Responsive Menu
            if( this.responsive && ! this.livemenu.is('.livemenu-responsive') ){

                Class.log( 'Livemenu Responsive' );

                // Build Responsive
                Class.responsive_build();

                // Open/Close Menu Listener
                Class.responsive_toggle();

            }

            // Horizontal Menu
            else{

                Class.log( 'Livemenu Horizontal' );

                // Add Horizontal class
                Class.livemenu.addClass('livemenu-horizontal');

                // Mega Posts Re-set the width of posts container
                lm_mega_posts_set_size( Class.livemenu );

            }

        },


        // display log message
        log : function( $message ){
            // return false if console does not exists
            if( typeof window.console === 'undefined' || this.config.debug === false ) return false;
            console.log( $message );
        },


        // Disable All Listeners
        // remove added classes
        clean : function(){

            this.log('Cleaning Started');

            // Hide Menu First
            this.livemenu.children('.livemenu').hide();

            // Remove Classes
            this.livemenu.removeClass('livemenu-horizontal livemenu-sticky livemenu-responsive');

            // Unbind/Off Listeners
            this.livemenu.find('.lm-item.lm-has-submenu, .lm-item-dropdowns .lm-sub-item.lm-has-submenu, .lm-posts-categories li, .lm-posts-categories li a, .lm-item.lm-has-submenu > a, .lm-item-dropdowns .lm-sub-item.lm-has-submenu > a').unbind().off();
            this.livemenu.find('.livemenu-responsive-controls > .era-icon').unbind().off();
            this.livemenu.find('.lm-responsive-links-arrow').unbind().off();

            // Close Submenus
            this.livemenu.find('.lm-has-submenu.lm-submenu-active')
                .removeClass('lm-submenu-active')
                .find('.lm-sub').hide();

            // Mega Posts
            this.livemenu.find('.lm-item-posts > .lm-sub > li')
                .removeClass('lm-col lm-col-80 lm-col-75 lm-col-66')
                .removeAttr('style')
                .parent().find('.lm-posts-categories > li.lm-submenu-active')
                    .removeClass('lm-submenu-active')
                    .parent().find('li:first').addClass('lm-submenu-active');

            // revert responsive icon direction
            this.livemenu.find('.lm-responsive-links-arrow')
                .removeClass('lm-arrow-up')
                .addClass('lm-arrow-down');

            // Last Display Menu
            this.livemenu.children('.livemenu').show();
        },


        // windows resize
        window_resize : function(){

            var Class = this;
            var timer;

            $(window).resize(function(){

                Class.log('Window resize');

                clearTimeout( timer );

                timer = setTimeout(function(){

                    // Responsive or Horizontal
                    Class.responsive = ( Class.config.responsive == 'on' && $(window).width() <= Class.config.responsive_breakpoint );

                    // Responsive Menu
                    if( Class.responsive && ! Class.livemenu.is('.livemenu-responsive') ){
                        Class.init();
                    }

                    if( ! Class.responsive && Class.livemenu.is('.livemenu-responsive') ){
                        Class.init();
                    }

                },150);

            });

        },


        // Responsive Build
        responsive_build : function(){

            var Class = this;

            Class.livemenu.children('.livemenu').hide();
            Class.livemenu.addClass('livemenu-responsive');

            /* Social Media */
            if( $('.lm-item-responsive-socialmedia').length == 0 ){

                Class.livemenu.children('.livemenu').append('<li class="lm-item lm-item-responsive-socialmedia"></li>')
                $('.lm-item.lm-item-socialmedia').each(function(){
                    $('.lm-item-responsive-socialmedia').append( $(this).find('> a').clone() );
                });
            }
        },


        // Responsive Toggle
        responsive_toggle : function(){
            var Class = this;

            $( '.livemenu-responsive-controls > .era-icon', Class.livemenu ).on( Class.touch_start,function(){

                if( $(this).parents('.livemenu-responsive').is('.livemenu-responsive-open') ){

                    $(this).parents('.livemenu-responsive').removeClass('livemenu-responsive-open')
                        .children('.livemenu').fadeOut( Class.config.jquery_time );

                }
                else{

                    $(this).parents('.livemenu-responsive').addClass('livemenu-responsive-open')
                        .children('.livemenu').fadeIn( Class.config.jquery_time );
                }

            });

        },


    };



    /**
     * Livemenu Plugin
     * @since 1.0
    **/
    $.fn.livemenu = function( config ){

        // Start The Show
        new ClassLivemenu( this, config );

        // chainability
        return this;

    };


})( jQuery );






// Functions For All The Menus
;(function($){


    // Event: Click on Gear Icon
    $(document).on( 'click', '.lm-open-menu-controls',function( event ){

        event.preventDefault();
        event.stopPropagation();

        var $self               = jQuery(this);
        var $controls           = $self.parent().find('> .lm-controls');
        var $menu_offset        = $self.parents('.livemenu-wrap').offset().top;
        var $menu_height        = $self.parents('.livemenu-wrap').outerHeight();
        var $top                = 0;

        // Open/Hide Control Buttons
        $('.lm-controls').slideUp(150);

        // if wpadminbar exists
        // add the controls under it.
        if( $('#wpadminbar').length ) {
            $top = $('#wpadminbar').outerHeight();
        }

        if( $menu_offset > $controls.outerHeight() ){
            $controls.css({
               'top'    : $top + 'px',
               'bottom' : 'auto',
            }).slideDown(250);
        }
        else{
            $controls.css({
               'top'    : 'auto',
               'bottom' : '0',
            }).slideDown(250);
            $controls.find('.era-icon-chevron-thin-down')
                .removeClass('era-icon-chevron-thin-down')
                .addClass('era-icon-chevron-thin-up');
        }

    });


    // Open Submenu & Controls
    $( document ).on( 'click', '.lm-item, .lm-sub-item', function(event){

        event.preventDefault();
        event.stopPropagation();

        var $self               = $(this);
        var $controls           = $self.find('> .lm-controls');
        var $menu_offset        = $self.parents('.livemenu-wrap').offset().top;
        var $menu_height        = $self.parents('.livemenu-wrap').outerHeight();
        var $top                = 0;

      // not for Mega Posts Categories"
      if( $self.parent().is('.lm-posts-categories') ) return;

      // check if this already open, then close it
      if( $self.is('.lm-submenu-active') ){

        $self.removeClass('lm-submenu-active');
        $self.find('> .lm-controls').hide();
        $self.find('> a .lm-responsive-links-arrow')
            .removeClass('lm-arrow-up')
            .addClass('lm-arrow-down');

      }

      // open
      else{

        // Hide Customizer Control
        jQuery('.lm-controls').slideUp(150);

        // if wpadminbar exists
        // add the controls under it.
        if( $('#wpadminbar').length ) {
            $top = $('#wpadminbar').outerHeight();
        }

        if( $menu_offset > $controls.outerHeight() ){
            $controls.css({
               'top'    : $top + 'px',
               'bottom' : 'auto',
            }).slideDown(250);
        }
        else{
            $controls.css({
               'top'    : 'auto',
               'bottom' : '0',
            }).slideDown(250);
            $controls.find('.era-icon-chevron-thin-down')
                .removeClass('era-icon-chevron-thin-down')
                .addClass('era-icon-chevron-thin-up');
        }

        // Open Submenu inside
        if( $self.is('.lm-has-submenu') ){
          $self.addClass('lm-submenu-active');
        }

        // Close Other Subs
        $self.siblings().removeClass('lm-submenu-active');

        // Responsive Links Arrow
        if( $self.parents('.livemenu-responsive').length ){

          $self.find('> a .lm-responsive-links-arrow')
            .removeClass('lm-arrow-down')
            .addClass('lm-arrow-up');

          $self.siblings().find('> a .lm-responsive-links-arrow')
            .removeClass('lm-arrow-up')
            .addClass('lm-arrow-down');

        }

      }

    });


    // Control Position
    $( document ).on( 'click', '.lm-controls .era-icon-chevron-thin-down', function(){

        $(this).parents('.lm-controls').css({
            top   : 'auto',
            bottom : '0',
        });

        $(this).removeClass('era-icon-chevron-thin-down').addClass('era-icon-chevron-thin-up');

    });
    $( document ).on( 'click', '.lm-controls .era-icon-chevron-thin-up', function(){

        var $top = 0;

        // if wpadminbar exists
        // add the controls under it.
        if( $('#wpadminbar').length ) {
          $top = $('#wpadminbar').outerHeight();
        }

        $(this).parents('.lm-controls').css({
            top   : $top,
            bottom : 'auto',
        });

        $(this).removeClass('era-icon-chevron-thin-up').addClass('era-icon-chevron-thin-down');

    });


})(jQuery);