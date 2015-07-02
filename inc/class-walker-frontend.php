<?php

/**
 * Create HTML list of nav menu items.
 *
 * @since 3.0.0
 * @uses Walker
 */
class livemenu_frontend_walker extends Walker {


   /* livemenu vars */
   public $lm_db_config          = array();
   public $lm_submenu_type       = '';
   public $lm_submenu_style      = '';
   public $lm_column_type        = '';
   public $lm_menu_id            = '';
   public $lm_dropdown_position  = '';
   public $lm_dropdown_width     = '';
   public $first_part_of_loop    = true;

   /* What the class handles */
   public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

   /*  Database fields to use */
   public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );


   /**
    * Constructor
   **/
   public function __construct( $DBconfig, $menu_id ) {
      $this->lm_db_config = $DBconfig;
      $this->lm_menu_id = $menu_id;
   }


   /**
    * Starts the list before the elements are added.
   **/
   public function start_lvl( &$output, $depth = 0, $args = array() ) {

      /** Dropdowns **/
      if( $this->lm_submenu_type === 'dropdowns' ){
         $output .= '<ul class="lm-sub lm-sub-dropdown-position-'.$this->lm_dropdown_position.'" style=" width:'.$this->lm_dropdown_width.'; '.$this->lm_dropdown_position.':'.$this->lm_dropdown_width.'; ">';
      }

   }//End Func


   /**
    * Ends the list of after the elements are added
    */
   public function end_lvl( &$output, $depth = 0, $args = array() ) {

      /** Dropdowns **/
      if( $this->lm_submenu_type === 'dropdowns' ){
         $output .= "</ul>";
      }

   }


   /**
    * Start the element output.
   **/
   public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

      // Item Meta Options
      $item_meta = get_post_meta( $item->ID );

      // submenu type
      if( $depth == 0 ){
         $this->lm_submenu_type = ( isset( $item_meta['lm-op-submenu'][0] ) ) ? $item_meta['lm-op-submenu'][0] : 'dropdowns' ;
      }

      // get the link html
      $item_classes = $this->lm_get_item_class( $item, $item_meta, $depth );
      $item_link    = $this->lm_get_item_link( $item, $item_meta, $depth );


      // Set Logo
      if( $this->first_part_of_loop ){

         $this->first_part_of_loop = false;

         if( ! is_null( $this->lm_db_config['settings']['lm-op-layoutAndLogo'] )
            && $this->lm_db_config['settings']['lm-op-layoutAndLogo'] != 'no-logo'
            && $this->lm_db_config['settings']['lm-op-logo'] != ''
         ){

            // Horizontal Logo
            $output .= '<li class="lm-logo lm-'.$this->lm_db_config['settings']['lm-op-layoutAndLogo'].'">';
            $output .=     '<a href="'.get_bloginfo('url').'"><img src="'.$this->lm_db_config['settings']['lm-op-logo'].'" alt="" ></a>';
            $output .= '</li>';

         }

      }


      // Depth 0
      if( $depth === 0 ){

         /** DropDowns Type **/
         if( $this->lm_submenu_type === 'dropdowns' ){

            $this->lm_dropdown_width     = ( isset($item_meta['lm-op-submenu-width'][0]) ) ? (int)$item_meta['lm-op-submenu-width'][0] . 'px' : '190px' ;
            $this->lm_dropdown_position  = ( isset($item_meta['lm-op-submenu-position'][0]) ) ? $item_meta['lm-op-submenu-position'][0] : 'left' ;

            $output .= '<li class="lm-item lm-item-dropdowns '.$item_classes.'" id="lm-item-'.$item->ID.'" >';
            $output .= $item_link;

         }// End IF


         /** Mega Type **/
         elseif( $this->lm_submenu_type === 'mega' ){

            $fullWidth = ( isset($item_meta['lm-op-submenu-size'][0]) ) ? $item_meta['lm-op-submenu-size'][0] : 'full_Width';
            $width     = ( isset($item_meta['lm-op-submenu-width'][0]) ) ? (int)$item_meta['lm-op-submenu-width'][0] . 'px' : '300px' ;
            $position  = ( isset($item_meta['lm-op-submenu-position'][0]) ) ? $item_meta['lm-op-submenu-position'][0] : 'left' ;

            // FullWidth or Custom Width
            if( $fullWidth == 'full_Width' ){

               $item_position = 'style=" position:static; "';
               $sub_style     = 'style=" width:100%; left:0; right:auto; "';

            }
            else{
               // Center submenu
               if( $position == 'center' ){

                  $item_position = 'style=" position:relative; "';
                  $sub_style     = 'style=" width:'.$width.'; left:50%; margin-left:-'.( (int)$width / 2 ).'px; right:auto; "';

               }
               else if( $position == 'left' ){

                  $item_position = 'style=" position:relative; "';
                  $sub_style     = 'style=" width:'.$width.'; left:0; right:auto; "';

               }
               else{

                  $item_position = 'style=" position:relative; "';
                  $sub_style     = 'style=" width:'.$width.'; left:auto; right:0; "';

               }
            }

            $output .= '<li class="lm-item lm-item-mega '.$item_classes.'" id="lm-item-'.$item->ID.'" '.$item_position.' >';
            $output .= $item_link;
            $output .= '<ul class="lm-sub" '.$sub_style.' >';

         }// End IF

      }// End IF


      // Depth > 0
      else{

         /** DropDowns **/
         if( $this->lm_submenu_type === 'dropdowns' ){

            $output .= '<li class="lm-sub-item '.$item_classes.'" id="lm-item-'.$item->ID.'" >';
            $output .= $item_link;

         }// End IF


         /** Mega **/
         if( $this->lm_submenu_type === 'mega' && $depth === 1 ){

            // get column type
            $this->lm_column_type = isset( $item_meta['lm-op-column-type'][0] ) ? $item_meta['lm-op-column-type'][0] : 'column_links';

            // column size
            $column_size = isset( $item_meta['lm-op-column-size'][0] ) ? $item_meta['lm-op-column-size'][0] : 'lm-col-25';

            /** Shortcodes & HTML **/
            if( $this->lm_column_type == 'column_shortcodes' ){

               $output .= '<li class="lm-sub-item '.$item_classes.' lm-col lm-col-shortcodes '.$column_size.'" id="lm-item-'.$item->ID.'" >';
               $output .= $item_link;
               $output .= '<div class="lm-column-content">'.do_shortcode( @$item_meta['lm-op-shortcodes-content'][0] ).'</div>';
               $output .= '</li>';

            }

            /** Widgets **/
            if( $this->lm_column_type == 'column_widgets' ){

               $widget = $item_meta['lm-op-widget'][0];

               if( $widget == 'recent_posts' ){
                  $tmp_content = $this->lm_widget_recent_posts( $item_meta['lm-op-widget-recent-posts-number'][0], 'post' );
               }
               elseif( $widget == 'recent_pages' ){
                  $tmp_content = $this->lm_widget_recent_posts( $item_meta['lm-op-widget-recent-posts-number'][0], 'page' );
               }
               elseif( $widget == 'recent_comments' ){
                  $tmp_content = $this->lm_widget_recent_comments( $item_meta['lm-op-widget-recent-comments-number'][0] );
               }
               elseif( $widget == 'categories' ){
                  $tmp_content = $this->lm_widget_categories( $item_meta['lm-op-widget-categories-number'][0] );
               }
               elseif( $widget == 'tags' ){
                  $tmp_content = $this->lm_widget_tags();
               }
               elseif( $widget == 'search' ){
                  $tmp_content = $this->lm_widget_search( $item_meta['lm-op-widget-search-text'][0] );
               }

               $output .= '<li class="lm-sub-item '.$item_classes.' lm-col lm-col-widgets lm-col-widgets-'.$widget.' '.$column_size.'" id="lm-item-'.$item->ID.'" >';
               $output .= $item_link;
               $output .= '<ul class="lm-column-content">'.$tmp_content.'</ul>';
               $output .= '</li>';

            }

            /** Links **/
            if( $this->lm_column_type == 'column_links' ){

               $output .= '<li class="lm-sub-item '.$item_classes.' lm-col lm-col-links '.$column_size.'" id="lm-item-'.$item->ID.'" >';
               $output .= $item_link;
               $output .= '<ul class="lm-column-content">';

            }

         }

         /* column_links & depth > 1 */
         if( $this->lm_submenu_type === 'mega' && $depth > 1 && $this->lm_column_type == 'column_links' ){

            $output .= '<li class="lm-sub-item lm-sub-item-of-links '.$item_classes.'" id="lm-item-'.$item->ID.'" >';
            $output .= $item_link;
            $output .= '</li>';

         }

      }// End Else

   }// End Func


   /**
    * Ends the element output, if needed.
   **/
   public function end_el( &$output, $item, $depth = 0, $args = array() ) {

      /* Dropdowns */
      if( $this->lm_submenu_type == 'dropdowns' ){
         $output .= "</li>";
      }

      /* Mega */
      if( $this->lm_submenu_type == 'mega' ){

         /* Shortcodes or Widgets */
         if( $depth == 0 ){
            $output .= "</ul></li>";
         }
         /* Links */
         if( $depth == 1 && $this->lm_column_type == 'column_links' ){
            $output .= "</ul></li>";
         }
      }

   }//End Func


   /**
    * Prepare Item HTML Class
    * @since 1.0
    * @return a html class
   **/
   function lm_get_item_class( $item, $item_meta, $depth ){

      /*** Prepare item classes ***/
      $item_class_names = 'lm-item-depth-'.$depth;

      // Current Item( current page )
      if( in_array('current-menu-item', $item->classes) ){
         $item_class_names .= ' lm-item-current';
      }

      // Item Has Submenu or not
      if( in_array('menu-item-has-children', $item->classes)
         || in_array( @$item_meta['lm-op-submenu'][0], array('posts','mega') )
      ){
         $item_class_names .= ' lm-has-submenu';
      }

      // IF this is the parent of a current link
      if( in_array('current-menu-ancestor', $item->classes) ){
         $item_class_names .= ' lm-item-current-ancestor';
      }

      // Top Links Positions
      if( $depth == 0 ){
         $position = isset( $item_meta['lm-op-position'][0] ) ? $item_meta['lm-op-position'][0] : 'left';
         $item_class_names .= ' lm-item-position-'.$position;
      }

      // Social media link
      if( @$item_meta['lm-op-social-item'][0] == 'on' ){
         $item_class_names .= ' lm-item-socialmedia';
      }

      // Disable Text
      if( @$item_meta['lm-op-disable-text'][0] == 'on' ){
         $item_class_names .= ' lm-item-disable-text';
      }

      return $item_class_names;
   }


   /**
    * Prepare Link
    *
    * @since 1.0
    * @return a html link
   **/
   function lm_get_item_link( $item, $item_meta, $depth ){

      /*** Title ***/
      $description = ( isset( $item->description ) && $item->description != '' ) ? '<span class="lm-item-desc">'.$item->description.'</span>' : '';
      $item_title = '<span class="lm-item-title">'.$item->title.$description.'</span>';

      /*** prepare the link attributes ***/
      $atts_output  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
      $atts_output .= ! empty( $item->target )     ? ' target="_blank"' : '';
      $atts_output .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn ) .'"' : '';
      $atts_output .= ! empty( $item->url )        ? ' href="'   . esc_url( $item->url ) .'"' : '';

      /*** Arrows ***/
      // Item Has Submenu or not
      $arrows_output    = '';
      $responsive_arrow = '';
      if( in_array('menu-item-has-children', $item->classes)
         || in_array( @$item_meta['lm-op-submenu'][0], array('posts','mega') )
      ){
         $arrows_output = ' lm-arrow-down ';
         if( $depth > 0 ){
            $arrows_output = ' lm-arrow-right ';
         }
         $responsive_arrow = '<span class="lm-responsive-links-arrow era-icon lm-arrow-down"></span>';
      }



      /*** all together ***/
      $item_output  = '<a '. $atts_output .'>';
      $item_output .= '<div class="era-icon '.$arrows_output.@$item_meta['lm-op-icon'][0].'">'.$item_title.$responsive_arrow.'</div>';
      $item_output .= '</a>';

      return $item_output;

   }// End Func


   /**
    * Widget : Recent Posts
    * @since 1.0
   **/
   function lm_widget_recent_posts( $number, $type ){

      global $wp_query;

      // Restore original Post Data
      wp_reset_postdata();

      // name this query_args to prevent a conflict with $args of the function
      $query_args = array(
         'suppress_filters'  => true, // WPML Support
         'post_type'         => $type,
         'posts_per_page'    => $number,
      );
      $the_query = new WP_Query( $query_args );

      // The Loop
      if( $the_query->have_posts() ) :

         $posts_output = '';
         while ( $the_query->have_posts() ) :
            $the_query->the_post();

            $post_id       = get_the_ID();
            $post_title    = get_the_title();
            $post_url      = get_post_permalink();
            $post_excerpt  = get_the_excerpt();
            $thumb_size    = array( 64, 64);

            $posts_output .= '<li class="lm-sub-item"><a href="'.$post_url.'"><div class="era-icon">';
            $posts_output .= '<span class="lm-item-title">'.era_images_manager::get_image( $post_id, $thumb_size, 'class="lm-lazy-load" alt="'.$post_title.'"', true /* true for lazy load */ );
            $posts_output .= $post_title.'<span class="lm-item-desc">'.$this->lm_the_excerpt_dynamic( $post_excerpt, 30 ).'</span></span>';
            $posts_output .= '</div></a></li>';

         endwhile;
         $posts_output .= '';

      endif;

      // Restore original Post Data
      wp_reset_postdata();

      return $posts_output;

   }// End Func


   /**
    * Widget : Recent Comments
    * @since 1.0
   **/
   function lm_widget_recent_comments( $number ){

      $comments_output = '';
      $comments = get_comments(array(
         'status' => 'approve',
         'number' => $number,
      ));

      foreach($comments as $comment) :
         $avatar           = get_avatar( $comment->user_id, 64 );
         $comment_url      = get_post_permalink( $comment->comment_post_ID );

         $comment_content  = $this->lm_the_excerpt_dynamic( $comment->comment_content, 30 );

         $comments_output .= '<li class="lm-sub-item"><a href="'.$comment_url.'"><div class="era-icon">';
         $comments_output .= '<span class="lm-item-title">' . $avatar . $comment->comment_author . '<span class="lm-item-desc">' . $comment_content . '</span></span>';
         $comments_output .= '</div></a></li>';
      endforeach;

      return $comments_output;

   }// End Func


   /**
    * Widget : Get Categories
    * @since 1.0
   **/
   function lm_widget_categories( $number ){

      $categories_output = '';

      $args = array(
         'type'         => 'post',
         'hierarchical' => 0,
         'number'       => $number,
      );
      $categories = get_categories( $args );

      foreach ($categories as $category) {

         $cat_link = get_category_link( $category->term_id );
         $cat_name = $category->name;
         $cat_desc = ( isset($category->description) && $category->description != '' ) ? '<span class="lm-item-desc"></span>' : '' ;

         $categories_output .= '<li class="lm-sub-item"><a href="'.$cat_link.'"><div class="era-icon">';
         $categories_output .= '<span class="lm-item-title">'.$cat_name.$cat_desc.'</span>';
         $categories_output .= '</div></a></li>';

      }

      return $categories_output;

   }// End Func


   /**
    * Widget : Get Tags
    * @since 1.0
   **/
   function lm_widget_tags(){

      $tags_output = '';

      $tags = get_tags();
      foreach ( $tags as $tag ) {
         $tag_link = get_tag_link( $tag->term_id );

         $tags_output .= "<a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
         $tags_output .= "{$tag->name}</a>";
      }

      return $tags_output;

   }// End Func


   /**
    * Widget : Search
    * @since 1.0
   **/
   function lm_widget_search( $placeholder ){

      return '
         <form role="search" method="get" class="livemenu-searchform" action="'.home_url( '/' ).'">
            <input type="text" placeholder="'.$placeholder.'" value="" name="s" class="livemenu-searchinput" />
            <button type="submit" class="livemenu-searchsubmit era-icon era-icon-magnifying-glass" ></button>
         </form>
      ';

   }// End Func


   /**
    * Return an excerpt of variable length (in characters)
    * @since 1.0
   **/
   function lm_the_excerpt_dynamic( $text, $length ){

      if( $text == '' ) return '';

      $text = apply_filters('the_content', $text);
      $text = str_replace(']]>', ']]>', $text);
      $text = strip_shortcodes( $text );
      $text = strip_tags($text);
      $text = substr($text,0,$length).' [...]';

      return apply_filters('the_excerpt',$text);

   }// End Func


} // Walker_Nav_Menu