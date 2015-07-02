<?php
/**
 * Admin : menu item options
 * @since 1.0
**/


/** Header Buttons **/
$box_config['header_output'] .= '<span class="era-btn lm-btn lm-btn-blue lm-btn-drag"><span>' . __('Move','era_fw') . '</span></span>';

$box_config['header_output'] .= era_helpers_options::button(array(
   'title'  => __('Close','era_fw') ,
   'class'  => 'lm-btn lm-btn-red lm-btn-cancel'
));
$box_config['header_output'] .= era_helpers_options::button(array(
   'title'  => '<span>' . __('Save','era_fw') . '</span>',
   'class'  => 'lm-btn lm-btn-green lm-btn-editbox-save'
));



/** Navigation **/
$nav_array 	= array(
	__('General','era_fw') 			=> 'general',
	__('Type','era_fw') 			=> 'submenu',
	__('Column','era_fw') 			=> 'column',
	__('Widgets','era_fw')			=> 'column_widgets',
	__('Shortcodes & HTML','era_fw')=> 'column_shortcodes',
);

$nav_output	= '<ul class="lm-item-box-nav">';
foreach( $nav_array as $name => $id ){
	$style = ( $item_type == $id ) ? 'style="display:block;"' : '';
	$nav_output .= '<li class="lm-item-box-nav-'.$id.'" '.$style.'>'.$name.'</li>';
}
$nav_output .= '</ul><!--lm_box_nav-->';



/** Page :: General **/
$content_output  = '<div class="lm-item-box-page lm-item-box-page-general" >';

	// Position
	$content_output .= '<div class="lm-item-op-content lm-item-op-content-item-position">';

		$content_output .= era_helpers_options::img_select(array(
			'title'  	=> __('Position','era_fw'),
			'desc'  	=> __('Set this link position','era_fw'),
			'class'  	=> 'lm-op',
			'id'     	=> 'lm-op-position',
			'options'	=> array(
				'left'	=> array(
					'name' 	=> __('Left','era_fw'),
					'img' 	=>LM_BASE_URL.'assets/img/item-left.png'
				),
				'right'  => array(
					'name' 	=> __('Right','era_fw'),
					'img' 	=>LM_BASE_URL.'assets/img/item-right.png'
				)
			),
			'value'           => @$item_meta['lm-op-position'][0],
			'container'       => true,
			'container_class' => 'lm-op-wrap lm-op-position-wrap'
		));

	$content_output .= '</div><!--lm-item-op-content-->';


	// Icon
	$content_output .= '<div class="lm-item-op-content lm-item-op-content-fontIcon">';

		$tmp_content = era_helpers_options::button(array(
			'class'  => 'lm-btn lm-btn-green lm-btn-open-icons-box',
			'title'  => __('Select Icon','era_fw'),
		));

		$tmp_content .= era_helpers_options::button(array(
			'class'  => 'lm-btn lm-btn-red lm-btn-remove-icon',
			'title'  => __('Remove Icon','era_fw'),
		));

		$tmp_content .= '<span id="lm-icon-placeholder"><span class="era-icon '.@$item_meta['lm-op-icon'][0].'"></span></span>';

		$content_output .= era_helpers_options::input(array(
			'title'           	=> __('Icon','era_fw'),
			'desc'           	=> __('Add a nice icon to this link','era_fw'),
			'type'	         	=> 'hidden',
			'class' 	        => 'lm-op ',
			'id'		        => 'lm-op-icon',
			'value'	         	=> @$item_meta['lm-op-icon'][0],
			'container'      	=> true,
			'container_class' 	=> 'lm-op-wrap',
			'after'           	=> $tmp_content,
		));

		// Disable Text
		$content_output .= era_helpers_options::checkbox(array(
			'title'           	=> __('Disable Text','era_fw'),
			'desc'           	=> __('Display only icon','era_fw'),
			'class'           	=> 'lm-op',
			'id'              	=> 'lm-op-disable-text',
			'value'           	=> @$item_meta['lm-op-disable-text'][0],
			'container'       	=> true,
			'container_class' 	=> 'lm-op-wrap'
		));

		// Social Media
		$content_output .= era_helpers_options::checkbox(array(
			'title'           	=> __('Use as Social Media','era_fw'),
			'desc'           	=> __('This option help detect your social media links','era_fw'),
			'class'           	=> 'lm-op',
			'id'              	=> 'lm-op-social-item',
			'value'           	=> @$item_meta['lm-op-social-item'][0],
			'container'       	=> true,
			'container_class' 	=> 'lm-op-wrap'
		));

	$content_output .= '</div><!--lm-item-op-content-->';

