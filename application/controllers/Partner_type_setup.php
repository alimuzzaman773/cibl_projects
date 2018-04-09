<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Partner_type_setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');


        $this->load->model('login_model');
        $this->load->library('session');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }

        $this->load->library('grocery_CRUD');
        $this->output->set_template('theme1');
    }

    public function _crud_view($output = null) {
        $this->load->view('default_view.php', $output);
    }

    public function index($params = null) {

        $crud = new grocery_CRUD();
        $crud->set_theme('datatables');
        $crud->set_table('partner_type_setup');

        $crud->display_as('discountPartners', 'Discount Partners');
        $crud->display_as('zipPartners', 'Zip Partners');

        $output = $crud->render();
        $this->_crud_view($output);


        // if (isset($params)) {
        //     if ($params == 'read') {
        //         $output->page_title = "Partner  Type";
        //         $this->_crud_view($output);
        //     } else if ($params == 'add') {
        //         $output->page_title = "Add Partner Name Type";
        //         $this->_crud_view($output);
        //     } else if ($params == 'edit') {
        //         $output->page_title = "Edit Partner Name Type";
        //         $this->_crud_view($output);
        //     } else {
        //         $output->page_title = "Partner Name Type";
        //         $this->_crud_view($output);
        //     }
        // } else {
        //     $output->page_title = "Partner Name Type";
        //     $this->_crud_view($output);
        // }
    }

}
