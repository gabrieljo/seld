<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Class U
 * this class contains all the 
 */

class U extends CI_Controller {

    private $client_id;
	
	public function __construct(){
		parent::__construct();


        $this->client_id = 1;
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
     * this method will list the user designs 
     */
    public function list_designs($msg='', $page=1){
        // Load Design Models
        $this->load->model('product_model', 'product');

        // Pagination for products.
        $per_page = PER_PAGE;

        $this->load->library('pagination');
        $config['base_url']         = site_url("u/list_designs/page/");
        $config['total_rows']       = $this->product->countProducts($this->client_id);
        $config['per_page']         = $per_page;
        $config['uri_segment']      = 4;

        $this->pagination->initialize($config);
        $currentPage                = intval($page) == 0 ? 1 : intval($page);
        $startIndex                 = ($currentPage - 1) * $per_page;

        $data['products']   = $this->product->getProducts($this->client_id, '', $per_page, $startIndex);
        $data['total']      = $config['total_rows'];
        $data['pagination'] = $this->pagination->create_links();

        // load view
        $this->load->view('m_design_list', $data);
    }

    /**
     * this method will create a new design.
     */
    public function create_design($value='')
    {
        # code...
    }

    /**
     * =====================================================================================
     * ======================= S E L D    D E S I G N     E D I T O R ======================
     * =====================================================================================
     */

    /**
     * this method will create a new Product (*if not created one yet)
     * 
     * this will check the status of the product, "new"
     */
    private function currentProduct($product_uid=''){

        // find if there is any "new" design for the client
        if ($product_uid == ''){
            $new_product = $this->product->hasNewProduct($this->client_id);
            if ($new_product != NULL){
                $product_uid = $new_product->pr_uid;
            }
            else{ 
                // Create New Product
                $data = array(
                        'pr_cl_id'      => $this->client_id,
                        'pr_status'     => 'new'
                    );
                $this->product->save($data);
                // Now get the product UID
                $new_product = $this->product->hasNewProduct($this->client_id);
                $product_uid = $new_product->pr_uid;
            }
        }
        $product    = $this->product->findById($product_uid);
        //new dBug($product);
        // DOUBLE check client ID 
        if ($product == null || $product->pr_cl_id != $this->client_id){
            redirect('u/file_not_found');
            echo '<h1>Error</h1><hr />Unable to find your file!';
            exit;
        }
        return $product;
    }

    /**
     * this method will display ERROR view, in case the file has been deleted or moved.
     */
    public function file_not_found(){
        $this->load->view('m_error');
    }

    /**
     * this is test function
     */
    public function canvas(){
        $data['title'] = 'HTML5 Canvas';
        $this->load->view('m_effects', $data);
    }

    /**
     * this method will handle the create design
     */
    public function create($product_uid=0, $step='product', $ref='', $ref2=''){

        // Load Design Models
        $this->load->model('product_model',         'product');
        $this->load->model('design_products_model', 'design');
        $this->load->model('design_themes_model',   'theme');
        $this->load->model('design_options_model',   'options');
        
        $product = $this->currentProduct($product_uid);

        // Reload URL
        $product_uid == '' && redirect('u/create/' . $product->pr_uid, 'refresh');
        
        if ($step == 'settings'){
            //$ref == '' && redirect('u/create');
            // Update PR_TYPE
            if ($ref != ''){
                $type = $this->design->findById($ref);
                $this->product->save(array('pr_type'=>$type->d_pr_id), $product->pr_uid);
                redirect('u/create/' . $product->pr_uid .'/settings');
            }

            $this->load->model('design_options_model', 'options');

            // Update PR_TH_ID
            $page           = 's2-settings';
            $data           = $this->step3($this->design->findById($product->pr_type, 'd_pr_id'));
            $data['form']   = @unserialize($product->pr_options);
            $data['options']= $this->options->findOptions($product->pr_type);
        }
        else if ($step == 'theme'){
            /**
             * save the settings and display the themes as per size.
             */
            if (isset($_POST['frmsubmit'])){
                $form = $_POST;
                unset($form['frmsubmit']);
                $form = serialize($form);
                $this->product->save(array('pr_options'=>$form), $product->pr_uid);
                redirect('u/create/' . $product->pr_uid . '/theme', 'refresh');
            }

            $page = 's3-theme';
            $data = $this->step2($product, $ref2);
            //$product = $this->currentProduct();
        }
        else if ($step == 'design'){
            if ($ref != ''){
                if ($ref == 'no-theme'){
                    $this->product->save(array('pr_th_id'=>0), $product->pr_uid);
                }
                else{
                    $theme = $this->theme->findById($ref);
                    $this->product->save(array('pr_th_id'=>$theme->d_th_id), $product->pr_uid);
                }
                //redirect('u/create/' . $product->pr_uid . '/design', 'refresh');
                redirect('u/create/' . $product->pr_uid . '/canvas', 'refresh');
            }

            $this->load->model('design_sizes_model',    'size');

            // Check the type of product >> DR_PR_TYPE
            if ($product->pr_type == 0){
                redirect('u/create/' . $product->pr_uid);
            }
            
            // paper sizes
            $pr_options = unserialize(@$product->pr_options);
            $pr_size    = $pr_options['set-size'] == "" ? "A4" : $pr_options['set-size'];
            $pr_pages   = intval(@$pr_options['set-pages']);

            $page = 's4-design';
            $data = $this->step4($product->pr_type, $product->pr_th_id, $pr_pages);

            $data['paper'] = $this->size->getDimension($pr_size);
            $data['theme'] = $this->theme->findById($product->pr_th_id, 'd_th_id');
        }
        else if ($step == 'canvas'){
            if ($ref != ''){
                if ($ref == 'no-theme'){
                    $this->product->save(array('pr_th_id'=>0), $product->pr_uid);
                }
                else{
                    $theme = $this->theme->findById($ref);
                    $this->product->save(array('pr_th_id'=>$theme->d_th_id), $product->pr_uid);
                }
                redirect('u/create/' . $product->pr_uid . '/design', 'refresh');
            }

            $this->load->model('design_sizes_model',    'size');

            // Check the type of product >> DR_PR_TYPE
            if ($product->pr_type == 0){
                redirect('u/create/' . $product->pr_uid);
            }
            
            // paper sizes
            $pr_options = unserialize(@$product->pr_options);
            $pr_size    = $pr_options['set-size'] == "" ? "A4" : $pr_options['set-size'];
            $pr_pages   = intval(@$pr_options['set-pages']);

            $page = 's4-canvas';
            $data = $this->step4($product->pr_type, $product->pr_th_id, $pr_pages);

            $data['paper'] = $this->size->getDimension($pr_size);
            $data['theme'] = $this->theme->findById($product->pr_th_id, 'd_th_id');
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

        $data['product']    = $product;
        $data['title']      = 'Create Design';
        $data['page']       = $page;
        $data['ref']        = $ref;
        $data['step']       = $step;

        $this->load->view('m_design', $data);
    }

    /**
     * the following methods handle the different steps of designing.
     */
    private function step1(){        
        $data['list'] = $this->design->findAll();
        return $data;
    }

    /**
     * this method will show user the list of themes.
     */
    private function step2($user_product='', $page=1){
        $ref                    = $user_product->pr_type;
        $product                = $this->design->findById($ref, 'd_pr_id');

        $user_options           = unserialize($user_product->pr_options);
        $size                   = isset($user_options['set-size']) ? $user_options['set-size'] : '';

        // Pagination for themes.
        $per_page = PER_PAGE;

        $this->load->library('pagination');
        $config['base_url']         = site_url("u/create/" . $user_product->pr_uid . "/theme/page/");
        $config['total_rows']       = $this->theme->countThemes($product->d_pr_id, $size);
        $config['per_page']         = $per_page;
        $config['uri_segment']      = 6;

        $this->pagination->initialize($config);
        $currentPage                = intval($page) == 0 ? 1 : intval($page);
        $startIndex                 = ($currentPage - 1) * $per_page;

        $data['status_title']       = 'SELD Creative Editor';
        $data['status_msg']         = $product->d_pr_name . ' Theme Select : Displaying Themes for SIZE <strong style="text-decoration:underline;color:#000;">' . $size . '</strong>';
        $data['product']            = $ref == '' ? '' : $product->d_pr_name;
        $data['themes']             = $this->theme->findThemes($product->d_pr_id, $size, '', $per_page, $startIndex);
        $data['pagination']         = $this->pagination->create_links();
        $data['total']              = $config['total_rows'];
        $data['empty_msg']          = 'Theme not available for <strong>' . $product->d_pr_name . '</strong> of size <strong>' . $size . '</strong>';
        return $data;
    }

    private function step3($type){
        $data['type']           = $type->d_pr_id;
        $data['status_msg']     = $type->d_pr_name . ' Options';
        return $data;
    }

    private function step4($product_id=0, $theme_id=0, $opt_page=0, $opt_face=0){
        $product    = $this->design->findById($product_id, 'd_pr_id');
        $theme      = $this->theme->findById($theme_id, 'd_th_id');
        
        // Page Properties ========================================================
        $prop = array();
        $prop['face']   = 1;//intval($opt_face) != 0 ? intval($opt_face) : intval($product->d_pr_face);
        $prop['page']   = intval($opt_page) != 0 ? intval($opt_page) : intval($product->d_pr_page);

        $data['prop']   = $prop;
        $data['folder'] = '1345a255120ac901e0ac2ee2b973a';
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

    /**
     * this method will save the layer contents to current product
     */
    public function save(){
        $this->load->model('product_model',         'product'   );

        $product_uid    = $this->input->post('id');
        //var_dump(($_POST['content']));
        $content = str_replace(array("\n", "\r"), '', trim($this->input->post('content')));
        $content = preg_replace("/[[:blank:]]+/", " ", $content);

        $form = array(
                        'pr_contents'   => $content,
                        'pr_status'     => 'designing',
                        'pr_title'      => urldecode($this->input->post('title')),
                        'pr_description'=> urldecode($this->input->post('description'))
                    );
        $this->product->save($form, $product_uid);
    }

    /**
     * this will save the html5 canvas object as JSON
     */
    public function saveCanvas(){
        $this->load->model('product_model',         'product'   );

        $product_uid= $this->input->post('id');
        $content    = $this->input->post('data');
        $form = array(
                        'pr_contents'   => $content,
                        'pr_status'     => 'designing',
                        'pr_title'      => 'new design',
                        'pr_description'=> 'description...'
                    );
        $this->product->save($form, $product_uid);
    }

    /**
     * this method will delete the requested design file.
     */
    public function delete($product_uid='', $tb=''){
        $this->load->model('product_model');

        $product = $this->product_model->findById($product_uid);
        if ($product != null && $product->pr_cl_id == $this->client_id){
            $this->product_model->delete($product_uid);
        }
        $tb = $tb == '' ? 'u/list_designs' : base64_decode($tb);
        redirect($tb);
    }
 
    /**
     * this method will upload the images
     * also
     * create a thumbnail of 150x150px.
     */
    public function upload($folder='temp'){

        $folder     = '1345a255120ac901e0ac2ee2b973a'; // get user folder name        
        $output_dir = "./files/uploads/{$folder}/";
        $thumbs_dir = "./files/uploads/{$folder}/thumbs/"; 
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
     * this will save the product pages as image.
     */
    public function saveimg(){
        
        // Load Design Models
        $this->load->model('product_model', 'product');

        $product_uid = $this->input->post('folder');
        $product = $this->product->findById($product_uid);

        $product == null && die('<h2>Invalid Access</h2>'.anchor('u/list_designs', 'Go Back'));

        $img        = $_POST['imgBase64'];
        $img        = str_replace('data:image/png;base64,', '', $img);
        $img        = str_replace(' ', '+', $img);
        $fileData   = base64_decode($img);

        // filename
        $folder     = './files/products/' . $product_uid . '/';
        $fileName   = $folder.'page-' . $this->input->post('name') . '.png';

        // save the file.
        file_put_contents($fileName, $fileData);

        // Update product publish record.
        $form = array(
                    'pr_publish' => '1',
                    'pr_publish_contents' => $product->pr_publish_contents . ',' . $this->input->post('name') . '.png'
                );
        $this->product->save($form, $product_uid);
    }

    /**
     * this will publish the design.
     *
     * All the components of the design will be saved as image
     *      and then compiled as one page. 
     */
    public function publish($product_uid=''){
        
        // Load Design Models
        $this->load->model('product_model', 'product');
        $this->load->model('design_sizes_model', 'size');
        $this->load->model('design_products_model', 'design');

        $product = $this->product->findById($product_uid);

        $product == null && die('<h2>Invalid Access</h2>'.anchor('u/list_designs', 'Go Back'));

        /**
         * this will prepare the folder, if does not exist, create one.
         */
        $folder_name = $product_uid;
            if (!file_exists('./files/products/'.$folder_name)){
            mkdir('./files/products/'.$folder_name, 0777, true);
        }

        $pr_options = unserialize(@$product->pr_options);
        $pr_size    = $pr_options['set-size'] == "" ? "A4" : $pr_options['set-size'];
        $pr_pages   = intval(@$pr_options['set-pages']);

        $pr_pages   = $pr_pages <= 0 ? 1 : $pr_pages;

        $data['prop']       = array('page'=>$pr_pages, 'face'=>1);
        $data['paper']      = $this->size->getDimension($pr_size);

        $data['title']      = 'publish';
        $data['product']    = $product;
        $data['folder']     = $folder_name;

        $this->load->view('m_design_publish', $data);
    }

    /**
     * this method will create the PDF file
     */
    public function preview($id=''){

        require_once("./application/libraries/dompdf/dompdf_config.inc.php");

        $this->load->model('product_model', 'product');
        $this->load->model('design_sizes_model', 'size');

        //$id = '1666055120ac901e0ac2ee2b974f6d86';
        $product = $this->product->findById($id);

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

            for ($k=0; $k<count($json); $k++){
                $obj = $json[$k];

                /**
                 * Get the contents for the current page only.
                 */
                if ($obj->page == $i){

                    /**
                     * perform action on the basis of object type.
                     * type:
                     *      canvas      - get the background color for the page.
                     *      image       - get the source URL and include with properties of object.
                     *      shape       - get the image and include in PDF.
                     *      text        - get the image and include in PDF.
                     */
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
                                $content.= '<img src="' . base_url() . 'files/products/' . $id . '/page-' . $obj->id . '.png" class="seld-image" />';
                            }
                            break;

                        case 'shape':
                            $content.= '<img src="' . base_url() . 'files/products/' . $id . '/page-' . $obj->id . '.png" class="seld-image" />';
                            break;

                        case 'text':
                            $content.= '<img src="' . base_url() . 'files/products/' . $id . '/page-' . $obj->id . '.png" class="seld-image" />';
                            break;
                    }
                    //$html.= $obj->name.'***';
                }
            }

            $html.= '<div class="print_pads" id="page-' . $i . '" style="' . $css . '">' . $content .'</div>';
        }

        //echo ($html);
        //new dBug($json);
        //die('***finished');

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

        $dompdf = new DOMPDF();

        if (get_magic_quotes_gpc())
            $output = stripslashes($output);

        $dompdf->load_html($output);
        $dompdf->set_paper($op_size, 'potrait');
        $dompdf->render();

        //$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
        $output = $dompdf->output();
        file_put_contents('files/products/' . $id . '/design.pdf', $output);
        exit;
    }

    /**
     * this method will add favourite
     */
    public function favourite($theme=''){
        $this->load->model('design_themes_model', 'theme');
        $this->load->model('favourite_model', 'favourite');

        $theme  = $this->theme->findById($theme);
        $id     = $theme->d_th_id;

        $data = array(
                    'fav_cl_id'     => $this->client_id,
                    'fav_thm_id'    => intval($id)
                );
        //echo $this->favourite->save($data);
    }

    public function t(){
        echo time().'-'.rand(999,999999);
        echo '<hr />';
        echo md5(rand(1000,999999).time());
    }
}

/* End of file u.php */
/* Location: ./application/controllers/u.php */