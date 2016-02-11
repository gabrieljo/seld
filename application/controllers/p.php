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
    public function login($tb=''){

        $form                   = array();
        $msg                    = '<p class="bg-info text-center">Use your Username/Password to Login.</p>';

        $form['cl_email']       = get_cookie(APP_MEMBER.'_userid') ? get_cookie(APP_MEMBER.'_userid') : '';
        $form['cl_password']    = '';
        $form['cl_remember']    = get_cookie(APP_MEMBER.'_userid') ? TRUE : FALSE;

        if ($this->input->post('email')){

            $login_type         = $this->input->post('login_mode');
            
            if ($login_type == 'facebook' || $login_type == 'google'){

                $fb = json_decode($this->input->post('login_oath'));
                if ($fb && $fb->email != '' && $fb->id != ''){

                    $fb_client = $this->client_model->findById($fb->email, 'cl_email');

                    if ($fb_client == null){
                        
                        $form = array(
                                    'cl_firstname'      => $fb->first_name,
                                    'cl_lastname'       => $fb->last_name,
                                    'cl_email'          => $fb->email,
                                    'cl_ref'            => $login_type,
                                    'cl_company'        => '',
                                    'cl_ref_id'         => $fb->id
                                );
                        $this->client_model->save($form);

                        // get ID and grant access.
                        $id         = $this->db->insert_id();
                        $fb_client  = $this->client_model->findById($id, 'cl_id');
                    }

                    $this->grantLoginAccess($fb_client, $tb);
                }
                die('<h1>Invalid Access</h1>' . anchor('p/login', 'Go Back &laquo;'));
            }
            else if ($login_type == 'naver'){

            }
            else if ($login_type == 'seld'){

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

                    /**
                     * Check for ADMIN LOGIN
                     */
                    $user = $this->user_model->validate($form['cl_email'], $form['cl_password']);
                    if ($user == null){
                        $msg = '<p class="text-danger text-center">Username/Password does not match!</p>';
                    }
                    else{
                        // Admin Login successful.
                        set_cookie(array('name'=>APP_ADMIN.'_admin', 'value'=>$user->usr_uid, 'expire'=>SESSION));

                        // Log Time
                        $log = array(
                                'usr_last_login' => date('Y-m-d H:i:s'),
                                'usr_last_ip'    => $_SERVER['REMOTE_ADDR']
                            );
                        $this->user_model->save($log, $user->usr_uid);
                        
                        // redirect to admin's (client) dashboard
                        redirect('a', 'refresh');
                    }
                }
                else{
                    $this->grantLoginAccess($user, $tb);
                }
            }
            else{
                die('<h1>Invalid Access</h1>' . anchor('', 'View Homepage &raquo;'));
            }
        }

        $data['form']       = $form;
        $data['title']      = 'Member Login :: ' . APP_NAME;
        $data['message']    = $msg;
        $data['tb']         = $tb;
        $data['meta']       = '<meta name="google-signin-client_id" content="161940943732-ga5aufi7ag62f2do6g4va451769tt2i8.apps.googleusercontent.com">';

        $this->load->view('login', $data);
    }

    /**
     * this will grant login access to the user.
     */
    private function grantLoginAccess($user='', $tb=''){

        set_cookie(array('name'=>APP_MEMBER.'_member', 'value'=>$user->cl_uid, 'expire'=>SESSION));

        // Log Time
        $log = array(
                'cl_last_login' => date('Y-m-d H:i:s'),
                'cl_last_ip'    => $_SERVER['REMOTE_ADDR']
            );
        $this->client_model->save($log, $user->cl_uid);

        // LOG
        $this->log_model->addLog($user->cl_id, 'User logged in.');

        // redirect to member's (client) dashboard
        $url = $tb == '' ? 'm' : base64_decode($tb);
        redirect($url, 'refresh');
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

        $data['title']      = 'Page not found | Error 404';
        $data['heading']    = '<span class="glyphicon glyphicon-remove"></span> 404 Page Not Found';
        $data['message']    = 'The page you requested was not found. It may have been removed or been moved to another location.';
        $data['class']      = 'danger';

        foreach ($param as $k=>$v){
            $data[$k] = $v;
        }

        $this->load->view('response', $data);
    }

    /**
     * this method will register new Member
     * the email address must be unique and hence validated.
     */
    public function register(){

        /**
         * form default values.
         */
        $form = array(
                    'cl_firstname'      => '',
                    'cl_lastname'       => '',
                    'cl_company'        => '',
                    'cl_telephone'      => '',
                    'cl_mobile'         => '',
                    'cl_address1'       => '',
                    'cl_address2'       => '',
                    'cl_address3'       => '',
                    'cl_postcode'       => '',
                    'cl_email'          => '',
                    'cl_password'       => '',
                );

        $msg = '';

        if ($this->input->post('submit')){

            foreach ($form as $k=>$v){
                $form[$k] = $this->input->post(str_replace('cl_', '', $k));
            }

            // successful
            // SHA1 encrypt password
            $this->load->library('encrypt');

            $form['cl_password'] = $this->encrypt->sha1($this->input->post('password'));
            
            if ($this->client_model->save($form)){

                /**
                 * Send confirmation Email.
                 */
                $id         = $this->db->insert_id();
                $client     = $this->client_model->findById($id, 'cl_id');

                /**
                 * Email Library
                 */
                /*$this->load->library('email');

                $config['protocol']     = 'sendmail';
                $config['mailpath']     = '/usr/sbin/sendmail';
                $config['charset']      = 'iso-8859-1';
                $config['wordwrap']     = TRUE;
                $config['mailtype']     = 'html';

                $this->email->initialize($config);

                $this->email->from('info@seld.or.kr', 'Seld Creative');
                $this->email->to($client->cl_email); 

                $this->email->subject('Registration :: Verify Email Address');
                $this->email->message('
                                    <br>Seld Creative Editor<br>
                                    <h1>Thanks for registering to SELD.</h1><hr />
                                    You are now a member of our <strong>SELD community</strong>. 
                                    <br>
                                    Please verify your email address by <a href="http://localhost/ce/git/seld/p/verify/' . $client->cl_uid . '/' . md5($client->cl_created_at) . '" target="_blank">clicking here</a>.
                                    <br><br>
                                    <i>Please ignore this email, if you did not register to us.</i>
                                    <hr />
                                '); 

                $this->email->send();*/

                $client != null && redirect('p/verify_email/' . $client->cl_uid, 'refresh');
                $msg = 'Unable to send confirmation.';
            }
        }

        $data['form']   = $form;
        $data['msg']    = $msg;
        $data['title']  = 'Member Registration :: ' . APP_NAME;

        $this->load->view('register', $data);
    }

    /**
     * this method will check if the email is available
     */
    public function check_availability(){

        $email  = ($this->input->post('email')) ? $this->input->post('email')  : '';
        $id     = ($this->input->post('id')) ? $this->input->post('id')  : '';

        echo $this->client_model->check_availability($email, $id) === TRUE ? 'available' : 'unavailable';
    }

    /**
     * this will verify user's email address
     * and change the status.
     */
    public function verify($client_uid='', $token=''){

        $client = $this->client_model->findById($client_uid);

        $client == null && die('Invalid Access');

        $client->cl_status != 'pending' && redirect('p/email_verified/', 'refresh');

        if ($token == md5($client->cl_created_at)){

            $this->client_model->save(array('cl_status' => 'active'), $client->cl_uid);
            redirect('p/email_verified/' . $client->cl_uid, 'refresh');            
        }

        die('<h1>Invalid Access</h1>' . anchor('p', 'View Homepage'));
    }

    /**
     * this will respond to user's signup by showing email verification message.
     */
    public function verify_email($client_uid=''){

        $client = $this->client_model->findById($client_uid);
        $data   = array();

        if ($client != null){
        
            $data['title']      = 'Registration successful | Verify Email';
            $data['heading']    = 'Congratulations!';
            $data['message']    = 'Your registration has been successful.<br />Please check your email and verify your email address by simply <strong>cliking a link sent in your email address.</strong>';
            $data['class']      = 'success';
            
        }

        $this->response($data);
    }

    /**
     * this will respond to user's signup by showing email verification message.
     */
    public function email_verified($client_uid=''){

        $client = $this->client_model->findById($client_uid);
        $data   = array();

        if ($client != null){
            
            $data['title']      = 'Email Verification Completed';
            $data['heading']    = '<span class="glyphicon glyphicon-ok"></span> Congratulations!';
            $data['message']    = 'You have successfully verified your email address.<br />Please login with your email and password to view your SELD profile. <br /><br />' . anchor('p/login', 'Click here to login') . '<hr /><div class="text-center"><i>You have to verify your email address only once.</i></div>';
            $data['class']      = 'success';
            
        }

        $this->response($data);            
    }

    /**
     * this will respond to user's signup by showing email verification message.
     */
    public function thank_you(){

        $data   = array();
            
        $data['title']      = 'Purchase Completed';
        $data['heading']    = '<span class="glyphicon glyphicon-ok"></span> Thank you!';
        $data['message']    = 'Your purchase has now been completed. You can check all your purchases in your profile page.<br /><br />' . anchor('m', 'Click here to view your purchases.') . '<hr /><div class="text-center"><i>You can use any of your purchased theme while creating a new design..</i></div>';
        $data['class']      = 'success';
            
        $this->response($data);            
    }
}

/* End of file p.php */
/* Location: ./application/controllers/p.php */