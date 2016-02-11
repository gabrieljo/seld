<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Design_Products_model extends CI_Model{

	private $tableName 	= 'tbl_design_products';
	private $prefix 	= 'd_pr';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
		parent::setOrder('d_pr_id'); 	// Set List Order
		parent::setStatus(FALSE);
	}
}

/* End of file Design_Products_model.php */
/* Location: application/controllers/Design_Products_model.php */