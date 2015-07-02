<?php

/**
 * Display a menu by ID
**/
function livemenu( $menu_id = '' ){
  	
  	// chech the id first
	if( $menu_id == '' ) _e('Please add a menu ID ex : <?php livemenu(1); ?>', 'era_fw' );

	$menu_id = (int)$menu_id;

	wp_nav_menu( array( 'theme_location' => 'livemenu'.$menu_id ) );

}

/** Shortcode for the menu itself **/
function livemenu_sc( $atts ) {
	livemenu( $atts['id'] );
}
add_shortcode('livemenu', 'livemenu_sc');
