<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package		CodeIgniter
 * @author		Sudarshan Shakya
 */

// ------------------------------------------------------------------------


class Category {

	public $CI;
	public $all;
	public $categories = array();

	public function __construct(){

		//log_message('debug', "Category Class Initialized");
		$this->CI =& get_instance();

		$this->CI->load->model('category_model');
		
		$this->_prepareCategory();
	}

	// --------------------------------------------------------------------

	private function _prepareCategory(){

		$this->all = $this->CI->category_model->findAll();

		// Get Parents
		$cats = array();
		foreach ($this->all->result() as $k){

			if ($k->cat_parent == 0){
				$cats['cat_' . $k->cat_id] = array(
								'id' 	=> $k->cat_id,
								'name' 	=> $k->cat_name,
								'subs'	=> array()								
							);
			}
		}

		// Assign subs.
		foreach ($this->all->result() as $k){

			if ($k->cat_parent != 0){
				$cats['cat_' . $k->cat_parent]['subs'][] = array(
								'id' 	=> $k->cat_id,
								'name' 	=> $k->cat_name
							);
			}
		}

		$this->categories = $cats;

		#new dBug($cats);exit;
		#new dBug($this->all);exit;
	}

	public function getCategories(){

		return $this->categories;
	}

	public function getSubs($parent_id=0){

		return $parent_id == 0 ? null : @$this->categories['cat_'.$parent_id]['subs'];
	}

	public function getSiblingCategories($id=0){

		$parent_id = 0;
		if ($id > 0){			
			foreach ($this->all->result() as $k){
				if ($k->cat_id == $id){
					$parent_id = $k->cat_parent;
					break;
				}
			}
		}

		$this->getSubs($parent_id);
	}
}


/* End of file Category.php */
/* Location: ./system/libraries/Category.php */