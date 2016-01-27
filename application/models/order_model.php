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