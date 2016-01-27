<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Member
 * Model Class for "member" and "member_meta" tables.
 *
 * @package		TruthCRM
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class User_model extends CI_Model{

	private $tableName 	= 'tbl_users';
	private $prefix 	= 'usr';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * This method validates the provided username/password.
	 * 
	 * @param $username 	The login ID of user
	 * @param $password 	The 
	 */
	public function validate($username=null, $password=null){
		$return = null;
		if ($username != null && $password != null){
			$row = $this->findById($username, 'usr_email');
			if ($row != null){ // Email Exists
				if ($row->usr_password == $password){
					$return = $row;
				}
				else{
					// Password doesn't match!
				}
			}
			else{
				// Email Not Found!
			}
		}
		return $return;
	}
}

/* End of file user_model.php */
/* Location: application/controllers/user_model.php */