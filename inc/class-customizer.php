<?php

/**
 * Livemenu Customizer Class
 * @since 1.0
**/
class livemenu_customizer{


   /**
    * Init
    * @since 1.0
   **/
   function __construct(){

      /* Customizer */
      add_action( 'wp_ajax_lm_customizer_box',        array( $this, 'get_box' ) );
      add_action( 'wp_ajax_nopriv_lm_customizer_box', array( $this, 'get_box' ) );

      /* Save & Create Files */
      add_action( 'wp_ajax_lm_save_config',         array( $this, 'save_config' ) );
      add_action( 'wp_ajax_nopriv_lm_save_config',  array( $this, 'save_config' ) );

      /* Add Menu Item */
      add_action( 'wp_ajax_lm_customizer_add_menu_item',         array( $this, 'save_new_menu_item' ) );
      add_action( 'wp_ajax_nopriv_lm_customizer_add_menu_item',  array( $this, 'save_new_menu_item' ) );

      /* Update Item Options */
      add_action( 'wp_ajax_lm_update_item_options'  ,        array( $this, 'update_item_option') );
      add_action( 'wp_ajax_nopriv_lm_update_item_options'  , array( $this, 'update_item_option') );

      /* Remove Item */
      add_action( 'wp_ajax_lm_remove_item'  ,        array( $this, 'remove_item') );
      add_action( 'wp_ajax_nopriv_lm_remove_item'  , array( $this, 'remove_item') );

      /* Re-order Items */
      add_action( 'wp_ajax_lm_reorder_items'  ,        array( $this, 'reorder_items') );
      add_action( 'wp_ajax_nopriv_lm_reorder_items'  , array( $this, 'reorder_items') );

      /* Reset Items Custom Style */
      add_action( 'wp_ajax_lm_reset_custom_style',          array( $this, 'reset_custom_style' ) );
      add_action( 'wp_ajax_nopriv_lm_reset_custom_style',   array( $this, 'reset_custom_style' ) );

      /** Create Assets Files **/
      add_action( 'livemenu_create_preview_assets',      array( $this, 'create_preview_assets' ), 10 );
      add_action( 'livemenu_create_assets_after_import', array( $this, 'create_assets_after_import'), 10 );
      add_action( 'livemenu_create_css_file',            array('livemenu_create_assets','css'), 10, 3 );
      add_action( 'livemenu_create_javascript_file',     array('livemenu_create_assets','javascript'), 10, 2 );
      add_action( 'livemenu_create_custom_css_file',     array('livemenu_create_assets','custom_items_css'), 10 );

      /** Update Cache Version **/
      add_action( 'livemenu_update_cache',   array( $this,'update_cache_version'), 10 );

      /** Prepare Fonts Action **/
      add_action( 'livemenu_prepare_fonts',  array( $this, 'prepare_fonts' ), 10 );

   }// End Func


   /**
    * Call Methods & Add Args
    * @since 1.0
   **/
   function get_box(){

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      call_user_func_array( array( $this, 'box_'.$_POST['page'] ) , array( $_POST ) );

      die('');
   }// End Func


   /**
    * Box : Remove Item
    * @since 2.0
   **/
   function box_item_remove( $data_post ){

      // Box Config
      $box_config                   = array();
      $box_config['atts']           = array(
         'menu_id'      => $data_post['menu_id'],
         'menu_class'   => $data_post['menu_class'],
         'box_id'       => 'item_remove_id',
         'item_id'      => $data_post['item_id'],
         'parent_id'    => $data_post['item_id'],
      );
      $box_config['title']   = __('Are you sure ?','era_fw');

      $box_config['header_output']  = era_helpers_options::button(array(
         'title'  => __('Close','era_fw'),
         'class'  => 'lm-btn lm-btn-red lm-btn-cancel'
      ));
      $box_config['header_output'] .= era_helpers_options::button(array(
         'title'  => __('Yes','era_fw'),
         'class'  => 'lm-btn lm-btn-green lm-btn-item-remove'
      ));

      $box_config['content_output'] = '';

      // Display the box
      $box_config['class']    = 'lm-box-item-remove';
      $box_config['type']     = 'custom';
      $box_config['width']    = '450px';
      $box_config['height']   = '56px';
      $box_config['top']      = '15%';
      $box_config['left']     = '2%';
      echo era_helpers_boxes::return_box( $box_config );

   }// End Func


