<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2016 SAC.
 * @since		Version 1.0
 * @createdby 	Gabriel Jo
 */

class Category_model extends CI_Model{

	private $tableName 	= 'tbl_category';
	private $prefix 	= 'cat';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
		$this->setStatus(FALSE);
	}

	function getCategories($level=0, $dept=null){

		$this->db->where('cat_level', $level);
		if($dept != null) $this->db->where('cat_dept1', $dept);
		return $this->db->get($this->tableName)->result();
	}

	/**
	 * get sub categoreis
	 */
	public function getSubCategories($id=0){

		$this->db->where('cat_parent', $id);
		return $this->db->get($this->tableName);
	}
	
}

/* End of file marekt_model.php */
/* Location: application/controllers/marekt_model.php */