<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Product_model extends CI_Model{

	private $tableName 	= 'tbl_products';
	private $prefix 	= 'pr';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}
}

/* End of file Product_model.php */
/* Location: application/controllers/Product_model.php */