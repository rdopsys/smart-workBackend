<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if(!defined('ABSPATH')) exit;



class mg_grid_on_elementor extends Widget_Base {
	
	public function get_icon() {
		return 'emtr_lcweb_icon';
	}
	
	public function get_name() {
		return 'mediagrid';
	}

	public function get_categories() {
		return array('general-elements');
	}

	public function get_title() {
		return 'Media Grid';
	}



   protected function _register_controls() {

		// be sure tax are registered
		include_once(MG_DIR .'/admin_menu.php'); 
		register_taxonomy_mg_grids();
		register_cpt_mg_item();
		
		
		/*** store common arrays into globals ***/
		
		// grids array
		$grids_arr = array(); 
		foreach(get_terms('mg_grids', array('hide_empty' => 0, 'orderby' => 'name')) as $grid) {
			$grids_arr[ $grid->term_id ] = $grid->name;
		}
		
		// pagination systems
		$pag_sys = array(
			'' => __('default one', MG_ML)
		);
		foreach(mg_static::pag_layouts() as $type => $name) {
			$pag_sys[ $type ] = $name;
		}
		
		// filters array (use full list for now)
		$filters_arr = array(
			'' => __('no initial filter', MG_ML)
		); 
		foreach(mg_static::item_cats() as $cat_id => $cat_name) {
			$filters_arr[ $cat_id ] = $cat_name;
		}


		
		///// ADVANCED FILTERS ADD-ON //////////
		////////////////////////////////////////
		
		$mgaf_f_list = (class_exists('mgaf_static')) ? mgaf_static::filters_list() : array();
		
		if(defined('MGAF_DIR') && !empty($mgaf_f_list)) {
			$filter_ctrl = array(
			  'label' 		=> __('Enable filters?', MG_ML),
			  'description'	=> __('Allows items filtering', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => '0',
			  'options' => array(
			  	'0' => __('No', MG_ML),
				'1' => __('Yes (MG categories)', MG_ML),
			  ) + $mgaf_f_list
		   );
		   
		   $search_f_cond = array(
		   	'condition' => array(
				  'filter' => array('0', '1'),
			  ),
		   );
		}
		
		else {
			$filter_ctrl = array(
			  'label' 		=> __('Enable filters?', MG_ML),
			  'description'	=> __('Allows items filtering by category', MG_ML),
			  'type' 		=> Controls_Manager::SWITCHER,
			  'default' 	=> '',
			  'label_on' 	=> __('Yes'),
			  'label_off' 	=> __('No'),
			  'return_value' => '1',
		   );
		   
		   $search_f_cond = array();
		}
		
		///////////////////////////////////////



		// MAIN PARAMS
		$this->start_controls_section(
			'main',
			array(
				'label' => __('Main Parameters', MG_ML),
			)
		);
  
  
		$this->add_control(
		   'gid',
		   array(
			  'label' 	=> __('Grid', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => current(array_keys($grids_arr)),
			  'options' => $grids_arr
		   )
		);
		
		$this->add_control(
		   'title_under',
		   array(
			  'label' 	=> __('Text under items?', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => '0',
			  'options' => array(
			  	'0' => __('No', MG_ML),
				'1' => __('Yes - attached to item', MG_ML),
				'2' => __('Yes - detached from item', MG_ML),
			  )
		   )
		);
		
		$this->add_control(
		   'pag_sys',
		   array(
			  'label' 	=> __('Pagination system', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => current(array_keys($pag_sys)),
			  'options' => $pag_sys
		   )
		);
		
		$this->add_control(
		   'filter',
		   $filter_ctrl
		);
		
		$this->add_control(
		   'search',
		   array(
			  'label' 		=> __('Enable search?', MG_ML),
			  'description'	=> __('Enables search bar for grid items', MG_ML),
			  'type' 		=> Controls_Manager::SWITCHER,
			  'default' 	=> '',
			  'label_on' 	=> __('Yes'),
			  'label_off' 	=> __('No'),
			  'return_value' => '1',
		   ) +  $search_f_cond
		);

		$this->add_control(
		   'filters_align',
		   array(
			  'label' 	=> __('Text under items?', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => 'top',
			  'options' => array(
			  	'top'	=> __('On top', MG_ML),
				'left'	=> __('Left side', MG_ML),
				'right'	=> __('Right side', MG_ML),
			  ),
			  'condition' => array(
				  'filter' => '1',
			  ),
		   )
		);
		
		$this->add_control(
		   'hide_all',
		   array(
			  'label' 		=> __('Hide "All" filter?', MG_ML),
			  'description'	=> __('Hides the "All" option from filters', MG_ML),
			  'type' 		=> Controls_Manager::SWITCHER,
			  'default' 	=> '',
			  'label_on' 	=> __('Yes'),
			  'label_off' 	=> __('No'),
			  'return_value' => '1',
			  'condition' => array(
				  'filter' => '1',
			  ),
		   )
		);
		
		$this->add_control(
		   'def_filter',
		   array(
			  'label' 	=> __('Default filter', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => current(array_keys($filters_arr)),
			  'options' => $filters_arr,
			  'condition' => array(
				  'filter' => '1',
			  ),
		   )
		);
       
		$this->add_control(
		   'mf_lightbox',
		   array(
			  'label' 	=> __('Media-focused lightbox mode?', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => '',
			  'options' => array(
			  	'' => __('As default', MG_ML),
				1  => __('Yes', MG_ML),
				0  => __('No', MG_ML),
			  ),
		   )
		);
		
		$this->add_control(
			'mobile_tresh', 
			array(
				'label' => __('Custom mobile threshold (in pixels)', MG_ML),
				'description'	=> __('Overrides global treshold. Leave empty to ignore', MG_ML),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 50,
						'max' => 2000,
						'step' => 10,
					),
				),
			)
		);
		
		
		///// OVERLAY MANAGER ADD-ON ///////////
		////////////////////////////////////////
		if(defined('MGOM_DIR')) {
			
			register_taxonomy_mgom(); // be sure tax are registered
			$overlays = get_terms('mgom_overlays', 'hide_empty=0');
			
			$ol_arr = array(
				__('default one', MG_ML) => ''
			);
			foreach($overlays as $ol) {
				$ol_arr[ $ol->term_id ] = $ol->name;	
			}
			
			$this->add_control(
			   'overlay',
			   array(
				  'label' 	=> __('Custom Overlay', MG_ML),
				  'type' 	=> Controls_Manager::SELECT,
				  'default' => current(array_keys($ol_arr)),
				  'options' => $ol_arr,
			   )
			);
		}
		////////////////////////////////////////
		
		
		$this->end_controls_section();
		
		
		
		// CUSTOM STYLES
		$this->start_controls_section(
			'style',
			array(
				'label' => __('Custom Styles', MG_ML) . '<br><em style="font-weight: normal; position: relative;line-height: 18px; font-size: 11px;">Leave fields empty to use global values</em>',
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		
		$this->add_control(
			'cell_margin', 
			array(
				'label' => __('Items margin', MG_ML),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
			)
		);
		
		$this->add_control(
			'border_w', 
			array(
				'label' => __('Items border width', MG_ML),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
			)
		);
		
		$this->add_control(
		   'border_col',
		   array(
			  'label' 		=> __('Items border color', MG_ML),
			  'type' 		=> Controls_Manager::COLOR,
			  'default' 	=> '',
		   )
		);
		
		$this->add_control(
			'border_rad', 
			array(
				'label' => __('Items border radius', MG_ML),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
			)
		);
		
		$this->add_control(
		   'outline',
		   array(
			  'label' 	=> __('Display items outline?', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => '',
			  'options' => array(
			  	'' => __('As default', MG_ML),
				1  => __('Yes', MG_ML),
				0  => __('No', MG_ML),
			  ),
		   )
		);
		
		$this->add_control(
		   'outline_col',
		   array(
			  'label' 		=> __('Outline color', MG_ML),
			  'type' 		=> Controls_Manager::COLOR,
			  'default' 	=> '',
		   )
		);

		$this->add_control(
		   'shadow',
		   array(
			  'label' 	=> __('Display items shadow?', MG_ML),
			  'type' 	=> Controls_Manager::SELECT,
			  'default' => '',
			  'options' => array(
			  	'' => __('As default', MG_ML),
				1  => __('Yes', MG_ML),
				0  => __('No', MG_ML),
			  ),
		   )
		);
		
		$this->add_control(
		   'txt_under_col',
		   array(
			  'label' 		=> __('Text under images color', MG_ML),
			  'type' 		=> Controls_Manager::COLOR,
			  'default' 	=> '',
		   )
		);

		$this->end_controls_section();
   }


	
	////////////////////////


	protected function render() {
     	$vals = $this->get_settings();
		//var_dump($vals);

		// numeric vals (ignore unit)	
		foreach(array('mobile_tresh', 'cell_margin', 'border_w', 'border_rad') as $f) {
			$vals[$f] = $vals[$f]['size'];	
		}	


		$parts = array(
			'gid', 'title_under', 'pag_sys', 'search', 'filter', 'filters_align', 'hide_all', 'def_filter', 'mobile_tresh',
			'cell_margin', 'border_w', 'border_col', 'border_rad', 'outline', 'outline_col', 'shadow', 'txt_under_col',
			'overlay'
		);
		$params = '';
		
		foreach($parts as $part) {
			$params .= $part.'="';
			
			if(!isset($vals[$part])) {$vals[$part] = '';}
			$params .= $vals[$part].'" ';	
		}
		
		echo do_shortcode('[mediagrid '. $params .']');
	}

}
