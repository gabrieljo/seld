<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Market_model extends CI_Model{

	private $tableName 	= 'tbl_market';
	private $prefix 	= 'mk';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	
}

/* End of file Market_model.php */
/* Location: application/controllers/Market_model.php */