   /**
    * Menu Style Box
   **/
   function box_menu_style( $data_post ){

      // Get the Data from Database
      $DBconfig = get_option( 'livemenu_'.$data_post['menu_class'].'_config' );
      $DBconfig = $DBconfig['style'];

      // Box Config
      $box_config                   = array();
      $box_config['atts']           = array(
         'menu_class'   => $data_post['menu_class'],
         'box_id'       => 'menu_style',
      );
      $box_config['title']   = $data_post['menu_class'];
      $box_config['header_output']  = '';
      $box_config['content_output'] = '';

      // inlcude the options
      require('config/menu-style.php');

      // Display the box
      $box_config['class']    = 'lm-box-menu-style';
      $box_config['type']     = 'custom';
      $box_config['width']    = '450px';
      $box_config['height']   = '450px';
      $box_config['top']      = '15%';
      $box_config['left']     = '2%';
      echo era_helpers_boxes::return_box( $box_config );

   }// End Func


   /**
    * Menu Settings Box
   **/
   function box_menu_settings( $data_post ){

      // Get the Data from Database
      $DBconfig = get_option( 'livemenu_'.$data_post['menu_class'].'_config' );
      $DBconfig = $DBconfig['settings'];

      // Box Config
      $box_config          = array();
      $box_config['atts']  = array(
         'menu_class'   => $data_post['menu_class'],
         'box_id'       => 'menu_settings',
      );
      $box_config['title']   = $data_post['menu_class'];
      $box_config['header_output']  = '';
      $box_config['content_output'] = '';

      // include the options
      require('config/menu-settings.php');

      // Display the box
      $box_config['class']    = 'lm-box-menu-settings';
      $box_config['type']     = 'custom';
      $box_config['width']    = '450px';
      $box_config['height']   = '450px';
      $box_config['top']      = '15%';
      $box_config['left']     = '2%';
      echo era_helpers_boxes::return_box( $box_config );

   }// End Func


   /**
    * Items Style Box
   **/
   function box_item_style( $data_post ){

      // Get the Data from Database
      $DBconfig = get_option( 'livemenu_'.$data_post['menu_class'].'_config' );
      $DBconfig = @$DBconfig['item_style'][$data_post['item_id']];

      // Box Config
      $box_config                   = array();
      $box_config['atts']           = array(
         'menu_class'   => $data_post['menu_class'],
         'box_id'       => 'item_style',
         'item_id'      => $data_post['item_id'],
      );
      $box_config['title']          = '#' . $data_post['item_id'];
      $box_config['header_output']  = '';
      $box_config['content_output'] = '';

      // include the options
      require('config/menu-item-style.php');

      // Display the box
      $box_config['class']    = 'lm-box-menu-item-style';
      $box_config['type']     = 'custom';
      $box_config['width']    = '450px';
      $box_config['height']   = '450px';
      $box_config['top']      = '15%';
      $box_config['left']     = '2%';
      echo era_helpers_boxes::return_box( $box_config );

   }// End Func


