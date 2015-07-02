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

if( !class_exists( 'era_helpers_options' ) ){

class era_helpers_options{


   /**
    * Load required assets for helper
    * @since 1.0
   **/
   static function load_assets( $folder_path ){

        // Load CSS
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'era-helpers-colorpicker', $folder_path.'css/colorpicker-spectrum.css' );
        wp_enqueue_style( 'era-helpers-options', $folder_path.'css/helpers-options.css' );

        // Load JS
        wp_enqueue_media();
        wp_enqueue_script( 'era-helpers-colorpicker', $folder_path.'js/colorpicker-spectrum.js' );
        wp_enqueue_script( 'era-helpers-options', $folder_path.'js/helpers-options.js', array('jquery', 'media-upload', 'thickbox', 'era-helpers-colorpicker' ) );

   }// End Func


    /**
     * Return a HTML Container with the option
     * @since 1.0.0
     * @param $class string : css class
    */
    static function container( $config ){

        $title      = ( @$config['title'] == "" ) ? '' : '<span class="era-op-title">'.$config['title'].'</span>';
        $desc       = ( @$config['desc']  == "" ) ? '' : '<span class="era-op-desc">'.$config['desc'].'</span>';

        if( $config['container'] ){
            $html_wrap   = '<div class="' .$config['container_class']. '">';
            $html_wrap  .= '<header>'.$title.$desc.'</header>';
            $html_wrap  .= $config['op_output'];
            $html_wrap  .= '</div>';

            return $html_wrap;
        }
        else{
            return $config['op_output'];
        }

    }// End Func


    /**
     * Return an option attr
     * @since 1.0.0
     * @param $attributes
    */
    static function attributes( $atts = array() ){

        $attr_wrap = '';
        foreach ($atts as $attr_name => $attr_val ){
            $attr_wrap .= $attr_name . '="' . $attr_val . '" ';
        }

        return $attr_wrap;
    }


    /**
     * Return a HTML Button
     * @since 1.0.0
     * @param $class string : css class
    */
    static function button( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'type'              => 'button',// <a> or <button>
            'id'                => '',      // html id
            'title'             => '',      // Title
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array()  // Button Attributes
        )));

        $output  = $before. '<' .$type. ' class="era-btn ' .$class. '" id="' .$id. '" ' .self::attributes($atts). ' >';
        $output .= $title.  '</'.$type.'>' .$after;

        return $output;
    }


    /**
     * Return Input ( text, email, password, hidden ... )
     * @since 1.0
     * @param settings array : input settings
    */
    static function input( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'type'              => 'text',  // text, email, password, hidden ...
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value'             => '',      // option value
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false, // Add a container to the option
            'container_class'   => '',
        )));

        $op_output  = '<section class="era-op-wrap era-op-input">';
        $op_output .= $before.'<input class="era-op '.$class.'" type="' .$type. '" value="' .esc_attr( $value ). '" id="'.$id.'" name="'.$id.'" '.self::attributes($atts).' >'.$after;
        $op_output .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func Input


    /**
     * Return Textarea
     * @since 1.0
     * @param settings array : input settings
    */
    static function textarea( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value'             => '',      // option value
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false, // Add a container to the option
            'container_class'   => '',
        )));

        $op_output  = '<section class="era-op-wrap era-op-textarea">';
        $op_output .= $before.'<textarea class="era-op '.$class.'" id="'.$id.'" name="'.$id.'" '.self::attributes($atts).' >'. esc_attr( $value ) .'</textarea>'.$after;
        $op_output .= '</section>';

        // Container
        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func


    /**
     * Return Select
     * @since 1.0
     * @param settings array : input settings
    */
    static function select( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value'             => '',      // option value
            'options'           => array(), // options array()
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false, // Add a container to the option
            'container_class'   => '',
        )));

        $options_wrap   = '';
        foreach ( $options as $op_name => $op_val){
            $isSelected = ( $op_val == $value ) ? ' selected ' : '';
            $options_wrap .= '<option value="'.$op_val.'" '.$isSelected.'>'.$op_name.'</option>';
        }

        $op_output   = '<section class="era-op-wrap era-op-select">';
        $op_output  .= $before.'<select class="era-op '.$class.'" id="'.$id.'" name="'.$id.'" '.self::attributes($atts).' >';
        $op_output  .= $options_wrap. '</select>'.$after;
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func


    /**
     * Return Checkbox
     * @since 1.0
     * @param settings array : settings
    */
    static function checkbox( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value'             => 'off',   // option value
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false,   // Add a container to the option
            'container_class'   => '',
        )));

        $isChecked  = ( $value == "on" ) ? ' checked ' : '';

        $op_output   = '<section class="era-op-wrap era-op-checkbox">';
        $op_output  .= $before.'<input class="era-op '.$class.'" type="checkbox" value="' .esc_attr( $value ). '" id="'.$id.'" name="'.$id.'" '.$isChecked.' '.self::attributes($atts).' >'. $after;
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func


    /**
     * Return colorpicker
     * @since 1.0
     * @param settings array : input settings
    */
    static function colorpicker( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value'             => '',      // option value
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false,   // Add a container to the option
            'container_class'   => '',
        )));

        $op_output   = '<section class="era-op-wrap era-op-colorpicker">';
        $op_output  .= $before.'<input class="era-op '.$class.'" type="text" value="' . esc_attr( $value ) . '" class="'.$class.'" id="'.$id.'" name="'.$id.'" '.self::attributes($atts).'>'.$after;
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func Input


    /**
     * Return Radio
     * @since 1.0
     * @param settings array : input settings
    */
    static function radio( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value'             => '',      // option value
            'options'           => array(), // options array()
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false,   // Add a container to the option
            'container_class'   => '',
        )));

        $options_wrap   = '';
        foreach ( $options as $op_name => $op_val){
            $isSelected = ( $op_val == $value ) ? ' checked="checked" ' : '';
            $options_wrap .= '<input type="radio" class="era-op '.$class.'" value="'.$op_val.'" '.$isSelected.' name="'.$id.'" '.self::attributes($atts).' >&nbsp;&nbsp;'.$op_name.'<br/>';
        }

        $op_output  = '<section class="era-op-wrap era-op-radio" >'.$before.$options_wrap.$after.'</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func


    /**
     * Return enable
     * @since 1.0
     * @param settings array : input settings
    */
    static function enable( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value'             => 'off',   // option value
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false,   // Add a container to the option
            'container_class'   => '',
        )));

        $op_output   = '<section class="era-op-wrap era-op-enable">';
        $op_output  .= $before.'<label for="'.$id.'" data-stat="'.$value.'" ></label>';
        $op_output  .= '<input type="checkbox" class="era-op '.$class.'" name="'.$id.'" id="'.$id.'" value="'.esc_attr($value).'" checked '.self::attributes($atts).' >'.$after;
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func


    /**
     * Return img's to select from = something like Layout select
     * @since 1.0
     * @param settings array : input settings
    */
    static function img_select( $config = array() ) {

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value'             => '',      // option value
            'options'           => array(), // options array()
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false,   // Add a container to the option
            'container_class'   => '',
        )));

        $options_wrap   = '';
        foreach ( $options as $op_key => $op_arr )
        {
            $isSelected = ( $op_key == $value ) ? ' checked="checked" ' : '';
            $options_wrap .= '<label><input type="radio" class="era-op '.$class.'" value="'.$op_key.'" '.$isSelected.' name="'.$id.'" '.self::attributes($atts).' >&nbsp;&nbsp;'.$op_arr['name'];
            $options_wrap .= '<img src="'.$op_arr['img'].'" ></label>';
        }

        $op_output = '<section class="era-op-wrap era-op-imgselect" >'.$before.$options_wrap.$after.'</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func


    /**
     * Return Upload Helper
     * @since 1.0
     * @param config array : settings
    */
    static function upload( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                    => '',      // id/name
            'title'                 => '',      // Title
            'input_type'            => 'text',     // text , hidden
            'button_title'          => __('Upload','era_fw'),     // Button Text
            'Library_type'          => 'image',     // "image" or "video,audio"
            'wp_media_button'       => __('Add Images','era_fw'),   // WP Media Button Text
            'wp_media_window_title' => __('Upload an Image','era_fw'), // WP Media Window Title
            'desc'                  => '',      // Description or Help
            'value'                 => '',      // option value
            'class'                 => '',      // css class
            'before'                => '',      // html before
            'after'                 => '',      // html after
            'atts'                  => array(), // HTML Attributes
            'container'             => false,   // Add a container to the option
            'container_class'       => '',
        )));

        // Prepare a HTML <img>
        $image_wrap = '';
        if( $value != '' ){
            $image_wrap = '<img src="' .$value. '" >';
        }

        // Upload Button
        $button_args = array(
            'class'  => 'era-btn era-btn-wpmedia-open',
            'title'  => $button_title,
            'atts'   => array(
                'data-multiple'     => false,
                'data-library_type' => $Library_type,
                'data-button_text'  => $wp_media_button,
                'data-frame_title'  => $wp_media_window_title,
            ),
        );

        $op_output   = '<section class="era-op-wrap era-op-upload">';
        $op_output  .= $before.'<input class="era-op '.$class.'" type="'.$input_type.'" value="' . esc_attr( $value ) . '" id="'.$id.'" name="'.$id.'" '.self::attributes($atts).' >';
        $op_output  .= self::button( $button_args );
        $op_output  .= $image_wrap. $after;
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func



    /**
    * Multiple Upload Helper
    * @since 1.0.0
    * @param config array of settings
    */
    static function multiple_upload( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                    => '',      // id/name
            'type'                  => 'text',  // Input type
            'title'                 => '',      // Title
            'Library_type'          => 'image',     // "image" or "video,audio"
            'wp_media_button'       => __('Add Images','era_fw'),   // WP Media Button Text
            'wp_media_window_title' => __('Upload an Image','era_fw'),
            'desc'                  => '',      // Description or Help
            'value'                 => '',      // option value
            'class'                 => '',      // css class
            'before'                => '',      // html before
            'after'                 => '',      // html after
            'atts'                  => array(), // HTML Attributes
            'container'             => false,   // Add a container to the option
            'container_class'       => '',
        )));

        // Prepare Images Selected
        $images_wrap = '';
        if( $value != '' ){
            $imagesIDS = explode( ',' , $value );
            foreach ( $imagesIDS as $imgID )
            { $images_wrap .= era_images::getImage( $imgID, 'thumbnail' ); }
        }// End IF

        // Add/Reset Buttons
        $button_add_args = array(
            'class'  => 'era-btn era-btn-wpmedia-open',
            'title'  => __('Add Images','era_fw'),
            'atts'   => array(
                'data-multiple'     => true,
                'data-library_type' => $Library_type,
                'data-button_text'  => $wp_media_button,
                'data-frame_title'  => $wp_media_window_title,
            ),
        );

        $button_reset_args = array(
        'class'  => 'era-btn-wpmedia-reset',
        'title'  => __('Remove Images','era_fw'),
        'atts'   => array( 'style' => 'margin-left:6px;'),
        'after'  => '<br/><br/>'
        );

        $op_output   = '<section class="era-op-wrap era-op-uploadmultiple">';
        $op_output  .= $before.'<input class="era-op '.$class.'" type="hidden" value="' . esc_attr( $value ) . '" id="'.$id.'" name="'.$id.'" '.self::attributes($atts).' >';
        $op_output  .= self::button( $button_add_args );
        $op_output  .= self::button( $button_reset_args );
        $op_output  .= $images_wrap.$after;
        $op_output  .= '</section>';

        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func



    /**
     * Background Helper
     * @since 1.0.0
     * @param config array of settings
    **/
    static function background( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                    => '',          // id/name
            'title'                 => '',          // Title
            'desc'                  => '',          // Description or Help

            'value_type'            => 'color',     // color, image or gradient
            'value_color'           => '#414141',   // default color
            'value_img'             => '',          // img url
            'value_img_repeat'      => 'no-repeat', // img repeat
            'value_img_attachment'  => 'scroll',    // img attachment
            'value_img_position'    => 'Top Left',  // img position
            'value_gradient_dir'    => 'left',      // gradient direction
            'value_gradient_color1' => '#000000',   // gradient color 1
            'value_gradient_color2' => '#393939',   // gradient color 2

            'atts'                  => array(),     // attributes

            'atts_type'             => array(),     // attributes
            'atts_color'            => array(),     // attributes
            'atts_img'              => array(),     // attributes
            'atts_img_repeat'       => array(),     // attributes
            'atts_img_attachment'   => array(),     // attributes
            'atts_img_position'     => array(),     // attributes
            'atts_gradient_dir'     => array(),     // attributes
            'atts_gradient_color1'  => array(),     // attributes
            'atts_gradient_color2'  => array(),     // attributes

            'class'                 => '',          // html class
            'before'                => '',          // html code before
            'after'                 => '',          // html code after
            'container'             => false,   // Add a container to the option
            'container_class'       => '',
        )));


        $op_output   = '<section class="era-op-wrap era-op-background '.$class.'" '.self::attributes($atts).' >';

        // Type
        $op_output  .= self::select(array(
            'options'   => array(
                __('Simple Color','era_fw')     => 'color',
                __('Background Image','era_fw') => 'image',
                __('Gradient','era_fw')         => 'gradient'
            ),
            'value'     => $value_type,
            'class'     => 'era-op-background-type',
            'id'        => $id . '-type',
            'atts'      => $atts_type,
        ));

        // Simple Color
        $hidde_class = ( empty( $value_type ) || $value_type == 'color' ) ? '' : 'hidden';
        $op_output  .= '<div class="era-op-bg-type-color '.$hidde_class.'">';
        $op_output  .= self::colorpicker(array(
            'value' => $value_color,
            'atts'  => $atts_color,
            'class'     => 'era-op-background-color',
            'id'        => $id . '-color',
        ));
        $op_output  .= '</div>';


        // Background Image
        $hidde_class = ( $value_type != 'image' ) ? 'hidden' : '';
        $op_output  .= '<div class="era-op-bg-type-image '.$hidde_class.'">';
        $op_output  .= self::upload(array(
            'value' => $value_img,
            'atts'  => $atts_color,
            'class' => 'era-op-background-image',
            'id'    => $id . '-image',
        ));
        $op_output  .= self::select(array(
            'options'   => array(
                'repeat' => 'repeat' ,
                'no-repeat' => 'no-repeat' ,
                'repeat-x' => 'repeat-x' ,
                'repeat-y' => 'repeat-y'
            ),
            'value'     => $value_img_repeat,
            'atts'      => $atts_img_repeat,
            'class'     => 'era-op-background-image-repeat',
            'id'        => $id . '-image-repeat',
        ));
        $op_output  .= self::select(array(
            'options'   => array(
                'scroll' => 'scroll' ,
                'fixed' => 'fixed' ,
                'local' => 'local'
            ),
            'value'     => $value_img_attachment,
            'atts'      => $atts_img_attachment,
            'class'     => 'era-op-background-image-attachment',
            'id'        => $id . '-image-attachment',
        ));
        $op_output  .= self::select(array(
            'options'   => array(
                'Left Top'      => 'Left Top' ,
                'Left Center'   => 'Left Center' ,
                'Left Bottom'   => 'Left Bottom' ,
                'Center Top'    => 'Center Top',
                'Center Center' => 'Center Center' ,
                'Center Bottom' => 'Center Bottom' ,
                'Right Top'     => 'Right Top' ,
                'Right Center'  => 'Right Center' ,
                'Right Bottom'  => 'Right Bottom' ,
            ),
            'value'     => $value_img_position,
            'atts'      => $atts_img_position,
            'class'     => 'era-op-background-image-position',
            'id'        => $id . '-image-position',
        ));
        $op_output  .= '</div>';


        // Gradient
        $hidde_class = ( $value_type != 'gradient' ) ? 'hidden' : '';
        $op_output  .= '<div class="era-op-bg-type-gradient '.$hidde_class.'">';
        $op_output  .= self::select(array(
            'options'   => array(
                __('Left to Right','era_fw') => 'left',
                __('Right to Left','era_fw') => 'right',
                __('Top to Bottom','era_fw') => 'top',
                __('Bottom to Top','era_fw') => 'Bottom'
            ),
            'value'     => $value_gradient_dir,
            'atts'      => $atts_gradient_dir,
            'class'     => 'era-op-gradient-dir',
            'id'        => $id . '-gradient-dir',
        ));
        $op_output  .= self::colorpicker(array(
            'value'     => $value_gradient_color1,
            'atts'      => $atts_gradient_color1,
            'class'     => 'era-op-gradient-color1',
            'id'        => $id . '-gradient-color1',
        ));
        $op_output  .= self::colorpicker(array(
            'value'     => $value_gradient_color2,
            'atts'      => $atts_gradient_color2,
            'class'     => 'era-op-gradient-color2',
            'id'        => $id . '-gradient-color2',
        ));
        $op_output  .= '</div>';


        $op_output  .= '</section>';

        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));


    }// End Func



    /**
     * Border Helper
     * @since 1.0
     * @param settings array : input settings
    */
    static function border( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            'value_size'        => '0px',   // size
            'atts_size'         => array(),     // attributes
            'value_color'       => '#000',  // color
            'atts_color'        => array(),     // attributes
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'atts'              => array(), // HTML Attributes
            'container'         => false,   // Add a container to the option
            'container_class'   => '',
        )));

        $op_output   = '<section class="era-op-wrap era-op-border">';
        $op_output  .= self::input(array(
            'value'     => $value_size,
            'class'     => 'era-op-border-size',
            'id'        => $id . '-size',
            'atts'      => $atts_size,
        ));
        $op_output  .= self::colorpicker(array(
            'value'     => $value_color,
            'class'     => 'era-op-border-color',
            'id'        => $id . '-color',
            'atts'      => $atts_color,
        ));
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func


    /**
     * Box-Shadow Helper
     * @since 1.0
     * @param settings array : input settings
    */
    static function boxShadow( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help

            'value_size'        => '0px',   // size
            'value_blur'        => '0px',   // blur
            'value_color'       => '#000',  // color

            'atts'              => array(), // HTML Attributes

            'atts_size'         => array(),
            'atts_blur'         => array(),
            'atts_color'        => array(),

            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'container'         => false,   // Add a container to the option
            'container_class'   => '',
        )));

        $op_output   = '<section class="era-op-wrap era-op-boxshadow '.$class.'" '.self::attributes($atts).' >';
        $op_output  .= self::input(array(
            'value'     => $value_size,
            'class'     => 'era-op-boxshadow-size',
            'id'        => $id . '-size',
            'atts'      => $atts_size,
        ));
        $op_output  .= self::input(array(
            'value'     => $value_blur,
            'class'     => 'era-op-boxshadow-blur',
            'id'        => $id . '-blur',
            'atts'      => $atts_blur,
        ));
        $op_output  .= self::colorpicker(array(
            'value'     => $value_color,
            'class'     => 'era-op-boxshadow-color',
            'id'        => $id . '-color',
            'atts'      => $atts_color,
        ));
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func


    /**
     * BorderRadius Helper
     * @since 1.0
     * @param settings array : input settings
    */
    static function borderRadius( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',      // id/name
            'title'             => '',      // Title
            'desc'              => '',      // Description or Help
            
            'value_topLeft'     => '0px',   //
            'value_topRight'    => '0px',   //
            'value_bottomLeft'  => '0px',   //
            'value_bottomRight' => '0px',   //

            'atts'              => array(), // HTML Attributes

            'atts_topRight'     => array(),
            'atts_topLeft'      => array(),
            'atts_bottomLeft'   => array(),
            'atts_bottomRight'  => array(),
            
            'class'             => '',      // css class
            'before'            => '',      // html before
            'after'             => '',      // html after
            'container'         => false,   // Add a container to the option
            'container_class'   => '',
        )));

        $op_output   = '<section class="era-op-wrap era-op-borderradius '.$class.'" '.self::attributes($atts).' >';
        $op_output  .= self::input(array(
            'value'     => $value_topLeft,
            'class'     => 'era-op-borderRadius-topLeft',
            'id'        => $id . '-topLeft',
            'atts'      => $atts_topLeft
        ));
        $op_output  .= self::input(array(
            'value'     => $value_topRight,
            'class'     => 'era-op-borderRadius-topRight',
            'id'        => $id . '-topRight',
            'atts'      => $atts_topRight
        ));
        $op_output  .= self::input(array(
            'value'     => $value_bottomLeft,
            'class'     => 'era-op-borderRadius-bottomLeft',
            'id'        => $id . '-bottomLeft',
            'atts'      => $atts_bottomLeft
        ));
        $op_output  .= self::input(array(
            'value'     => $value_bottomRight,
            'class'     => 'era-op-borderRadius-bottomRight',
            'id'        => $id . '-bottomRight',
            'atts'      => $atts_bottomRight
        ));
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'             => $desc,
            'op_output'         => $op_output
        ));

    }// End Func



    /**
     * BorderRadius Helper
     * @since 1.0
     * @param settings array : input settings
    */
    static function font( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                => '',                  // id/name
            'title'             => '',                  // Title
            'desc'              => '',                  // Description or Help

            'value_fontFamily'  => 'Arial, sans-serif', // Default Font Family
            'value_fontSize'    => '14px',              // Font Size
            'value_fontStyle'   => 'Normal',            // Font Style
            'value_fontColor'   => '#ffffff',           // Font Color

            'atts_fontFamily'   => array(),             // Attributes
            'atts_fontSize'     => array(),             // Attributes
            'atts_fontStyle'    => array(),             // Attributes
            'atts_fontColor'    => array(),             // Attributes

            'atts'              => array(),             // HTML Attributes

            'class'             => '',                  // css class
            'before'            => '',                  // html before
            'after'             => '',                  // html after
            'container'         => false,               // Add a container to the option
            'container_class'   => '',                  // Container Class
        )));

        $op_output   = '<section class="era-op-wrap era-op-font '.$class.'" '.self::attributes($atts).' >';
        $op_output  .= self::select(array(
            'value'     => $value_fontFamily,
            'class'     => 'era-op-fontFamily',
            'id'        => $id . '-fontFamily',
            'atts'      => $atts_fontFamily,
            'options'   => era_fonts::fonts_list(),
        ));
        $op_output  .= self::select(array(
            'value'     => $value_fontSize,
            'class'     => 'era-op-fontSize',
            'id'        => $id . '-fontSize',
            'atts'      => $atts_fontSize,
            'options'   => era_fonts::sizes_list(),
        ));
        $op_output  .= self::select(array(
            'value'     => $value_fontStyle,
            'class'     => 'era-op-fontStyle',
            'id'        => $id . '-fontStyle',
            'atts'      => $atts_fontStyle,
            'options'   => era_fonts::style_list(),
        ));
        $op_output  .= self::colorpicker(array(
            'value'     => $value_fontColor,
            'class'     => 'era-op-fontColor',
            'id'        => $id . '-fontColor',
            'atts'      => $atts_fontColor,
        ));
        $op_output  .= '</section>';

        // Container
        return self::container(array(
            'container'         => $container,
            'container_class'   => $container_class,
            'title'             => $title,
            'desc'              => $desc,
            'op_output'         => $op_output
        ));

    }// End Func



    /**
    *
    * Return a box
    * @since 1.0.0
    *
    * @param settings array : input settings
    *
    */
    static function box( $config = array() ){

        // Default Settings
        extract( wp_parse_args( $config, array(
            'id'                    => '',      // id/name
            'class'                 => '',      // css class
            'attr'                  => '',      // html attributes
            'content'               => '',      // content
            'header_content'        => '',      // footer content
        )));

        $header_content = ( $header_content != '' ) ? '<header>'.$header_content.'</header>' : '' ;

        $html_wrap  = '<div class="era-shadow">';
        $html_wrap .= '<div id="'.$id.'" class="era-box '.$class.'" '.$attr.' >';
        $html_wrap .= $header_content;
        $html_wrap .= '<section>'.$content.'</section>';
        $html_wrap .= '</div>';
        $html_wrap .= '</div>';

        return $html_wrap;

    }// End Func


}// End Class Helpers
}// End IF