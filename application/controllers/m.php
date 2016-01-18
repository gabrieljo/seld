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
        $this->load->view('m_index', $data);
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
    public function create($value='')
    {
        # code...
    }
}

/* End of file m.php */
/* Location: ./application/controllers/m.php */