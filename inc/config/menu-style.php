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
   'title'  => '<span>' . __('Save','era_fw') . '</span>' ,
   'class'  => 'lm-btn lm-btn-green lm-btn-save-menu-style'
));

$tmp = ( $DBconfig['lm-op-skinOrCustom'] == 'skin' ) ? 'no_visible' : '';
$box_config['header_output'] .= era_helpers_options::select(array(
   'id'        => 'lm-op-page-selector',
   'value'     => 'skin_or_custom',
   'class'     => 'lm-op-page-selector '.$tmp,
   'options'   => array(
      __('Skin or Custom','era_fw')          => 'skin_or_custom',
      __('Menu','era_fw')                    => 'general',
      __('Top Links','era_fw')               => 'top_links',
      __('Current Link','era_fw')            => 'current_link',
      __('Submenu','era_fw')                 => 'submenu',
      __('Sub Links','era_fw')               => 'sub_links',
      __('Search Widget','era_fw')           => 'search_form',
      __('General Text','era_fw')            => 'general_text',
      __('Responsive','era_fw')              => 'responsive',
   ),
   'container'       => true,
   'container_class' => 'lm-box-page-select-wrap',
));



/**
 * Content
**/
// Skin or Custom Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="skin_or_custom">';

   $box_config['content_output'] .= era_helpers_options::select(array(
      'id'              => 'lm-op-skinOrCustom',
      'title'           => __('Skin or Custom','era_fw'),
      'desc'            => __('Select skin or create your own style','era_fw'),
      'value'           => $DBconfig['lm-op-skinOrCustom'],
      'options'         => array(
         __('Skin','era_fw')           => 'skin',
         __('Custom Style','era_fw')   => 'custom',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

   $tmp = ( $DBconfig['lm-op-skinOrCustom'] != 'skin' ) ? 'hidden' : '';
   $upload_dir = wp_upload_dir();
   $all_skins = get_option('livemenu_skins');
   $box_config['content_output'] .= era_helpers_options::select(array(
      'id'        => 'lm-op-skin',
      'title'     => __('Select Skin','era_fw'),
      'desc'      => __('Select the skin you want to use on this menu','era_fw'),
      'options'   => $all_skins,
      'value'           => $DBconfig['lm-op-skin'],
      'after'           => era_helpers_options::button(array(
         'title'  => __('Preview','era_fw'),
         'class'  => 'lm-btn lm-btn-blue lm-btn-preview-skin',
         'atts'   => array(
            'data-url' => $upload_dir['baseurl'] . '/livemenu/css/',
         ),
      )),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-position-wrap '.$tmp
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Menu Style Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="general">';

   $box_config['content_output'] .= era_helpers_options::select(array(
      'id'              => 'lm-op-menuWideOrBoxed',
      'title'           => __('Layout','era_fw'),
      'desc'            => __('Select from boxed or wide layout','era_fw'),
      'options'         => array(
         __('Boxed','era_fw') => 'boxed',
         __('Wide','era_fw')  => 'wide',
      ),
      'atts'            => array(
         'data-css_class' => ' .'.$data_post['menu_class'].'.livemenu-wrap',
      ),
      'value'           => $DBconfig['lm-op-menuWideOrBoxed'],
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

   $menuheight_output = era_helpers_options::input(array(
      'id'                 => 'lm-op-menuHeight',
      'value'              => $DBconfig['lm-op-menuHeight'],
      'atts'               => array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-wrap #livemenuProp# height',
         'data-css2' => ' .' . $data_post['menu_class'] .'.livemenu-horizontal .livemenu > .lm-item > a #livemenuProp# height',
         'data-css3' => ' .' . $data_post['menu_class'] .'.livemenu-horizontal .livemenu > .lm-item > a #livemenuProp# line-height',
         'data-css4' => ' .' . $data_post['menu_class'] .'.livemenu-horizontal .livemenu > .lm-item > .lm-sub #livemenuProp# top',
         'data-css5' => ' .' . $data_post['menu_class'] .'.livemenu-horizontal .lm-logo img #livemenuProp# max-height',
         'data-css6' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .livemenu #livemenuProp# top',
         'data-css7' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .livemenu-responsive-controls #livemenuProp# height',
      ),
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'                 => 'lm-op-menuWidth',
      'title'              => __('Width & Height','era_fw'),
      'value'              => $DBconfig['lm-op-menuWidth'],
      'after'              => $menuheight_output,
      'atts'               => array(
         'data-css'  => '.' . $data_post['menu_class'] . '.livemenu-horizontal .livemenu #livemenuProp# width',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::background(array(
      'id'                    => 'lm-op-menuBackground',
      'title'                 => __('Background','era_fw'),
      'desc'                  => __('Select between simple color, background image or gradient','era_fw'),
      'value_type'            => $DBconfig['lm-op-menuBackground-type'],
      'value_color'           => $DBconfig['lm-op-menuBackground-color'],
      'value_img'             => $DBconfig['lm-op-menuBackground-image'],
      'value_img_repeat'      => $DBconfig['lm-op-menuBackground-image-repeat'],
      'value_img_attachment'  => $DBconfig['lm-op-menuBackground-image-attachment'],
      'value_img_position'    => $DBconfig['lm-op-menuBackground-image-position'],
      'value_gradient_dir'    => $DBconfig['lm-op-menuBackground-gradient-dir'],
      'value_gradient_color1' => $DBconfig['lm-op-menuBackground-gradient-color1'],
      'value_gradient_color2' => $DBconfig['lm-op-menuBackground-gradient-color2'],

      'atts_type'             => array(
         'data-css'  => '.' . $data_post['menu_class'] .'.livemenu-wrap',
      ),
      'atts_color'            => array(
         'data-css1' => '.' . $data_post['menu_class'].'.livemenu-responsive .livemenu #livemenuProp# background-color', // this for responsive
         'data-css2' => '.' . $data_post['menu_class'].'.livemenu-responsive .lm-item > a .lm-responsive-links-arrow.lm-arrow-up #livemenuProp# background-color',

      ),

      'container'             => true,
      'container_class'       => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::border(array(
      'id'              => 'lm-op-menuBorder',
      'title'           => __('Border','era_fw'),
      'desc'            => __('Add a border to your menu ?','era_fw'),
      'value_size'      => $DBconfig['lm-op-menuBorder-size'],
      'value_color'     => $DBconfig['lm-op-menuBorder-color'],
      'atts_size'       => array(
         'data-css'    => '.' . $data_post['menu_class'] .'.livemenu-wrap #livemenuProp# border-width',
      ),
      'atts_color'       => array(
         'data-css'    => '.' . $data_post['menu_class'] .'.livemenu-wrap #livemenuProp# border-color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::boxShadow(array(
      'id'              => 'lm-op-menuBoxShadow',
      'title'           => __('Box Shadow','era_fw'),
      'desc'            => __('Add box shadow to your menu ? ( Size, Blur, Color )','era_fw'),
      'value_size'      => $DBconfig['lm-op-menuBoxShadow-size'],
      'value_blur'      => $DBconfig['lm-op-menuBoxShadow-blur'],
      'value_color'     => $DBconfig['lm-op-menuBoxShadow-color'],
      'atts'            => array(
         'data-css' => '.'.$data_post['menu_class'].'.livemenu-wrap',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::borderRadius(array(
      'id'                 => 'lm-op-menuBorderRadius',
      'title'              => __('Border Radius','era_fw'),
      'desc'               => __('Add Border Radius to your menu ? ( Top Left, Top Right, Bottom Left, Bottom Right','era_fw'),
      'value_topLeft'      => $DBconfig['lm-op-menuBorderRadius-topLeft'],
      'value_topRight'     => $DBconfig['lm-op-menuBorderRadius-topRight'],
      'value_bottomLeft'   => $DBconfig['lm-op-menuBorderRadius-bottomLeft'],
      'value_bottomRight'  => $DBconfig['lm-op-menuBorderRadius-bottomRight'],
      'atts'               => array(
         'data-css' => '.' . $data_post['menu_class'] .'.livemenu-wrap',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'                 => 'lm-op-menu-zindex',
      'title'              => __('Menu Z-index','era_fw'),
      'value'              => $DBconfig['lm-op-menu-zindex'],
      'atts'               => array(
         'data-css1'  => '.' . $data_post['menu_class'] . '.livemenu-wrap #livemenuProp# z-index',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap lm-op-wrap-small-options',
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Top Links Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="top_links">';

   $box_config['content_output'] .= era_helpers_options::font(array(
      'id'                 => 'lm-op-topLinksFont',
      'title'              => __('Font','era_fw'),
      'desc'               => __('font family, size, style, color','era_fw'),
      'value_fontFamily'   => $DBconfig['lm-op-topLinksFont-fontFamily'],
      'value_fontSize'     => $DBconfig['lm-op-topLinksFont-fontSize'],
      'value_fontStyle'    => $DBconfig['lm-op-topLinksFont-fontStyle'],
      'value_fontColor'    => $DBconfig['lm-op-topLinksFont-fontColor'],

      'atts_fontFamily'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item > a .lm-item-title #livemenuProp# font-family',
      ),
      'atts_fontStyle'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item > a .lm-item-title #livemenuProp# font-style',
      ),
      'atts_fontSize'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item > a #livemenuProp# font-size',
      ),
      'atts_fontColor'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item > a #livemenuProp# color',
         'data-css2' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item > a #livemenuProp# color',
         'data-css3' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item > a .lm-responsive-links-arrow.lm-arrow-up #livemenuProp# color',
      ),

      'container'          => true,
      'container_class'    => 'lm-op-wrap border-bottom',
   ));

   $toplinkscolorh_output = era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-topLinksBgColorHover',
      'value'           => $DBconfig['lm-op-topLinksBgColorHover'],
      'atts'            => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item:hover > a #livemenuProp# background-color',
         'data-css2' => ' .' . $data_post['menu_class'] .' .lm-item.lm-submenu-active > a #livemenuProp# background-color',
         'data-css3' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item > a:hover #livemenuProp# background-color',
         'data-css4' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item.lm-submenu-active > a #livemenuProp# background-color',
         'data-css5' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a:hover #livemenuProp# background-color',
         'data-css6' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item.lm-submenu-active > a #livemenuProp# background-color',
         'data-css7' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item > a:hover #livemenuProp# background-color',
         'data-css8' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item.lm-submenu-active > a #livemenuProp# background-color',
      ),
   ));

   $box_config['content_output'] .= era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-topLinksColorHover',
      'title'           => __('Color & Background Color ( Hover )','era_fw'),
      'value'           => $DBconfig['lm-op-topLinksColorHover'],
      'after'           => $toplinkscolorh_output,
      'atts'            => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item:hover > a #livemenuProp# color',
         'data-css2' => ' .' . $data_post['menu_class'] .' .lm-item.lm-submenu-active > a #livemenuProp# color',
         'data-css3' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item:hover > a #livemenuProp# color',
         'data-css4' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item.lm-submenu-active > a #livemenuProp# color',
         'data-css5' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a:hover #livemenuProp# color',
         'data-css6' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item.lm-submenu-active > a #livemenuProp# color',
         'data-css7' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item > a:hover #livemenuProp# color',
         'data-css8' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item.lm-submenu-active > a #livemenuProp# color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-topLinksBorderColor',
      'title'           => __('Border Color','era_fw'),
      'value'           => $DBconfig['lm-op-topLinksBorderColor'],
      'atts'            => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item > a #livemenuProp# border-color',
         'data-css2' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item > a #livemenuProp# border-color',
         'data-css3' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-responsive-socialmedia #livemenuProp# border-color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-topLinksPadding',
      'title'           => __('Padding ( Left & Right )','era_fw'),
      'value'           => $DBconfig['lm-op-topLinksPadding'],
      'atts'            => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item > a #livemenuProp# padding-left',
         'data-css2' => ' .' . $data_post['menu_class'] .' .lm-item > a #livemenuProp# padding-right',
         'data-css3' => ' .' . $data_post['menu_class'] .' .lm-logo-left #livemenuProp# padding-right',
         'data-css4' => ' .' . $data_post['menu_class'] .' .lm-logo-right #livemenuProp# padding-left',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-topLinksIconSize',
      'title'           => __('Icon Size','era_fw'),
      'value'           => $DBconfig['lm-op-topLinksIconSize'],
      'atts'            => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item > a .era-icon:before #livemenuProp# font-size',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   /* RTL Solution */
   if( is_rtl() ){
      $tmp_options = array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-rtl .lm-item > a .era-icon:before #livemenuProp# padding-left',
         'data-css2' => ' .' . $data_post['menu_class'] .'.livemenu-rtl .lm-item > a .era-icon:after #livemenuProp# padding-right',
      );
   }
   else{
      $tmp_options = array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item > a .era-icon:before #livemenuProp# padding-right',
         'data-css2' => ' .' . $data_post['menu_class'] .' .lm-item > a .era-icon:after #livemenuProp# padding-left',
      );
   }

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-topLinksIconPadding',
      'title'           => __('Icon Padding ( Icon & Arrow )','era_fw'),
      'value'           => $DBconfig['lm-op-topLinksIconPadding'],
      'atts'            => $tmp_options,
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Sub Links Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="sub_links">';

   $box_config['content_output'] .= era_helpers_options::font(array(
      'id'                 => 'lm-op-subLinksFont',
      'title'              => __('Font','era_fw'),
      'desc'               => __('font family, size, style, color','era_fw'),
      'value_fontFamily'   => $DBconfig['lm-op-subLinksFont-fontFamily'],
      'value_fontSize'     => $DBconfig['lm-op-subLinksFont-fontSize'],
      'value_fontStyle'    => $DBconfig['lm-op-subLinksFont-fontStyle'],
      'value_fontColor'    => $DBconfig['lm-op-subLinksFont-fontColor'],
      'atts_fontFamily'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .lm-item-title #livemenuProp# font-family',
      ),
      'atts_fontSize'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a #livemenuProp# font-size',
      ),
      'atts_fontColor'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a,
                          .' . $data_post['menu_class'] .' .lm-sub a #livemenuProp# color',
         'data-css2' => ' .' . $data_post['menu_class'].'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a,
                          .' . $data_post['menu_class'].'.livemenu-responsive .lm-item-posts .lm-sub-item > a #livemenuProp# color',
         'data-css3' => ' .' . $data_post['menu_class'].'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a .lm-responsive-links-arrow.lm-arrow-up,
                          .' . $data_post['menu_class'].'.livemenu-responsive .lm-item-posts .lm-sub-item > a .lm-responsive-links-arrow.lm-arrow-up #livemenuProp# color',

      ),
      'atts_fontStyle'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .lm-item-title #livemenuProp# font-style',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap border-bottom',
   ));

   $sublinks_output = era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-subLinksBgColorHover',
      'value'           => $DBconfig['lm-op-subLinksBgColorHover'],
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-col > a:hover ,
                          .' . $data_post['menu_class'] .' .lm-col .lm-sub-item:hover > a,
                          .' . $data_post['menu_class'] .' .lm-item-posts .lm-sub-item:hover > a,
                          .' . $data_post['menu_class'] .' .lm-item-dropdowns .lm-sub-item > a:hover,
                          .' . $data_post['menu_class'] .' .lm-sub .lm-submenu-active > a #livemenuProp# background-color',
      ),
   ));

   $box_config['content_output'] .= era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-subLinksColorHover',
      'title'           => __('Color & Background Color ( Hover )','era_fw'),
      'value'           => $DBconfig['lm-op-subLinksColorHover'],
      'after'           => $sublinks_output,
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-col > a:hover ,
                          .' . $data_post['menu_class'] .' .lm-col .lm-sub-item:hover > a,
                          .' . $data_post['menu_class'] .' .lm-item-posts .lm-sub-item:hover > a,
                          .' . $data_post['menu_class'] .' .lm-item-dropdowns .lm-sub-item > a:hover,
                          .' . $data_post['menu_class'] .' .lm-sub .lm-submenu-active > a
                          .' . $data_post['menu_class'] .' .lm-sub a:hover #livemenuProp# color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-subLinksBorderColor',
      'title'           => __('Border Color ( Dropdown Links & Mega Column Title )','era_fw'),
      'value'           => $DBconfig['lm-op-subLinksBorderColor'],
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item-dropdowns .lm-sub-item > a,
                          .' . $data_post['menu_class'] .' .lm-col > a,
                          .' . $data_post['menu_class'] .' .lm-item-posts .lm-sub-item > a,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item > a #livemenuProp# border-color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-subLinksPadding',
      'title'           => __('Padding','era_fw'),
      'value'           => $DBconfig['lm-op-subLinksPadding'],
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a #livemenuProp# padding',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-subLinksIconSize',
      'title'           => __('Icon Size','era_fw'),
      'value'           => $DBconfig['lm-op-subLinksIconSize'],
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .era-icon:before #livemenuProp# font-size',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   // RTL Support
   if( is_rtl() ){
      $tmp_options =  array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-rtl .lm-sub-item > a .era-icon:before #livemenuProp# padding-left',
         'data-css2' => ' .' . $data_post['menu_class'] .'.livemenu-rtl .lm-sub-item > a .era-icon:after #livemenuProp# padding-right',
      );
   }
   else{
      $tmp_options =  array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .era-icon:before #livemenuProp# padding-right',
         'data-css2' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .era-icon:after #livemenuProp# padding-left',
      );
   }

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-subLinksIconPadding',
      'title'           => __('Icon Padding ( Icon & Arrow )','era_fw'),
      'value'           => $DBconfig['lm-op-subLinksIconPadding'],
      'atts'            => $tmp_options,
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));


   $box_config['content_output'] .= era_helpers_options::font(array(
      'id'                 => 'lm-op-subLinksDescFont',
      'title'              => __('Description Font','era_fw'),
      'desc'               => __('change the font (family,size,style,color)','era_fw'),
      'value_fontFamily'   => $DBconfig['lm-op-subLinksDescFont-fontFamily'],
      'value_fontSize'     => $DBconfig['lm-op-subLinksDescFont-fontSize'],
      'value_fontStyle'    => $DBconfig['lm-op-subLinksDescFont-fontStyle'],
      'value_fontColor'    => $DBconfig['lm-op-subLinksDescFont-fontColor'],
      'atts_fontFamily'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .lm-item-desc #livemenuProp# font-family',
      ),
      'atts_fontSize'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .lm-item-desc #livemenuProp# font-size',
      ),
      'atts_fontColor'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .lm-item-desc #livemenuProp# color',
      ),
      'atts_fontStyle'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-sub-item > a .lm-item-desc #livemenuProp# font-style',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap border-bottom',
   ));


