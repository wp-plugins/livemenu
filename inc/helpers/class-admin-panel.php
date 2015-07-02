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
 * Build Admin Panel
 *
 * 
 * $panel_args = array(
 * 		id 				-> Panel ID
 *  	class 			-> Panel Class
 *  	title 			-> Panel Title
 *  	reset_button 	-> reset button args
 * 		save_button 	-> save button args
 * 		footer_text 	-> footer copyright text
 * );
 *
**/

if( ! class_exists('era_helpers_admin_panel') ){

class era_helpers_admin_panel{

	
	public $wp_args;
	public $panel_args;
	public $panel_nav_items;
	public $panel_pages;
	public $folder_url;


	/**
	 * Constructor
	 *
	 * @since 1.0
	 * @param array $wp_args : WP Menu/Submenu args
	 * @param array $panel_args : Panel Args
	 * @param array $panel_nav_items : Panel nav items
	 * @param string $panel_pages : Panel pages ( html content )
	 * @param string $folder_url : folder url
	**/
	function __construct( $wp_args, $panel_args, $panel_nav_items, $panel_pages, $folder_url ){

		// Set Variables
		$this->wp_args 			= $wp_args;
		$this->panel_args 		= $panel_args;
		$this->panel_nav_items 	= $panel_nav_items;
		$this->panel_pages 		= $panel_pages;
		$this->folder_url 		= $folder_url;

		// Add WP Menu/Submenu
		add_action( 'admin_menu', array( $this, 'add_wp_page' ) );
		
		// Load Panel Asset Files
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );

	}// End Func


	/**
	 * Add a menu/submenu to WordPress Left Navigation
	 *
	 * @since 1.0
	 * @param array $wp_args : WP Menu/Submenu args
	**/
	function add_wp_page( $wp_args ){
		
		// Menu
		if( $this->wp_args['type'] == 'menu' ){
		
			add_menu_page(
				$this->wp_args['page_title'], 
				$this->wp_args['menu_title'],
				$this->wp_args['capability'], // ex: manage_options
				$this->wp_args['menu_slug'],
				array( $this , 'panel_html_render' ),
				$this->wp_args['icon_url'],
				$this->wp_args['position']
			);			
		
		}

		// Submenu
		else{
			
			add_submenu_page(
				$this->wp_args['parent_slug'], 
				$this->wp_args['page_title'],
				$this->wp_args['menu_title'], 
				$this->wp_args['capability'], // ex: manage_options
				$this->wp_args['menu_slug'],
				array( $this , 'panel_html_render' )
			);

		}


	}// End Func


	/**
	 * Display the Admin Panel
	 *
	 * @since 1.0
	**/
	function panel_html_render(){

		$this->header_html();

		$this->container_html();

		$this->footer_html();

	}// End Func


	/**
	 * Header HTML
	 *
	 * @since 1.0
	**/
	function header_html(){

		$html  = '<div class="era-panel '.$this->panel_args['class'].'" id="'.$this->panel_args['id'].'" >';
		$html .= '<header>
					<span>'.$this->panel_args['title'].'</span>
				';

			// Save/Reset Buttons
			if( array_key_exists( 'save_button', $this->panel_args ) ){
				$html .= era_helpers_options::button( $this->panel_args['save_button'] );
			}
			if( array_key_exists( 'reset_button', $this->panel_args ) ){
				$html .= era_helpers_options::button( $this->panel_args['reset_button'] );			
			}

		$html .= '</header>';

		echo $html;

	}// End Func


	/**
	 * Container HTML
	 *
	 * @since 1.0
	**/
	function container_html(){

		// Nav
		$this->nav_html();

		// Pages
		echo $this->panel_pages;

	}// End Func


	/**
	 * Nav HTML
	 *
	 * @since 1.0
	**/
	function nav_html(){
		
		$html = '<ul class="era-panel-nav">';

		// Items
		$counter_1 = 0;
		$counter_2 = 0;
		foreach( $this->panel_nav_items as $item => $sub_items ){
			
			$counter_1 += 1;
			$html .= '<li data-id="'.$counter_1.'"><span>'.$item.'</span>';

			// Sub Items
			if( count( $sub_items ) ){
				$html .= '<ul>';

				foreach( $sub_items as $sub_i ){

					$counter_2 += 1;
					$html .= '<li data-id="'.$counter_1.'-'.$counter_2.'"><span>'.$sub_i.'</span></li>';				

				}

				$html .= '</ul>';
			}

			$html .= '</li>';

		}

		$html .= '</ul>';

		echo $html;

	}// End Func


	/**
	 * Load Javascript/CSS Assets
	 *
	 * @since 1.0
	**/
	function footer_html(){

		echo ' 	<footer>'.$this->panel_args['footer_text'].'</footer>
			  </div>';

	}// End Func


	/**
	 * Load Javascript/CSS Assets
	 *
	 * @since 1.0
	**/
	function load_assets(){

		// CSS
		wp_enqueue_style( 'era-helpers-panel', $this->folder_url.'css/panel.css' );

		// RTL 
		if( is_rtl() ){
			wp_enqueue_style( 'era-helpers-panel-rtl', $this->folder_url.'css/panel.rtl.css' );
		}

		// JS
		wp_enqueue_script( 'era-helpers-panel', $this->folder_url.'js/panel.js', array('jquery'), false, true );

	}// End Func


}// End Class

}// End If