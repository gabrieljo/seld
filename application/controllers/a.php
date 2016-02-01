<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * this controller is for giving access to the admin panel.
 */
class A extends CI_Controller {

    private $admin;     // this holds the admin user information.
	
    /**
     * this will extend parent controller's functions and get the stored ADMIN ID.
     * redirect to login if ADMIN ID is not available.
     */
	public function __construct(){
		parent::__construct();

        $id             = check_admin_login();
        $this->admin    = $this->user_model->findById($id);
    }

    /**
     * this will show the dashboard.
     */
    public function index(){
        
        /**
         * Redirect to member dashboard.
         */
        $this->admin->usr_access == 'manager' && redirect('a/dashboard');

        $data['title']  = 'Welcome to Admin Panel';
        $this->load->view('admin/index', $data);
    }

    /**
     * list all articles
     */
    public function articles($msg='page', $page=1){

        $this->load->model('article_model');

        $type = 'all';
        $type = ($msg=='notice' || $msg=='news' || $msg=='qna') ? $msg : $type;

        // Search Keyword..
        $keyword = @get_cookie(APPID.'_article_keyword');
        if ($this->input->post('frm_search')){
            $keyword = $this->input->post('keyword');
            set_cookie(array('name'=>APPID.'_article_keyword', 'value'=>$keyword, 'expire'=>SESSION));
        }

        $per_page = PER_PAGE; // from constants.

        // Pagination
        $this->load->library('pagination');
        $config['base_url']          = site_url('a/articles/'. $msg . '/');
        $config['total_rows']        = $this->article_model->countArticles($type, $keyword);
        $config['per_page']          = $per_page;
        $config['uri_segment']       = 4;
        $config['num_links']         = 2;
        $config['use_page_numbers']  = 'TRUE';

        $this->pagination->initialize($config); 

        $currentPage        = intval($page) == 0 ? 1 : intval($page);
        $startIndex         = ($currentPage - 1) * $per_page;
        $endIndex           = $startIndex + $per_page;

        $data['type']       = $type;
        $data['keyword']    = $keyword;
        $data['screen_id']  = 'list';
        $data['articles']   = $this->article_model->findAllArticles($type, $keyword, $per_page, $startIndex);
        $data['pagination'] = $this->pagination->create_links();
        $data['total']      = $config['total_rows'];

        $this->load->view('admin/articles', $data);
    }

    /**
     * this will handle article form.
     */
    public function article($id='', $tb=''){

        $this->load->model('article_model');

        $form['art_type']       = 0;
        $form['art_title']      = '';
        $form['art_contents']   = '';
        $form['art_status']     = 'published';

        if ($id != ''){
            $user = $this->article_model->findById($id);

            $form['art_type']    = $user->art_type;
            $form['art_title']   = $user->art_title;
            $form['art_contents']= $user->art_contents;
            $form['art_status']  = $user->art_status;
        }

        // if form is submitted
        if ($this->input->post('frmsubmit')){

            $form['art_type']    = $this->input->post('art_type');
            $form['art_title']   = $this->input->post('art_title');
            $form['art_contents']= $this->input->post('art_contents');
            $form['art_status']  = $this->input->post('art_status');

            // additional info
            if ($this->input->post('m_passwd') != ''){
                $form['m_passwd']   = md5($this->input->post('m_passwd'));              
            }
            // save
            $this->article_model->save($form, $id);
            if ($tb == '')
                redirect('a/articles');
            else
                redirect(base64_decode($tb));
        }
        
        $data['tb']         = $tb;
        $data['form']       = $form;
        $data['id']         = $id;

        $this->load->view('admin/article', $data);
    }