$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Current Link Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="current_link">';

   $sublinks_output = era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-current-link-bgcolor',
      'value'           => $DBconfig['lm-op-current-link-bgcolor'],
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item.lm-item-current > a #livemenuProp# background-color',
         'data-css2' => ' .' . $data_post['menu_class'] .' .lm-sub-item.lm-item-current > a #livemenuProp# background-color',
      ),
   ));

   $box_config['content_output'] .= era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-current-link-color',
      'title'           => __('Current Link Color & Background Color','era_fw'),
      'desc'            => __('Set the color/background color of current link ( active )','era_fw'),
      'value'           => $DBconfig['lm-op-current-link-color'],
      'after'           => $sublinks_output,
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .lm-item.lm-item-current > a #livemenuProp# color',
         'data-css2' => ' .' . $data_post['menu_class'] .' .lm-sub-item.lm-item-current > a #livemenuProp# color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));


$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Submenu Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="submenu">';

   $box_config['content_output'] .= era_helpers_options::background(array(
      'id'                    => 'lm-op-submenuBackground',
      'title'                 => __('Background','era_fw'),
      'desc'                  => __('Select between simple color, background image or gradient','era_fw'),
      'value_type'            => $DBconfig['lm-op-submenuBackground-type'],
      'value_color'           => $DBconfig['lm-op-submenuBackground-color'],
      'value_img'             => $DBconfig['lm-op-submenuBackground-image'],
      'value_img_repeat'      => $DBconfig['lm-op-submenuBackground-image-repeat'],
      'value_img_attachment'  => $DBconfig['lm-op-submenuBackground-image-attachment'],
      'value_img_position'    => $DBconfig['lm-op-submenuBackground-image-position'],
      'value_gradient_dir'    => $DBconfig['lm-op-submenuBackground-gradient-dir'],
      'value_gradient_color1' => $DBconfig['lm-op-submenuBackground-gradient-color1'],
      'value_gradient_color2' => $DBconfig['lm-op-submenuBackground-gradient-color2'],
      'atts_type'             => array(
         'data-css' => ' .' . $data_post['menu_class'] .' .lm-sub',
      ),
      'atts_color'            => array(
         'data-css1'          => '.' . $data_post['menu_class'].'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a .lm-responsive-links-arrow.lm-arrow-up,
                                  .' . $data_post['menu_class'].'.livemenu-responsive .lm-item-posts .lm-sub-item > a .lm-responsive-links-arrow.lm-arrow-up #livemenuProp# background-color',
      ),
      'container'             => true,
      'container_class'       => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::border(array(
      'id'              => 'lm-op-submenuBorder',
      'title'           => __('Border','era_fw'),
      'desc'            => __('Add a border to the submenus ?','era_fw'),
      'value_size'      => $DBconfig['lm-op-submenuBorder-size'],
      'value_color'     => $DBconfig['lm-op-submenuBorder-color'],
      'atts_size'       => array(
         'data-css' => ' .' . $data_post['menu_class'] .'.livemenu-wrap .livemenu .lm-sub #livemenuProp# border-width',
      ),
      'atts_color'       => array(
         'data-css' => ' .' . $data_post['menu_class'] .'.livemenu-wrap .livemenu .lm-sub #livemenuProp# border-color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::boxShadow(array(
      'id'              => 'lm-op-submenuBoxShadow',
      'title'           => __('Box Shadow','era_fw'),
      'desc'            => __('Add box shadow to the submenu ? ( Size, Blur, Color )','era_fw'),
      'value_size'      => $DBconfig['lm-op-submenuBoxShadow-size'],
      'value_blur'      => $DBconfig['lm-op-submenuBoxShadow-blur'],
      'value_color'     => $DBconfig['lm-op-submenuBoxShadow-color'],
      'atts'            => array(
         'data-css' => ' .' . $data_post['menu_class'] .'.livemenu-wrap .livemenu .lm-sub',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::borderRadius(array(
      'id'                 => 'lm-op-submenuBorderRadius',
      'title'              => __('Border Radius','era_fw'),
      'desc'               => __('Add Border Radius to the submenu ? ( Top Left, Top Right, Bottom Left, Bottom Right','era_fw'),
      'value_topLeft'      => $DBconfig['lm-op-submenuBorderRadius-topLeft'],
      'value_topRight'     => $DBconfig['lm-op-submenuBorderRadius-topRight'],
      'value_bottomLeft'   => $DBconfig['lm-op-submenuBorderRadius-bottomLeft'],
      'value_bottomRight'  => $DBconfig['lm-op-submenuBorderRadius-bottomRight'],
      'atts'               => array(
         'data-css' => ' .' . $data_post['menu_class'] .'.livemenu-wrap .livemenu .lm-sub',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap border-bottom',
   ));

   // Padding
   $tmp = era_helpers_options::input(array(
      'id'              => 'lm-op-submenuPadding-rightleft',
      'value'           => $DBconfig['lm-op-submenuPadding-rightleft'],
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > .lm-sub #livemenuProp# padding-left',
         'data-css2' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > .lm-sub #livemenuProp# padding-right',
         'data-css3' => ' .' . $data_post['menu_class'] .' .lm-item-posts > .lm-sub,
                          .' . $data_post['menu_class'] .' .lm-item-mega > .lm-sub #livemenuProp# padding-left',
         'data-css4' => ' .' . $data_post['menu_class'] .' .lm-item-posts > .lm-sub,
                          .' . $data_post['menu_class'] .' .lm-item-mega > .lm-sub #livemenuProp# padding-right',
      ),
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-submenuPadding-topbottom',
      'title'           => __('Padding','era_fw'),
      'desc'            => __('Add some padding to the submenu( Top & Bottom / Left & Right )','era_fw'),
      'value'           => $DBconfig['lm-op-submenuPadding-topbottom'],
      'atts'            => array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > .lm-sub #livemenuProp# padding-top',
         'data-css2' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts > .lm-sub > .lm-sub-item > .lm-sub #livemenuProp# padding-bottom',
         'data-css3' => ' .' . $data_post['menu_class'] .' .lm-item-posts > .lm-sub,
                          .' . $data_post['menu_class'] .' .lm-item-mega > .lm-sub #livemenuProp# padding-top',
         'data-css4' => ' .' . $data_post['menu_class'] .' .lm-item-posts > .lm-sub,
                          .' . $data_post['menu_class'] .' .lm-item-mega > .lm-sub #livemenuProp# padding-bottom',
      ),
      'after'           => $tmp,
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));


$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Search Widget Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="search_form">';


   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-searchInputHeight',
      'title'           => __('Search Bar Height','era_fw'),
      'value'           => $DBconfig['lm-op-searchInputHeight'],
      'atts'               => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu-searchinput #livemenuProp# height',
         'data-css2' => ' .' . $data_post['menu_class'] .' .livemenu-searchinput #livemenuProp# line-height',
         'data-css3' => ' .' . $data_post['menu_class'] .' .livemenu-searchsubmit #livemenuProp# height',
         'data-css4' => ' .' . $data_post['menu_class'] .' .livemenu-searchsubmit #livemenuProp# line-height',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $searchinput_border = era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-search-input-border-Color',
      'value'           => $DBconfig['lm-op-search-input-border-Color'],
      'atts'               => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu-searchinput #livemenuProp# border-color',
      ),
   ));

   $box_config['content_output'] .= era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-searchInputBgColor',
      'title'           => __('Input Background & Border color','era_fw'),
      'value'           => $DBconfig['lm-op-searchInputBgColor'],
      'after'           => $searchinput_border,
      'atts'              => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu-searchinput #livemenuProp# background-color',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::font(array(
      'id'                 => 'lm-op-searchTextFont',
      'title'              => __('Search Text Font','era_fw'),
      'desc'               => __('change the search text font (family,size,style,color)','era_fw'),
      'value_fontFamily'   => $DBconfig['lm-op-searchTextFont-fontFamily'],
      'value_fontSize'     => $DBconfig['lm-op-searchTextFont-fontSize'],
      'value_fontStyle'    => $DBconfig['lm-op-searchTextFont-fontStyle'],
      'value_fontColor'    => $DBconfig['lm-op-searchTextFont-fontColor'],
      'atts_fontFamily'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu-searchinput #livemenuProp# font-family',
      ),
      'atts_fontSize'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-searchinput #livemenuProp# font-size',
      ),
      'atts_fontColor'    => array(
         'data-css0' => ' .' . $data_post['menu_class'] .' .livemenu-searchsubmit #livemenuProp# color',
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu-searchform ::-webkit-input-placeholder #livemenuProp# color',
         'data-css2' => ' .' . $data_post['menu_class'] .' .livemenu-searchform :-moz-placeholder #livemenuProp# color',
         'data-css3' => ' .' . $data_post['menu_class'] .' .livemenu-searchform ::-moz-placeholder #livemenuProp# color',
         'data-css4' => ' .' . $data_post['menu_class'] .' .livemenu-searchform :-ms-input-placeholder #livemenuProp# color',
      ),
      'atts_fontStyle'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu-searchinput #livemenuProp# font-style',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-searchTextPadding',
      'title'           => __('Search Text Padding','era_fw'),
      'desc'            => __('Set the search text padding ( left,right )','era_fw'),
      'value'           => $DBconfig['lm-op-searchTextPadding'],
      'atts'               => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu-searchinput #livemenuProp# padding-left',
         'data-css2' => ' .' . $data_post['menu_class'] .' .livemenu-searchinput #livemenuProp# padding-right',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-searchIconSize',
      'title'           => __('Icon Size','era_fw'),
      'value'           => $DBconfig['lm-op-searchIconSize'],
      'atts'               => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu-searchsubmit #livemenuProp# font-size',
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom',
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// General Text
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="general_text">';

   $box_config['content_output'] .= era_helpers_options::font(array(
      'id'                 => 'lm-op-submenuText',
      'title'              => __('General Text Font','era_fw'),
      'desc'               => __('font family, size, style, color','era_fw'),
      'value_fontFamily'   => $DBconfig['lm-op-submenuText-fontFamily'],
      'value_fontSize'     => $DBconfig['lm-op-submenuText-fontSize'],
      'value_fontStyle'    => $DBconfig['lm-op-submenuText-fontStyle'],
      'value_fontColor'    => $DBconfig['lm-op-submenuText-fontColor'],
      'atts_fontFamily'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu .lm-sub #livemenuProp# font-family',
      ),
      'atts_fontSize'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu .lm-sub #livemenuProp# font-size',
      ),
      'atts_fontColor'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu .lm-sub #livemenuProp# color',
      ),
      'atts_fontStyle'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .' .livemenu .lm-sub #livemenuProp# font-style',
      ),
      'container'          => true,
      'container_class'    => 'lm-op-wrap border-bottom',
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Responsive Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="responsive">';

   $barsicon_output = era_helpers_options::colorpicker(array(
      'id'              => 'lm-op-responsiveIcon-color',
      'value'           => $DBconfig['lm-op-responsiveIcon-color'],
      'atts'            => array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .livemenu-responsive-controls .era-icon-menu #livemenuProp# color',
      ),
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-responsiveIcon-size',
      'title'           => __('3Bars Icon Size & Color','era_fw'),
      'value'           => $DBconfig['lm-op-responsiveIcon-size'],
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .livemenu-responsive-controls .era-icon-menu #livemenuProp# font-size',
      ),
      'after'           => $barsicon_output,
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-responsiveLinks-height',
      'title'           => __('Main Links Height','era_fw'),
      'desc'            => __('Set the main links height','era_fw'),
      'value'           => $DBconfig['lm-op-responsiveLinks-height'],
      'atts'    => array(
         'data-css1' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item > a ,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a ,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item > a ,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item > a .lm-responsive-links-arrow,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a .lm-responsive-links-arrow,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item > a .lm-responsive-links-arrow #livemenuProp# height',

         'data-css2' => ' .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item > a ,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a ,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item > a ,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item > a .lm-responsive-links-arrow,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-dropdowns .lm-sub-item > a .lm-responsive-links-arrow,
                          .' . $data_post['menu_class'] .'.livemenu-responsive .lm-item-posts .lm-sub-item > a .lm-responsive-links-arrow #livemenuProp# line-height',

      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-responsiveLinks-links-padding',
      'title'           => __('Padding','era_fw'),
      'desc'            => __('Add padding to your links','era_fw'),
      'value'           => $DBconfig['lm-op-responsiveLinks-links-padding'],
      'atts'            => array(
         'data-css_class' => ' .' . $data_post['menu_class'],
      ),
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-small-options border-bottom',
   ));


$box_config['content_output'] .= '</div><!--lm-box-content-page-->';