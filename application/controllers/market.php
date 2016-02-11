<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * this controller is for giving access to the admin panel.
 */
class Market extends CI_Controller {

    private $client = null;

    /**
     * this will extend parent controller's functions and get the stored ADMIN ID.
     * redirect to login if ADMIN ID is not available.
     */
	public function __construct(){

		parent::__construct();

        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->model('rating_model');
        $this->load->model('favourite_model');

        $this->client = (object) array('cl_id'=>0);

        $id = get_cookie(APP_MEMBER.'_member');
        if ($id){
            $this->client = $this->client_model->findById($id);
        }

        $this->load->library('cart');
    }

    /**
     * this will show the dashboard.
     */
    public function index($order_by='name-asc', $page=1, $cat_id=0){
        $this->page($order_by, $page, $cat_id);
    }

    /**
     * show all the categories by default.
     * Select first category for displaying sub-categories if none selected.
     */
    public function page($order_by='name-asc', $page=1, $cat_id=0){

        $per_page   = PER_PAGE; // from constants.
        $category   = $this->category_model->findById($cat_id, 'cat_id');
        $sub_id     = $category != null && $category->cat_parent == 0 ? $category->cat_id : intval(@$category->cat_parent);

        // Pagination
        $this->load->library('pagination');
        $config['base_url']          = site_url('market/page/' . $order_by . '/');
        $config['total_rows']        = $this->product_model->countMarketProducts($this->client->cl_id, $category);
        $config['per_page']          = $per_page;
        $config['uri_segment']       = 4;
        $config['num_links']         = 2;
        $config['use_page_numbers']  = 'TRUE';

        $this->pagination->initialize($config);

        $currentPage                = intval($page) == 0 ? 1 : intval($page);
        $startIndex                 = ($currentPage - 1) * $per_page;
        $endIndex                   = $startIndex + $per_page;

        $data['sub_id']             = $sub_id;
        $data['cat_id']             = $cat_id;
        $data['categories']         = $this->category_model->getCategories();
        $data['sub_categories']     = $this->category_model->getSubCategories($sub_id);
        $data['items']              = $this->product_model->getMarketProducts($this->client->cl_id, $category, $order_by, $per_page, $startIndex);
        $data['pagination']         = $this->pagination->create_links();
        $data['total']              = $config['total_rows'];
        $data['order_by']           = $order_by;

        $this->load->view('market/index', $data);
    }


    /**
     * this will show the detail page of design.
     */
    public function design($uid='', $title=''){

        $this->load->model('design_options_model');

        $product        = $this->product_model->findById($uid);

        $product == null && die('<h1>The design has been moved!</h1>' . anchor('market', 'View all &raquo;'));

        /**
         * Set Hit Counts. check every SESSION TIMER.
         */
        $session = @get_cookie(APPID.'_design_session_' . $product->pr_uid);
        if (!$session){
            $this->product_model->save(array('pr_mk_hits'=>(++$product->pr_mk_hits)), $product->pr_uid);
            set_cookie(array('name'=>APPID.'_design_session_' . $product->pr_uid, 'value'=>'1', 'expire'=>SESSION));
        }

        $data['title']          = 'SELD Design :: Detail Page';
        $data['categories']     = $this->category_model->getCategories();
        $data['sub_categories'] = $this->category_model->getCategories('1', '0');
        $data['product']        = $product;
        $data['favourite']      = $this->favourite_model->getFavourite($this->client->cl_id, $product->pr_id);
        $data['rating']         = $this->rating_model->getRatingAverage($product->pr_id);
        $data['client_id']      = $this->client->cl_id;
        $data['d_options']      = $this->design_options_model->findOptions($product->pr_type);

        $this->load->view('market/design', $data);
    }

    /**
     * this will list all the items in the shopping cart.
     */
    public function cart(){

        $data['title']      = 'My Cart';
        $data['cart']       = $this->cart->contents();
        $data['amount']     = $this->cart->total();

        $this->load->view('market/cart', $data);
    }

    /**
     * this will deal with payment and checkout.
     */
    public function checkout(){

        $data['title']      = 'Checkout';
        $data['amount']     = $this->cart->total();
        $data['total']      = $this->cart->total_items();
        $data['client']     = $this->client;

        $this->load->view('market/checkout', $data);
    }

