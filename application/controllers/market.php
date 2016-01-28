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

    }

    /**
     * this will show the dashboard.
     */
    public function index(){
        
        $this->load->view('market/index');
    }

    
}