   /**
    * Save Customizer Config/Settings
    * @since 1.0
   **/
   function save_config(){

      // check if admin
      if( ! current_user_can('manage_options') ){
         _e('Not Allowed','era_fw');
         die('');
      }

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // Prepare DATA
      $options          = explode('_-_eraAND-_-', $_POST['data_string'] );
      $new_data         = array();
      $new_google_fonts = array();

      foreach( $options as $op ){
         $op               = explode('_-_eraEQAUL-_-', $op);
         $op[1]            = urldecode( $op[1] );
         $op[1]            = stripslashes( $op[1] );
         $new_data[$op[0]] = $op[1];

         // Add Google Font Used
         if( strpos($op[0], 'fontFamily') != false ){
            $new_google_fonts[] = $op[1];
         }
      }// End Foreach

      // Get Old Config
      if( ! $old_config = get_option( 'livemenu_'.$_POST['menu_class'].'_config' ) ){
         $old_config = array();
      }

      // Box : menu_settings
      if( $_POST['box_id'] == 'menu_settings' ){
         $old_config['settings'] = $new_data;
         update_option( 'livemenu_'.$_POST['menu_class'].'_config', $old_config );

         // Create Style File
         do_action('livemenu_create_javascript_file');
      }

      // Box : menu_style
      else if( $_POST['box_id'] == 'menu_style' ){

         $old_config['style'] = $new_data;
         update_option( 'livemenu_'.$_POST['menu_class'].'_config', $old_config );

         // Google Fonts
         do_action('livemenu_prepare_fonts',$_POST['menu_class']);

         // check if skin or custom
         if( $old_config['style']['lm-op-skinOrCustom'] == 'skin' ){
            require_once('skins/'.$old_config['style']['lm-op-skin'].'.php');
            $new_data = $lm_skin_config;
         }

         // Create Style File
         do_action(
            'livemenu_create_css_file',
            $_POST['menu_class'],
            $new_data,
            $_POST['menu_class'].'.css'
         );

      }

      // Box : item_style
      else if( $_POST['box_id'] == 'item_style' ){

         $old_config['item_style'][ $_POST['item_id'] ] = $new_data;
         update_option( 'livemenu_'.$_POST['menu_class'].'_config', $old_config );

         // Create Style File
         do_action('livemenu_create_custom_css_file', $_POST['menu_class'] );

      }

      echo __('Done...','era_fw');

      die('');

   }// End Func


   /**
    * Reset Custom Items Style
    * @since 1.0
   **/
   function reset_custom_style(){

      // check if admin
      if( ! current_user_can('manage_options') ){
         _e('Not Allowed','era_fw');
         die('');
      }

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      //
      if( ! $old_config = get_option( 'livemenu_'.$_POST['menu_class'].'_config' ) ){
         die('');
      }

      // Reset
      unset( $old_config['item_style'][ $_POST['item_id'] ] );
      update_option( 'livemenu_'.$_POST['menu_class'].'_config', $old_config );

      // Create Style File
      do_action('livemenu_create_custom_css_file', $_POST['menu_class'] );

      echo __('Done...','era_fw');
      die('');

   }// End Func


   /**
    * Update Cache Version
    * @since 1.0
   **/
   function update_cache_version(){
      if( $version = get_option('livemenu_cache_version') ){
         update_option('livemenu_cache_version',($version+1));
      }
      else
      {
         update_option('livemenu_cache_version',1);
      }
   }// End Func


   /**
    * Prepare Used Google Font
    * to prevent calling unused fonts & duplucated fonts
    * @since 1.0
   **/
   function prepare_fonts( $location ){

      $DBConfig   = get_option( 'livemenu_'.$location.'_config' );
      $used_fonts = array();

      // get used fonts
      foreach ( $DBConfig['style'] as $name => $val ){
         if( strpos( $name, 'fontFamily' ) != false ){
            $used_fonts[] = $val;
         }
      }

      // Save Current Menu Fonts
      update_option( 'livemenu_'.$location.'_google_fonts_used', $used_fonts );

      // Filter All the Menu Fonts & Get a unique collection
      $livemenu_used_menus = get_option('livemenu_registered_menus');
      $locations           = array();
      $all_used_fonts      = array();

      foreach( $livemenu_used_menus as $location => $menu_id ){

         if( $tmp_get_fonts = get_option( 'livemenu_'.$location.'_google_fonts_used' ) ){
            $all_used_fonts = array_merge( $all_used_fonts, $tmp_get_fonts );
         }

      }

      // remove duplicated fonts
      $all_used_fonts = array_unique( $all_used_fonts );

      // return only google fonts
      $all_used_fonts = era_fonts::filter_google_fonts( $all_used_fonts );

      update_option('livemenu_fonts_used',$all_used_fonts);

   }// End Func


   /**
    * Create Default Assets Files & Save Default Settings To DB
    * @since 1.0
   **/
   function create_preview_assets(){

      // Get All Livemenu Skins
      $all_skins = get_option('livemenu_skins');

      // Get Registered Menus
      $livemenu_used_locations = ( get_option('livemenu_registered_menus') != false ) ? get_option('livemenu_registered_menus') : array();

      // Foreach Menu
      foreach( $livemenu_used_locations as $location => $menu_id ){

         // Create Preview Skins
         $default_css_settings = array();
         foreach( $all_skins as $skin_name => $skin_val ){

            require('skins/'.$skin_name.'.php');

            if( $skin_name == LM_DEFAULT_SKIN ){
               $default_css_settings = $lm_skin_config;
            }

            do_action(
               'livemenu_create_css_file',            // Action
               $location,                             // ID
               $lm_skin_config,                       // this is the skin config
               $location.'-prev-'.$skin_name.'.css'   // Slug
            );

         }

         // Current Config
         $current_config = ( get_option( 'livemenu_'.$location.'_config' ) == false ) ? array() : get_option( 'livemenu_'.$location.'_config' ) ;

         // if no settings saved
         if( ! isset( $current_config['settings'] ) ){

            require('config/js_default.php');
            $current_config['settings'] = $lm_default_settings;

            update_option( 'livemenu_'.$location.'_config', $current_config );
         }
         do_action( 'livemenu_create_javascript_file' );


         // if no style saved
         if( ! isset( $current_config['style'] ) ){

            $current_config['style'] = $default_css_settings;

            // Save To DB
            update_option( 'livemenu_'.$location.'_config', $current_config );
         }
         // Create Default CSS File [ default skin : LM_DEFAULT_SKIN ]
         do_action(
            'livemenu_create_css_file',
            $location,
            $default_css_settings,
            $location.'.css' // slug
         );


      }// End Foreach

   }// End Func


   /**
    * Create Assets Files after Importing
    * @since 1.0
   **/
   function create_assets_after_import( $all_config ){

      // create css/js files for each location
      foreach( $all_config['config'] as $location => $config ){

         // Create JS Settings File
         do_action( 'livemenu_create_javascript_file' );

         // Create Style File
         do_action(
            'livemenu_create_css_file',
            $location,
            $config['style'],
            $location.'.css'
         );

         // Create Style File, for menu items custom style
         do_action('livemenu_create_custom_css_file', $location );

      }

   }// End Func


   /**
    * Add Menu Item
    * @since 2.0
   **/
   function box_menu_add_items( $data_post ){

      // Box Config
      $box_config                   = array();
      $box_config['atts']           = array(
         'menu_id'      => $data_post['menu_id'],
         'menu_class'   => $data_post['menu_class'],
         'box_id'       => 'menu_add_items',
         'parent_id'    => $data_post['item_id'],
      );
      $box_config['title']   = $data_post['menu_class'];
      $box_config['header_output']  = '';
      $box_config['content_output'] = '';

      // inlcude the options
      require('config/menu-item-add.php');

      // Display the box
      $box_config['type']     = 'custom';
      $box_config['width']    = '450px';
      $box_config['height']   = '450px';
      $box_config['top']      = '15%';
      $box_config['left']     = '2%';
      echo era_helpers_boxes::return_box( $box_config );

   }// End Func


