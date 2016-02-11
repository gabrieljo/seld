<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Order_model extends CI_Model{

	private $tableName 	= 'tbl_orders';
	private $prefix 	= 'or';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this will find the orders history of the product.
	 */
	public function getProductHistory($client_id=0, $product_id=0, $limit=200, $offset=0){

		$this->db->where('or_cl_id', $client_id);
		$this->db->where('or_pr_id', $product_id);
		$this->db->order_by('or_created_at', 'desc');
		return $this->db->get($this->tableName, $limit, $offset);
	}

	/**
	 * this will find the orders history of the product.
	 */
	public function getAllOrders($type='', $limit=200, $offset=0){

		$this->db->select('tbl_orders.*, tbl_clients.cl_firstname, tbl_clients.cl_lastname, tbl_clients.cl_uid, tbl_products.pr_uid');
		$this->db->from('tbl_orders');
		$this->db->join('tbl_clients', 'tbl_clients.cl_id = tbl_orders.or_cl_id');
		$this->db->join('tbl_products', 'tbl_products.pr_id = tbl_orders.or_pr_id');

		if ($type != 'all'){
			$this->db->where('or_status', $type);			
		}

		$this->db->order_by('or_created_at', 'desc');
		$this->db->limit($limit, $offset);
		return $this->db->get();
	}

	/**
	 * this will count total orders of type
	 */
	public function countAllOrders($type='all'){
		
		if ($type != 'all'){
			$this->db->where('or_status', $type);			
		}
		$this->db->from($this->tableName);
		return $this->db->count_all_results();
	}

	/**
	 * this method will find products of the client
	 */
	public function getOrders($client_id=0, $limit=20, $offset=0){
		
		$this->db->where('or_cl_id', $client_id);
		return $this->db->get($this->tableName, $limit, $offset);
	}

	/**
	 * this method will count total themes for the product
	 */
	public function countOrders($client_id=0){
		
		$this->db->where('or_cl_id', $client_id);
		$this->db->from($this->tableName);
		return $this->db->count_all_results();
	}
}

/* End of file order_model.php */
/* Location: application/controllers/order_model.php */