<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class U extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
    }

    public function index(){
        $data['title'] = 'User Dashboard ';
        $this->load->view('m_index', $data);
    }

    /**
     * this method will log out user
     */
    public function logout(){
        delete_cookie(APP_MEMBER.'_member');
        redirect('p/login', 'refresh');
    }

    /**
     * this method will handle the create design
     */
    public function create($step='product', $ref=''){
        
        if ($step == 'settings'){
            $page = 's2-settings';
            $data = $this->step2($ref);
        }
        else if ($step == 'theme'){
            $page = 's3-theme';
            $data = $this->step3($ref);
        }
        else if ($step == 'custom'){
            $page = 's4-custom';
            $data = $this->step4($ref);
        }
        else if ($step == 'preivew'){
            $page = 's5-preview';
            $data = $this->step5($ref);
        }
        else if ($step == 'payment'){
            $page = 's6-payment';
            $data = $this->step6($ref);
        }
        else {
            $page = 's1-product';
            $data = $this->step1($ref);
        }

        $data['title']  = 'Create Design';
        $data['page']   = $page;
        $data['ref']    = $ref;
        $data['step']   = $step;
        $this->load->view('m_design', $data);
    }

    /**
     * the following methods handle the different steps of designing.
     */
    private function step1(){
        $this->load->model('design_products_model', 'design');
        $data['list'] = $this->design->findAll();
        return $data;
    }
    private function step2($ref=''){
        $this->load->model('design_products_model', 'design');
        $product = $this->design->findById($ref);
        $data['product'] = $ref == '' ? '' : $product->d_pr_name;
        return $data;
    }
    private function step3(){
        $data = array();
        return $data;
    }
    private function step4(){
        $data = array();
        return $data;
    }
    private function step5(){
        $data = array();
        return $data;
    }
    private function step6(){
        $data = array();
        return $data;
    }

}

/* End of file u.php */
/* Location: ./application/controllers/u.php */