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
        $data['admin']  = $this->admin;

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
        $data['admin']      = $this->admin;

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
        $data['admin']      = $this->admin;

        $this->load->view('admin/article', $data);
    }

    /**
     * this will delete article.
     */
    public function articleDelete($id='', $tb=''){

        $this->load->model('article_model');

        $this->article_model->delete($id);
        redirect(base64_decode($tb));
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
        $data['title']              = 'All the SELD Users';
        $data['admin']              = $this->admin;
        
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
        $data['admin']              = $this->admin;

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
        $data['admin']              = $this->admin;

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
    public function dashboard($view='awaiting-confirmation', $page=1){

        $this->load->model('order_model');

        /**
         * Redirect to admin dashboard.
         */
        $this->admin->usr_access == 'admin' && redirect('a');

        /**
         * Save the status and note.
         */
        if ($this->input->post('uid')){

            $form = array(
                        'or_status' => $this->input->post('status'),
                        'or_note' => $this->input->post('note')
                    );
            $this->order_model->save($form, $this->input->post('uid'));
            redirect('a/dashboard/' . $view . '/' . $page, 'refresh');
            die('<h1>Invalid Access</h1>');
        }

        // Search Keyword..
        /*$keyword = @get_cookie(APPID.'_orders_keyword');
        if ($this->input->post('frm_search')){
            $keyword = $this->input->post('keyword');
            set_cookie(array('name'=>APPID.'_article_keyword', 'value'=>$keyword, 'expire'=>SESSION));
        }*/

        $per_page = PER_PAGE; // from constants.

        // Pagination
        $this->load->library('pagination');
        $config['base_url']          = site_url('a/dashboard/'. $view . '/');
        $config['total_rows']        = $this->order_model->countAllOrders($view);
        $config['per_page']          = $per_page;
        $config['uri_segment']       = 4;
        $config['num_links']         = 2;
        $config['use_page_numbers']  = 'TRUE';

        $this->pagination->initialize($config); 

        $currentPage        = intval($page) == 0 ? 1 : intval($page);
        $startIndex         = ($currentPage - 1) * $per_page;
        $endIndex           = $startIndex + $per_page;

        $data['title']  = 'Manager Dashboard';
        $data['orders'] = $this->order_model->getAllOrders($view, $per_page, $startIndex);
        $data['admin']  = $this->admin;
        $data['view']   = $view;
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $this->load->view('admin/mgr_dashboard', $data);
    }

    /**
     * this will load options
     */
    public function loadOptions($pr_uid=''){

        $this->load->model('product_model');
        $this->load->model('design_options_model');

        $product    = $this->product_model->findById($pr_uid);
        $d_options  = $this->design_options_model->findOptions($product->pr_type);

        $design_keys = array();
        foreach($d_options->result() as $opt){
            $key = $opt->d_op_dep_id == 0 ? $opt->d_op_name : $opt->d_op_name . '-' . $opt->d_op_id;
            $design_keys[$key] = $opt->d_op_title;
        }

        $html = '<dl class="dl-horizontal">';
        $options = unserialize($product->pr_options);
        foreach ($options as $k=>$v){
            $html.= '<dt>' . $design_keys[$k] . '</dt><dd> ' . ucfirst($v) . '</dd>';
        }
        echo $html . '</dl>';
    }


    /**
     * this method will create the PDF file
     */
    public function pdf($id='', $filename=''){

        require_once("./application/libraries/dompdf/dompdf_config.inc.php");        

        $this->load->model('product_model', 'product');
        $this->load->model('design_sizes_model', 'size');

        $product = $this->product->findById($id, 'pr_id');
        $id = $product->pr_uid;

        //new dBug($product);
        if ($product == null || $product->pr_options == ''){
            echo '<h2>Unable to find preview!</h2>';
            exit;
        }

        // Find Size
        $options = unserialize($product->pr_options);
        $op_size = isset($options['set-size']) && $options['set-size'] != '' ? $options['set-size'] : 'A4';
        $paper   = $this->size->getDimension($op_size);

        //die("<h1>SORT OUT PAGES FIRST!</h1>");

        $html           = $product->pr_contents;
        $total_pages    = intval($options['set-pages']);
        $pages          = @$options['set-pages'];

        if ($product->pr_type == '2' || $product->pr_type == '1'){ // type leaflet or business card.
            $total_pages = strtolower($pages) == 'single side' ? 1 : 2; // single side OR double side.
        }
        else{
            $total_pages = intval($pages) <= 0 ? 1 : intval($pages);
        }

        if ($html == '' || $total_pages <= 0){
            die('<h1>Preview can not be generated! 603</h1>');
        }

        // parse Json content.
        $json = json_decode($html);

        // prepare pads first.
        $html = '';
        for ($i=1; $i<=$total_pages; $i++){
        //for ($i=1; $i<=1; $i++){

            /**
             * get page contents.
             */
            $css = $content = ''; 

            /*for ($k=0; $k<count($json); $k++){
                $obj = $json[$k];

                /**
                 * Get the contents for the current page only.
                 * /
                if ($obj->page == $i){

                    /**
                     * perform action on the basis of object type.
                     * type:
                     *      canvas      - get the background color for the page.
                     *      image       - get the source URL and include with properties of object.
                     *      shape       - get the image and include in PDF.
                     *      text        - get the image and include in PDF.
                     * /
                    switch ($obj->name){
                        case 'canvas':
                            $css = 'background:' . $obj->bgColor;
                            break;

                        case 'image':
                            $style = 'width:' . $obj->width . 'px;height:' . $obj->height . 'px;top:' . $obj->y . 'px;left:' . $obj->x.'px;';
                            if ($obj->rotation == 0){
                                $content.= '<img src="' . $obj->src . '" class="seld-image" style="'. $style . '" />';                                
                            }
                            else{
                                $content.= '<img src="' . base_url() . 'files/products/' . $id . '/design/page-' . $obj->id . '.png" class="seld-image" />';
                            }
                            break;

                        case 'shape':
                            $content.= '<img src="' . base_url() . 'files/products/' . $id . '/design/page-' . $obj->id . '.png" class="seld-image" />';
                            break;

                        case 'text':
                            $content.= '<img src="' . base_url() . 'files/products/' . $id . '/design/page-' . $obj->id . '.png" class="seld-image" />';
                            break;
                    }
                    //$html.= $obj->name.'***';
                }
            }*/

            //$html.= '<div class="print_pads" id="page-' . $i . '" style="' . $css . '">' . $content .'</div>';
            $style = '';
            $html.= '<div class="print_pads" id="page-' . $i . '" style="' . $css . '"><img src="' . base_url() . 'files/products/' . $product->pr_uid . '/design/page-' . $i . '.png" class="seld-image" style="'. $style . '" /></div>';
        }

        #$html = "<div class='mytext'>Super Text</div>";
        
        $style = 'width:' . $paper->d_sz_width . 'px;height:' . $paper->d_sz_height . 'px';
        $output = '<html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <style>
                                @page { margin: 0px; }
                                body { margin: 0px; }
                                /**{font-family:Arial, sanskrit, agisarang, serif;}*/
                                .print_pads{border:1px solid #eee;' . $style . 'position:relative;overflow:hidden;}
                                .layer{
                                    position:absolute; min-width: 80px;min-height: 24px;border: 1px dashed transparent; padding: 0px; margin:0; 
                                }
                                .seld-image{position:absolute;}
                                .overlay{overflow:hidden;}
                            </style>
                        </head>
                        <body>' . $html . '</body>
                    </html>';

                    echo $html;

        $dompdf = new DOMPDF();

        if (get_magic_quotes_gpc())
            $output = stripslashes($output);

        $dompdf->load_html($output);
        $dompdf->set_paper($op_size, 'potrait');
        $dompdf->render();

        //$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
        $output = $dompdf->output();
        file_put_contents('files/products/' . $id . '/design/' . $filename . '.pdf', $output);
        exit;
    }

    /**
     * this will update PDF name in order table.
     */
    public function pdfgenerate($order_id='', $name=''){

        ($order_id == '' || $name == '') && die('Invalid Access');

        $this->load->model('order_model');

        $this->order_model->save(array('or_file'=>$name.'.pdf'), $order_id);
    }
}