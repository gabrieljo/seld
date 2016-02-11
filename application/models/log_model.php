<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		SelD
 * @author		Creative Edge
 * @copyright	Copyright (c) 2015 NCThink LIMITED.
 * @since		Version 1.0
 * @createdby 	Sudarshan Shakya
 */

class Log_model extends CI_Model{

	private $tableName 	= 'tbl_logs';
	private $prefix 	= 'log';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this will find the log history of the client.
	 */
	public function getLogs($client_id=0, $limit=200, $offset=0){

		$this->db->where('log_cl_id', $client_id);
		$this->db->order_by('log_created_at', 'desc');

		return $this->db->get($this->tableName, $limit, $offset);
	}

	/**
	 * this will count total user logs
	 */
	public function countUserLogs($client_id=0){

		$this->db->where('log_cl_id', $client_id);
		return $this->db->count_all_results($this->tableName);
	}

	/**
	 * this will add log
	 */
	public function addLog($client_id=0, $title='', $ref='', $type='session'){

		$form = array(
					'log_uid' 		=> md5(rand(1000,999999).time()),
					'log_cl_id' 	=> $client_id,
					'log_title'		=> $title,
					'log_type'		=> $type,
					'log_ref'		=> $ref,
					'log_ip'		=> $_SERVER['REMOTE_ADDR'],
					'log_created_at' => date('Y-m-d H:i:s')
				);
		$this->db->insert($this->tableName, $form);
	}

	public function addDesignLog($client_id=0, $title='', $ref=''){
		$this->addLog($client_id, $title, $ref, 'design');
	}

	public function addTransactionLog($client_id=0, $title='', $ref=''){
		$this->addLog($client_id, $title, $ref, 'transaction');
	}
}

/* End of file Log_model.php */
/* Location: application/controllers/Log_model.php */