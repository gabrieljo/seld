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

class Article_model extends CI_Model{

	private $tableName 	= 'tbl_articles';
	private $prefix 	= 'art';

	function __construct(){	
		parent::__construct($this->tableName, $this->prefix);
	}

	/**
	 * this will count total articles.
	 */
	public function findAllArticles($type='all', $keyword='', $limit=1000, $offset=0){
        if ($type != 'all'){
            $this->db->where('art_type', $type);            
        }
        if ($keyword != ''){
            $this->db->like('art_title', $keyword);
        }

        $query  = $this->db->get($this->tableName, $limit, $offset);
        return $query;
    }

    /**
     * this method will count the total members.
     */
    public function countArticles($type=-1, $keyword=''){
        if ($type >= 0){
            $this->db->where('art_type', $type);            
        }
        if ($keyword != ''){
            $this->db->like('art_title', $keyword);
        }
        return $this->db->count_all_results($this->tableName);
    }
}

/* End of file client_model.php */
/* Location: application/controllers/client_model.php */