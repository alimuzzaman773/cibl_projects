<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Routing_number extends CI_Controller {

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
        $this->load->view('routing_number/routing_number_view.php',$output);
    }

    public function index($params = null) {
        
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('routing_number');


        $crud->columns('bankName', 'districtName', 'branchName', 'routingNumber', 'creationDtTm' ,'isActive');
        $crud->display_as('bankName', 'Bank Name')
                ->display_as('districtName', 'District Name')
                ->display_as('branchName', 'Branch Name')
                ->display_as('routingNumber', 'Routing Number')
                ->display_as('creationDtTm', 'Creation Date & Time')
                ->display_as('isActive', 'Is Active');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

       $output = $crud->render();
        if (isset($params)) {
            if ($params == 'read') {
                $output->page_title = "Routing Number Setup";
                $this->_crud_view($output);
            } else if ($params == 'add') {
                $output->page_title = "Add New Routing Number";
                $this->_crud_view($output);
            } else if ($params == 'edit') {
                $output->page_title = "Edit Routing Number";
                $this->_crud_view($output);
            } else {
                $output->page_title = "Routing Number Setup";
                $this->_crud_view($output);
            }
        } else {
            $output->page_title = "Routing Number Setup";
            $this->_crud_view($output);
        }
    }

}


