<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Class M
 * this class contains all the functions for client (member) profile.
 * Seld Editor, Client's information
 */

class M extends CI_Controller {

    private $client; // holds information of logged-in client.
	
    /**
     * this will extend parent controller's functions and get the stored Client ID.
     * redirect to login if Client ID is not available. 
     */
	public function __construct(){

		parent::__construct();

        $id             = check_member_login();
        $this->client   = $this->client_model->findById($id);
    }

    /**
     * this will display member dashboard page. 
     */
    public function index(){

        $data['title']  = sprintf('Member Dashboard :: %s %s', $this->client->cl_firstname, $this->client->cl_lastname);
        $data['logs']   = $this->log_model->getLogs($this->client->cl_id, 3);
        $this->load->view('member/index', $data);
    }


    /**
     * this method will log out user
     * and redirect to member login page.
     */
    public function logout(){

        delete_cookie(APP_MEMBER.'_member');

        $this->log_model->addLog($this->client->cl_id, 'User logged out.');
        redirect('p/login', 'refresh');
    }

    /**
     * this method will list the user designs 
     * user will have access to their design, and option to publish/print design.
     */
    public function designs($msg='', $page=1){

        // Load Design Models
        $this->load->model('product_model');
        $this->load->library('pagination');

        // Pagination for products.
        $per_page                   = PER_PAGE;
        $config['base_url']         = site_url("m/designs/page/");
        $config['total_rows']       = $this->product_model->countProducts($this->client->cl_id);
        $config['per_page']         = $per_page;
        $config['uri_segment']      = 4;

        $this->pagination->initialize($config);
        $currentPage                = intval($page) == 0 ? 1 : intval($page);
        $startIndex                 = ($currentPage - 1) * $per_page;

        $data['products']           = $this->product_model->getProducts($this->client->cl_id, '', $per_page, $startIndex);
        $data['total']              = $config['total_rows'];
        $data['pagination']         = $this->pagination->create_links();
        $data['page']               = $page;
        $data['total_perpage']      = $per_page;

        // load view
        $this->load->view('member/design_list', $data);
    }

    /**
     * this method will create a new design.
     */
    public function create($id=''){

        /**
         * create a new product if id is not given.
         */
        $this->load->model('product_model');
        $this->load->model('design_sizes_model');
        $this->load->model('design_products_model');

        /**
         * create new product.
         * redirect to same method with id.
         */
        if ($id == ''){

            /**
             * Find NEW design if doesn't already exist.
             */
            $product = $this->product_model->hasNewProduct($this->client->cl_id);
            if ($product){
                redirect('m/create/' . $product->pr_uid); 
                die('<h1>Design not found!</h1>' . anchor('m/designs', 'Go Back <<'));
            }
            
            $data   = array(
                        'pr_cl_id'      => $this->client->cl_id,
                        'pr_status'     => 'new'
                    );
            
            if ($this->product_model->save($data)){

                $id         = $this->db->insert_id();
                $product    = $this->product_model->findById($id, 'pr_id');

                // Update Log
                $this->log_model->addDesignLog($this->client->cl_id, 'User created a design.', 'm/create/'.$product->pr_uid);

                // Create Product Folders
                if (!file_exists('./files/products/'.$product->pr_uid)){

                    mkdir('./files/products/'.$product->pr_uid, 0777, true);
                    mkdir('./files/products/'.$product->pr_uid.'/thumbs', 0777, true);
                    mkdir('./files/products/'.$product->pr_uid.'/design', 0777, true);
                    mkdir('./files/products/'.$product->pr_uid.'/design/thumbs', 0777, true);

                    // also copy index.html to stop direct access.
                    $file = './files/products/index.html';
                    @copy($file, './files/products/'.$product->pr_uid.'/index.html');
                    @copy($file, './files/products/'.$product->pr_uid.'/thumbs/index.html');
                    @copy($file, './files/products/'.$product->pr_uid.'/design/index.html');
                    @copy($file, './files/products/'.$product->pr_uid.'/design/thumbs/index.html');
                }

                $product->pr_cl_id == $this->client->cl_id && redirect('m/create/' . $product->pr_uid);
                redirect('m/designs', 'refresh');
            }
            else
                die('<h1>Unable to create design now! Try again later.</h1>');
        }

        $product = $this->product_model->findById($id);

        if ($product == null) die('<h1>The file you requested has been moved!</h1>' . anchor('m/designs', 'Go Back to list &laquo;'));

        /**
         * get product properties if available.
         * Override default values if option is available.
         */
        $canvas = (object) array('type'=>1, 'page'=>1, 'size'=>'empty', 'orientation'=>'landscape', 'fold'=>0, 'width'=>0, 'height'=>0);
        if ($product->pr_options != ''){
            
            $options = unserialize(@$product->pr_options);

            // CAnvas Type -- Design Product type
            $canvas->type = $product->pr_type;

            // get total pages.
            $pages = @$options['set-pages'];
            if ($product->pr_type == '2' || $product->pr_type == '1'){ // type leaflet or business card.
                $canvas->page = strtolower($pages) == 'single side' ? 1 : 2; // single side OR double side.

                // get folding lines
                $fold = @$options['set-folding-paper'];
                $canvas->fold = $fold == '' ? 0 : intval($fold);
            }
            else{
                $canvas->page = intval($pages) <= 0 ? 1 : intval($pages);
            }

            // get paper size
            $sizes = @$options['set-size'];
            $canvas->size = $sizes == '' ? 'A4' : $sizes;

            // get paper orientation
            $orientation = @$options['set-orientation'];
            $canvas->orientation =  $orientation == '' ? 'landscape' : $orientation;
        }

        /**
         * Set Paper orientation.
         */
        $paperSize = $this->design_sizes_model->getDimension($canvas->size);
        $paper_width    = $paperSize->d_sz_width;
        $paper_height   = $paperSize->d_sz_height;

        if (strtolower($canvas->orientation) == 'portrait'){
            $canvas->width   = $paper_height > $paper_width ? $paper_width : $paper_height;
            $canvas->height  = $paper_height > $paper_width ? $paper_height : $paper_width;
        }
        else{
            $canvas->width   = $paper_height > $paper_width ? $paper_height : $paper_width;
            $canvas->height  = $paper_height > $paper_width ? $paper_width : $paper_height;
        }

        // Load VIEW
        $data['title']      = 'SELD Creative Editor';
        $data['product']    = $product;
        $data['canvas']     = $canvas;
        $data['d_types']    = $this->design_products_model->findAll();

        $this->load->view('member/canvas', $data);
    }

