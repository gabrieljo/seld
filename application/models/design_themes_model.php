<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Design_Themes_model extends CI_Model{

	private $tableName 	= 'tbl_design_themes';
	private $prefix 	= 'd_th';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
		parent::setOrder('d_th_order'); 	// Set List Order
	}

	/**
	 * this method will find the themes for the product
	 */
	public function findThemes($product_id=0, $size='', $keyword='', $limit=20, $offset=0){
		if ($keyword != ''){
			$this->db->like('d_th_name', $keyword);
			$this->db->or_like('d_th_description', $keyword);
		}
		$this->db->order_by('d_th_order');
		$this->db->where('d_th_status', '1');
		if ($size != ''){
			$this->db->where('d_th_size', $size);
		}
		$this->db->where('d_th_pid', $product_id);
		return $this->db->get($this->tableName, $limit, $offset);
	}

	/**
	 * this method will count total themes for the product
	 */
	public function countThemes($product_id=0, $size='', $keyword=''){
		if ($keyword != ''){
			$this->db->like('d_th_name', $keyword);
			$this->db->or_like('d_th_description', $keyword);
		}
		$this->db->where('d_th_status', '1');
		if ($size != ''){
			$this->db->where('d_th_size', $size);
		}
		$this->db->where('d_th_pid', $product_id);
		$this->db->from($this->tableName);
		return $this->db->count_all_results();		
	}
}

/* End of file Design_Themes_model.php */
/* Location: application/controllers/Design_Themes_model.php */