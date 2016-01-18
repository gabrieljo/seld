<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class P extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
    }

    public function index(){
        $data['title']  = 'Welcome to ' . APP_NAME;
    	$this->load->view('n_index', $data);
    }

    public function old(){
        $data['title']  = 'Welcome to ' . APP_NAME;
        $this->load->view('index', $data);
    }

    /**
     * this method displays the about Us page.
     */
    public function about(){
        $data['title'] = 'About ' . APP_NAME;
        $this->load->view('about', $data);
    }

    public function contact(){
        $data['title'] = 'Contact TruthCRM';
        $this->load->view('contact', $data);
    }

    /**
     * this method will display a login form for the user.
     * if successful, redirect to controller "u"
     */
    public function login(){

        $form                   = array();
        $msg                    = '<p class="bg-info text-center">Use your Username/Password to Login.</p>';

        $form['cl_email']       = (get_cookie(APP_MEMBER.'_userid')) ? get_cookie(APP_MEMBER.'_userid') : '';
        $form['cl_password']    = '';
        $form['cl_remember']    = (get_cookie(APP_MEMBER.'_userid')) ? TRUE : FALSE;

        if ($this->input->post('email')){

            $form['cl_email']       = $this->input->post('email');
            $form['cl_password']    = $this->input->post('password');
            $remember               = $this->input->post('remember');

            // SHA1 encrypt password
            $this->load->library('encrypt');
            $form['cl_password']  = $this->encrypt->sha1($this->input->post('password'));

            // Remember Username
            if ($remember == TRUE)
                set_cookie(array('name' => APP_MEMBER.'_userid', 'value'=>$form['cl_email'], 'expire'=>REMEMBER));
            else
                delete_cookie(APP_MEMBER.'_userid');

            // DB Check
            $user = $this->client_model->validate($form['cl_email'], $form['cl_password']);
            if ($user == null){
                $msg = '<p class="text-danger text-center">Username/Password does not match!</p>';
            }
            else{
                set_cookie(array('name'=>APP_MEMBER.'_member', 'value'=>$user->cl_uid, 'expire'=>SESSION));
                // Log Time
                $log = array(
                        'cl_last_login' => date('Y-m-d H:i:s'),
                        'cl_last_ip'    => $_SERVER['REMOTE_ADDR']
                    );
                $this->client_model->save($log, $user->cl_uid);
                
                // redirect to member's (client) dashboard
                redirect('m', 'refresh');
            }
        }

        $data['form']       = $form;
        $data['title']      = 'Member Login :: ' . APP_NAME;
        $data['message']    = $msg;

        $this->load->view('login', $data);
    }

    /**
     * this will deal with the forgot password section.
     */
    public function forgot_password(){
        $data['title'] = 'Forgot Password Member :: TruthCRM';
        $this->load->view('forgot', $data);
    }

    /**
     * this method will display the message to user.
     */
    public function response($param=array()){
        $data['title']      = 'Response :: TruthCRM';
        $data['heading']    = '404 Page Not Found';
        $data['message']    = 'The page you requested was not found. It may have been removed or been moved to another location.';
        $data['class']      = 'info';

        foreach ($param as $k=>$v){
            $data[$k] = $v;
        }

        $this->load->view('response', $data);
    }

    /**
     * this method will register new Member
     */
    public function register(){
        $form = array();
        $form['cl_company']    = '';
        $form['cl_email']      = '';
        $form['cl_password']   = '';

        if ($this->input->post('submit')){
            $form['cl_company']    = $this->input->post('company');
            $form['cl_email']      = $this->input->post('email');

            // SHA1 encrypt password
            $this->load->library('encrypt');
            $form['cl_password']   = $this->encrypt->sha1($this->input->post('password'));
            
            $this->client_model->save($form);
            redirect('p/verify_email', 'refresh');
        }

        $data['form']   = $form;
        $data['title']  = 'Member Registration :: ' . APP_NAME;
        $this->load->view('register', $data);
    }

    /**
     * this method will check if the email is available
     */
    public function check_availability(){
        $email  = ($this->input->post('email')) ? $this->input->post('email')  : '';
        $id     = ($this->input->post('id')) ? $this->input->post('id')  : '';

        if ($this->client_model->check_availability($email, $id) === TRUE)
            echo 'available';
        else
            echo 'unavailable';

        //$this->output->enable_profiler(true);
    }

    public function verify_email(){
        $data['title']      = 'Registration successful | Verify Email';
        $data['heading']    = 'Congratulations!';
        $data['message']    = 'Your registration has been successful.<br /><br />Please verify your email address to continue.';
        $data['class']      = 'success';
        
        $this->response($data);
    }
}

/* End of file p.php */
/* Location: ./application/controllers/p.php */