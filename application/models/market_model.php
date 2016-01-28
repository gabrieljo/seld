<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2016 SAC.
 * @since		Version 1.0
 * @createdby 	Gabriel Jo
 */

class Market_model extends CI_Model{

	private $tableName 	= 'tbl_market';
	private $prefix 	= 'mk';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	function getList(){

		$this->db->select('tbl_market.mk_id, tbl_products.pr_title, tbl_products.pr_description, tbl_market.mk_price, tbl_market.mk_org_price, tbl_market.mk_uid');
		$this->db->from($this->tableName);
		$this->db->join('tbl_products', 'tbl_products.pr_id = tbl_market.mk_pr_id');
		$this->db->where('mk_cat_id', '0');

		return $query = $this->db->get()->result();
	}
}

/* End of file marekt_model.php */
/* Location: application/controllers/marekt_model.php */