    /**
     * this will process the payment and place an order.
     */
    public function payment(){

        $this->client->cl_id == 0 && redirect('p/login/');

        $this->load->model('purchase_model');
        $this->load->model('purchase_item_model');

        // add purchase.
        $form = array(
                    'pc_cl_id'      => $this->client->cl_id,
                    'pc_amount'     => $this->cart->total(),
                    'pc_items'      => $this->cart->total_items(),
                    'pc_status'     => 'paid',
                    'pc_ip'         => $_SERVER['REMOTE_ADDR']
                );

        //new dBug($this->cart->contents());

        if ($this->purchase_model->save($form)){

            $pc_id      = $this->db->insert_id();
            // add items.
            foreach ($this->cart->contents() as $item):

                $form = array(
                            'pci_pc_id'         => $pc_id,
                            'pci_pr_id'         => $item['id'],
                            'pci_cl_id'         => $this->client->cl_id,
                            'pci_author_id'     => $item['cl_id'],
                            'pci_status'        => 'published',
                            'pci_price'         => $item['price']
                        );

                // save
                $this->purchase_item_model->save($form);
            endforeach;

            $this->cart->destroy();
        }

        // redirect to response page.
        redirect('p/thank_you', 'refresh');
    }

    /**
     * this will add the product to the shopping cart.
     * 
     * If the product is being repeated, just ignore the request.
     * USER LOGIN REQUIRED only during checkout.
     */
    public function addToCart($product_uid='', $ajax='1'){

        $product = $this->product_model->findById($product_uid);

        $product == null && die('<h1>Invalid Access</h1>' . anchor('market', 'View Designs &raquo;'));

        $client = $this->client_model->findById($product->pr_cl_id, 'cl_id');

        $client == null && die('<h1>Design not found!</h1>');

        // find cheapest price.
        $price1 = $product->pr_mk_orig_price;
        $price2 = $product->pr_mk_price;

        $price = $price1;
        if ($price2 > 0 && $price2 < $price1){
            $price = $price2;
        }

        // check for shopping cart.
        $data = array(
                   'id'         => $product->pr_id,
                   'uid'        => $product->pr_uid,
                   'qty'        => 1,
                   'price'      => $price,
                   'name'       => $product->pr_title,
                   'author'     => $client->cl_firstname . ' ' . $client->cl_lastname,
                   'cl_id'      => $client->cl_id
                );

        $this->cart->insert($data);

        if ($ajax != '0'){            
            //$url = 'market/design/' . $product->pr_uid . '/' . url_title($product->pr_title);
            $url = 'market/cart';
            redirect($url, 'refresh');
        }
    }

    /**
     * this will remove item from cart.
     */
    public function removeProduct($rowid=''){

        $data = array(
                    'rowid' => $rowid,
                    'qty'   => 0
                );
        $this->cart->update($data);

        redirect('market/cart', 'refresh');
    }

    /**
     * this will set the rating of the product
     */
    public function rating($product_uid='', $ajax='1', $tb=''){

        $product = $this->product_model->findById($product_uid);

        ($this->client->cl_id == 0 || $product == null) && redirect('p/login/' . base64_encode(current_url()));

        $rating = $this->rating_model->getRating($this->client->cl_id, $product->pr_id);
        $value  = intval(@$this->input->post('rating'));

        if ($rating){

            $this->rating_model->save(array('rt_value'=>$value), $rating->rt_uid);
        }
        else{
            // Add product to client's favourite.
            $form = array(
                        'rt_cl_id'     => $this->client->cl_id,
                        'rt_pr_id'     => $product->pr_id,
                        'rt_value'     => $value
                    );
            $this->rating_model->save($form);
        }

        if ($ajax != '0'){            
            $url = $tb=='' ? 'market' : base64_decode($tb);
            redirect($url, 'refresh');
        }
    }

    /**
     * this will set the favourite of the product
     */
    public function favourite($product_uid='', $tb=''){

        $product = $this->product_model->findById($product_uid);

        ($this->client->cl_id == 0 || $product == null) && redirect('p/login/' . base64_encode(current_url()));

        $fav = $this->favourite_model->getFavourite($this->client->cl_id, $product->pr_id);

        if ($fav){
            $status = $fav->fav_status == 'published' ? 'deleted' : 'published';
            // toggle Favourite.
            $this->favourite_model->save(array('fav_status'=>$status), $fav->fav_uid);
        }
        else{
            // Add product to client's favourite.
            $form = array(
                        'fav_cl_id'     => $this->client->cl_id,
                        'fav_pr_id'     => $product->pr_id,
                        'fav_status'   => 'published'
                    );
            $this->favourite_model->save($form);
        }

        $url = $tb=='' ? 'market' : base64_decode($tb);
        redirect($url, 'refresh');
    }
}