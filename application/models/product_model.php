<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Product_model extends CI_Model{

	private $tableName 	= 'tbl_products';
	private $prefix 	= 'pr';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this method will find products of the client
	 */
	public function getProducts($client_id=0, $keyword='', $limit=20, $offset=0){
		if ($keyword != ''){
			$this->db->like('pr_title', $keyword);
			$this->db->or_like('pr_description', $keyword);
		}
		// get product type
		$this->db->join('tbl_design_products', 'tbl_design_products.d_pr_id=tbl_products.pr_type', 'left');
		$this->db->order_by('pr_created_at', 'desc');
		$this->db->where('pr_cl_id', $client_id);
		return $this->db->get($this->tableName, $limit, $offset);
	}

	/**
	 * this method will count total themes for the product
	 */
	public function countProducts($client_id=0, $keyword=''){
		if ($keyword != ''){
			$this->db->like('pr_title', $keyword);
			$this->db->or_like('pr_description', $keyword);
		}
		$this->db->where('pr_cl_id', $client_id);
		$this->db->from($this->tableName);
		return $this->db->count_all_results();		
	}

	/**
	 * this method will find if there is any "new" design of the client.
	 * @return boolean true of false
	 */
	public function hasNewProduct($client_id=0){
		$this->db->where('pr_cl_id', 	$client_id);
		$this->db->where('pr_status', 	'new');

		$query = $this->db->get($this->tableName);
		return $query->row();
	}
}

/* End of file Product_model.php */
/* Location: application/controllers/Product_model.php */