    /**
     * this will show options for design publishing.
     */
    public function publish($product_uid='', $action='', $action_id=''){

        $this->load->model('product_model');
        $this->load->model('order_model');
        $this->load->model('design_options_model');

        $product = $this->product_model->findById($product_uid);
        ($product == null || $product->pr_status == 'new') && die('<h1>Invalid Access</h1>'.anchor('m/designs', 'Go Back <<'));

        if ($action == 'pay' && $action_id != ''){
            $order = $this->order_model->findById($action_id);
            ($order == null || $order->or_status != 'awaiting-payment') && die('<h1>Invalid Access</h1>'.anchor('m/designs', 'Go Back <<'));

            // complete the order.
            $form = array(
                        'or_status' => 'awaiting-confirmation',
                        'or_payment_date' => date('Y-m-d H:i:s')
                    );
            $this->order_model->save($form, $order->or_uid);
            redirect('m/publish/' . $product_uid, 'refresh');
        }

        /**
         * update market.
         */
        if ($this->input->post('frmAddMarket')){
            //
            $form = array(
                    'pr_mk_orig_price'      => floatval($this->input->post('mk_orig_price')),
                    'pr_mk_price'           => floatval($this->input->post('mk_price')),
                    'pr_mk_description'     => $this->input->post('mk_description')
                );

            if ($product->pr_mk_status == 'unlisted'){
                $form['pr_mk_status'] = 'listed';
                $form['pr_mk_created_at'] = date('Y-m-d H:i:s');
            }
            
            $this->product_model->save($form, $product->pr_uid);
            redirect('m/publish/' . $product->pr_uid);
            exit;
        }

        /**
         * Calculate Total Price for User Selected options.
         */
        $user_option    = unserialize($product->pr_options);
        $options        = $this->design_options_model->findOptions($product->pr_type);
        $page_rate      = 1;
        $particulars    = array();
        $total          = 0; // total rate per copy.

        foreach ($options->result() as $opt){

            // get unit price for option.
            $opt_options    = unserialize(@$opt->d_op_options_price);
            $opt_key        = $opt->d_op_dep_id == 0 ? $opt->d_op_name : $opt->d_op_name.'-'.$opt->d_op_id;

            if (array_key_exists($opt_key, $user_option)){

                $price = doubleval($opt_options[$user_option[$opt_key]]);

                if ($opt_key == 'set-pages'){
                    $page_rate = floatval($user_option[$opt_key]);
                    if ($page_rate == 0){
                        $page_rate = strtolower($user_option[$opt_key]) == 'one side' ? 1 : 2;
                    }
                    continue;
                }
                if ($price == 0) continue;

                $total += $price;
                $particulars[] = (object) array('title'=>$opt->d_op_title, 'desc'=>$user_option[$opt_key], 'rate'=>$price);
            }
        }

        /**
         * Update Print Order
         */

        if ($this->input->post('frmSubmit')){

            $quantity   = $this->input->post('printQuantity');
            $total_amt  = $total * $quantity * $page_rate;

            $form = array(
                        'or_cl_id'      => $product->pr_cl_id,
                        'or_pr_id'      => $product->pr_id,
                        'or_rate'       => $total,
                        'or_status'     => 'awaiting-payment',
                        'or_amount'     => $total_amt,
                        'or_quantity'   => $quantity
                    );
            $this->order_model->save($form);

            $id = $this->db->insert_id();
            $order = $this->order_model->findById($id, 'or_id');

            redirect('m/publish/' . $product->pr_uid . '/payment/' . $order->or_uid);
            die('<h1>Invalid Access</h1>');
        }

        $data['title']      = 'Publish Design';
        $data['action']     = $action;
        $data['action_id']  = $action_id;
        $data['product']    = $product;
        $data['particulars']= $particulars;
        $data['page_rate']  = $page_rate;
        $data['history']    = $this->order_model->getProductHistory($this->client->cl_id, $product->pr_id, 100);

        $this->load->view('member/publish', $data);
    }

