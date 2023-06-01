<?php
//  visual composer integration


function mg_on_visual_composer() {
	
	// be sure tax are registered
	include_once(MG_DIR .'/admin_menu.php'); 
	register_taxonomy_mg_grids();
	register_cpt_mg_item();
	
	
	// grids array
	$grids_arr = array(); 
	foreach(get_terms('mg_grids', array('hide_empty' => 0, 'orderby' => 'name')) as $grid) {
    	$grids_arr[ $grid->name ] = $grid->term_id;
    }
	
	// pagination systems
	$pag_sys = array(
		__('default one', MG_ML) => ''
	);
	foreach(mg_static::pag_layouts() as $type => $name) {
		$pag_sys[$name] = $type;
	}
	
	// filters array (use full list for now)
	$filters_arr = array(
		__('no initial filter', MG_ML) => ''
	); 
	foreach(mg_static::item_cats() as $cat_id => $cat_name) {
    	$filters_arr[ $cat_name ] = $cat_id;
    }
	
	
	
	
	///// ADVANCED FILTERS ADD-ON //////////
	////////////////////////////////////////
	
	if(class_exists('mgaf_static')) {
		mgaf_register_filters_taxonomy(); // register taxonomy	
		$mgaf_f_lists = mgaf_static::filters_list();
	}
	
	if(defined('MGAF_DIR') && !empty($mgaf_f_lists)) {
		$filter_array = array(
			'group'			=> __('Main parameters', MG_ML),
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_filter_grid',
			'heading' 		=> __('Enable filters?', MG_ML),
			'param_name' 	=> 'filter',
			'admin_label' 	=> true,
			'value' 		=> array(
				__('No', MG_ML) => '0',
				__('Yes (MG categories)', MG_ML) => '1',
			  ) + 
			  array_flip($mgaf_f_lists),
			  
			'description'	=> __('Allows items filtering', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		);
			
		$search_f_cond = array(
			'element'	=> 'filter',
			'value'		=> array('0', '1'),
			'not_empty'	=> false,
		);
	}
	
	else {
		$filter_array = array(
			'group'			=> __('Main parameters', MG_ML),
			'type' 			=> 'checkbox',
			'class' 		=> 'mg_filter_grid',
			'param_name' 	=> 'filter',
			'value' 		=> array(
				'<strong>'. __('Enable filters?', MG_ML) .'</strong>' => 1
			),
			'description'	=> __('Allows items filtering by category', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		);
	   
		$search_f_cond = false;
	}
		
	///////////////////////////////////////
	
	
		
	// filters enabling dependency
	$filters_dependency = array(
		'element'	=> 'filter',
		'value'		=> array('1'),
		'not_empty'	=> false,
	);
	
	
	
	// parameters
	$params = array(
		array(
			'group'			=> __('Main parameters', MG_ML),
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_vc_cat',
			'heading' 		=> __('Grid', MG_ML),
			'param_name' 	=> 'gid',
			'admin_label' 	=> true,
			'value' 		=> $grids_arr,
			'description'	=> __('Which grid to display?', MG_ML),
		),
		array(
			'group'			=> __('Main parameters', MG_ML),
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_title_under',
			'heading' 		=> __('Text under items?', MG_ML),
			'param_name' 	=> 'title_under',
			'admin_label' 	=> true,
			'value' 		=> array(
				__('No', MG_ML) => 0,
				__('Yes - attached to item', MG_ML) => 1,
				__('Yes - detached from item', MG_ML) => 2,
			),
			//'description'	=> __('Moves overlay title beneath items', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		
		$filter_array,
		
		array(
			'group'			=> __('Main parameters', MG_ML),
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_pag_sys',
			'heading' 		=> __('Pagination system', MG_ML),
			'param_name' 	=> 'pag_sys',
			'admin_label' 	=> true,
			'value' 		=> $pag_sys,
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		
		
		array(
			'group'			=> __('Main parameters', MG_ML),
			'type' 			=> 'checkbox',
			'dependency'	=> $search_f_cond,
			'class' 		=> 'mg_search_bar',
			'param_name' 	=> 'search',
			'value' 		=> array(
				'<strong>'. __('Enable search?', MG_ML) .'</strong>' => 1
			),
			'description'	=> __('Enables search bar for grid items', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		
		array(
			'group'			=> __('Main parameters', MG_ML),
			'dependency'	=> $filters_dependency,
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_filters_align',
			'heading' 		=> __('Filters position', MG_ML),
			'param_name' 	=> 'filters_align',
			'value' 		=> array(
				__('On top', MG_ML) => 'top',
				__('Left side', MG_ML) => 'left',
				__('Right side', MG_ML) => 'right',
			),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Main parameters', MG_ML),
			'dependency'	=> $filters_dependency,
			'type' 			=> 'checkbox',
			'class' 		=> 'mg_hide_all',
			'param_name' 	=> 'hide_all',
			'value' 		=> array(
				'<strong>'. __('Hide "All" filter?', MG_ML) .'</strong>' => 1
			),
			'description'	=> __('Hides the "All" option from filters', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Main parameters', MG_ML),
			'dependency'	=> $filters_dependency,
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_def_filter',
			'heading' 		=> __('Default filter', MG_ML),
			'param_name' 	=> 'def_filter',
			'value' 		=> $filters_arr,
			'description'	=> '',
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Main parameters', MG_ML),
			'type' 			=> 'textfield',
			'class' 		=> 'mg_mobile_treshold',
			'heading' 		=> __('Custom mobile threshold (in pixels)', MG_ML),
			'param_name' 	=> 'mobile_tresh',
			'value' 		=> '',
			'description'	=> __('Overrides global threshold. Leave empty to ignore', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		
		
		
		/* STYLING */
		array(
			'group'			=> __('Custom styles', MG_ML),
			'type' 			=> 'textfield',
			'class' 		=> 'mg_cells_margin',
			'heading' 		=> __('Items margin', MG_ML),
			'param_name' 	=> 'cell_margin',
			'admin_label' 	=> true,
			'value' 		=> '',
			'description'	=> __('Leave empty to use default value', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Custom styles', MG_ML),
			'type' 			=> 'textfield',
			'class' 		=> 'mg_border_w',
			'heading' 		=> __('Items border width', MG_ML),
			'param_name' 	=> 'border_w',
			'admin_label' 	=> true,
			'value' 		=> '',
			'description'	=> __('Leave empty to use default value', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Custom styles', MG_ML),
			'type' 			=> 'colorpicker',
			'class' 		=> 'mg_border_color',
			'heading' 		=> __('Items border color', MG_ML),
			'param_name' 	=> 'border_col',
			'admin_label' 	=> true,
			'value' 		=> '',
			'description'	=> __('Leave empty to use default value', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Custom styles', MG_ML),
			'type' 			=> 'textfield',
			'class' 		=> 'mg_cells_radius',
			'heading' 		=> __('Items border radius', MG_ML),
			'param_name' 	=> 'border_rad',
			'admin_label' 	=> true,
			'value' 		=> '',
			'description'	=> __('Leave empty to use default value', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Custom styles', MG_ML),
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_outline',
			'heading' 		=> __("Display items outline?", MG_ML),
			'param_name' 	=> 'outline',
			'admin_label' 	=> true,
			'value' 		=> array(
				__('As default', MG_ML) => '',
				__('Yes', MG_ML) => 1,
				__('No', MG_ML) => 0,
			),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Custom styles', MG_ML),
			'type' 			=> 'colorpicker',
			'class' 		=> 'mg_outline_color',
			'heading' 		=> __('Outline color', MG_ML),
			'param_name' 	=> 'outline_col',
			'admin_label' 	=> true,
			'value' 		=> '',
			'description'	=> __('Leave empty to use default value', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Custom styles', MG_ML),
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_shadow',
			'heading' 		=> __("Display items shadow?", MG_ML),
			'param_name' 	=> 'shadow',
			'admin_label' 	=> true,
			'value' 		=> array(
				__('As default', MG_ML) => '',
				__('Yes', MG_ML) => 1,
				__('No', MG_ML) => 0,
			),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
		array(
			'group'			=> __('Custom styles', MG_ML),
			'type' 			=> 'colorpicker',
			'class' 		=> 'mg_txt_under_color',
			'heading' 		=> __('Text under images color', MG_ML),
			'param_name' 	=> 'txt_under_col',
			'admin_label' 	=> true,
			'value' 		=> '',
			'description'	=> __('Leave empty to use default value', MG_ML),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
		),
	);
	
	
	
	///// OVERLAY MANAGER ADD-ON ///////////
	if(defined('MGOM_DIR')) {
		register_taxonomy_mgom(); // be sure tax are registered
		$overlays = get_terms('mgom_overlays', 'hide_empty=0');
		
		$ol_arr = array(
			__('default one', MG_ML) => ''
		);
		foreach($overlays as $ol) {
			$ol_arr[ $ol->name ] = $ol->term_id;	
		}
		
		$params[] = array(
			'group'			=> __('Main parameters', MG_ML),
			'type' 			=> 'dropdown',
			'class' 		=> 'mg_custom_overlay',
			'heading' 		=> __('Custom Overlay', MG_ML),
			'param_name' 	=> 'overlay',
			'admin_label' 	=> true,
			'value' 		=> $ol_arr,
		);
	}
	///////////////////////////////////////*/
	
		  
	
	// compile
	vc_map(
        array(
            'name' 			=> 'Media Grid',
			'description'	=> __("Displays LCweb's Media Grid", MG_ML),
            'base' 			=> 'mediagrid',
            'category' 		=> __("Content", "mg_ml"),
			'icon'			=> MG_URL .'/img/vc_icon.png',
            'params' 		=> $params,
			//'custom_markup' => load_template( MG_DIR .'/builders_integration/vc_custom_markup.php')
        )
    );
}
add_action( 'vc_before_init', 'mg_on_visual_composer');