<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Purchase_model extends CI_Model{

	private $tableName 	= 'tbl_purchases';
	private $prefix 	= 'pc';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this will count total user logs
	 */
	public function countPurchases($client_id=0){

		$this->db->where('pc_cl_id', $client_id);
		//$this->db->where('pc_status', 'paid');
		return $this->db->count_all_results($this->tableName);
	}

	/**
	 * this will return the list of favourites.
	 */
	public function getPurchases($client_id=0, $limit=20, $offset=0){

		$this->db->select('tbl_purchases.*');

		$this->db->where('pc_cl_id', $client_id);
		//$this->db->where('pc_status', 'paid');

		$this->db->limit($limit, $offset);
		return $this->db->get($this->tableName);
	}
}

/* End of file purchase_model.php */
/* Location: application/controllers/purchase_model.php */