$content_output .= '</div><!--page-->';


// Depth 0
if( $item_depth == 0 ){


	/** Page : Submenu **/
	$content_output  .= '<div class="lm-item-box-page lm-item-box-page-submenu" >';

		// type
		$content_output .= '<div class="lm-item-op-content">';

			$content_output .= era_helpers_options::img_select(array(
				'title' 	=> __('Type','era_fw'),
				'desc' 		=> __('Set this menu item type','era_fw'),
				'class' 	=> 'lm-op',
				'id'		=> 'lm-op-submenu',
				'options'=> array(
					'dropdowns' => array(
						'name' 	=> __('Dropdowns','era_fw'),
						'img' 	=> LM_BASE_URL.'assets/img/sub-dropdown.png'
					),
					'mega' => array(
						'name' => __('Mega','era_fw'),
						'img'  => LM_BASE_URL.'assets/img/sub-widget.png'
					),
				),
				'value'	           => @$item_meta['lm-op-submenu'][0],
				'container_class'   => 'lm-op-wrap lm-op-submenu-wrap',
				'container'         => true,
			));

		$content_output .= '</div><!--lm-item-op-content-->';

		// Size
		$content_output .= '<div class="lm-item-op-content lm-item-op-content-submenu-size">';

			$content_output .= era_helpers_options::radio(array(
				'title' 	=> __('Submenu Size','era_fw'),
				'desc' 		=> __('Set this submenu Size','era_fw'),
				'class' 	=> 'lm-op',
				'id'		=> 'lm-op-submenu-size',

				'options'=> array(
					__('Full Width','era_fw') 	=> 'full_Width',
					__('Custom Width','era_fw')	=> 'custom_width',
				),

				'value'	           	=> @$item_meta['lm-op-submenu-size'][0],
				'container_class'   => 'lm-op-wrap lm-op-menu-type-wrap',
				'container'         => true,
			));

		$content_output .= '</div><!--lm-item-op-content-->';


		// Width & Position
		$content_output .= '<div class="lm-item-op-content lm-item-op-content-submenu-widht-and-position">';

			$content_output .= era_helpers_options::input(array(
				'title' 	=> __('Submenu Custom Width','era_fw'),
				'desc' 		=> __('Set this submenu width ','era_fw'),
				'class' 	=> 'lm-op',
				'id'		=> 'lm-op-submenu-width',
				'value'	           	=> @$item_meta['lm-op-submenu-width'][0],
				'container_class'   => 'lm-op-wrap',
				'container'         => true,
			));

			$content_output .= era_helpers_options::radio(array(
				'title' 	=> __('Submenu Position','era_fw'),
				'desc' 		=> __('Set this submenu position ( "center" will not work for dropdowns )','era_fw'),
				'class' 	=> 'lm-op',
				'id'		=> 'lm-op-submenu-position',
				'options'=> array(
					__('Left','era_fw')		=> 'left',
					__('Right','era_fw') 	=> 'right',
					__('Center','era_fw') 	=> 'center',
				),
				'value'	           	=> @$item_meta['lm-op-submenu-position'][0],
				'container_class'   => 'lm-op-wrap',
				'container'         => true,
			));

		$content_output .= '</div><!--lm-item-op-content-->';

	$content_output  .= '</div><!--Page-->';

}

