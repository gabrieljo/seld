<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Purchase_item_model extends CI_Model{

	private $tableName 	= 'tbl_purchase_items';
	private $prefix 	= 'pci';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this will find all the purchased themes by user.
	 */
	public function findThemes($cl_id=0){

		$this->db->select('tbl_purchase_items.*, tbl_products.*');
		$this->db->join('tbl_products', 'tbl_products.pr_id = tbl_purchase_items.pci_pr_id');
		$this->db->where('pci_cl_id', $cl_id);
		return $this->db->get($this->tableName);
	}

	/**
	 * list all the items.
	 */
	public function findItems($pc_id=0){

		$this->db->select("tbl_purchase_items.*, tbl_products.*, tbl_clients.*");
		$this->db->join('tbl_products', 'tbl_products.pr_id = tbl_purchase_items.pci_pr_id');
		$this->db->join('tbl_clients', 'tbl_clients.cl_id = tbl_purchase_items.pci_author_id');

		$this->db->where('pci_pc_id', $pc_id);
		return $this->db->get($this->tableName);
	}

	/**
	 * this will calculate total buyers and total earnings for a product.
	 */
	public function getItemSummary($product_id=0){

		$sql = "SELECT SUM(pci.pci_price) AS totalAmount, COUNT(pci.pci_id) AS totalTrans
					FROM tbl_purchase_items pci
						INNER JOIN tbl_purchases p
							ON p.pc_id = pci.pci_pc_id AND pci.pci_status='published' AND p.pc_status='paid'
						WHERE pci.pci_pr_id=" . $product_id;
		$sql = $this->db->query($sql);
		return $sql->row();
	}
}

/* End of file Purchase_item_model.php */
/* Location: application/controllers/Purchase_item_model.php */