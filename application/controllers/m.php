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
        $this->load->view('member/index', $data);
    }

    /**
     * this method will log out user
     * and redirect to member login page.
     */
    public function logout(){

        delete_cookie(APP_MEMBER.'_member');
        redirect('p/login', 'refresh');
    }

    /**
     * this method will list the user designs 
     * user will have access to their design, and option to publish/print design.
     */
    public function designs($msg='', $page=1){

        // Load Design Models
        $this->load->model('product_model', 'product');
        $this->load->library('pagination');

        // Pagination for products.
        $per_page                   = PER_PAGE;
        $config['base_url']         = site_url("m/designs/page/");
        $config['total_rows']       = $this->product->countProducts($this->client->cl_id);
        $config['per_page']         = $per_page;
        $config['uri_segment']      = 4;

        $this->pagination->initialize($config);
        $currentPage                = intval($page) == 0 ? 1 : intval($page);
        $startIndex                 = ($currentPage - 1) * $per_page;

        $data['products']   = $this->product->getProducts($this->client->cl_id, '', $per_page, $startIndex);
        $data['total']      = $config['total_rows'];
        $data['pagination'] = $this->pagination->create_links();

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
            $data   = array(
                        'pr_cl_id'      => $this->client->cl_id,
                        'pr_status'     => 'new'
                    );
            
            if ($this->product_model->save($data)){
                $id         = $this->db->insert_id();
                $product    = $this->product_model->findById($id, 'pr_id');

                // Create Product Folders
                if (!file_exists('./files/products/'.$product->pr_uid)){
                    mkdir('./files/products/'.$product->pr_uid, 0777, true);
                    mkdir('./files/products/'.$product->pr_uid.'/thumbs', 0777, true);

                    // also copy index.html to stop direct access.
                    $file = './files/products/index.html';
                    @copy($file, './files/products/'.$product->pr_uid);
                    @copy($file, './files/products/'.$product->pr_uid.'/thumbs');
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
         */
        $canvas = (object) array('page'=>1, 'size'=>'empty');
        if ($product->pr_options != ''){
            $options = unserialize(@$product->pr_options);

            $pages = intval(@$options['set-pages']);
            $canvas->page = $pages <= 0 ? 1 : $pages;

            $sizes = @$options['set-size'];
            $canvas->size = $sizes == '' ? 'A4' : $sizes;
        }

        // Load VIEW
        $data['title']      = 'SELD Creative Editor';
        $data['product']    = $product;
        $data['canvas']     = $canvas;
        $data['paper']      = $this->design_sizes_model->getDimension($canvas->size);
        $data['d_types']    = $this->design_products_model->findAll();

        #new dBug($data);exit;

        $this->load->view('member/canvas', $data);
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
}

/* End of file m.php */
/* Location: ./application/controllers/m.php */