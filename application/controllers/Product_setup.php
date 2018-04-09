<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');

        $this->load->model('login_model');
        $this->load->library('session');
        if($this->login_model->check_session()){
            redirect('/admin_login/index');
        }
        
        $this->load->library('grocery_CRUD');
        $this->output->set_template('theme1');
    }


    public function _crud_view($output = null)
    {
        $this->load->view('default_view.php',$output);
    }

    public function index($params = null) {

        $moduleCodes = $this->session->userdata('contentSetupModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(product, $moduleCodes);
        if($index > -1){



        $crud = new grocery_CRUD();

        $crud->set_theme('flexigrid');
        $crud->set_table('product_setup');

        $crud->required_fields('parentName', 'childName', 'productName');

        $crud->unique_fields('productName');

        //$crud->columns('parentName', 'childName', 'productName', 'productDetails', 'isActive');
	 $crud->columns('parentName', 'childName', 'productName', 'productDetails', 'isActive', 'productOrder');

        $crud->display_as('parentName', 'Category');
        $crud->display_as('childName', 'Sub-Category');
        $crud->display_as('tagline', 'Tagline');
        $crud->display_as('productDetails', 'Description');
        $crud->display_as('productName', 'Product Name');
	 $crud->display_as('productOrder', 'Product Order');
        $crud->display_as('isActive', 'Is Active');


        $crud->field_type("createdBy", "hidden");
        $crud->field_type("updatedBy", "hidden");
        $crud->field_type("creationDtTm", "hidden");
        $crud->field_type("updateDtTm", "hidden");

        $crud->set_relation('parentName', 'product_type_setup', 'parentName');
        $crud->set_relation('childName', 'product_type_setup', 'childName');


       // $crud->change_field_type('updateDtTm', 'hidden');

	 $crud->callback_before_insert(array($this, 'add_data'));
        $crud->callback_before_update(array($this, 'update_data'));

        $output = $crud->render();

        if (isset($params)) {
            if ($params == 'read') {
                $output->page_title = "Product Type";
                $this->_crud_view($output);
            } else if ($params == 'add') {
                $output->page_title = "Add Product Name Type";
                $this->_crud_view($output);
            } else if ($params == 'edit') {
                $output->page_title = "Edit Product Name Type";
                $this->_crud_view($output);
            } else {
                $output->page_title = "Product Name Type";
                $this->_crud_view($output);
            }
        } else {
            $output->page_title = "Product Name Type";
            $this->_crud_view($output);
        }

	}else{
            echo "not allowed";
            die();
        }


    }


function add_data($post_array)
{
    $post_array['createdBy'] = $this->session->userdata('adminUserId');;
    $post_array['updatedBy'] = $this->session->userdata('adminUserId');;
    $post_array['creationDtTm'] = input_date();
    $post_array['updateDtTm'] = input_date();
    return $post_array;
}


function update_data($post_array)
{
    $post_array['updatedBy'] = $this->session->userdata('adminUserId');;
    $post_array['updateDtTm'] = input_date();
    return $post_array;
}


}


