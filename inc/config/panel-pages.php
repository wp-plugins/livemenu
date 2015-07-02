<?php 

// Import & Export Page
$lm_panel_pages = '<div class="era-panel-page era-panel-page-1">';

	$lm_panel_pages .= __('To Export Menus, Please Follow Tutorial','era_fw') . '<br/><br/>';

	$lm_panel_pages .= era_helpers_options::button(array(
		'id'	=> 'lm-btn-export-config',
		'title'	=> __('Export Settings & Style') . '<img src="'.LM_BASE_URL.'assets/img/1.gif" >',
		'after'	=> '<br/>'
	));

	$lm_panel_pages .= era_helpers_options::button(array(
		'id'	=> 'lm-btn-import-config',
		'title'	=> __('Import Settings & Style','era_fw') . '<img src="'.LM_BASE_URL.'assets/img/1.gif" >',
		'after'	=> '<br/>'
	));

	$lm_panel_pages .= era_helpers_options::button(array(
		'id'	=> 'lm-btn-import-menus',
		'title'	=> __('Import Menus','era_fw') . '<img src="'.LM_BASE_URL.'assets/img/1.gif" >',
		'after'	=> '<br/><br/>'
	));

	$lm_panel_pages .= __('Importing & Exporting take time to finish, please dont close the browser','era_fw') . '<br/><br/>';

$lm_panel_pages .= '</div>';


// Add Menus Page
$lm_panel_pages .= '<div class="era-panel-page era-panel-page-2">';

	$lm_panel_pages .= __('How many menus you want to add ?','era_fw') . '<br/><br/>';

	$lm_panel_pages .= era_helpers_options::select(array(
		'id'		=> 'lm-panel-menus-count',
		'value'		=> get_option('livemenu_menus_added_count'),
		'options'	=> array(
			0 	=> 0,
			1 	=> 1,
			2 	=> 2,
			3 	=> 3,
			4 	=> 4,
			5 	=> 5,
			6 	=> 6,
			7 	=> 7,
			8 	=> 8,
			9 	=> 9,
			10 	=> 10,
		),
		'after'	=> '<br/><br/>'
	));

	$lm_panel_pages .= era_helpers_options::button(array(
		'id'	=> 'lm-panel-save-menus-count',
		'title'	=> __('Add','era_fw') . '<img src="'.LM_BASE_URL.'assets/img/1.gif" >',
		'after'	=> '<br/><br/>'
	));

	$lm_panel_pages .= __('For more info, please check the documentation, section Integrration','era_fw') . '<br/><br/>';

$lm_panel_pages .= '</div>';


// Custom CSS
$lm_panel_pages .= '<div class="era-panel-page era-panel-page-3">';

	$lm_panel_pages .= __('Custom CSS','era_fw') . '<br/><br/>';

	$lm_panel_pages .= era_helpers_options::textarea(array(
		'id'		=> 'lm-panel-custom-css',
		'value'		=> get_option('livemenu_custom_css'),
		'after'	=> '<br/><br/>'
	));

	$lm_panel_pages .= era_helpers_options::button(array(
		'id'	=> 'lm-panel-save-custom-css',
		'title'	=> __('Save','era_fw') . '<img src="'.LM_BASE_URL.'assets/img/1.gif" >',
		'after'	=> '<br/><br/>'
	));

$lm_panel_pages .= '</div>';


// Developers
$lm_panel_pages .= '<div class="era-panel-page era-panel-page-4">';

	$lm_panel_pages .= __('Enable Demo','era_fw') . '<br/><br/>';

	$lm_panel_pages .= era_helpers_options::checkbox(array(
		'id'		=> 'lm-panel-demo',
		'value'		=> get_option('livemenu_enable_demo'),
		'after'	=> '<br/><br/>'
	));

	$lm_panel_pages .= era_helpers_options::button(array(
		'id'	=> 'lm-panel-enable-demo',
		'title'	=> __('Save','era_fw') . '<img src="'.LM_BASE_URL.'assets/img/1.gif" >',
		'after'	=> '<br/><br/>'
	));

	$lm_panel_pages .= __('Enable Demo for your clients, same as ','era_fw') . '<a href="livemenuwp.com/live?livemenu_live=on">'.__('My Demo Site','era_fw').'</a>' . '<br/><br/>';

$lm_panel_pages .= '</div>';