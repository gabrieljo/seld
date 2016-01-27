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

	/**
	 * find the list of users with total designs
	 */
	public function getClients($keyword='', $limit=20, $offset=0){

		/**
		 * Join Products.
		 */
		$this->db->select('tbl_clients.*, COUNT(tbl_products.pr_id) as total');
		$this->db->join('tbl_products', 'tbl_clients.cl_id = tbl_products.pr_cl_id')
				 ->group_by('tbl_products.pr_cl_id');
		
		if ($keyword != ''){
			/**
			 * check if keyword has space. (firstname_lastname)
			 */
			$spaces = explode(' ', $keyword);

			$fname = @$spaces[0];
			$lname = count($spaces > 1) ? @$spaces[1] : $fname;

			if (count($spaces) > 1){ // check firstname and lastname.
				$this->db->like('cl_firstname', $fname, 'after');
				$this->db->like('cl_lastname', $lname, 'after');
			}
			else{
				$this->db->like('cl_firstname', $fname, 'after');
				$this->db->or_like('cl_lastname', $fname, 'after');
				$this->db->or_like('cl_email', $fname);
			}
		}
		$this->db->order_by("cl_firstname", "asc");
		return $this->db->get_where($this->tableName, array(), $limit, $offset);
	}

	/**
	 * this method will count the total rows
	 */
	public function getClientsCount($keyword=''){

		if ($keyword != ''){
			/**
			 * check if keyword has space. (firstname_lastname)
			 */
			$spaces = explode(' ', $keyword);
			$fname = @$spaces[0];
			$lname = count($spaces > 1) ? @$spaces[1] : $fname;

			if (count($spaces) > 1){ // check firstname and lastname.
				$this->db->like('cl_firstname', $fname, 'after');
				$this->db->like('cl_lastname', $lname, 'after');
			}
			else{
				$this->db->like('cl_firstname', $fname, 'after');
				$this->db->or_like('cl_lastname', $fname, 'after');
				$this->db->or_like('cl_email', $fname);
			}
		}

		$query 	= $this->db->from($this->tableName);
		return $this->db->count_all_results();
	}
}

/* End of file client_model.php */
/* Location: application/controllers/client_model.php */