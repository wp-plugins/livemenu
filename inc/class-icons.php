<?php

/**
 * This File Contain Font Icons Manager Methods
 *
 * @author      Sabri Taieb ( codezag )
 * @copyright   Copyright (c) Sabri Taieb
 * @link        http://vamospace.com
 * @since       Version 1.0.0
 * @package     era_framework
 *
**/


if( !class_exists( 'era_fontIconsManager' ) )
{
    class era_fontIconsManager
    {
        static function showFontIcons()
        {
            global $era_fontIconsList;

            $output = "";
            foreach ( $era_fontIconsList as $icon )
            {
                $output .= '<span class="era_icon fa fa-'.$icon.'" data-icon_class="fa-'.$icon.'" ></span>';
            }

            return $output;
        }
    }
}