    /**
     * this will open the cart page for printing.
     */
    public function order($product_uid=''){

        // Load product
        $this->load->model('product_model');

        $product = $this->product_model->findById($product_uid);

        $product->pr_status == 'new' || $product->pr_status == 'pending' && redirect('m/designs');

        $data['title'] = 'Order Print';
        $this->load->view('member/order', $data);
    }

    /**
     * this will save the file details.
     */
    public function save($product_uid='', $type='design'){
        
        $this->load->model('product_model');

        $form = null;
        if ($type == 'design'){
            
            $content = str_replace(array("\n", "\r"), '', trim($this->input->post('content')));
            $content = preg_replace("/[[:blank:]]+/", " ", $content);

            $form = array(
                            'pr_contents'   => $content,
                            'pr_status'     => 'designing',
                            'pr_title'      => urldecode($this->input->post('title')),
                            'pr_description'=> urldecode($this->input->post('desc'))
                        );
        }
        else if ($type == 'options'){

            $form_post   = $_POST;
            unset($form_post['frmsubmit']);

            $form = array('pr_options' => serialize($form_post));
        }
        else if ($type == 'general'){

            // file info
            $form = array(                            
                            'pr_type'       => $this->input->post('type'),
                            'pr_th_id'      => $this->input->post('th_id')                           
                        );
        }

        $form != null && $this->product_model->save($form, $product_uid);
    }

    /**
     * this will copy the design.
     * ignore copy request for "NEW" design.
     */
    public function copy($product_uid='', $tb=''){

        $this->load->model('product_model');

        $product = $this->product_model->findById($product_uid);
        if ($product != null && $product->pr_cl_id == $this->client->cl_id && ($product->pr_status == 'pending' || $product->pr_status == 'completed')){
            
            $new_copy = array(
                            'pr_cl_id'      => $product->pr_cl_id,
                            'pr_title'      => $product->pr_title . ' - Copy',
                            'pr_description'=> $product->pr_description,
                            'pr_type'       => $product->pr_type,
                            'pr_th_id'      => $product->pr_th_id,
                            'pr_options'    => $product->pr_options,
                            'pr_contents'   => $product->pr_contents,
                            'pr_preview'    => $product->pr_preview,
                            'pr_status'     => 'designing',
                            'pr_publish'    => $product->pr_publish,
                            'pr_created_at' => date('Y-m-d H:i:s')
                        );

            if ($this->product_model->save($new_copy)){

                $id         = $this->db->insert_id();
                $new_copy    = $this->product_model->findById($id, 'pr_id');

                // Create Product Folders
                if (!file_exists('./files/products/'.$new_copy->pr_uid)){

                    mkdir('./files/products/'.$new_copy->pr_uid, 0777, true);
                    mkdir('./files/products/'.$new_copy->pr_uid.'/thumbs', 0777, true);
                    mkdir('./files/products/'.$new_copy->pr_uid.'/design', 0777, true);
                    mkdir('./files/products/'.$new_copy->pr_uid.'/design/thumbs', 0777, true);

                    // also copy index.html to stop direct access.
                    $file = './files/products/index.html';

                    @copy($file, './files/products/'.$new_copy->pr_uid.'/index.html');
                    @copy($file, './files/products/'.$new_copy->pr_uid.'/thumbs/index.html');
                    @copy($file, './files/products/'.$new_copy->pr_uid.'/design/index.html');
                    @copy($file, './files/products/'.$new_copy->pr_uid.'/design/thumbs/index.html');
                }

                /**
                 * Copy all files from prev design to new.
                 */
                $this->copy_files('./files/products/'.$product->pr_uid, './files/products/'.$new_copy->pr_uid);
            }
        }

        $tb = $tb == '' ? 'm/designs' : base64_decode($tb);
        redirect($tb);
    }

