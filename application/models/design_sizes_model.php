<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * NO CRUD FUNCTIONALITY ``````````````````````````````
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Design_sizes_model extends CI_Model{

	private $tableName 	= 'tbl_design_sizes';
	private $prefix 	= 'd_sz';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this method will find products of the client
	 */
	public function getDimension($size='a4'){
		$dimn = $this->findById($size, 'd_sz_name');
		return $dimn;
	}
}

/* End of file Product_model.php */
/* Location: application/controllers/Product_model.php */