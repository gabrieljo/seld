<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Design_Options_model extends CI_Model{

	private $tableName 	= 'tbl_design_options';
	private $prefix 	= 'd_op';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
		parent::setOrder('d_op_sortorder', 'desc'); 	// Set List Order
		parent::setStatus(FALSE);
	}

	/**
	 * this method will find the options for given type
	 */
	public function findOptions($type=0){
		$this->db->where('d_op_pr_id', $type);
		$this->db->where('d_op_status', '1');
		return $this->db->get($this->tableName);
	}
}

/* End of file Design_Options_model.php */
/* Location: application/controllers/Design_Products_model.php */