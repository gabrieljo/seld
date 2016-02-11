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

class Favourite_model extends CI_Model{

	private $tableName 	= 'tbl_favourites';
	private $prefix 	= 'fav';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this will find favourite record for client and product.
	 */
	public function getFavourite($client_id=0, $product_id=0){

		$this->db->where('fav_cl_id', $client_id);
		$this->db->where('fav_pr_id', $product_id);

		$row = $this->db->get($this->tableName);
		return $row->row();
	}

	/**
	 * this will count total user logs
	 */
	public function countUserFavourites($client_id=0){

		$this->db->where('fav_cl_id', $client_id);
		$this->db->where('fav_status', 'published');
		return $this->db->count_all_results($this->tableName);
	}

	/**
	 * this will return the list of favourites.
	 */
	public function getWatchlist($client_id=0, $limit=20, $offset=0){

		$this->db->select('tbl_favourites.*, tbl_products.*');
		$this->db->join('tbl_products', 'tbl_products.pr_id = tbl_favourites.fav_pr_id');

		$this->db->where('fav_cl_id', $client_id);
		$this->db->where('fav_status', 'published');

		$this->db->limit($limit, $offset);
		return $this->db->get($this->tableName);
	}
}

/* End of file favourite_model.php */
/* Location: application/controllers/favourite_model.php */