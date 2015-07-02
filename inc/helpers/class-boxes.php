<?php
/**
 * ERA Framework
 *
 * An Awesome Wordpress Framework, created by codezag
 *
 * @author      Sabri Taieb ( codezag )
 * @copyright   Copyright (c) Sabri Taieb
 * @link        http://vamospace.com
 * @since       Version 1.0.0
 * @package     era_framework
 *
**/

/**
 * INFO
 *
 * Table of Content:
 * 1) Button
 * 2) Input
 * 3) Textarea
 *
 *
 *
**/

if( !class_exists( 'era_helpers_boxes' ) ){

class era_helpers_boxes{


	/**
	 * Load required assets for helper
	 * @since 1.0
	**/
	static function load_assets( $folder_path ){

		// Load CSS
		wp_enqueue_style( 'era-helpers-boxes', $folder_path.'css/colorpicker-spectrum.css' );

	}// End Func


	/**
	 * Return Box
	 * @since 1.0
	**/
	static function return_box( $config ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'type'   			=> 'full', 	// full or custom
            'id'     			=> '',     	// html id
            'class'  			=> '',     	// html id
            'shadow' 			=> false, 	// full screen shadow behind the box
            'title'  			=> '',     	// Title
            'header_output'		=> '',		// Header HTML Content
            'content_output'	=> '',		// Header HTML Content
            'atts'   			=> array(), // Attributes
            'width'	 			=> 'auto',
            'height' 			=> 'auto',
            'top'	 			=> 'auto',
            'bottom' 			=> 'auto',
            'left'   			=> 'auto',
            'right'  			=> 'auto',
        )));

		// Custom Size
		$style = '';
		if( $type == 'custom' ){
			$style = '	
				style = "
					width:'.$width.'; 
					height:'.$height.'; 
					top:'.$top.'; 
					left:'.$left.'; 
					right:'.$right.'; 
					bottom:'.$bottom.';
				"
			';
		}

		// Attributes
		$atts_output = '';
		foreach( $atts as $data_tag => $value ){
			$data_tag = str_replace('data-', '', $data_tag);
			$atts_output .= 'data-' .$data_tag. '="' .$value. '" ';
		}

		// Add Data Attributes
		$output  = '<div class="era-box era-box-'.$type.' '.$class.'" '.$style.' '.$atts_output.' >';

		// Header
		$output .= '<div class="era-box-header">';
		$output .= 	'<h3>'.$title.'</h3>';
		$output .= 	$header_output;
		$output .= '</div><!--era-box-header-->';

		// content
		$output .= '<div class="era-box-content">';
		$output .= 	$content_output;
		$output .= '</div><!--era-box-content-->';
		
		// End
		$output .= '</div><!--era-box-->';

		if( $shadow ){
			$output = '<div class="era-shadow era-shadow-'.$class.'">' .$output. '</div>';
		}

		return $output;
	}


}//End Class

}//End If