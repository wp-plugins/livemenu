<?php

/**
 * Header
**/
$box_config['header_output'] .= '<span class="era-btn lm-btn lm-btn-blue lm-btn-drag"><span>' . __('Move','era_fw') . '</span></span>';

$box_config['header_output'] .= era_helpers_options::button(array(
   'title'  => __('Close','era_fw'),
   'class'  => 'lm-btn lm-btn-red lm-btn-cancel'
));


/**
 * Content
**/
$box_config['content_output'] .= '<div class="lm-box-content-page">';

   // Get Pages
   $box_config['content_output'] .= lm_live_get_all_pages();

   // Custom Link
   $box_config['content_output'] .= lm_live_get_custom_link();

   // Get Taxonomies
   $box_config['content_output'] .= lm_live_get_all_taxonomies();

$box_config['content_output']  .= '</div><!--lm-box-content-page-->';


/**
 * Get all Pages
 * @since 2.0
**/
function lm_live_get_all_pages(){

   // Get All Pages
   $query_args = array(
      'post_type'         => 'page',
      'posts_per_page'    =>  -1,
   );

   // Restore original Post Data
   wp_reset_postdata();

   // The Loop
   $the_query     = new WP_Query( $query_args );
   $posts_output  = '';

   if( $the_query->have_posts() ) :

      $posts_output .= '<div class="lm-live-items-box" >';
      $posts_output .= '<h3>'.__('Pages','era_fw').'<span class="era-icon era-icon-triangle-down"></span></h3>';
      $posts_output .= '<div class="lm-live-items-box-content" data-type="post_type">';
      
      $posts_output .= '<select>';

      $lm_counter = 1;
      while ( $the_query->have_posts() ) :
         
         $the_query->the_post();

         $post_id    = get_the_ID();
         $post_title = get_the_title();
         $post_url   = get_post_permalink( $post_id );
          
         $posts_output .= '<option value="'.$post_id.'" data-guid="'.$post_url.'" data-object="page" >'.$post_title.'</option>';

         $lm_counter += 1;

      endwhile;

      $posts_output .= '</select>';

      $posts_output .= '<button class="lm-btn lm-btn-blue lm-live-add-btn">'.__('Add to Menu','era_fw').'</button>';

      $posts_output .= '</div></div>';

   endif;

   // Restore original Post Data
   wp_reset_postdata();

   return $posts_output;
}


/**
 * Get Custom Link
 * @since 2.0
**/
function lm_live_get_custom_link(){

   $output = '<div class="lm-live-items-box" >';
   $output .= '<h3>'.__('Custom Link','era_fw').'<span class="era-icon era-icon-triangle-down"></span></h3>';
   $output .= '<div class="lm-live-items-box-content" data-type="custom">';


   $output .= '
   <p>
      <label class="howto" for="lm-live-custom-menu-item-url">
         <span>'.__('URL','era_fw').'</span>
         <input id="lm-live-custom-menu-item-url" type="text" value="http://" />
      </label>
   </p>';

   $output .= '
   <p>
      <label class="howto" for="lm-live-custom-menu-item-text">
         <span>'.__('Link Text','era_fw').'</span>
         <input id="lm-live-custom-menu-item-text" type="text" value="" />
      </label>
   </p>';

   $output .= '<button class="lm-btn lm-btn-blue lm-live-add-btn">'.__('Add to Menu','era_fw').'</button>';

   $output .= '</div></div>';

   return $output;
}


/**
 * Get All Taxonomies
 * @since 2.0
**/
function lm_live_get_all_taxonomies(){

   // Get Taxonomies
   $taxonomies = get_taxonomies( array(), 'object' );

   if ( !$taxonomies )
      return '';

   $output = '';

   // remove these taxonomies
   $removeKeys = array('post_tag', 'nav_menu', 'link_category', 'post_format');
   foreach( $removeKeys as $key ){
      unset($taxonomies[$key]);
   }

   // add taxonomies   
   foreach( $taxonomies as $tax ){

      $output .= '<div class="lm-live-items-box" >';
      $output .= '<h3>'.$tax->labels->name.'<span class="era-icon era-icon-triangle-down"></span></h3>';
      $output .= '<div class="lm-live-items-box-content" data-type="taxonomy">';
      
      $output .= '<select>';
      $categories = get_categories(array('taxonomy'=>$tax->name));
      foreach ($categories as $category) {

         $cat_url = get_term_link( $category->term_id );
         if( is_wp_error($cat_url) ) $cat_url = '';

         $output .= '<option value="'.$category->term_id.'" data-guid="'.$cat_url.'" data-object="'.$tax->name.'">';
         $output .= $category->cat_name;
         $output .= '</option>';
      }
      $output .= '</select>';

      $output .= '<button class="lm-btn lm-btn-blue lm-live-add-btn">'.__('Add to Menu','era_fw').'</button>';
      $output .= '</div></div>';
   
   }

   return $output;
}