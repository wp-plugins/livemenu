/**
 * Livemenu: Front-End Plugin
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
        this.remove_menu_border();

        // Remove Arrow From Search Link
        this.remove_arrow_from_search();

        // Handle WPML [ Before init() ]
        this.handle_wpml();

        // Handle RTL [ Before init() ]
        this.rtl_support = this.livemenu.is('.livemenu-rtl');
        this.handle_rtl();

        // Initialise
        this.init( );

        // Window Resize [Must be Called from here to prevent re-call when resizing] required 
        this.window_resize();

        // window scroll [Must be Called from here to prevent re-call when resizing] required
        this.handle_sticky();

        // Lazy Load Images
        this.handle_lazy_load();
         
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
            if( Class.responsive && ! Class.livemenu.is('.livemenu-responsive') ){

                Class.log( 'Livemenu Responsive' );

                // Build Responsive
                Class.responsive_build();

                // Open/Close Menu Listener
                Class.responsive_toggle();

                // Main Links Listener
                Class.responsive_links_listener();
            
            }

            // Horizontal Menu 
            else{

                Class.log( 'Livemenu Horizontal' );

                // Add Horizontal class
                Class.livemenu.addClass('livemenu-horizontal');

                // when responsive is on, these classes will be removed
                Class.mega_posts_set_size();

                // Handle Trigger
                Class.handle_trigger();

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

            var Class = this;

            Class.log('Cleaning Started');

            // Hide Menu First
            Class.livemenu.children('.livemenu').hide();

            // Remove Classes
            Class.livemenu.removeClass('livemenu-horizontal livemenu-sticky livemenu-responsive');

            // Unbind/Off Listeners
            Class.livemenu.find('.lm-item.lm-has-submenu, .lm-item-dropdowns .lm-sub-item.lm-has-submenu, .lm-posts-categories li, .lm-posts-categories li a, .lm-item.lm-has-submenu > a, .lm-item-dropdowns .lm-sub-item.lm-has-submenu > a').unbind().off();
            Class.livemenu.find('.livemenu-responsive-controls > .era-icon').unbind().off();
            Class.livemenu.find('.lm-responsive-links-arrow').unbind().off();

            // Close Submenus
            Class.livemenu.find('.lm-has-submenu.lm-submenu-active')
                .removeClass('lm-submenu-active')
                .find('.lm-sub').hide();

            // Mega Posts
            Class.livemenu.find('.lm-item-posts > .lm-sub > li')
                .removeClass('lm-col lm-col-80 lm-col-75 lm-col-66')
                .removeAttr('style')
                .parent().find('.lm-posts-categories > li.lm-submenu-active')
                    .removeClass('lm-submenu-active')
                    .parent().find('li:first').addClass('lm-submenu-active');

            // revert responsive icon direction
            Class.livemenu.find('.lm-responsive-links-arrow')
                .removeClass('lm-arrow-up')
                .addClass('lm-arrow-down');

            // Last Display Menu
            Class.livemenu.children('.livemenu').show();
        },


        // Remove Left/Right Border when Menu is Bigger Than Window
        remove_menu_border : function(){

            var Class = this;

            if( Class.livemenu.outerWidth() > $(window).width() && Class.livemenu.css('border-left-width') == '1px' ) {
                Class.livemenu.css({ 'border-left-width':'0px', 'border-right-width':'0px' });
            }
            
        },


        // Remove Arrow From Top Link Search Type
        remove_arrow_from_search : function(){

            var Class = this;

            Class.livemenu.find('.lm-item.lm-item-disable-text > a .era-icon-magnifying-glass').addClass('lm-remove-arrow-from-search');

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


        // Mega Posts Re-set the width of posts container
        mega_posts_set_size : function(){

            var Class = this;

            $( '.lm-item-posts', Class.livemenu ).each(function(){

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

        },


        // Lazy Load Images
        handle_lazy_load : function(){
            setTimeout(function(){
                $('.lm-lazy-load').each(function(){
                    var $self = $(this);
                    $self.attr( 'src', $self.data('src') );
                });
            },290);
        },


        // Handle WPML
        handle_wpml : function(){

            var Class = this;
            var $self = Class.livemenu.find('.menu-item-language');

            $self.attr('class','lm-item lm-item-depth-0 lm-has-submenu lm-item-dropdowns lm-item-wpml lm-item-position-right');
            $self.find('ul').attr('class','lm-sub')
            $self.find('li').attr('class','lm-sub-item lm-item-depth-1');
            $self.find('a').wrapInner('<div class="era-icon"><span class="lm-item-title"></span></div>');
            
            $self.find('> a .era-icon').addClass('lm-arrow-down').append('<span class="lm-responsive-links-arrow era-icon lm-arrow-down"></span>');

        },


        // Handle RTL
        handle_rtl : function(){

            var Class = this;
            
            if( ! Class.rtl_support ) return;

            Class.livemenu.find('.lm-arrow-right').removeClass('lm-arrow-right').addClass('lm-arrow-left');
        
        },


        // Sticky Menu 
        handle_sticky : function(){

            var Class = this;

            if( Class.config.sticky == 'on' ){

                var $old_offset_top = ( Class.livemenu.length ) ? Class.livemenu.offset().top : 0;

                $(window).on('scroll',function(){
                    
                    if( $(window).scrollTop() > $old_offset_top && $(window).width() > Class.config.responsive_breakpoint ){
                        
                        Class.livemenu.addClass('livemenu-sticky');
                        if( ! Class.livemenu.prev().is('.livemenu-sticky-holder') )
                            Class.livemenu.before('<div class="livemenu-sticky-holder" style="height:'+Class.livemenu.css('height')+';width:100%;background:transparent !important;border:none !important;"></div>');
                    
                    }
                    else{
                        Class.livemenu.removeClass('livemenu-sticky');
                        Class.livemenu.prev('.livemenu-sticky-holder').remove();      
                    }

                });

            }

        },


        // Handle Trigger: Touch,Click or Hover
        handle_trigger : function(){

            var Class = this;

            // Touch or Click 
            if( this.touch_device || this.config.jquery_trigger == 'click' ){
                this.log('Click Listener Started');
                this.click_listener();

                // we can not clean it, so dont call it everytime you resize[required]
                if( ! this.click_outside_started ){
                    this.click_outside_started = true;
                    this.click_outside_listener();
                }
            }
            // Hover
            else{
                this.log('Hover Listener Started');
                this.hover_listener();
            }
        },


        // Hover Trigger
        hover_listener : function(){
            
            var Class = this; // [this] will change inside jQuery functions
            var open_intent_timer;

            // Set the Listener
            // must hover on lm-item, but for click must click on .lm-item > a
            $( '.lm-item.lm-has-submenu, .lm-item-dropdowns .lm-sub-item.lm-has-submenu', Class.livemenu ).on( 'mouseenter', function(event){
                
                var $self   = $(this); // this required, to prevent the conflict inside the setTimout
                var $sub    = $self.find('> .lm-sub');

                // open submenu after 150 milliseconds
                open_intent_timer = setTimeout(function(){
                    
                    Class.handle_animation({

                        target      : $sub,
                        animation   : Class.config.jquery_animation,
                        speed       : Class.config.jquery_time,
                        callback    : function(){

                            var $sub        = $(this);
                            var $parent     = $sub.parent();
                            $parent.addClass('lm-submenu-active');

                        },
                        reverse     : false, // Show
                    });

                },150);

            });


            // mouse leave, close submenu
            $( '.lm-item.lm-has-submenu, .lm-item-dropdowns .lm-sub-item.lm-has-submenu', Class.livemenu ).on( 'mouseleave', function(event){
                
                var $self = $(this);
                var $sub    = $self.find('> .lm-sub');

                clearTimeout( open_intent_timer );

                Class.handle_animation({
                    target      : $sub,
                    animation   : Class.config.jquery_animation,
                    speed       : Class.config.jquery_time,
                    callback    : function(){
                        
                        var $sub     = $(this);
                        var $parent  = $sub.parent();

                        $parent.removeClass('lm-submenu-active');
                    },
                    reverse     : true, // Hide
                });

            });

            
            // Horizontal Mega Posts Categories Hover
            $( '.lm-posts-categories li', Class.livemenu ).on( 'mouseenter', function(event){
                
                var self   = $(this);
                var num    = self.data('number');
                var target = self.parents('.lm-sub').find('> li:eq('+num+')');

                // if this current then dont do animation
                if( ! self.is('.lm-submenu-active') ){
                    
                    event.preventDefault();
                    event.stopPropagation();

                    // current
                    self.parent().find('.lm-submenu-active').removeClass('lm-submenu-active');
                    self.addClass('lm-submenu-active');

                    // Hide Others
                    target.parents('.lm-sub').find('> li')
                        .stop(true,true).css('display', 'none');
                    
                    // Show Selected Posts
                    target.stop(true,true).css('display', 'inline-block');
                
                }

            });

        },


        // Click Trigger
        click_listener : function(){
            
            var Class = this; // [this] will change inside jQuery functions
            var intent_timer;

            // Set the Listener
            $( '.lm-item.lm-has-submenu > a, .lm-item-dropdowns .lm-sub-item.lm-has-submenu > a', Class.livemenu ).on( Class.touch_start, function( event ){

                event.preventDefault();
                event.stopPropagation();

                // $self is .lm-item 
                var $self   = $(this).parent();
                var $sub    = $self.find('> .lm-sub');

                // Handle Link, Follow or Prevent
                var link_prevented = Class.handle_link( $self, event, Class );

                // Open Submenu
                if( link_prevented ){

                    // Open This Sub
                    Class.handle_animation({
                        target      : $sub,
                        animation   : Class.config.jquery_animation,
                        speed       : Class.config.jquery_time,
                        callback    : function(){

                            var $sub        = $(this);
                            var $parent     = $sub.parent();
                            $parent.addClass('lm-submenu-active');

                        },
                        reverse     : false, // Show
                    });


                    // Close Other Subs
                    $self.siblings().each(function(){
 
                        var $self   = $(this);
                        var $sub    = $self.find('> .lm-sub');

                        // IF Has not an Open SUBMENU
                        if( ! $self.is('.lm-submenu-active') ) return;

                        // Hide Submmenu
                        Class.handle_animation({
                            target      : $sub,
                            animation   : Class.config.jquery_animation,
                            speed       : 200,
                            callback    : function(){

                                var $sub     = $(this); 
                                var $parent  = $sub.parent();

                                $parent.removeClass('lm-submenu-active');

                                // Dropdowns Submenus inside this
                                if( $sub.parents('.lm-item-dropdowns').length ){
                                    $sub.find('.lm-sub').each(function(){
                                        $(this).hide();
                                        $(this).parent().removeClass('lm-submenu-active');
                                    });   
                                }

                            },
                            reverse     : true, // Hide
                        });

                    });

                }

            });


            // Horizontal Mega Posts Categories Hover
            $( '.lm-posts-categories li', Class.livemenu ).on( Class.touch_start, function(event){
                
                var self   = $(this);
                var num    = self.data('number');
                var target = self.parents('.lm-sub').find('> li:eq('+num+')');

                // if this current then dont do animation
                if( ! self.is('.lm-submenu-active') ){
                    
                    event.preventDefault();
                    event.stopPropagation();

                    // current
                    self.parent().find('.lm-submenu-active').removeClass('lm-submenu-active');
                    self.addClass('lm-submenu-active');

                    // Hide Others
                    target.parents('.lm-sub').find('> li')
                        .stop(true,true).css('display', 'none');
                    
                    // Show Selected Posts
                    target.stop(true,true).css('display', 'inline-block');

                }

                // Else Follow the link
                else{
                    if( link_target == '_blank' ) window.open( link_url , '_blank');
                    else window.location = link_url;
                }

            });

        },


        // Click Outside
        click_outside_listener : function(){

            var Class = this; // [this] will change inside jQuery functions

            // on/off
            if( ! Class.config.click_outside ) return false;

            Class.log('Click Outside Listener Started');

            // Set the Listener
            $( document ).on( Class.touch_start, '*', function(event){

                event.stopPropagation();

                var $self = $(this);

                // click outside the menu
                if( ! $self.parents('.livemenu-wrap').length ){

                    Class.log('Click or Touch Outside');

                    // Each Item that have submenu
                    $('.lm-submenu-active', Class.livemenu ).each(function(){

                        var $self   = $(this);
                        var $sub    = $(this).find('> .lm-sub');

                        // Hide Submmenu
                        Class.handle_animation({
                            target      : $sub,
                            animation   : Class.config.jquery_animation,
                            speed       : Class.config.jquery_time,
                            callback    : function(){

                                var $sub     = $(this); 
                                var $parent  = $sub.parent();

                                $parent.removeClass('lm-submenu-active');

                                // Dropdowns Submenus inside this
                                if( $sub.parents('.lm-item-dropdowns').length ){
                                    $sub.find('.lm-sub').each(function(){
                                        $(this).hide();
                                        $(this).parent().removeClass('lm-submenu-active');
                                    });   
                                }

                            },
                            reverse     : true, // Hide
                        });

                    });

                }// End IF

            });

        },


        // Handle Link, Follow or Prevent
        handle_link : function( target, event, Class ){

            var parent = target;

            var link        = parent.find('> a');
            var link_text   = link.text();
            var link_url    = link.attr('href');
            var link_target = link.attr('target');

            // if submenu is open, Follow link
            // dont follow when link = #
            if( parent.is('.lm-submenu-active') ){

                if( link_target == '_blank' ) window.open( link_url , '_blank');
                else window.location = link_url;
                
                return false;   
            
            }

            // else prevent the first click
            else{

                Class.log('Link Prevented: '+link_text);
                return true;
            
            }

        },


        // Handle Animation
        handle_animation : function( OBJ ){

            // show/hide
            if( OBJ.animation == 'show' || OBJ.animation == 'none' ){
                // if reverse == true, use hide
                if( OBJ.reverse ){
                    return OBJ.target.stop(true,true).hide( OBJ.speed, OBJ.callback );
                }
                else{
                    return OBJ.target.stop(true,true).show( OBJ.speed, OBJ.callback );
                }
            }

            // fade in/out
            else if( OBJ.animation == 'fade' ){
                // if reverse == true, use fadeOut
                if( OBJ.reverse ){
                    return OBJ.target.stop(true,true).fadeOut( OBJ.speed, OBJ.callback );
                }
                else{
                    return OBJ.target.stop(true,true).fadeIn( OBJ.speed, OBJ.callback );
                }
            }

            // slide Up/down
            else if( OBJ.animation == 'slide' ){
                // if reverse == true, use hide
                if( OBJ.reverse ){
                    return OBJ.target.stop(true,true).slideUp( OBJ.speed, OBJ.callback );
                }
                else{
                    return OBJ.target.stop(true,true).slideDown( OBJ.speed, OBJ.callback );
                }
            }

            // FadeSlide Up
            else if( OBJ.animation == 'fadeSlideUp' ){

                // set top first
                var $old_offset_top = parseInt( OBJ.target.css('top') );

                // if reverse == true, use hide
                if( OBJ.reverse ){
                    return OBJ.target.stop(true,true)
                        .animate({ top : '+=20', opacity : 0 }, OBJ.speed, function(){
                            OBJ.callback();
                            $(this).css({ 'display':'none', 'top':'', 'opacity':'1' }); /* required to reset ( top:'' ) */
                            $(this).parent().removeClass('lm-submenu-active');
                        });
                }
                else{
                    return OBJ.target.stop(true,true)
                        .css({ 'display' : 'block', 'opacity' : '0', 'top':($old_offset_top + 20) + 'px' })
                        .animate({ top : '-=20', opacity : 1 }, OBJ.speed, OBJ.callback );
                }

            }

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


        // responsive main links listener
        responsive_links_listener : function(){
            
            var Class = this;

            // Open Submenu
            $( '.lm-item.lm-has-submenu > a, .lm-item-dropdowns .lm-sub-item.lm-has-submenu > a, .lm-item-posts .lm-sub-item.lm-has-submenu > a', Class.livemenu ).on( Class.touch_start,function( event ){

                event.preventDefault();
                event.stopPropagation();

                var $self = $(this).parent();
                var $sub  = $self.find('> .lm-sub');

                // Handle Link, Follow or Prevent
                var link_prevented = Class.handle_link( $self, event, Class );

                // Open Submenu
                if( link_prevented ){

                    // Open This Sub
                    Class.handle_animation({
                        target      : $sub,
                        animation   : 'slide',
                        speed       : Class.config.jquery_time,
                        callback    : function(){

                            var sub     = $(this); 
                            var parent  = sub.parent();

                            parent.addClass('lm-submenu-active');
                            parent.find('> a .lm-responsive-links-arrow')
                                .removeClass('lm-arrow-down')
                                .addClass('lm-arrow-up');

                        },
                        reverse     : false, // Show
                    });
                }

            });

            // Close Submenu
            $( '.lm-responsive-links-arrow', Class.livemenu ).on( Class.touch_start,function( event ){

                if( $(this).is('.lm-arrow-down') ) return;

                event.preventDefault();
                event.stopPropagation();

                var $self   = $(this);
                var $parent = $self.parents('.era-icon').parent().parent();
                var $sub    = $parent.find('> .lm-sub');

                // Open This Sub
                Class.handle_animation({
                    target      : $sub,
                    animation   : 'slide',
                    speed       : Class.config.jquery_time,
                    callback    : function(){

                        var sub     = $(this); 
                        var parent  = sub.parent();

                        parent.removeClass('lm-submenu-active');
                        parent.find('> a .lm-responsive-links-arrow')
                            .removeClass('lm-arrow-up')
                            .addClass('lm-arrow-down');
                    
                    },
                    reverse     : true, // hide
                });

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