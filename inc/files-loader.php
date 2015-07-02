<?php

/**
 * Include Files for backend AND frontend
**/
require_once('class-livemenu.php');
require_once('class-fonts.php');
require_once('class-images.php');


/**
 * Include these files on admin & live customizer
**/
if( is_admin() || ( isset( $_GET['livemenu_live'] ) && $_GET['livemenu_live'] == 'on' ) ){

   require_once('icons-list.php');
   require_once('helpers/class-options.php');
   require_once('helpers/class-boxes.php');
   require_once('helpers/class-admin-panel.php');
   require_once('class-customizer.php');
   require_once('class-ajax.php');
   require_once('class-create-files.php');
}


/**
 * Include Files for backend only
**/
if( is_admin() ){

	require_once('class-walker-admin.php');
   require_once('class-post_type-taxonomy-helper.php');

   require_once('config/panel-nav-items.php');
   require_once('config/panel-pages.php');

}


/**
 * Include Files for frontend only
**/
else{

   require_once('func-livemenu.php');

   // Walkers
   require_once('class-walker-customizer.php');
   require_once('class-walker-frontend.php');

}