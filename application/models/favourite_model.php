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
}

/* End of file client_model.php */
/* Location: application/controllers/client_model.php */