<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Model {

	private $prefix 	='';	// Prefix of column Names
	private $tableName 	= ''; 	// Name of the model table
	private $primaryKey = '';	// Primary ID column name
	private $list_order = NULL;	// Order of the list display
	private $status 	= TRUE;	// Has Status field

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct($table='', $prefix='', $key="")
	{
		$this->prefix = $prefix;
		$this->tableName = $table;

		if ($key == ''){
			$this->primaryKey = $prefix.'_uid';
		}
		else{
			$this->primaryKey = $prefix.'_id';
		}
	}
	//log_message('debug', "Model Class Initialized");

	/**
	 * __get
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string
	 * @access private
	 */
	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}

	/**
	 * this method will set the order of the list display field
	 */
	public function setOrder($order='', $sort='asc'){
		if (is_array($order) && !empty($order)){
			$this->list_order = $order;
		}
		else if($order!=''){
			$this->list_order = array($order=>$sort);
		}
	}

	/**
	 * this method will set the status field
	 */
	public function setStatus($val=TRUE){
		$this->status = $val;
	}

	/**
	 * This method will check if the extension of the model class has set up the configuration.
	 * 
	 * The configuration consists of table name, table fields and primary key
	 * @access private
	 */
	private function __checkConfig(){
		if ( $this->tableName == "" || $this->prefix == "" || $this->primaryKey == ""){
			$message = 'Class : <strong>'.get_class($this).'</strong><br />Model class not configured correctly!<br /><br /><i>';
			$message.= $this->tableName   	== "" ? 'Table Name not set<br />'  	: '';
			$message.= $this->prefix 		== "" ? 'Table Prefix not set<br />' 	: '';
			$message.= $this->primaryKey  	== "" ? 'Primary Key not set<br />' 	: '';
			show_error($message.'</i>');
		}
	}

	/**
	 * This method will find a specific row as requested.
	 * 		All the meta values is also returned if existent.
	 *
	 * @param $id 		The id of the row required
	 * @param $field 	The field name to query
	 */
	public function findById($id=0, $field=null){
		$this->__checkConfig();

		$field = $field == null ? $this->primaryKey : $field;

		$query = $this->db->get_where($this->tableName, array($field=>$id));
		$return = $query->row();
		return $return;
	}

	/**
	 * This method will find all the rows as requested.
	 *
	 * @param $limit 	If not set of is zero then max of 1000 rows are returned.
	 * @param $offset   Starting point of records display
	 */
	public function findAll($limit=1000, $offset=0, $options=array()){
		$this->__checkConfig();

		if ($this->status == TRUE)
			$this->db->where($this->prefix.'_status !=', 'deleted');

		// Additional conditions
		if (is_array($options) && !empty($options)){
			foreach ($options as $k=>$v){
				$this->db->where($k, $v);
			}
		}

		// Order Field
		if ($this->list_order != NULL){
			foreach ($this->list_order as $k=>$v){
				$v = $v == '' ? 'ASC' : $v;
				$this->db->order_by($k, $v);
			}
		}

		$query 	= $this->db->get($this->tableName, $limit, $offset);
		return $query;
	}

	/**
	 * this method will count the total rows available
	 */
	public function countRows($key=null, $value=null){
		if ($key != null && $value != null){
			$this->db->where($key, $value);
		}

		if ($this->status == TRUE)
			$this->db->where($this->prefix.'_status !=', 'deleted');
		
		$query 	= $this->db->from($this->tableName);
		return $this->db->count_all_results();
	}

	/**
	 * This method will save the record into database (INSERT OR UPDATE).
	 * 		The data for "META table" is dependent upon the table's _UID field.
	 * 		If _UID is not available, mysql_insert_id will be used
	 *
	 * @param $id 		The id of the row (0 if New)
	 * @param form 		The assoc. array of the table fields with the values
	 * @param meta 		The assoc. array of the meta property of the record.
	 * @return boolean
	 */
	public function save($form=array(), $id=0){
		$this->__checkConfig();
		// Create a Unique identifier for the row
		$uid 	= function_exists('random_string') ? random_string('unique') : md5(rand(1000,999999).time());
		$return = true;

		if (($id === 0 || $id == '') && is_array($form) && count($form) > 0){ 	// Insert
			// Update Sortorder field automatically!
			if ($this->list_order != NULL){
				$new_value		= 1;

				foreach ($this->list_order as $k=>$v){
					$order_field = $k;
				}
				
				$this->db->select_max($order_field, 'maxval');
				$query 	= $this->db->get($this->tableName);
				$row 	= $query->row();
				$new_value = $row != null ? $row->maxval + 1 : $new_value;

				$form[$order_field] = $new_value;
			}
			$form[$this->prefix . '_created_at'] = date("Y-m-d H:i:s");
			$form[$this->prefix . '_uid'] = $uid;

			$return = $this->db->insert($this->tableName, $form);
		}
		else{
			$form[$this->prefix . '_updated_at'] = date("Y-m-d H:i:s");

			$this->db->where($this->primaryKey, $id);

			$return = $this->db->update($this->tableName, $form);
		}
		return $return;
	}

	/**
	 * This method will trash the selected record.
	 *
	 * @param $id 		The field value to check before deleting : PRIMARY KEY VALUE
	 * @param $delete 	boolean value for permanently deleting the record
	 */
	public function trash($id=0, $field=null, $value='deleted'){
		$this->__checkConfig();

		$field = $field != null ? $field : $this->prefix.'_status';
		if ($field != null){
			$this->db->where($this->primaryKey, $id);
			$this->db->update($this->tableName, array($field=>$value));
		}			
	}


	/**
	 * This method will delete the selected record permanently.
	 *
	 * @param $id 		The field value to check before deleting : PRIMARY KEY VALUE
	 * @param $delete 	boolean value for permanently deleting the record
	 */
	public function delete($id=0, $field=null){
		$this->__checkConfig();

		$field = $field != null ? $field : $this->primaryKey;
		// WARNING! THIS WILL DELETE THE ROW PERMANENTLY
		$this->db->delete($this->tableName, array($field=>$id));
		
	}
}
// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */