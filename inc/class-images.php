<?php


/**
 * Images Manager Class
**/
if( !class_exists('era_images_manager') ){

class era_images_manager{

	static $sizes = array();


	/**
	 * Add Sizes
	 *
	 * @since 1.0
	**/
	static function add_size( $new_size ){
		if( gettype($new_size[0]) != null && gettype($new_size[1]) != null ){
			$new_size[0] = (int)$new_size[0];
			$new_size[1] = (int)$new_size[1];
			self::$sizes[$new_size[0].'X'.$new_size[1]] = $new_size;
		}
	}


	/**
	 * Listen when upload/remove an image
	 *
	 * this will create a copy of the images when you upload a new image
	 * and clean the images when you delete an image
	 *
	 * @since 1.0
	**/
	static function listener(){
		add_filter( 'wp_generate_attachment_metadata', array( 'era_images_manager' , 'wp_attachment_meta' ) , 13, 2 );
		add_filter( 'delete_attachment',  array( 'era_images_manager' , 'clean_images') );
	}


	/**
	 * Attachment Metadata
	 *
	 * This function is attached to the 'wp_generate_attachment_metadata' filter hook.
	 * http://code.tutsplus.com/tutorials/ensuring-your-theme-has-retina-support--wp-33430
	 *
	 * @since 1.0
	 */
	static function wp_attachment_meta( $metadata, $attachment_id ) {

		foreach( self::$sizes as $key => $size ){
			self::crop_images( get_attached_file( $attachment_id ), $size );
		}

	    return $metadata;
	}


	/**
	 * Crop Image & Create Retina version
	 *
	 * This function is attached to the 'wp_generate_attachment_metadata' filter hook.
	 *
	 * @since 1.0
	 */
	static function crop_images( $file, $size ) {

        $resized_file = wp_get_image_editor( $file );

        if ( ! is_wp_error( $resized_file ) ) {

        	/** Create Image With Custom width / height  **/
            $filename = $resized_file->generate_filename( $size[0] . 'x' . $size[1] );
            $resized_file->resize( $size[0], $size[1], true );
            $resized_file->save( $filename );

        	/** Create Retina **/
        	$resized_file = wp_get_image_editor( $file );
            $filename = $resized_file->generate_filename( $size[0] . 'x' . $size[1] . '@2x' );
            $resized_file->resize( ($size[0] * 2), ($size[1] * 2), true );
            $resized_file->save( $filename );

        }// End IF

	}// End Func


	/**
	 * Delete custom / retina images
	 *
	 * This function is attached to the 'delete_attachment' filter hook.
	 * 
	 * @since 1.0
	**/
	static function clean_images( $attachment_id ){

	    $meta 		= wp_get_attachment_metadata( $attachment_id );
	    $upload_dir = wp_upload_dir();
	    $path 		= @pathinfo( $meta['file'] );

		if ( !in_array("dirname", $path) )
			return false;
		
		// Delete Images
		foreach( self::$sizes as $key => $size ){
			$custom_image = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $path['filename'] . '-' . $size[0] . 'x' . $size[1] . '.' . $path['extension'] ;
			$retina_image = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $path['filename'] . '-' . $size[0] . 'x' . $size[1] . '@2x.' . $path['extension'] ;
			@unlink( $custom_image ); 
			@unlink( $retina_image ); 
		}

	}// End Func
	

	/**
	 * Get Image URl
	 *
	 * this return an array of image url and retina version url
	 * 
	 * @since 1.0
	**/
	static function get_image_url( $post_id, $size = array() ){

		// if no thumbnail attached return ''
		if( get_post_thumbnail_id( $post_id ) == '' )
			return '';

		// if size is empty return the original url
		$original_image_url = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
		if( count($size) === 0 ){
			return $original_image_url;
		}

		// Return image with size
		else{
			$file 		= get_attached_file( get_post_thumbnail_id( $post_id ) );
			$path 		= str_replace( basename($file), '', $file );
            $image_name = basename($file);
            preg_match("/\.[a-zA-z]{1,4}$/", $image_name, $matches);

            if( isset($matches[0]) ){

            	$image_name = str_replace($matches[0], '', $image_name);
                $image_path = $path.$image_name.'-'.$size[0].'x'.$size[1].$matches[0];

                if( file_exists($image_path) ){

                	$upload_dir = wp_upload_dir();
                	$image_url 	= wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
                	$image_path = explode('uploads', $image_url );
                	$image_path = $image_path[1];
                	$image_path = str_replace( basename($image_path), '', $image_path );

                	return $upload_dir['baseurl'].$image_path.$image_name.'-'.$size[0].'x'.$size[1].$matches[0];
                }
                else{
                	return $original_image_url;
                }

            }
            else {
				return $original_image_url;
            }

		}// End Else

	}// End Func


	/**
	 * Get Image URl
	 *
	 * this return an array of image url and retina version url
	 * 
	 * @since 1.0
	 * @param int $post_id : WP Post ID
	 * @param array $size : Image Size ( width, height )
	 * @param string $attr : HTML Attributes
	 * @param bool $lazy_load : lazy load feature
	 * @return string : HTML img tag
	**/
	static function get_image( $post_id, $size = array(), $attr='', $lazy_load = false ){

		$image_url = self::get_image_url( $post_id, $size );

		// return nothing, if no image url
		if( $image_url == '' ) return '';
		
		// return the img tag
		if( $lazy_load ){
			return '<img src="" data-src="'.$image_url.'" width="'.$size[0].'" height="'.$size[0].'" '.$attr.' >';
		}
		else{
			return '<img src="'.$image_url.'" width="'.$size[0].'" height="'.$size[0].'" '.$attr.' >';
		}

	}// End Func


}// End Class

}// End If