    /**
     * this method will delete the requested design file.
     * this will also delete all the files and published-images.
     */
    public function delete($product_uid='', $tb=''){
        $this->load->model('product_model');

        $product = $this->product_model->findById($product_uid);
        if ($product != null && $product->pr_cl_id == $this->client->cl_id && $product->pr_status != 'pending'){           

            $this->product_model->save(array('pr_status'=>'deleted'), $product->pr_uid);
        }

        $tb = $tb == '' ? 'm/designs' : base64_decode($tb);
        redirect($tb);
    }

    /**
     * this method will upload the images
     * also
     * create a thumbnail of 150x150px.
     */
    public function upload($folder='temp'){

        $output_dir = "./files/products/{$folder}/";
        $thumbs_dir = "./files/products/{$folder}/thumbs/";
        $ret        = array();

        if(isset($_FILES["myfile"])){
         
            $error = $_FILES["myfile"]["error"];
         
            if (!is_array($_FILES["myfile"]['name'])){ //single file     
                $ImageName      = str_replace(' ','-',strtolower($_FILES['myfile']['name']));
                $ImageType      = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.
     
                $ImageExt       = substr($ImageName, strrpos($ImageName, '.'));
                $ImageExt       = str_replace('.','',$ImageExt);
                $ImageName      = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
                $NewImageName   = time() . '-' . rand(100000, 9999999) . '.' . $ImageExt;
     
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir. $NewImageName);
                //echo "<br> Error: ".$_FILES["myfile"]["error"];
                $ret[$NewImageName]= $thumbs_dir.$NewImageName;
                
                // Create Thumbnail
                $config['source_image']     = $output_dir. $NewImageName;
                $config['create_thumb']     = FALSE;
                $config['maintain_ratio']   = TRUE;
                $config['width']            = 150;
                $config['height']           = 100;
                $config['new_image']        = $thumbs_dir . $NewImageName;
                $config['quality']          = '50%';

                $this->load->library('image_lib', $config);
                $this->image_lib->resize();
            }
            echo json_encode($ret);
        }
    }

    /**
     * this will save the canvas page as image.
     */
    public function saveImage($folder='', $page=0){

        $folder == '' && !isset($_POST['imgBase64']) && die('<h1>Invalid Access!</h1>');

        $img        = $_POST['imgBase64'];
        $img        = str_replace('data:image/png;base64,', '', $img);
        $img        = str_replace(' ', '+', $img);
        $fileData   = base64_decode($img);

        // filename
        $folder     = './files/products/' . $folder . '/design/';

        $fileName   = $folder.'page-' . $page . '.png';

        // save the file.
        if (file_put_contents($fileName, $fileData)){

            /**
             * Create a thumbnail of each page.
             * Size: 200x150 Max
             */
            // Create Thumbnail
            $output_dir = "{$folder}page-{$page}.png";
            $thumbs_dir = "{$folder}thumbs/page-{$page}.png";

            echo $output_dir . '--' . $thumbs_dir;
            $config['source_image']     = $output_dir;
            $config['create_thumb']     = FALSE;
            $config['maintain_ratio']   = TRUE;
            $config['width']            = 150;
            $config['height']           = 100;
            $config['new_image']        = $thumbs_dir;
            $config['quality']          = '30%';

            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
        }

        echo 'done';
    }

    /**
     * this will handle ajax requests from the editor.
     */
    public function ajax($ref1='', $ref2='', $ref3='', $ref4=''){
        switch ($ref1){
            case 'load-options':
                $this->load->model('design_options_model');
                $options = $this->design_options_model->findOptions($ref2);

                $html = '';
                foreach ($options->result() as $opt){
                    $load = unserialize($opt->d_op_load);

                    $name_field = $opt->d_op_name;
                    $name_field.= $load['type'] == 'default' ? '' : '-' . $opt->d_op_id;
                    $class      = $load['type'] == 'default' ? '' : 'hidden';
                    $ajax_load  = $load['type'] == 'default' ? '' : 'ajax_load';

                    $options    = unserialize($opt->d_op_options);
                    $value      = isset($form[''.$opt->d_op_name]) ? $form[''.$opt->d_op_name] : $opt->d_op_default;
                    $attr       = 'class="form-control control-form input-xs ' . $ajax_load . '" id="form-control-' . $opt->d_op_id . '" data-id="' . $opt->d_op_id . '" data-dep-id="' . $opt->d_op_dep_id . '" data-dep-val="' . $opt->d_op_dep_val . '" data-name="' . $opt->d_op_name . '" ';

                    $field = '<h4 class="file_info_heading">' . $opt->d_op_attr . '</h4>';

                    $field.= '<div class="form-group form-group-sm ' . $class . '">
                        <label for="' . $opt->d_op_name . '" class="col-sm-4 control-label">' . $opt->d_op_title . '</label>
                        <div class="col-sm-6">' . form_dropdown($name_field, $options, $value, $attr) . '</div>
                    </div><div class="cf"></div>';

                    $html.= $field;
                }

                // next btn
                $html.= '<div class="form-group form-group-sm">
                        <label class="col-sm-4 control-label">&nbsp;</label>
                        <div class="col-sm-6">
                           <button class="btn btn-sm btn-primary" id="canvas_file_options_selection"><span class="glyphicon glyphicon-ok"></span> Select </button>
                        </div>
                    </div><div class="cf"></div><br />';

                echo $html;
                break;

            case 'load-themes':

                $this->load->model('design_themes_model');
                $this->load->library('pagination');

                $size = $ref3;
                $page = intval($ref4) <= 0 ? 1 : intval($ref4);

                // Pagination for themes.
                $per_page = 2;//PER_PAGE;

                $config['base_url']         = site_url("m/ajax/load-themes/{$ref1}/{$ref2}/{$ref3}/");
                $config['total_rows']       = $this->design_themes_model->countThemes($ref2, $size);
                $config['per_page']         = $per_page;
                $config['uri_segment']      = 7;

                $this->pagination->initialize($config);
                $currentPage                = intval($page) == 0 ? 1 : intval($page);
                $startIndex                 = ($currentPage - 1) * $per_page;

                $themes = $this->design_themes_model->findThemes($ref2, $size, '', $per_page, $startIndex);
                $html = '<ul class="product-themes">';
                $total = 0;
                foreach ($themes->result() as $theme):
                    $html.= '<li>
                                <div class="theme-preview">' . inc('design/themes/'.$theme->d_th_image) . '</div>
                                <div class="theme-buttons">
                                    <button class="btn btn-primary btn-sm btn-select-theme" title="select theme" data-ref="' . $theme->d_th_id . '"><span class="glyphicon glyphicon-ok"></span> Select</button>
                                </div>
                                <div class="theme-description">
                                    <h3>' . $theme->d_th_name . '</h3>' . $theme->d_th_description . '
                                </div>
                            </li>';
                    $total++;
                endforeach;

                $html.= '</ul>'.$this->pagination->create_links();

                if ($total == 0){
                    $html = '<div class="text-center">
                                <h4 class="text-warning"><span class="glyphicon glyphicon-exclamation-sign"></span> Theme not available.</h4>
                                <button class="btn btn-primary btn-sm btn-select-theme" title="Continue without theme" data-ref="0"><span class="glyphicon glyphicon-ok"></span> Continue without theme...</button>
                            </div>';
                }
                echo $html;

                break;
        }
    }

    /**
     * Settings
     */
    public function settings(){
        die("Coming Soon...");
    }

    /**
     * Profile
     */
    public function profile(){
        die("Coming Soon...");
    }

    /**
     * this will copy files from source to destination
     */
    private function copy_files($src='', $dst=''){

        $dir = opendir($src); 

        while(false !== ( $file = readdir($dir)) ){ 

            if (( $file != '.' ) && ( $file != '..' )) {

                if ( is_dir($src . '/' . $file) ) { 
                    $this->copy_files($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file, $dst . '/' . $file);
                } 
            } 
        } 
        closedir($dir); 
    }
}

/* End of file m.php */
/* Location: ./application/controllers/m.php */