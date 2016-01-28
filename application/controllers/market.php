<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * this controller is for giving access to the admin panel.
 */
class Market extends CI_Controller {

    /**
     * this will extend parent controller's functions and get the stored ADMIN ID.
     * redirect to login if ADMIN ID is not available.
     */
	public function __construct(){
		parent::__construct();

        $this->load->model('market_model', 'market');
        $this->load->model('category_model', 'category');
    }

    /**
     * this will show the dashboard.
     */
    public function index(){
        $data['categories']     = $this->category->getCategories();
        $data['sub_categories'] = $this->category->getCategories('1', '0');
        $data['items']          = $this->market->getList();
        
        
        $this->load->view('market/index', $data);
    }

    
}