    /**
     * this will list all the users
     */
    public function users($msg='page', $page=1){

        /**
         * Redirect to member dashboard.
         */
        $this->admin->usr_access == 'manager' && redirect('a/dashboard');

        $this->load->library('pagination');

        // Search Keyword..
        $keyword = @get_cookie(APPID.'_users_keyword');
        if ($this->input->post('frm_search')){
            $keyword = $this->input->post('keyword');
            set_cookie(array('name'=>APPID.'_users_keyword', 'value'=>$keyword, 'expire'=>SESSION));
        }

        // Pagination for users.
        $per_page                   = PER_PAGE;
        $config['base_url']         = site_url("a/users/page/");
        $config['total_rows']       = $this->client_model->getClientsCount($keyword);
        $config['per_page']         = $per_page;
        $config['uri_segment']      = 4;

        $this->pagination->initialize($config);
        $currentPage                = intval($page) == 0 ? 1 : intval($page);
        $startIndex                 = ($currentPage - 1) * $per_page;

        $data['keyword']            = $keyword;
        $data['users']              = $this->client_model->getClients($keyword, $per_page, $startIndex);
        $data['pagination']         = $this->pagination->create_links();
        $data['title'] = 'All the SELD Users';
        
        $this->load->view('admin/users', $data);
    }

    /**
     * this will list all the designs of the client.
     */
    public function designs($cl_uid=''){

        /**
         * Redirect to member dashboard.
         */
        $this->admin->usr_access == 'manager' && redirect('a/dashboard');

        if ($cl_uid == '') die('<h1>Invalid Client</h1>');

        // load model
        $this->load->model('product_model');

        // Products
        $client = $this->client_model->findById($cl_uid);

        $data['title']              = 'SELD Client Designs';
        $data['client']             = $client;
        $data['designs']            = $this->product_model->getProducts($client->cl_id, '', 1000);

        $this->load->view('admin/designs', $data);
    }

    /**
     * this will list all the order-report of the clients.
     */
    public function orders($cl_uid=''){

        /**
         * Redirect to member dashboard.
         */
        $this->admin->usr_access == 'manager' && redirect('a/dashboard');

        if ($cl_uid == '') die('<h1>Invalid Client</h1>');

        // load model
        $this->load->model('order_model');

        // Products
        $client = $this->client_model->findById($cl_uid);

        $data['title']              = 'SELD Client Designs';
        $data['client']             = $client;
        $data['orders']             = $this->order_model->getOrders($client->cl_id, 1000);

        $this->load->view('admin/orders', $data);
    }

    /**
     * this method will log out user
     * and redirect to member login page.
     */
    public function logout(){

        delete_cookie(APP_ADMIN.'_admin');
        redirect('p/login', 'refresh');
    }

    /**
     * this will list out all the options available for SELD Products.
     */
    public function opt($msg='view', $uid=''){

        /**
         * Redirect to member dashboard.
         */
        $this->admin->usr_access == 'manager' && redirect('a/dashboard');

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
        $f['d_op_options_price'] = '';
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
            $f['d_op_options_price'] = $opt->d_op_options_price;
            $f['d_op_default'] = $opt->d_op_default;
            $f['d_op_load'] = $opt->d_op_load;
            $f['d_op_attr'] = $opt->d_op_attr;
        }

        if (isset($_POST['title'])){
            $options = explode("\n", $this->input->post('options'));
            $all_prices = explode("\n", $this->input->post('prices'));
            $opts = array();
            $prices = array();
            $first_val = '';
            for ($i=0; $i<count($options); $i++){
                if ($options[$i] != ''){
                    
                    $opt = trim($options[$i]);
                    $opt = str_replace(array('\r', '\n'), "", trim($opt));

                    $opts[''.$opt] = ucfirst($opt);
                    $prices[''.$opt] = doubleval(@$all_prices[$i]);
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
            $f['d_op_options_price'] = serialize($prices);
            $f['d_op_default'] = $this->input->post('default') == '' && $uid == '' ? $first_val : $this->input->post('default');
            $f['d_op_load'] = serialize($load);
            $f['d_op_attr'] = $this->input->post('attr');

            //new dBug($f);exit;
            
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
        $this->load->view('member/mt', $data);
    }


    /**
     * this is the dashboard for manager.
     */
    public function dashboard(){

        /**
         * Redirect to admin dashboard.
         */
        $this->admin->usr_access == 'admin' && redirect('a');

        $data['title'] = 'Manager Dashboard';
        $this->load->view('admin/mgr_dashboard', $data);
    }
}