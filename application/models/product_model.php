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
	 * this will return the list of market items.
	 */
	public function getMarketProducts($client_id=0, $category=null, $order_by='name-asc', $limit=20, $offset=0){

		$this->allMarketProducts($client_id, $category);

		if ($order_by == 'name-asc'){
			$this->db->order_by('pr_title', 'asc');			
		}
		else if($order_by == 'name-desc'){
			$this->db->order_by('pr_title', 'desc');
		}
		else if($order_by == 'date-asc'){
			$this->db->order_by('pr_created_at', 'asc');
		}
		else if($order_by == 'date-desc'){
			$this->db->order_by('pr_created_at', 'desc');
		}
		else if ($order_by == 'price-asc'){
			$this->db->order_by('pr_mk_orig_price', 'asc');
		}
		else{
			$this->db->order_by('pr_mk_orig_price', 'desc');
		}

		$this->db->limit($limit, $offset);
		return $this->db->get();
	}

	/**
	 * this will return the market products count
	 */
	public function countMarketProducts($client_id=0, $category=null){

		$this->allMarketProducts($client_id, $category);
		return $this->db->count_all_results();
	}

	/**
	 * market products conditions.
	 */
	private function allMarketProducts($client_id=0, $category=null, $keyword=''){

		$this->db->select('tbl_products.*, tbl_category.cat_name, tbl_favourites.fav_status');
		$this->db->from('tbl_products');

		if ($category){
			$id = $category->cat_id;
			$this->db->join('tbl_category', 'tbl_category.cat_id=tbl_products.pr_cat_id AND (tbl_category.cat_id=' . $id . ' OR tbl_category.cat_parent=' . $id .')');
		}
		else{
			$this->db->join('tbl_category', 'tbl_category.cat_id = tbl_products.pr_cat_id', 'left');
		}

		/**
		 * join tbl_favourites
		 */
		//$this->db->join('tbl_favourites', 'tbl_favourites.fav_cl_id = ' . $client_id . ' AND tbl_favourites.fav_pr_id = tbl_products.pr_id', 'left');
		$this->db->join('tbl_favourites', 'tbl_favourites.fav_pr_id = tbl_products.pr_id AND tbl_favourites.fav_cl_id=' . $client_id, 'left');

		$this->db->where('pr_mk_status', 'listed');		
		$this->db->where('pr_contents !=', '');
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
		$this->db->order_by('pr_title', 'asc');
		
		$this->db->where('pr_cl_id', $client_id);
		$this->db->where('pr_status != ', 'deleted');

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
		$this->db->where('pr_status != ', 'deleted');
		
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