   /**
    * Save New Menu Item
    * @since 2.0
   **/
   function save_new_menu_item(){

      // check if admin
      if( ! current_user_can('manage_options') ){
         echo __('Not Allowed','era_fw') . ' #lmerror#';
         die('');
      }

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // Create post object
      $my_post = array(
         'post_author'     => get_current_user_id(),
         'post_status'     => 'publish',
         'comment_status'  => 'open',
         'ping_status'     => 'open',
         'post_name'       => $_POST['id'],
         'post_title'      => $_POST['title'],
         'post_parent'     => 0 ,
         'guid'            => '',
         'menu_order'      => $_POST['order'],
         'post_type'       => 'nav_menu_item',
         'comment_count'   => 0,
      );

      // Insert the post into the database
      $post_id = wp_insert_post( $my_post );

      // Update post
      $my_post = array(
         'ID'           => $post_id,
         'post_name'    => $post_id,
         'guid'         => get_bloginfo('url').'/?p='.$post_id
      );

      // Update the post into the database
      wp_update_post( $my_post );

      // Insert Post Meta Into DB
      $metas = array(
         '_menu_item_type'             => $_POST['type'],
         '_menu_item_menu_item_parent' => $_POST['parent_id'],
         '_menu_item_object_id'        => $_POST['id'],
         '_menu_item_object'           => $_POST['object'],
         '_menu_item_target'           => '',
         '_menu_item_classes'          => array( 0 => '' ),
         '_menu_item_xfn'              => '',
         '_menu_item_url'              => $_POST['url'],
      );

      foreach( $metas as $meta_key => $meta_value ){
         add_post_meta($post_id, $meta_key, $meta_value );
      }

      // Relates an object (post, link etc) to a term
      $menu_id = wp_get_nav_menu_object( $_POST['menu_id'] );
      wp_set_object_terms( $post_id, $menu_id->term_id, 'nav_menu' );

      // Display Menu
      $menu_config = array( 'menu_id'=>$_POST['menu_id'] , 'menu_class'=>$_POST['menu_class'] );
      echo livemenu_customizer::get_menu_output( $menu_config );

      die('');

   }// End Func


   /**
    * Edit Item Settings
    * @since 2.0
   **/
   function box_menu_edit_item( $data_post ){

      // Get Item
      $item          = get_post( $data_post['item_id'] );
      $item_meta     = get_post_meta( $data_post['item_id'] );
      $item_depth    = $data_post['item_depth'];
      $item_type     = ( isset( $data_post['item_type'] ) ? $data_post['item_type'] : 'dropdowns' );

      // Item Name
      if( $item_meta['_menu_item_type'][0] == 'post_type' ){
         $tmp_post   = get_post( $item_meta['_menu_item_object_id'][0] );
         $item_name  = $tmp_post->post_title;
      }
      elseif( $item_meta['_menu_item_type'][0] == 'custom' ){
         $item_name  = $item->post_title;
      }
      else{
         $tmp_term   = get_term( $item_meta['_menu_item_object_id'][0], $item_meta['_menu_item_object'][0] );
         $item_name  = $tmp_term->name;
      }

      // Column Type
      $column_type_class = '';
      if( $item_depth == 1 && $item_type == 'mega' ){
         $column_type_class = 'lm-box-item-column-' . @$item_meta['lm-op-column-type'][0];
      }

      // Box Config
      $box_config                   = array();
      $box_config['atts']           = array(
         'menu_id'      => $data_post['menu_id'],
         'menu_class'   => $data_post['menu_class'],
         'box_id'       => 'menu_edit_item',
         'parent_id'    => $data_post['item_id'],
         'item_depth'   => $item_depth,
         'item_type'    => $item_type,
         'item_id'      => $data_post['item_id'],
         'column_type'  => @$item_meta['lm-op-column-type'][0],
      );
      $box_config['title']          = $item_name . '<span> [ '.$item_type.' ] </span>';
      $box_config['header_output']  = '';
      $box_config['content_output'] = '';

      // inlcude the options
      require('config/menu-item-options.php');

      $box_config['content_output'] .= $nav_output;
      $box_config['content_output'] .= $content_output;

      // Display the box
      $box_config['class'] = 'lm-box-edit-item lm-box-item-type-'.$item_type.' lm-box-item-depth-'.$item_depth.' '.$column_type_class;
      $box_config['type']     = 'custom';
      $box_config['width']    = '700px';
      $box_config['height']   = '450px';
      $box_config['top']      = '15%';
      $box_config['left']     = '2%';
      echo era_helpers_boxes::return_box( $box_config );

   }// End Func


