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

class Client_model extends CI_Model{

	private $tableName 	= 'tbl_clients';
	private $prefix 	= 'cl';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
		parent::setOrder('cl_company'); 	// Set List Order
	}

	/**
	 * This method validates the provided email/password.
	 * 
	 * @param $email 	The login ID of user
	 * @param $password 	The 
	 */
	public function validate($email=null, $password=null){
		$return = null;
		if ($email != null && $password != null){
			$row = $this->findById($email, 'cl_email');
			if ($row != null){ // Email Exists
				if ($row->cl_status=='active' && $row->cl_password == $password){
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

	/**
	 * This method will check the existence of the given field in the db.
	 *
	 * @param $id 		The Current ID of member (0 if new)
	 * @param $email 	The email to check for
	 * @return boolean 	TRUE / FALSE
	 */
	public function check_availability($email='', $id=''){
		$this->db->where('cl_uid != ', 	$id);
		$this->db->where('cl_email', 	$email);
		$this->db->select('cl_uid');
		$query = $this->db->get($this->tableName);
		$row = $query->row();
		return $row == null ? true : false;
	}
}

/* End of file client_model.php */
/* Location: application/controllers/client_model.php */