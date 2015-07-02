<?php

if( !class_exists('era_helper_post_type_taxonomy') ){

    /**
     * Return Categories on a "Select <select>", "List <ul>", "select box"
     * @package CTFramework
     * @since Version 1.0.0
     * 
     */
    class era_helper_post_type_taxonomy {

        /**
         * Return All Post Types / Categories on a "Select <select>"
         * @since Version 1.0
         * @package ERA FRAMEWORK
         */
        static function get_select( $config ){
            
            // Default Settings
            extract( wp_parse_args( $config, array(
                'id'                => '',      // id/name
                'class'             => '',      // html class
                'options_selected'  => array(), // selected options
                'multiple'          => false,   // multiple select
                'echo'              => false,   // return or echo the result
                'attr'              => '',      // add html attributes
            )));

            // get All taxonomies/post_types
            $all_post_types = get_post_types( '' , 'names' );

            // exclude these default post types
            $not_needed = array( 'attachment', 'revision', 'nav_menu_item' );
            $all_post_types = array_diff( $all_post_types, $not_needed );

            // Prepare the select
            $isMultipe = ( $multiple ) ? ' multiple ' : '';
            $html  = '<select id="'.$id.'" class="'.$class.'" name="'.$id.'" '.$attr.' '.$isMultipe.' >';
            
            foreach ( $all_post_types as $post_type ) {
                
                // Get Post type name
                $post_type_name = get_post_type_object( $post_type );
                $post_type_name = $post_type_name->labels->singular_name;

                $html .= '<optgroup label="'.$post_type_name.'">';

                $isSelected = ( in_array( $post_type, $options_selected ) ) ? ' selected="selected" ' : '';
                $html .= '<option data-type="post_type" value="'.$post_type.'" '.$isSelected.' >['.__('Post Type','era_fw').'] '.$post_type_name.'</option>';

                // Get all the taxonomies of this post_type
                $all_taxnonomies = get_object_taxonomies( $post_type );

                // delete not needed taxonomies
                $all_taxnonomies = array_diff( $all_taxnonomies, array('post_tag') );

                // Get all the categories of each taxonomy
                foreach( $all_taxnonomies as $taxo ){
                    $args = array(
                        'era_options_selected' => $options_selected, // pass the selected options to the args
                        'hide_empty'    => 1,
                        'hierarchical'  => 1,
                        'show_count'    => 0,
                        'orderby'       => 'name',
                        'pad_counts'    => 0,
                        'echo'          => 0,
                        'taxonomy'      => $taxo,
                        'title_li'      => '',
                        'style'         => 'none',
                        'walker'        => new era_walker_list_categories_as_select_options()
                    );

                    $html .= wp_list_categories($args);
                }

                $html .= '</optgroup>';
            
            }
            $html .= '</select>';
            
            if( $echo )
                echo $html;
            else
                return $html;
        }

    }// End Class

}


if( !class_exists('era_walker_list_categories_as_select_options') )
{
    /**
     * Create HTML '<select>' of categories.
     *
     * @package WordPress
     * @since 2.1.0
     * @uses Walker
     */
    class era_walker_list_categories_as_select_options extends Walker_Category {

        /** walker var **/
        var $tree_type = 'category';
        var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

        function start_lvl( &$output, $depth = 0, $args = array() ) {}
        function end_lvl( &$output, $depth = 0, $args = array() ) {}


        /**
         * @see Walker::start_el()
         * @since 2.1.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $category Category data object.
         * @param int $depth Depth of category in reference to parents.
         * @param array $args
         */
        function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
            
            // needed to get the selected options
            $options_selected = $args['era_options_selected'];

            // Category Name
            $cat_name = esc_attr( $category->name );
            $cat_name = apply_filters( 'list_cats', $cat_name, $category );

            // Prepare Select "<option>"
            $indent = '';
            if ( $category->category_parent == 0 ){
                $indent = '&nbsp;&nbsp;&nbsp;';
            }
            else{
                $indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            }

            // get category url
            $cat_url = get_term_link( $category );

            // is the category selected or not 
            $iSselected = ( in_array( $category->cat_ID, $options_selected ) ) ? ' selected="selected" ' : '';
    
            $output .= '<option data-type="category" data-taxonomy="'.$category->taxonomy.'" data-cat_url = "'.$cat_url.'" value="'.$category->cat_ID.'" '.$iSselected.' >';
            $output .= $indent.$cat_name . '</option>';
            
        }


        /**
         * @see Walker::end_el()
         * @since 2.1.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $page Not used.
         * @param int $depth Depth of category. Not used.
         * @param array $args Only uses 'list' for whether should append to output.
         */
        function end_el( &$output, $page, $depth = 0, $args = array() ) {
            $output .= "";
        }

    }// End Class
}