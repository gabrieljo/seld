<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
     * this method will create a new Product
     * 
     * Create NEW product if not already created.
     */
    private function currentProduct(){

        $id = intval(@get_cookie(APPID.'_product_create'));
        $id = 1;
        if ($id > 0){ // check
            $product    = $this->product->findById($id, 'pr_id');
            if ($product == NULL || $product->pr_status != 'designing' || $product->pr_cl_id != $this->client_id){
                $id = 0;
            }
        }

        if ($id == 0){
            $data = array(
                        'pr_cl_id'     => $this->client_id
                    );
            var_dump($data);exit;
            $this->product->save($data);
            $id = $this->db->insert_id();
            $product    = $this->product->findById($id, 'pr_id');
            set_cookie(array('name'=>APPID.'_product_create', 'value'=>$id, 'expire'=>SESSION));
        }
        return $product;
    }

    /**
     * this method will handle the create design
     */
    public function create($step='product', $ref='', $ref2=''){

        // Load Design Models
        $this->load->model('product_model',         'product'   );
        $this->load->model('design_products_model', 'design'    );
        $this->load->model('design_themes_model',   'theme'     );
        //echo $this->client_id;exit;
        // Create NEW PRODUCT
        $product = $this->currentProduct();
        
        // ========================== STEP 2 ============================
        if ($step == 'theme'){
            $ref=='' && redirect('u/create');
            // Update PR_TYPE
            $type = $this->design->findById($ref);
            $this->product->save(array('pr_type'=>$type->d_pr_id), $product->pr_uid);

            $page = 's2-theme';
            $data = $this->step2($ref, $ref2);
            $product = $this->currentProduct();
        }
        else if ($step == 'settings'){
            $ref=='' && redirect('u/create');
            // Update PR_TH_ID
            $theme = $this->theme->findById($ref);
            $this->product->save(array('pr_th_id'=>$theme->d_th_id), $product->pr_uid);

            $page = 's3-settings';
            $data = $this->step3($this->design->findById($product->pr_type, 'd_pr_id'));
        }
        else if ($step == 'design'){
            if (isset($_POST['frmsubmit'])){
                $form = $_POST;
                unset($form['frmsubmit']);
                $form = serialize($form);
                $this->product->save(array('pr_options'=>$form), $product->pr_uid);
                redirect('u/create/design', 'refresh');
            }
            $page = 's4-design';
            $data = $this->step4($product->pr_type, $product->pr_th_id);
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
    private function step2($ref='', $page=1){

        $product                = $this->design->findById($ref);

        // Pagination for themes.
        $per_page = PER_PAGE;

        $this->load->library('pagination');
        $config['base_url']          = site_url("u/create/theme/{$ref}/");
        $config['total_rows']        = $this->theme->countThemes($product->d_pr_id);
        $config['per_page']          = $per_page;
        $config['uri_segment']       = 5;

        $this->pagination->initialize($config);
        $currentPage                = intval($page) == 0 ? 1 : intval($page);
        $startIndex                 = ($currentPage - 1) * $per_page;

        $data['status_title']       = 'SELD Creative Editor';
        $data['status_msg']         = $product->d_pr_name . ' Theme Select';
        $data['product']            = $ref == '' ? '' : $product->d_pr_name;
        $data['themes']             = $this->theme->findThemes($product->d_pr_id, '', $per_page, $startIndex);
        $data['pagination']         = $this->pagination->create_links();
        $data['total']              = $config['total_rows'];
        return $data;
    }

    private function step3($type){
        //new dBug($type);exit;
        $data['type']           = $type->d_pr_id;
        $data['status_msg']     = $type->d_pr_name . ' Options';
        return $data;
    }

    private function step4($product_id=0, $theme_id=0){
        $product    = $this->design->findById($product_id, 'd_pr_id');
        $theme      = $this->theme->findById($theme_id, 'd_th_id');
        
        // Prepare Status Menu for TOTAL PAGES AND FACES ==========================
        $status = '';
        if ($product->d_pr_face == 2){
            $options = array('front'=>'Front', 'back' => 'Back');
            //$status.= 'Side: ' . form_dropdown('design-face', $options, 'front', 'id="design-face"') . ' <span class="gap"></span> ';
            $data['design_face'] = form_dropdown('design-face', $options, 'front', 'id="design-face"');
        }
        if ($product->d_pr_page > 1){
            $options = array();
            for ($i=1; $i<=$product->d_pr_page; $i++){
                $options["".$i] = 'Page ' . $i;
            }
            //$status.= 'Page Number: ' . form_dropdown('design-page', $options, '1', 'id="design-page"');            
            $data['design_page'] = form_dropdown('design-page', $options, '1', 'id="design-page"');            
        }

        // == theme dimension =====================================================
        if ($theme->d_th_properties == ''){
            $prop['width']  = 500;
            $prop['height'] = 400;
            $prop['scale']  = 1;
        }
        else{
            $prop = unserialize($theme->d_th_properties);
        }
        $prop['face']   = $product->d_pr_face;
        $prop['page']   = $product->d_pr_page;

        $data['prop']   = $prop;
        $data['folder'] = '1345a255120ac901e0ac2ee2b973a';
        //$data['status_msg'] = $status;
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

        $product = $this->currentProduct();
        //var_dump(($_POST['content']));
        //new dBug($product);
        $content = str_replace(array("\n", "\r"), '', trim($this->input->post('content')));
        $content = preg_replace("/[[:blank:]]+/", " ", $content);

        $form = array(
                        'pr_contents' => $content
                    );
        $this->product->save($form, $product->pr_uid);
    }

    /**
     * this method will create the PDF file
     */
    public function export($id=''){

        require_once("./application/libraries/dompdf/dompdf_config.inc.php");

        $this->load->model('product_model', 'product');

        $id = '1666055120ac901e0ac2ee2b974f6d86';
        $product = $this->product->findById($id);       

        $skip = array(
                    '<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90; display: none;"></div>',
                    '<div class="ui-resizable-handle ui-resizable-s" style="z-index: 90; display: none;"></div>',
                    '<div class="ui-resizable-handle ui-resizable-e" style="z-index: 90; display: none;"></div>',
                    '<span class="glyphicon glyphicon-move"></span>',
                    'ui-resizable-autohide',
                    'ui-draggable-handle',
                    'ui-resizable-handle', 
                    'ui-resizable-se', 
                    'ui-resizable-e', 
                    'ui-resizable-s',
                    'ui-resizable',
                    'ui-draggable',
                    'ui-rotatable-handle',
                    'ui-icon',
                    '<div class=" "></div>'
                    );
        //echo htmlentities($product->pr_contents);
        $html = str_replace($skip, '', $product->pr_contents);
        //echo '<hr />' . htmlentities($html);exit;

        // Process and render PDF output
        $dom    = new DOMDocument();
        $dom->loadHTML($html);
        $html   = '';

        $layers         = $dom->getElementsByTagName('div');
        $layers_text    = $dom->getElementsByTagName('textarea');
        $layers_text_sn = 0;

        for ($i=0; $i<$layers->length; $i++){
            $style  = $layers->item($i)->getAttribute('style');
            $type   = $layers->item($i)->getAttribute('data-type');

            if ($type == 'text'){
                $html.= '<div style="position:absolute;'.$style.'">';
                // Prepare content
                $textarea   = $layers_text->item($layers_text_sn);
                $content    = mb_convert_encoding($textarea->getAttribute('data-content'), 'HTML-ENTITIES', 'UTF-8');
                $html.= '<div style="padding:2px;'. $textarea->getAttribute('style') . '">' . urldecode($content) . '</div>';

                $html.= '</div>';
                $layers_text_sn++;
                //echo $html.'<hr />';
            }
        }

        //$html.= '<div style="position;absolute;width:500px;height:830px;right:0;top:550px;background:url(' . base_url(). '/files/img/logox.png) no-repeat center center;"></div>';
        //exit;
        $output = '<html>
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <style>
                                *{ font-family: DejaVu Sans, font-size: 12px;}
                                #coverT, #coverB, #coverL, #coverR{
                                    width:200px; height:500px; 
                                    position:absolute; z-index:200; 
                                    background:#fff
                                }
                                #coverL{display:block;margin-left:-208px;}
                                #coverR{display:block;margin-left:675px;}
                                #coverT{width:1000px;height:280px;margin:-280px 0 0 -100px;}
                                #coverB{width:1000px;height:200px;margin:485px 0 0 -100px;}
                            </style>
                        </head>
                        <body>
                            <div id="coverL"></div>
                            <div id="coverR"></div>
                            <div id="coverT"></div>
                            <div id="coverB"></div>
                            ' . $html . '
                        </body>
                    </html>';

        $dompdf = new DOMPDF();
        $dompdf->load_html($output);
        $dompdf->set_paper("a4", "potrait");
        $dompdf->render();

        $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
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