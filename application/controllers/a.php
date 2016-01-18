<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class A extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
    }

    public function index(){
        redirect('a/opt');
    }

    public function opt($msg='view', $uid=''){
        $this->load->model('design_options_model', 'options');
        $this->load->model('design_products_model', 'design');

        if ($msg != "view"){
            $msg = $msg == "success" ? '<h3>Saved Successfully!</h3>' : '<h2>Failed to Save!</h2>';
        }
        else{
            $msg = '';
        }

        $f = array();
        $f['d_op_pr_id'] = 1;
        $f['d_op_col'] = 1;
        $f['d_op_title'] = '';
        $f['d_op_title_kr'] = '';
        $f['d_op_name'] = '';
        $f['d_op_dep_id'] = '0';
        $f['d_op_dep_val'] = '';
        $f['d_op_options'] = '';
        $f['d_op_default'] = '';
        $f['d_op_load'] = '';
        $f['d_op_attr'] = '';

        // Edit form
        if ($uid != ''){
            $opt = $this->options->findById($uid);

            $f['d_op_pr_id'] = $opt->d_op_pr_id;
            $f['d_op_col'] = $opt->d_op_col;
            $f['d_op_title'] = $opt->d_op_title;
            $f['d_op_title_kr'] = $opt->d_op_title_kr;
            $f['d_op_name'] = $opt->d_op_name;
            $f['d_op_dep_id'] = $opt->d_op_dep_id;
            $f['d_op_dep_val'] = $opt->d_op_dep_val;
            $f['d_op_options'] = $opt->d_op_options;
            $f['d_op_default'] = $opt->d_op_default;
            $f['d_op_load'] = $opt->d_op_load;
            $f['d_op_attr'] = $opt->d_op_attr;
        }

        if (isset($_POST['title'])){
            $options = explode("\n", $this->input->post('options'));
            $opts = array();
            $first_val = '';
            for ($i=0; $i<count($options); $i++){
                if ($options[$i] != ''){
                    
                    $opt = trim($options[$i]);
                    $opt = str_replace(array('\r', '\n'), "", trim($opt));

                    $opts[''.$opt] = ucfirst($opt);
                }
                $first_val = $first_val == '' ? $options[$i] : $first_val;
            }

            $type = $this->input->post('depid') == 0 ? 'default' : 'dynamic';
            $load = array(
                    'type' => $type
                );
            $f['d_op_pr_id'] = $this->input->post('pr_id');
            $f['d_op_col'] = $this->input->post('cols');
            $f['d_op_title'] = $this->input->post('title');
            $f['d_op_title_kr'] = $this->input->post('title');
            $f['d_op_name'] = $this->input->post('name');
            $f['d_op_dep_id'] = $this->input->post('depid');
            $f['d_op_dep_val'] = $this->input->post('depname');
            $f['d_op_options'] = serialize($opts);
            $f['d_op_default'] = $this->input->post('default') == '' && $uid == '' ? $first_val : $this->input->post('default');
            $f['d_op_load'] = serialize($load);
            $f['d_op_attr'] = $this->input->post('attr');
            
            if ($this->options->save($f, $uid)){
                redirect('a/opt/success/'. $uid);
            }
            redirect('a/opt/failure/'. $uid);
        }

        $data['uid']        = $uid;
        $data['items']      = $this->options->findAll();
        $data['designs']    = $this->design->findAll();
        $data['f']          = $f;
        $data['msg']        = $msg;

        //new dBug($data);
        $this->load->view('mt', $data);
    }    
}