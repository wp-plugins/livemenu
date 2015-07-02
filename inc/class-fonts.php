<?php


/**
 * Fonts Manager
 * manage normal/google fonts class
 * @since 1.0
**/

if( !class_exists('era_fonts') ){

class era_fonts {

	public static $standard = array(
		"Arial , sans-serif"		=>		"Arial , sans-serif",
		"Arial Black , sans-serif"	=>		"Arial Black , sans-serif",
		"Verdana , sans-serif"		=>		"Verdana , sans-serif",
		"Impact , sans-serif"		=>		"Impact , sans-serif",
		"Helvetica , sans-serif"	=>		"Helvetica , sans-serif",
		"Georgia , sans-serif"		=>		"Georgia , sans-serif",
		"Tahoma , sans-serif"		=>		"Tahoma , sans-serif",
		"Courier New , sans-serif"	=>		"Courier New , sans-serif"
	);

	/**
	 * Return the fonts
	**/
	static function fonts_list(){
		if( $gwf = get_option('era_googleFonts') ){
    		return array_merge( self::$standard, $gwf );
    	}
    	else{
    		return self::$standard;
    	}
	}// End Func


	/**
	 * Return the font Sizes
	**/
	static function sizes_list(){
		$sizes = array();
		for( $i=1; $i < 101 ; $i++ ){ 
			$sizes[$i.'px'] = $i.'px';
		}
		return $sizes;
	}// End Func


	/**
	 * Return the font Sizes
	**/
	static function style_list(){
		return array( 
			__('Normal'	,'era_fw')			=>	'font-style:normal;font-weight:normal;',
			__('Italic' ,'era_fw')			=>	'font-style:italic;font-weight:normal;',
			__('Bold' ,'era_fw')			=>	'font-style:normal;font-weight:bold;',
			__('Bold / Italic' ,'era_fw')	=>	'font-style:italic;font-weight:bold;',
		);
	}// End Func


	/**
	 * Return  only google fonts
	 */
	static function only_google_font_list(){
    	return get_option('era_googleFonts');
	}


	/**
	 *
	 * Return  only standard fonts
	 *
	 */
	static function only_standard_font_list()
	{
    	return $this->standard;
	}


	/**
	 *
	 * Download googel fonts list
	 *
	 */
	static function download_google_fonts(){

		if( get_option('era_googleFonts') ) return false;

	    $key = "AIzaSyBj2NGLBDMdyzLvqQc4RHi3g0cHnFfU6GI";
	    $sort= "alpha";

	    $http = (!empty($_SERVER['HTTPS'])) ? "https" : "http";
	    $google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $key . '&sort=' . $sort;

	    //lets fetch it
	    $response = wp_remote_retrieve_body( wp_remote_get($google_api_url, array('sslverify' => false )));
	    if( !is_wp_error( $response ) ){

	        $data = json_decode($response, true);
	        $items = $data['items'];
	        foreach ($items as $item){
	            $font_list[ $item['family'] ] = $item['family'];
	        }

	        //save list to db
	        update_option( 'era_googleFonts' , $font_list );
	    }
	    else{
	    	update_option('era_errors','googlefonts_fetch_error');
	    }

	}// End Func


	/**
	 *	return only google fonts
	 *	@param $fonts array()
	 */
	static function filter_google_fonts( $fonts ){

		$google_fonts = array();

		// add only google fonts
		foreach( $fonts as $font_name ){
			if( !in_array( $font_name, self::$standard ) ){
				$google_fonts[] = str_replace( ' ', '%20', $font_name );
			}
		}

		return $google_fonts;

	}// End Func


	/**
	 * Enqueue Google Fonts to the Front-End
	 */
	static function load_frontend_google_fonts( $used_fonts, $enqueue_name ){
	
		$used_fonts = implode( '|', $used_fonts );

        /*$query_args = array(
            'family' => urlencode( implode( '|', $used_fonts ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );

		$fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' ); */
		echo '//fonts.googleapis.com/css?family='.$used_fonts;
		
		wp_enqueue_style( "ggoeldpzdpzddadza", '//fonts.googleapis.com/css?family='.$used_fonts , array(), null );

	}// End Func


} // end class
} // end if