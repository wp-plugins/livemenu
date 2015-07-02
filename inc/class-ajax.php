<?php

/**
 * Admin AJAX Actions
 * @since 1.0
**/
class livemenu_ajax{


   /**
    * Add WP Action
    * @since 1.0
   **/
	function __construct(){

      add_action( 'wp_ajax_livemenu_menu_status'   , array( $this, 'menu_status') );
      add_action( 'wp_ajax_livemenu_enable_menu'   , array( $this, 'enable_menu') );
      add_action( 'wp_ajax_livemenu_disable_menu'  , array( $this, 'disable_menu') );

      add_action( 'wp_ajax_lm_export_config', array( $this, 'export_config' ) );
      add_action( 'wp_ajax_lm_import_config', array( $this, 'import' ) );

      add_action( 'wp_ajax_lm_admin_save_custom_css', array( $this, 'save_custom_css' ) );
      add_action( 'wp_ajax_lm_admin_save_menus_to_add', array( $this, 'save_menus_to_add' ) );
      add_action( 'wp_ajax_lm_admin_save_enable_mode', array( $this, 'save_enable_live_mode' ) );


      add_action( 'wp_ajax_livemenu_admin_box_edit_item', array( $this, 'box_edit_item' ) );

   }// End Func


   /**
    * Save : Enable Live mode
    * @since 1.0
   **/
   function save_enable_live_mode(){

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // Save
      update_option( 'livemenu_enable_demo', $_POST['enable_demo'] );

      die('');
   }


   /**
    * Save Menus To Add
    * @since 1.0
   **/
   function save_menus_to_add(){

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // Save
      update_option( 'livemenu_menus_added_count', $_POST['custom_css'] );

      // Create the css file
      livemenu_create_assets::global_custom_css();

      die('');
   }


   /**
    * Save custom css
    * @since 1.0
   **/
   function save_custom_css(){

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // Save
      update_option( 'livemenu_custom_css', $_POST['custom_css'] );

      // Create the css file
      livemenu_create_assets::global_custom_css();

      die('');
   }


   /**
    * Export Settings & Style
    * @since 1.0
   **/
   function export_config(){

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // check if there active livemenu
      if( ! $livemenu_used_menus = get_option('livemenu_registered_menus') ) die('#error#no_active_menus_found');

      // get config by each used location
      foreach( $livemenu_used_menus as $location => $menu_id ){
         $all_config['config'][ $location ] = get_option('livemenu_'.$location.'_config');
      }

      // get fonts used & widget areas
      $all_config['fonts_used']     = get_option('livemenu_fonts_used');

      // save to json file
      echo livemenu_create_assets::export_file( $all_config );

      die('');
   }


   /**
    * Import menus or config
    * @since 1.0
   **/
   function import(){

      // check permission
      if( ! current_user_can('manage_options') ) die('');

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // import menus or config
      $filepath = parse_url( $_POST['file_url'] );
      $filepath = preg_replace('!^(.+uploads)!', '', $filepath['path'] ) ;
      $upload_dir = wp_upload_dir();
      $filepath = $upload_dir['basedir'].$filepath;

      if( pathinfo( $filepath, PATHINFO_EXTENSION ) == 'xml' ){
         $this->import_menus( $filepath );
      }
      else{
         $this->import_config();
      }

   }// End Func


   /**
    * Import Style / Settings of the menus
    * @since 1.0
   **/
   function import_menus( $filepath ){

      // check if the import class is available, else include it
      // this class is a part of the wordpress-importer plugin
      if( ! class_exists( 'WP_Import' ) ) {

         if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) define( 'WP_LOAD_IMPORTERS', true );
         require_once( LM_BASE_DIR . 'inc/import/wordpress-importer/wordpress-importer.php' );
      
      }

      // inlcude our custom importing class
      require_once( LM_BASE_DIR . 'inc/import/class-import.php' );

      $wp_import = new livemenu_wp_import();
      $wp_import->rename_existing_menus();   // to prevent duplicate menu items
      $wp_import->fetch_attachments = false; // true for demo dummy data import 
      $wp_import->import( $filepath );       // import from xml file

      _e('Menus Imported','era_fw');

