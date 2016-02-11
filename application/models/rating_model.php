<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Member
 * Model Class for "member" and "member_meta" tables.
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Rating_model extends CI_Model{

	private $tableName 	= 'tbl_ratings';
	private $prefix 	= 'rt';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this will find rating for client and product.
	 */
	public function getRating($client_id=0, $product_id=0){

		$this->db->where('rt_cl_id', $client_id);
		$this->db->where('rt_pr_id', $product_id);

		$query = $this->db->get($this->tableName);
		return $query->row();
	}

	/**
	 * this will calculate average rating
	 */
	public function getRatingAverage($product_id=0){

		$this->db->select_avg('rt_value', 'rating');
		$this->db->where('rt_pr_id', $product_id);

		$query = $this->db->get($this->tableName);
		$row = $query->row();
		return $row->rating;
	}
}

/* End of file rating_model.php */
/* Location: application/controllers/rating_model.php */