// Depth 1
else if( $item_depth == 1 ){


	/** Page :: Column for submenu type :: Mega  **/
	$content_output  .= '<div class="lm-item-box-page lm-item-box-page-column" >';

		// Column Type
		$content_output .= '<div class="lm-item-op-content">';

			$content_output .= era_helpers_options::radio(array(
				'title' 	=> __('Column Type','era_fw'),
				'desc' 		=> __('Set this column type','era_fw'),
				'class' 	=> 'lm-op',
				'id'		=> 'lm-op-column-type',
				'value'	    => @$item_meta['lm-op-column-type'][0],
				'options'	=> array(
					__('Widgets','era_fw') 	=> 'column_widgets',
					__('Shortcodes & HTML') =>'column_shortcodes',
					__('Links','era_fw') 	=>'column_links',
				),
				'container_class'   => 'lm-op-wrap lm-op-column-type-wrap',
				'container'         => true,
			));
		$content_output .= '</div>';


		$content_output .= '<div class="lm-item-op-content">';

			$content_output .= era_helpers_options::radio(array(
				'title' 	=> __('Column Size','era_fw'),
				'desc' 		=> __('Set this column size','era_fw'),
				'class' 	=> 'lm-op',
				'id'		=> 'lm-op-column-size',
				'options'=> array(
					__('20%','era_fw') 	=> 'lm-col-20',
					__('25%','era_fw') 	=> 'lm-col-25',
					__('33%','era_fw') 	=> 'lm-col-33',
					__('50%','era_fw') 	=> 'lm-col-50',
					__('66%','era_fw') 	=> 'lm-col-66',
					__('75%','era_fw') 	=> 'lm-col-75',
					__('80%','era_fw') 	=> 'lm-col-80',
					__('100%','era_fw')	=> 'lm-col-100',
				),
				'value'	           	=> @$item_meta['lm-op-column-size'][0],
				'container_class'   => 'lm-op-wrap',
				'container'         => true,
			));

		$content_output .= '</div><!--lm-item-op-content-->';

	$content_output .= '</div><!--page-->';


	/** Page :: Col Widgets  **/
	$content_output  .= '<div class="lm-item-box-page lm-item-box-page-column_widgets" >';

		// Widgets Settings
		$content_output .= '<div class="lm-item-op-content">';

			$content_output .= era_helpers_options::select(array(
				'title'     => __('Select Widget','era_fw'),
				'class'     => 'lm_op',
				'id'        => 'lm-op-widget',
				'value'     => @$item_meta['lm-op-widget'][0],
				'options'   => array(
					__('Recent Posts','era_fw') 	=> 'recent_posts',
					__('Recent Pages','era_fw') 	=> 'recent_pages',
					__('Recent Comments','era_fw') 	=> 'recent_comments',
					__('Categories','era_fw') 		=> 'categories',
					__('Tag Cloud','era_fw') 		=> 'tags',
					__('Search','era_fw') 			=> 'search',
				),
				'container' => true,
				'container_class' => 'lm-op-wrap'
			));

		$content_output .= '</div><!--lm-item-op-content-->';


		// Recent Posts
		$tmp_hide = ( @$item_meta['lm-op-widget'][0] === NULL || @$item_meta['lm-op-widget'][0] == 'recent_posts' ) ? '' : 'hidden';
		$content_output .= '<div class="'.$tmp_hide.' lm-item-op-content lm-item-op-content-widget lm-item-op-content-widget-recent_posts">';

			$content_output .= era_helpers_options::select(array(
				'title'     => __('Number of posts to show','era_fw'),
				'options'   => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8,
					9 => 9,
					10 => 10,
					11 => 11,
					12 => 12,
					13 => 13,
					14 => 14,
					15 => 15,
					16 => 16,
					17 => 17,
					18 => 18,
					19 => 19,
					20 => 20,
					30 => 30,
					40 => 40,
					50 => 50,
					60 => 60,
					70 => 70,
					80 => 80,
					90 => 90,
					100 => 100,
				),
				'class'     => 'lm-op',
				'id'        => 'lm-op-widget-recent-posts-number',
				'value'     => @$item_meta['lm-op-widget-recent-posts-number'][0],
				'container' => true,
				'container_class' => 'lm-op-wrap'
			));

		$content_output .= '</div><!--lm-item-op-content-->';

		// Recent Pages
		$tmp_hide = ( @$item_meta['lm-op-widget'][0] == 'recent_pages' ) ? '' : 'hidden';
		$content_output .= '<div class="'.$tmp_hide.' lm-item-op-content lm-item-op-content-widget lm-item-op-content-widget-recent_pages">';

			$content_output .= era_helpers_options::select(array(
				'title'     => __('Number of pages to show','era_fw'),
				'options'   => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8,
					9 => 9,
					10 => 10,
					11 => 11,
					12 => 12,
					13 => 13,
					14 => 14,
					15 => 15,
					16 => 16,
					17 => 17,
					18 => 18,
					19 => 19,
					20 => 20,
					30 => 30,
					40 => 40,
					50 => 50,
					60 => 60,
					70 => 70,
					80 => 80,
					90 => 90,
					100 => 100,
				),
				'class'     => 'lm-op',
				'id'        => 'lm-op-widget-recent-pages-number',
				'value'     => @$item_meta['lm-op-widget-recent-pages-number'][0],
				'container' => true,
				'container_class' => 'lm-op-wrap'
			));

		$content_output .= '</div><!--lm-item-op-content-->';

		// Recent Comments
		$tmp_hide = ( @$item_meta['lm-op-widget'][0] == 'recent_comments' ) ? '' : 'hidden';
		$content_output .= '<div class="'.$tmp_hide.' lm-item-op-content lm-item-op-content-widget lm-item-op-content-widget-recent_comments">';

			$content_output .= era_helpers_options::select(array(
				'title'     => __('Number of comments to show','era_fw'),
				'options'   => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8,
					9 => 9,
					10 => 10,
					11 => 11,
					12 => 12,
					13 => 13,
					14 => 14,
					15 => 15,
					16 => 16,
					17 => 17,
					18 => 18,
					19 => 19,
					20 => 20,
					30 => 30,
					40 => 40,
					50 => 50,
					60 => 60,
					70 => 70,
					80 => 80,
					90 => 90,
					100 => 100,
				),
				'class'     => 'lm-op',
				'id'        => 'lm-op-widget-recent-comments-number',
				'value'     => @$item_meta['lm-op-widget-recent-comments-number'][0],
				'container' => true,
				'container_class' => 'lm-op-wrap'
			));

		$content_output .= '</div><!--lm-item-op-content-->';

		// Categories
		$tmp_hide = ( @$item_meta['lm-op-widget'][0] == 'categories' ) ? '' : 'hidden';
		$content_output .= '<div class="'.$tmp_hide.' lm-item-op-content lm-item-op-content-widget lm-item-op-content-widget-categories">';

			$content_output .= era_helpers_options::select(array(
				'title'     => __('Number of categories to show','era_fw'),
				'options'   => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7,
					8 => 8,
					9 => 9,
					10 => 10,
					11 => 11,
					12 => 12,
					13 => 13,
					14 => 14,
					15 => 15,
					16 => 16,
					17 => 17,
					18 => 18,
					19 => 19,
					20 => 20,
					30 => 30,
					40 => 40,
					50 => 50,
					60 => 60,
					70 => 70,
					80 => 80,
					90 => 90,
					100 => 100,
				),
				'class'     => 'lm-op',
				'id'        => 'lm-op-widget-categories-number',
				'value'     => @$item_meta['lm-op-widget-categories-number'][0],
				'container' => true,
				'container_class' => 'lm-op-wrap'
			));

		$content_output .= '</div><!--lm-item-op-content-->';

		// Search
		$tmp_hide = ( @$item_meta['lm-op-widget'][0] == 'search' ) ? '' : 'hidden';
		$content_output .= '<div class="'.$tmp_hide.' lm-item-op-content lm-item-op-content-widget lm-item-op-content-widget-search">';

			$content_output .= era_helpers_options::input(array(
				'title'     => __('Search Text','era_fw'),
				'class'     => 'lm-op',
				'id'        => 'lm-op-widget-search-text',
				'value'     => @$item_meta['lm-op-widget-search-text'][0],
				'container' => true,
				'container_class' => 'lm-op-wrap'
			));

		$content_output .= '</div><!--lm-item-op-content-->';

	$content_output .= '</div><!--page-->';


	/** Page :: Col Shortcodes & HTML  **/
	$content_output  .= '<div class="lm-item-box-page lm-item-box-page-column_shortcodes" >';

		// Widgets Settings
		$content_output .= '<div class="lm-item-op-content">';

			$content_output .= era_helpers_options::button(array(
				'class'  => 'button button-large lm-open-wp-editor-box',
				'title'  => __('Open WP Editor','era_fw')
			));

			$content_output .= era_helpers_options::textarea(array(
				'class'     => 'lm_op',
				'id'        => 'lm-op-shortcodes-content',
				'value'     => @$item_meta['lm-op-shortcodes-content'][0],
			));

		$content_output .= '</div><!--lm-item-op-content-->';

	$content_output .= '</div><!--page-->';

}