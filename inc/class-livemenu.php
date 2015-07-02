<?php


/**
 * Livemenu Manager Class
 * @since 1.0
**/
class livemenu_manager{


    /**
     * Start Here
     * @since 1.0
    **/
    function __construct(){

        // Detect Mode & Initialise
        $this->detect_mode();

    }// End Func


    /**
     * Detect If Mode is Admin, Customizer, Frontend
     * And initialize by mode
     * @since 1.0
    **/
    function detect_mode(){

        // Only Admin
        if( is_admin() ){
            $this->mode_admin();
        }

        // Frontend
        else {
            $this->mode_frontend();
        }

        // Required for both Admin and Customizer
        if( is_admin() || ( isset( $_GET['livemenu_live'] ) && $_GET['livemenu_live'] == 'on' ) ){
            $this->mode_admin_or_customizer();
        }

        // For All Modes

        /** Add Buttons to TOP BAR **/
        add_action( 'wp_before_admin_bar_render', array( $this, 'add_buttons_to_top_bar' ) );

        /** Register Nav Menus **/
        add_action('init', array( $this, 'register_nav_menus' ) );


    }// End Func


    /**
     * Admin Or Customizer
     * @since 1.0
    **/
    function mode_admin_or_customizer(){

        /** AJAX Actions **/
        new livemenu_ajax();

        /** Customizer Actions **/
        new livemenu_customizer();

        /** Add Icons/Wp Editor Boxes to the footer **/
        add_action( 'wp_footer', array( $this , 'add_boxes_to_footer' ), 300 );

    }// End Func