      die('');

   }// End Func


   /**
    * Import Style / Settings of the menus
    * @since 1.0
   **/
   function import_config(){

      // get config file
      $all_config = @file_get_contents( $_POST['file_url'] );

      // convert json to array
      $all_config = json_decode( $all_config, true );

      // create each location config
      foreach( $all_config['config'] as $location => $config ){
         update_option( 'livemenu_'.$location.'_config', $config );
      }

      // create used_fonts & widget areas options
      update_option( 'livemenu_fonts_used', $all_config['fonts_used'] );

      // create asset files
      do_action( 'livemenu_create_assets_after_import', $all_config );
      
      _e('Config Imported!','era_fw');
      
      die('');

   }// End Func


   /**
    * Check the menu status & return the buttons
    * @since 1.0
   **/
   function menu_status(){

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // get menus & locations used by user
      $wp_used_locations = explode( '#LMSEP#', $_POST['theme_locations'] );

      // get menu id 
      $menu_info  = wp_get_nav_menu_object($_POST['menu_name']);

      // get livemenu registered menus & locations 
      $livemenu_used_locations = ( get_option('livemenu_registered_menus') ) ? get_option('livemenu_registered_menus') : array();

      $location_exists_counter = 0;
      
      foreach( $livemenu_used_locations as $location => $menu_id ){
         
         if(   $menu_info->term_id == $menu_id 
               && in_array( $location, $wp_used_locations )
         ){

            $location_exists_counter = $location_exists_counter + 1;               
         
         }

      }

      // update livemenu registered menus 
      if( $location_exists_counter == count( $wp_used_locations ) ){
         echo '<button class="button button-large button-primary lm-btn-disable">'
            .__('Disable Livemenu','era_fw')
            .'<img src="'.LM_BASE_URL.'assets/img/1.gif" >'
            .'</button>';
      }
      else{
         echo '<button class="button button-large button-primary lm-btn-enable">'
            .__('Enable Livemenu','era_fw')
            .'<img src="'.LM_BASE_URL.'assets/img/1.gif" >'
            .'</button>';
      }

      die('');
   }


   /**
    * Enable Livemenu
    * And call wp_action to create preview skins
    * @since 1.0
   **/
   function enable_menu(){


      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // get menus & locations used by user
      $wp_used_locations = explode( '#LMSEP#', $_POST['theme_locations'] );

      // get menu id 
      $menu_info  = wp_get_nav_menu_object($_POST['menu_name']);

      if( ! $menu_info ){
         echo __('Please select theme locations & click on "save menu" first','era_fw') . ' #error#';
         die('');
      }

      // get livemenu registered menus & locations 
      $livemenu_used_locations = ( get_option('livemenu_registered_menus') ) ? get_option('livemenu_registered_menus') : array();
      
      foreach( $wp_used_locations as $location ){
         
         $livemenu_used_locations[ $location ] = $menu_info->term_id;

      }

      // update livemenu registered menus 
      update_option( 'livemenu_registered_menus', $livemenu_used_locations );

      // create style, preview skins & js settings
      do_action( 'livemenu_create_preview_assets' );

      // Return Disable button      
      echo '<button class="button button-large button-primary lm-btn-disable">'
            .__('Disable Livemenu','era_fw')
            .'<img src="'.LM_BASE_URL.'assets/img/1.gif" >'
         .'</button>';


      die('');

   }// End Func


   /**
    * Disable Livemenu
    * and create preview skins
    * @since 1.0
   **/
   function disable_menu(){

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      // get menus & locations used by user
      $wp_used_locations = get_theme_mod('nav_menu_locations');

      // get livemenu registered menus & locations 
      $livemenu_used_locations = ( get_option('livemenu_registered_menus') ) ? get_option('livemenu_registered_menus') : array();
      $updated_livemenu_locations = array();

      // get menu id
      $menu_info = wp_get_nav_menu_object($_POST['menu_name']);

      // remove every location related to this menu id
      foreach( $livemenu_used_locations as $location => $menu_id ){
      
         if( $menu_id != $menu_info->term_id ){
            $updated_livemenu_locations[ $location ] = $menu_id;
         }

      }

      update_option( 'livemenu_registered_menus', $updated_livemenu_locations );

      // return enable button
      echo '<button class="button button-large button-primary lm-btn-enable">'
               .__('Enable Livemenu','era_fw')
               .'<img src="'.LM_BASE_URL.'assets/img/1.gif" >'
            .'</button>';

      die('');

   }// End Func


   /**
    * Admin :: Return item options box
    * @since 1.0
   **/
   function box_edit_item(){

      // check nonce
      check_ajax_referer( 'lm_nonce_string', 'nonce' );

      $data_post = $_POST;

      // Get Item
      $item                = get_post( $data_post['item_id'] );
      $item_meta           = get_post_meta( $data_post['item_id'] );
      $item_depth          = $data_post['item_depth'];
      $item_type           = ( isset( $data_post['item_type'] ) ? $data_post['item_type'] : 'dropdowns' );
      $item_column         = ( isset( $data_post['item_column'] ) ? $data_post['item_column'] : 'column_widgets' );

      // Column Type
      $column_type_class = '';
      if( $item_depth == 1 ){
         $column_type_class = 'lm-box-item-column-' . $item_column;
      }

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

      // Box Config
      $box_config                   = array();
      $box_config['atts']           = array(
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
      echo era_helpers_boxes::return_box( $box_config );

      die('');

   }// End Func


}// End Class