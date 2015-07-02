<?php


/**
 * Header
**/

$box_config['header_output'] .= '<span class="era-btn lm-btn lm-btn-blue lm-btn-drag"><span>' . __('Move','era_fw') . '</span></span>';

$box_config['header_output'] .= era_helpers_options::button(array(
   'title'  => __('Cancel','era_fw'),
   'class'  => 'lm-btn lm-btn-red lm-btn-cancel'
));
$box_config['header_output'] .= era_helpers_options::button(array(
   'title'  => '<span>' . __('Reset','era_fw') . '</span>',
   'class'  => 'lm-btn lm-btn-red lm-btn-reset-items-style'
));
$box_config['header_output'] .= era_helpers_options::button(array(
   'title'  => '<span>' . __('Save','era_fw') . '</span>',
   'class'  => 'lm-btn lm-btn-green lm-btn-save-item-style'
));

$box_config['header_output'] .= era_helpers_options::select(array(
   'id'        => 'lm-op-page-selector',
   'class'     => 'lm-op-page-selector ',
   'options'   => array(
      __('Item Style','era_fw')    => 'item_style',
      __('Submenu Style','era_fw') => 'submenu_style',
   ),
   'container'       => true,
   'container_class' => 'lm-box-page-select-wrap',
));



/**
 * Item Style
**/
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="item_style">';

   $background_output = era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-custom-background-color',
      'value'           => @$DBconfig['lm-op-custom-background-color'],
      'atts'            => array(
         'data-css'  => ' #lm-item-'.$data_post['item_id'].' > a #livemenuProp# background-color',
      )
   ));

   $box_config['content_output'] .= era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-custom-color',
      'title'           => __('Color / Background color','era_fw'),
      'desc'            => __('Custom color and background color for this link','era_fw'),
      'value'           => @$DBconfig['lm-op-custom-color'],
      'after'           => $background_output,
      'atts'            => array(
         'data-css'  => ' #lm-item-'.$data_post['item_id'].' > a #livemenuProp# color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom'
   ));

   $background_output = era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-custom-background-color-hover',
      'value'           => @$DBconfig['lm-op-custom-background-color-hover'],
      'atts'            => array(
         'data-css1'  => ' #lm-item-'.$data_post['item_id'].':hover > a #livemenuProp# background-color',
         'data-css2'  => ' #lm-item-'.$data_post['item_id'].'.lm-submenu-active > a #livemenuProp# background-color',
      ),
   ));

   $box_config['content_output'] .= era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-custom-color-hover',
      'title'           => __('Color / Background color ( Hover )','era_fw'),
      'desc'            => __('Color and background color when hover over this link','era_fw'),
      'value'           => @$DBconfig['lm-op-custom-color-hover'],
      'after'           => $background_output,
      'atts'            => array(
         'data-css1'  => ' #lm-item-'.$data_post['item_id'].':hover > a #livemenuProp# color',
         'data-css2'  => ' #lm-item-'.$data_post['item_id'].'.lm-submenu-active > a #livemenuProp# color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom'
   ));

$box_config['content_output']  .= '</div><!--lm-box-content-page-->';


// Submenu Style
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="submenu_style">';

   $box_config['content_output'] .= era_helpers_options::background(array(
      'id'                    => 'lm-op-custom-submenuBackground',
      'title'                 => __('Background','era_fw'),
      'desc'                  => __('Select between simple color, background image or gradient','era_fw'),
      'value_type'            => @$DBconfig['lm-op-custom-submenuBackground-type'],
      'value_color'           => @$DBconfig['lm-op-custom-submenuBackground-color'],
      'value_img'             => @$DBconfig['lm-op-custom-submenuBackground-image'],
      'value_img_repeat'      => @$DBconfig['lm-op-custom-submenuBackground-image-repeat'],
      'value_img_attachment'  => @$DBconfig['lm-op-custom-submenuBackground-image-attachment'],
      'value_img_position'    => @$DBconfig['lm-op-custom-submenuBackground-image-position'],
      'value_gradient_dir'    => @$DBconfig['lm-op-custom-submenuBackground-gradient-dir'],
      'value_gradient_color1' => @$DBconfig['lm-op-custom-submenuBackground-gradient-color1'],
      'value_gradient_color2' => @$DBconfig['lm-op-custom-submenuBackground-gradient-color2'],
      'atts_type'             => array(
         'data-css' => '.livemenu-horizontal #' . $data_post['item_id'] .' > .lm-sub',
      ),
      'container'             => true,
      'container_class'       => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::border(array(
      'id'              => 'lm-op-custom-submenuBorder',
      'title'           => __('Border','era_fw'),
      'desc'            => __('Add a border to the submenus ?','era_fw'),
      'value_size'      => @$DBconfig['lm-op-custom-submenuBorder-size'],
      'value_color'     => @$DBconfig['lm-op-custom-submenuBorder-color'],
      'atts_size'       => array(
         'data-css' => '.livemenu-horizontal #' . $data_post['item_id'] .' > .lm-sub #livemenuProp# border-width',
      ),
      'atts_color'       => array(
         'data-css' => '.livemenu-horizontal #' . $data_post['item_id'] .' > .lm-sub #livemenuProp# border-color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));
   
   $box_config['content_output'] .= era_helpers_options::boxShadow(array(
      'id'              => 'lm-op-custom-submenuBoxShadow',
      'title'           => __('Box Shadow','era_fw'),
      'desc'            => __('Add box shadow to the submenu ? ( Size, Blur, Color )','era_fw'),
      'value_size'      => @$DBconfig['lm-op-custom-submenuBoxShadow-size'],
      'value_blur'      => @$DBconfig['lm-op-custom-submenuBoxShadow-blur'],
      'value_color'     => @$DBconfig['lm-op-custom-submenuBoxShadow-color'],
      'atts'            => array(
         'data-css' => '.livemenu-horizontal #' . $data_post['item_id'] .' > .lm-sub',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::borderRadius(array(
      'id'                 => 'lm-op-custom-submenuBorderRadius',
      'title'              => __('Border Radius','era_fw'),
      'desc'               => __('Add Border Radius to the submenu ? ( Top Left, Top Right, Bottom Left, Bottom Right','era_fw'),
      'value_topLeft'      => @$DBconfig['lm-op-custom-submenuBorderRadius-topLeft'],
      'value_topRight'     => @$DBconfig['lm-op-custom-submenuBorderRadius-topRight'],
      'value_bottomLeft'   => @$DBconfig['lm-op-custom-submenuBorderRadius-bottomLeft'],
      'value_bottomRight'  => @$DBconfig['lm-op-custom-submenuBorderRadius-bottomRight'],
      'atts'               => array(
         'data-css' => '.livemenu-horizontal #' . $data_post['item_id'] .' > .lm-sub',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap border-bottom',
   ));

$box_config['content_output']  .= '</div><!--lm-box-content-page-->';