    /**
     * Mode Admin
     *
     * @since 1.0
    **/
    function mode_admin(){

        global $lm_panel_nav_items, $lm_panel_pages;

        /** Admin Panel **/

        // WP Left Nav Args
        $wp_args = array(
            'type'          => 'submenu',
            'parent_slug'   => 'themes.php',
            'page_title'    => __('Livemeu Panel','era_fw'),
            'menu_title'    => __('Livemenu','era_fw'),
            'capability'    => 'manage_options',
            'menu_slug'     => 'livemenu_panel',
        );

        // Panel Args
        $panel_args = array(
            'id'    => 'livemenu-panel',
            'class' => 'livemenu-panel',
            'title' => __('Livemenu Panel','era_fw') . ' ' . LM_VERSION,
            'footer_text' => 'By Vamospace.com'
        );

        new era_helpers_admin_panel( $wp_args, $panel_args, $lm_panel_nav_items, $lm_panel_pages, LM_BASE_URL.'inc/helpers/' );

        /** Load style & javascript to admin **/
        add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ), 3000 );

        /** Add FontAwesome Box, WP Editor Box to footer**/
        add_action( 'admin_head', array( $this , 'add_boxes_to_footer' ), 10 );

        /** Replace Admin Nav Walker  **/
        add_filter( 'wp_edit_nav_menu_walker', array( $this,'replace_admin_menu_walker') , 3000 );

        /** Add Image Sizes **/
        if( $livemenu_used_menus = get_option('livemenu_registered_menus') ){

            foreach( $livemenu_used_menus as $location => $menu_id ){
                $DBconfig = get_option( 'livemenu_' .$location. '_config' );
                era_images_manager::add_size( array($DBconfig['style']['lm-op-megapostsThumbWidth'],$DBconfig['style']['lm-op-megapostsThumbHeight']) );

            }

        }
        era_images_manager::listener();

        /** Allow WP Media Upload to Upload XML **/
        add_filter('upload_mimes', array( $this, 'custom_upload_xml' ) );

    }// End Func


    /**
     * Allow WP Media Upload to Upload XML
     * @since 1.0
    **/
    function custom_upload_xml($mimes) {
        return array_merge( $mimes, array('xml' => 'application/xml') );
    }// End Func


    /**
     * Mode Frontend
     * @since 1.0
    **/
    function mode_frontend(){

        /** Replace Frontend nav Walker  **/
        add_filter( 'wp_nav_menu_args', array( $this,'replace_frontend_walker'), 5000);

        /** Load Front CSS/JS **/
        add_action( 'wp_head', array( $this, 'load_frontend_assets' ), 7 );

        /** Load Google Fonts Used **/
        add_action( 'wp_head', array( $this, 'load_used_fonts' ), 100 );

    }// End Func


    /**
     * Admin : Load Assets on nav-menus.php only
     * @since 1.0
    **/
    function load_admin_assets(){

        // load only on nav-menus.php & widgets.php
        if( basename( $_SERVER['PHP_SELF'] ) != "nav-menus.php"
        && basename( $_SERVER['PHP_SELF'] ) != "themes.php"
        ){
            return false;
        }

        // Load ERA Helpers
        era_helpers_options::load_assets( LM_BASE_URL.'inc/helpers/' );

        // CSS
        wp_enqueue_style( 'livemenu-admin',      LM_BASE_URL.'assets/css/admin.css', LM_VERSION );
        wp_enqueue_style( 'livemenu-customizer', LM_BASE_URL.'assets/css/customizer.css', LM_VERSION );
        wp_enqueue_style( 'era-font-icons',      LM_BASE_URL.'assets/css/era-icons/style.css', LM_VERSION );

        // RTL
        if( is_rtl() ){
            wp_enqueue_style( 'livemenu-customizer-rtl', LM_BASE_URL.'assets/css/customizer.rtl.css', LM_VERSION );            wp_enqueue_style( 'livemenu-admin-rtl', LM_BASE_URL.'assets/css/admin.rtl.css', LM_VERSION );
        }

        // JS
        wp_enqueue_media();
        wp_enqueue_script( 'livemenu-admin', LM_BASE_URL.'assets/js/admin.js', array('era-helpers-options'), LM_VERSION, true );
        wp_enqueue_script( 'livemenu-boxes', LM_BASE_URL.'assets/js/boxes.js', array(), LM_VERSION, true );

        $params = array(
            'currentPage'       => basename( $_SERVER['PHP_SELF'] ),

            'enable_text'       => __('Enable Livemenu', 'era_fw'),
            'disable_text'      => __('Disable Livemenu', 'era_fw'),
            'select_location'   => __('Please select the theme location and save the menu before enabling livemenu', 'era_fw'),

            'ajaxurl'               => admin_url('admin-ajax.php'),
            'loadimg'               => LM_BASE_URL . 'assets/img/1.gif',
            'nonce'                 => wp_create_nonce('lm_nonce_string'),
            'editItemUpdateText'    => __('Item Options Saved...','era_fw'),
        );
        wp_localize_script( 'livemenu-admin', 'lm_obj', $params );

    }// End Func


    /**
     * Register New WP menus
     * @since 1.0
    **/
    function register_nav_menus(){

        if( $counter = get_option('livemenu_menus_added_count') ){

            for ( $i=1; $i <= $counter ; $i++) {

                register_nav_menu( 'livemenu'.$i, 'Livemenu ( '.$i. ' )' );

            }

        }

    }// End Func


    /**
     * Add wp_editor box, fontawesome box, to the footer
     * @since 1.0
    **/
    function add_boxes_to_footer(){

        global $era_icons;

        $demo_site = ( get_option('livemenu_enable_demo') ) ? get_option('livemenu_enable_demo') : 'off';

        // Customization Mode or Admin
        if(
               ( isset( $_GET['livemenu_live'] ) && $_GET['livemenu_live'] == 'on' && $demo_site == 'on' )
            || ( isset( $_GET['livemenu_live'] ) && $_GET['livemenu_live'] == 'on' && current_user_can('manage_options') )
            || is_admin()
        ){


            if( is_admin() ){
                // load only on nav-menus.php & widgets.php
                if( basename( $_SERVER['PHP_SELF'] ) != "nav-menus.php"
                && basename( $_SERVER['PHP_SELF'] ) != "themes.php"
                ){
                    return false;
                }
            }

            // Icons Box
            $box_config                   = array();
            $box_config['atts']           = array();
            $box_config['title']   = __('Select Icon','era_fw');

            // Header Buttons
            $box_config['header_output']  = era_helpers_options::button(array(
               'title'  => __('Close','era_fw'),
               'class'  => 'lm-btn lm-btn-hide lm-btn-red'
            ));

            // Content
            $box_config['content_output'] = '';
            $box_config['content_output'] .= '<section class="lm-box-icons-container" >';
            foreach( $era_icons as $icon ){
                $box_config['content_output'] .= '<span class="lm-box-icons-icon" data-icon="'.$icon.'"><span class="era-icon '.$icon.'"></span></span>';
            }
            $box_config['content_output'] .= '</section>';

            // Display the box
            $box_config['class']    = 'lm-box-icons';
            $box_config['type']     = 'custom';
            $box_config['width']    = '450px';
            $box_config['height']   = '450px';
            $box_config['top']      = '15%';
            $box_config['left']     = '20%';
            echo era_helpers_boxes::return_box( $box_config );


            // WP Editor Box
            $box_config            = array();
            $box_config['atts']    = array();
            $box_config['title']   = __('WordPress Editor','era_fw');

            // Header Buttons
            $box_config['header_output']   = era_helpers_options::button(array(
               'title'  => __('Close','era_fw'),
               'class'  => 'lm-btn lm-btn-hide lm-btn-red'
            ));
            $box_config['header_output']  .= era_helpers_options::button(array(
               'title'  => __('Add','era_fw'),
               'class'  => 'lm-btn lm-btn-green lm-btn-wpeditor-get-text'
            ));

            // Content
            ob_start();

                if( is_admin() ){

                    wp_editor( '', 'lm_wp_editor' );
                    $box_config['content_output'] = ob_get_contents();

                }
                else{

                    wp_editor( '', 'lm_wp_editor' );
                    \_WP_Editors::enqueue_scripts();
                    print_footer_scripts();
                    \_WP_Editors::editor_js();
                    $box_config['content_output'] = ob_get_contents();

                }

            ob_end_clean();

            // Display the box
            $box_config['class']    = 'lm-box-wpeditor';
            echo era_helpers_boxes::return_box( $box_config );


            /***
             ** Loading Box
            ***/
            $box_config                   = array();
            $box_config['atts']           = array();
            $box_config['title']   = __('Saving...','era_fw');

            // Header Buttons
            $box_config['header_output']  = '';

            // Content
            $box_config['content_output'] = '';

            // Display the box
            $box_config['class']    = 'lm-box-ajaxloading';
            $box_config['type']     = 'custom';
            $box_config['width']    = 'auto';
            $box_config['height']   = '57px';
            $box_config['right']    = '2%';
            $box_config['bottom']   = '2%';
            echo era_helpers_boxes::return_box( $box_config );

        }

    }// End Func


    /**
     * Add Buttons to Top Bar
     * @since 1.0
    **/
    function add_buttons_to_top_bar() {

        global $wp_admin_bar;

        $wp_admin_bar->add_menu( array(
            'id'    => 'lm-top-bar-link',
            'title' => __('Livemenu Live Builder','era_fw'),
            'href'  => $this->get_live_builder_link( true ),
        ));

        if( ! isset( $_GET['livemenu_live'] ) ){
            $wp_admin_bar->add_menu( array(
                'parent'  => false, // use 'false' for a root menu, or pass the ID of the parent menu
                'id'      => 'lm-top-bar-customizer-link', // link ID, defaults to a sanitized title value
                'title'   => __('Live Builder','era_fw'), // link title
                'href'    =>  $this->get_live_builder_link( true ), // name of file
                'meta'    => false, // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
                'parent'  => 'lm-top-bar-link',
            ));
        }
        else{
            $wp_admin_bar->add_menu( array(
                'parent'  => false, // use 'false' for a root menu, or pass the ID of the parent menu
                'id'      => 'lm-top-bar-customizer-link', // link ID, defaults to a sanitized title value
                'title'   => __('Disable Live Builder','era_fw'), // link title
                'href'    =>  $this->get_live_builder_link(), // name of file
                'meta'    => false, // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
                'parent'  => 'lm-top-bar-link',
            ));
        }

        $wp_admin_bar->add_menu( array(
            'parent'  => false, // use 'false' for a root menu, or pass the ID of the parent menu
            'id'      => 'lm-top-bar-structure-link', // link ID, defaults to a sanitized title value
            'title'   => __('Structure Mode','era_fw'), // link title
            'href'    => admin_url('nav-menus.php') , // name of file
            'meta'    => false, // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
            'parent'  => 'lm-top-bar-link',
        ));

        $wp_admin_bar->add_menu( array(
            'parent'  => false, // use 'false' for a root menu, or pass the ID of the parent menu
            'id'      => 'lm-top-bar-panel-link', // link ID, defaults to a sanitized title value
            'title'   => __('Admin Panel','era_fw'), // link title
            'href'    => admin_url('themes.php') . '?page=livemenu_panel', // name of file
            'meta'    => false, // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
            'parent'  => 'lm-top-bar-link',
        ));

        $wp_admin_bar->add_menu( array(
            'parent'  => false, // use 'false' for a root menu, or pass the ID of the parent menu
            'id'      => 'lm-top-bar-documentation-link', // link ID, defaults to a sanitized title value
            'title'   => __('Documentation','era_fw'), // link title
            'href'    => 'http://vamospace.com/docs/livemenu' , // name of file
            'meta'    => false, // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
            'parent'  => 'lm-top-bar-link',
        ));

    }// End Func


    /**
     * Get Current Full Url ( http://stackoverflow.com/questions/6768793/get-the-full-url-in-php )
     * @since 2.0
    **/
    function get_live_builder_link( $live = false ){

        $s      = $_SERVER;
        $ssl    = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp     = strtolower($s['SERVER_PROTOCOL']);

        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');

        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;

        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        $url  = $protocol . '://' . $host . $s['REQUEST_URI'];

        if( is_admin() ){
            $url = get_bloginfo('url');
        }

        if( $live ){

            if( strpos( $url , '?' ) ){
                $url = $url . '&livemenu_live=on';
            }
            else{
                $url = $url . '?livemenu_live=on';
            }

        }
        else{
            $url = str_replace('&livemenu_live=on', '', $url );
            $url = str_replace('?livemenu_live=on', '', $url );
        }


        return $url;

    }// End Func


    /**
    * Load css files to Front
    * @since 1.0
    **/
    function load_frontend_assets(){

        // CSS
        wp_enqueue_style( 'livemenu-frontend', LM_BASE_URL.'assets/css/frontend.min.css', LM_VERSION );
        wp_enqueue_style( 'era-font-icons', LM_BASE_URL.'assets/css/era-icons/style.css', LM_VERSION );

        // RTL
        if( is_rtl() ){
            wp_enqueue_style( 'livemenu-frontend-rtl', LM_BASE_URL.'assets/css/frontend.rtl.css', LM_VERSION );
        }

        $demo_site = ( get_option('livemenu_enable_demo') ) ? get_option('livemenu_enable_demo') : 'off';

        // Customization Mode
        if(
               ( isset( $_GET['livemenu_live'] ) && $_GET['livemenu_live'] == 'on' && $demo_site == 'on' )
            || ( isset( $_GET['livemenu_live'] ) && $_GET['livemenu_live'] == 'on' && current_user_can('manage_options') )
        ){
            // Load ERA Helpers
            era_helpers_options::load_assets( LM_BASE_URL.'inc/helpers/' );

            // CSS
            wp_enqueue_style( 'livemenu-customizer', LM_BASE_URL.'assets/css/customizer.css', LM_VERSION );

            // RTL
            if( is_rtl() ){
                wp_enqueue_style( 'livemenu-customizer-rtl', LM_BASE_URL.'assets/css/customizer.rtl.css', LM_VERSION );
            }

            // JS
            $parameters = array(
                'ajaxurl'               => admin_url('admin-ajax.php'),
                'loadimg'               => LM_BASE_URL . 'assets/img/1.gif',
                'nonce'                 => wp_create_nonce('lm_nonce_string'),
                'editItemUpdateText'    => __('Item Options Saved...','era_fw'),
            );

            wp_enqueue_script(  'livemenu-customizer'   , LM_BASE_URL.'assets/js/customizer.js', array('jquery','jquery-ui-core', 'era-helpers-options'), LM_VERSION, true );
            wp_enqueue_script(  'livemenu-boxes'        , LM_BASE_URL.'assets/js/boxes.js', array('jquery','jquery-ui-core','jquery-ui-draggable', 'jquery-ui-sortable', 'era-helpers-options'), LM_VERSION, true );
            wp_enqueue_script(  'livemenu-live-style'   , LM_BASE_URL.'assets/js/live-style.js', array('jquery', 'era-helpers-options'), LM_VERSION, true );
            wp_localize_script( 'livemenu-boxes'        , 'lm_obj', $parameters );

        }

        // Frontend
        else{
            wp_enqueue_script( 'livemenu', LM_BASE_URL.'assets/js/livemenu.min.js', array('jquery'), LM_VERSION, true );
        }

        // Get Cache Version
        $version = get_option('livemenu_cache_version');

        // Uploads "Folder"
        $upload_dir = wp_upload_dir();
        $upload_url = $upload_dir['baseurl'].'/livemenu/';
        $upload_dir = $upload_dir['basedir'].'/livemenu/';

        // SSL Support
        if( is_ssl() ){
            $upload_url = str_replace('http://', 'https://', $upload_url );
        }

        // Load Custom CSS/JS for each menu
        if( $livemenu_used_menus = get_option('livemenu_registered_menus') ){

            foreach( $livemenu_used_menus as $location => $menu_id ){

                // CSS
                wp_enqueue_style( 'livemenu-frontend-menu-'.$location, $upload_url.'css/'.$location.'.css', $version );

                if( file_exists( $upload_dir.'css/'.$location.'-custom.css' ) )
                    wp_enqueue_style( 'livemenu-frontend-menu-custom-'.$location, $upload_url.'css/'.$location.'-custom.css', $version );

            }

        }// End If

        // Global Custom CSS
        if( file_exists( $upload_dir.'css/livemenu-global-css.css' ) ){
            wp_enqueue_style( 'livemenu-frontend-global', $upload_url.'css/livemenu-global-css.css', $version );
        }

        // JS
        wp_enqueue_script( 'livemenu-frontend-global', $upload_url.'js/livemenu_global_obj.js', array(), $version, true );
        wp_enqueue_script( 'livemenu-retina', LM_BASE_URL.'assets/js/retina.min.js', array(), LM_VERSION, true );

    }// End Func


    /**
    * Load Google Fonts Used
    * @since 1.0
    **/
    function load_used_fonts(){

        if( ! $used_fonts = get_option('livemenu_fonts_used') ) return;

        $used_fonts = implode( '|', $used_fonts );

        wp_enqueue_style( "livemenu-fonts-used", '//fonts.googleapis.com/css?family='.$used_fonts.'&subset=latin%2Clatin-ext' , array(), null );

    }// End Func


    /**
    * Replace frontend nav walker
    * @since 1.0
    **/
    function replace_frontend_walker( $args ){

        $demo_site = ( get_option('livemenu_enable_demo') ) ? get_option('livemenu_enable_demo') : 'off';

        // Live Customization Walker
        if(
               ( isset( $_GET['livemenu_live'] ) && $_GET['livemenu_live'] == 'on' && $demo_site == 'on' )
            || ( isset( $_GET['livemenu_live'] ) && $_GET['livemenu_live'] == 'on' && current_user_can('manage_options') )
        ){

            if( $livemenu_used_menus = get_option('livemenu_registered_menus') ){

                foreach( $livemenu_used_menus as $location => $menu_id ){

                    // check if this location exists in the registered menus locations
                    if( $args['theme_location'] == $location ){

                        $DBconfig = get_option( 'livemenu_'.$location.'_config' );

                        // Responsive Logo
                        $resp_logo_output = '';
                        if( ! is_null( $DBconfig['settings']['lm-op-layoutAndLogo'] )
                            && $DBconfig['settings']['lm-op-layoutAndLogo'] != 'no-logo'
                            && $DBconfig['settings']['lm-op-logo'] != ''
                        ){
                            $resp_logo_output = '
                            <a class="lm-logo lm-'.$DBconfig['settings']['lm-op-layoutAndLogo'].'"
                                href="'.get_bloginfo('url').'">
                                <img src="'.$DBconfig['settings']['lm-op-logo'].'" alt="" >
                            </a>';
                        }

                        // Responsive Constrols
                        $responsive_output = '';
                        if( $DBconfig['settings']['lm-op-responsive'] == 'on' ){
                            $responsive_output .= '
                            <div class="livemenu-responsive-controls">
                                '.$resp_logo_output.'
                                <span class="era-icon era-icon-menu"></span>
                            </div>
                            ';
                        }

                        // Controls
                        $controls_html  = '<span class="lm-open-menu-controls era-icon era-icon-cog"></span>';
                        $controls_html .= '<div class="lm-controls" data-type="menu">';
                        $controls_html .= '  <a data-menu_id="'.$menu_id.'" data-menu_class="'.$location.'" data-item_id="0" data-page="menu_add_items">'
                                                .__('Add Item','era_fw')
                                            .'</a>';
                        $controls_html .= '  <a data-menu_id="'.$menu_id.'" data-menu_class="'.$location.'" data-page="menu_settings">'
                                                .__('Settings','era_fw')
                                            .'</a>';
                        $controls_html .= '  <a data-menu_id="'.$menu_id.'" data-menu_class="'.$location.'" data-page="menu_style">'
                                                .__('Style','era_fw')
                                            .'</a>';
                        $controls_html .= '  <span class="era-icon era-icon-chevron-thin-down"></span>';
                        $controls_html .= '</div>';

                        // RTL
                        $rtl = '';
                        if( is_rtl() ){
                            $rtl = 'livemenu-rtl';
                        }

                        $menu_config = array( 'menu_id' => $menu_id, 'menu_class' => $location );

                        $args['walker']         = new livemenu_customizer_walker( $menu_config, $DBconfig, $location );
                        $args['container_id']   = 'livemenu-id-'.$location;
                        $args['menu_class']     = 'livemenu';
                        $args['menu_id']        = '';
                        $args['fallback_cb']    = false;
                        $args['container_class']= $location.' livemenu-wrap '.$rtl;
                        $args['items_wrap']     = $responsive_output.'<ul id="'.$menu_id.'" class="livemenu">%3$s</ul>' . $controls_html;
                        $args['depth']          = 4;
                        $args['container']      = 'div';

                        // Thank you wordpress for not showing the menu if no items added !
                        // At least give us the option :()
                        // Detect if this menu has items
                        $menu_obj = wp_get_nav_menu_object( $menu_id );
                        $menu_items = wp_get_nav_menu_items( $menu_id );

                        if( count($menu_items) == 0 ){
                            echo '
                            <div id="livemenu-id-'.$location.'" class="'.$location.' livemenu-wrap '.$rtl.'">
                                <span class="lm-open-menu-controls era-icon era-icon-cog"></span>
                                '.$responsive_output.'<ul id="menu-'.$menu_obj->slug.'" class="livemenu"></ul>'.$controls_html.'
                            </div>
                            ';
                        }

                    }

                }
            }

        }

        // Frontend Walker
        else{

            if( $livemenu_used_menus = get_option('livemenu_registered_menus') ){

                foreach( $livemenu_used_menus as $location => $menu_id ){

                    // check if this location exists in the registered menus locations
                    if( $args['theme_location'] == $location ){

                        $DBconfig = get_option( 'livemenu_'.$location.'_config' );

                        // Responsive Logo
                        $resp_logo_output = '';
                        if( ! is_null( $DBconfig['settings']['lm-op-layoutAndLogo'] )
                            && $DBconfig['settings']['lm-op-layoutAndLogo'] != 'no-logo'
                            && $DBconfig['settings']['lm-op-logo'] != ''
                        ){
                            $resp_logo_output = '
                            <a class="lm-logo lm-'.$DBconfig['settings']['lm-op-layoutAndLogo'].'"
                                href="'.get_bloginfo('url').'">
                                <img src="'.$DBconfig['settings']['lm-op-logo'].'" alt="" >
                            </a>';
                        }

                        // Responsive Constrols
                        $responsive_output = '';
                        if( $DBconfig['settings']['lm-op-responsive'] == 'on' ){
                            $responsive_output .= '
                            <div class="livemenu-responsive-controls">
                                '.$resp_logo_output.'
                                <span class="era-icon era-icon-menu"></span>
                            </div>
                            ';
                        }

                        // RTL
                        $rtl = '';
                        if( is_rtl() ){
                            $rtl = 'livemenu-rtl';
                        }

                        $args['walker']         = new livemenu_frontend_walker( $DBconfig, $location );
                        $args['container_id']   = 'livemenu-id-'.$location;
                        $args['menu_class']     = 'livemenu';
                        $args['menu_id']        = '';
                        $args['fallback_cb']    = 'lm_cutomizer_walker_fb';
                        $args['container_class']= $location.' livemenu-wrap '.$rtl;
                        $args['items_wrap']     = $responsive_output.'<ul id="%1$s" class="livemenu">%3$s</ul>';
                        $args['depth']          = 4;
                        $args['container']      = 'div';

                    }
                }
            }

        }

        return $args;

    }// End If


    /**
    * Replace Admin menu walker
    * @since 1.0
    **/
    function replace_admin_menu_walker( $name ){
        return 'livemenu_backend_walker';
    }// End Func


    /**
    * Plugin Activation Hook
    **/
    static function plugin_activation_hook(){

        // Create "livemenu" Folder inside Uploads Folder
        $upload_dir =   wp_upload_dir();
        $upload_dir =   $upload_dir['basedir'].'/livemenu/';
        if( ! file_exists($upload_dir) ){
            // Create Folders
            @wp_mkdir_p( $upload_dir );
            @wp_mkdir_p( $upload_dir. '/css' );
            @wp_mkdir_p( $upload_dir. '/js' );
        }

        // Detect Skins Names & Save them into the DB
        $scandir    = @scandir(  __dir__ . '/skins/' );
        $all_skins  = array();
        foreach ( $scandir as $key => $value){
            if( $value != "." && $value != ".." ){
                $value = str_replace('.php', '', $value );
                $all_skins[$value] = $value;
            }
        }
        update_option('livemenu_skins',$all_skins);

        // Create demo skins
        do_action( 'livemenu_create_preview_assets' );

        // Download Google Fonts
        era_fonts::download_google_fonts();

    }// End Func


}// End Class lm_manager