   /**
    * Update item options
    * @since 1.0
   **/
   function update_item_option(){

      // check if admin
      if( ! current_user_can('manage_options') ){
         echo __('Not Allowed','era_fw') . '#lmerror#';
         die('');
      }

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // Explode Data
      $data_rows     = explode('_-_eraAND-_-', $_POST['data_string'] );
      $new_data      = array();
      $fonts_used    = array();

      foreach( $data_rows as $op ){
         $op_split = explode( '_-_eraEQAUL-_-', $op );
         $op_name = $op_split[0];

         // Decode Value
         $op_value = @urldecode( $op_split[1] );
         $op_value = stripslashes( $op_value );

         // save meta data
         update_post_meta( $_POST['item_id'], $op_name, $op_value);
      }

      // Display Menu
      $menu_config = array( 'menu_id'=>$_POST['menu_id'] , 'menu_class'=>$_POST['menu_class'] );
      echo livemenu_customizer::get_menu_output( $menu_config );

      die('');

   }// End Func


   /**
    * Remove menu item
    * @since 2.0
   **/
   function remove_item(){

      // check if admin
      if( ! current_user_can('manage_options') ){
         echo __('Not Allowed','era_fw') . '#lmerror#';
         die('');
      }

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // Delete Relationships
      wp_delete_object_term_relationships( $_POST['item_id'], 'nav_menu' );

      // Display Menu
      $menu_config = array( 'menu_id'=>$_POST['menu_id'] , 'menu_class'=>$_POST['menu_class'] );
      echo livemenu_customizer::get_menu_output( $menu_config );

      die('');

   }// End Func


   /**
    * Remove menu item
    * @since 2.0
   **/
   function reorder_items(){

      // check if admin
      if( ! current_user_can('manage_options') ){
         echo __('Not Allowed','era_fw') . '#lmerror#';
         die('');
      }

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // Prepare DATA
      $allItems         = explode('//_-LMSEP-_//', $_POST['data_string'] );

      foreach( $allItems as $item_str ){

         $item_arr = explode('//_-LMORD-_//', $item_str);

         // Args
         $my_post = array(
               'ID'           => (int)$item_arr[0],
               'menu_order'   => (int)$item_arr[1],
         );

         // Update the post into the database
         wp_update_post( $my_post );

      }// End Foreach

      die('');

   }// End Func


   /**
    * Get Customizer Menu
    * @since 2.0
   **/
   static function get_menu_output( $menu_config ){

      $menu_class = $menu_config['menu_class'];
      $menu_id    = (int)$menu_config['menu_id'];

      // Get Walker Content
      $args = array();

      // Get DBoptions
      $DBconfig = get_option( 'livemenu_'.$menu_class.'_config' );

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
      $controls_html .= '  <a data-menu_class="'.$menu_class.'" data-item_id="0" data-page="menu_add_items">'
                              .__('Add Item','era_fw')
                          .'</a>';
      $controls_html .= '  <a data-menu_class="'.$menu_class.'" data-page="menu_settings">'
                              .__('Settings','era_fw')
                          .'</a>';
      $controls_html .= '  <a data-menu_class="'.$menu_class.'" data-page="menu_style">'
                              .__('Style','era_fw')
                          .'</a>';
      $controls_html .= '  <span class="era-icon era-icon-chevron-thin-down"></span>';
      $controls_html .= '</div>';

      // RTL
      $rtl = ( is_rtl() ) ? 'livemenu-rtl' : '';

      require( LM_BASE_DIR . 'inc/class-walker-customizer.php' );

      // RTL
      $rtl = '';
      if( is_rtl() ){
          $rtl = 'livemenu-rtl';
      }

      $args['walker']         = new livemenu_customizer_walker( $menu_config, $DBconfig, $menu_class );
      $args['container_id']   = 'livemenu-id-'.$menu_class;
      $args['menu_class']     = 'livemenu';
      $args['menu_id']        = '';
      $args['menu']           = $menu_id;
      $args['fallback_cb']    = false;
      $args['container_class']= $menu_class.' livemenu-wrap '.$rtl;
      $args['items_wrap']     = $responsive_output.'<ul id="'.$menu_id.'" class="livemenu">%3$s</ul>' . $controls_html;
      $args['depth']          = 4;
      $args['container']      = 'div';
      $args['echo']           = false;

      $menu_output = wp_nav_menu( $args );
      $menu_output = preg_replace('!^(\<.+\>)!', '', $menu_output);
      $menu_output = preg_replace('!(\<\/div\>)$!', '', $menu_output);

      return $menu_output;

   }// End Func


}// End Class