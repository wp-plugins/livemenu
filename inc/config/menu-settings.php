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
   'title'  => '<span>' . __('Save','era_fw') . '</span>',
   'class'  => 'lm-btn lm-btn-green lm-btn-save-menu-settings'
));

$box_config['header_output'] .= era_helpers_options::select(array(
   'id'        => 'lm-op-page-selector',
   'value'     => 'layout',
   'class'     => 'lm-op-page-selector',
   'options'   => array(
      __('Layout and Logo','era_fw')=> 'layout_and_logo',
      __('Responsive','era_fw')     => 'responsive',
      __('Sticky','era_fw')         => 'sticky',
      __('Jquery','era_fw')         => 'jquery',
   ),
   'container'       => true,
   'container_class' => 'lm-box-page-select-wrap',
));


/**
 * Content
**/
// Layout Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="layout_and_logo">';

   $box_config['content_output'] .= era_helpers_options::select(array(
      'id'        => 'lm-op-layoutAndLogo',
      'title'     => __('Select Logo Position on Desktops/Laptops','era_fw'),
      'options'   => array(
         __('No Logo','era_fw')                 => 'no-logo',
         __('Logo Left, Menu Right','era_fw')   => 'logo-left',
         __('Logo Right, Menu Left','era_fw')   => 'logo-right',
         //__('Logo Center, Menu Bottom','era_fw')=> 'logo-center-menu-bottom',
         //__('Logo Left, Menu Bottom','era_fw')  => 'logo-left-menu-bottom',
         //__('Logo Right, Menu Bottom','era_fw') => 'logo-right-menu-bottom',
      ),
      'atts'            => array(
         'data-css_class'  => '.'.$data_post['menu_class']. ' .livemenu',
      ),
      'value'           => $DBconfig['lm-op-layoutAndLogo'],
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom'
   ));

   $tmp_class = ( $DBconfig['lm-op-layoutAndLogo'] == 'no-logo' ) ? 'hidden' : '';
   $box_config['content_output'] .= era_helpers_options::upload(array(
      'id'              => 'lm-op-logo',
      'title'           => __('Upload Logo','era_fw'),
      'desc'            => __('upload a nice logo for your site','era_fw'),
      'value'           => $DBconfig['lm-op-logo'],
      'container'       => true,
      'container_class' => 'lm-op-wrap lm-op-wrap-logo-upload border-bottom '.$tmp_class
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Responsive Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="responsive">';

   $box_config['content_output'] .= era_helpers_options::checkbox(array(
      'id'              => 'lm-op-responsive',
      'title'           => __('Responsive On/Off','era_fw'),
      'desc'            => __('Support mobile devices by enabling responsive','era_fw'),
      'value'           => $DBconfig['lm-op-responsive'],
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom'
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-responsive-start-width',
      'title'           => __('Responsive Start At This Width ( Breakpoint )','era_fw'),
      'desc'            => __('Set the width that you want responsive to start work','era_fw'),
      'value'           => $DBconfig['lm-op-responsive-start-width'],
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom'
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// Sticky Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="sticky">';

   $box_config['content_output'] .= era_helpers_options::checkbox(array(
      'id'              => 'lm-op-sticky',
      'title'           => __('Sticky On/Off','era_fw'),
      'desc'            => __('make your menu scroll when you scroll down!','era_fw'),
      'value'           => $DBconfig['lm-op-sticky'],
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom'
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';


// jQuery Page
$box_config['content_output'] .= '<div class="lm-box-content-page" data-page="jquery">';

   $box_config['content_output'] .= era_helpers_options::select(array(
      'id'              => 'lm-op-jqueryTrigger',
      'title'           => __('Trigger Hover/Click','era_fw'),
      'desc'            => __('hover trigger is the most used these days!','era_fw'),
      'options'         => array(
         __('Hover','era_fw')    => 'hover',
         __('Click','era_fw')    => 'click',
      ),
      'value'           => $DBconfig['lm-op-jqueryTrigger'],
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom'
   ));

   $box_config['content_output'] .= era_helpers_options::select(array(
      'id'              => 'lm-op-jqueryAnimation',
      'title'           => __('Animation','era_fw'),
      'desc'            => __('select the animation','era_fw'),
      'options'         => array(
         __('None','era_fw')        => 'none',
         __('Fade','era_fw')        => 'fade',
         __('Slide','era_fw')       => 'slide',
         __('fadeSlideUp','era_fw') => 'fadeSlideUp',
      ),
      'value'           => $DBconfig['lm-op-jqueryAnimation'],
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom'
   ));

   $box_config['content_output'] .= era_helpers_options::input(array(
      'id'              => 'lm-op-jqueryTime',
      'title'           => __('Animation Time','era_fw'),
      'desc'            => __('set the animation time ( millseconds )','era_fw'),
      'value'           => $DBconfig['lm-op-jqueryTime'],
      'container'       => true,
      'container_class' => 'lm-op-wrap border-bottom'
   ));

$box_config['content_output'] .= '</div><!--lm-box-content-page-->';
