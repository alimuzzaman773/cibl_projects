<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Biller_setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');

        $this->load->model('login_model');
        $this->load->library('my_session');
        $this->my_session->checkSession();
        $this->load->library('grocery_CRUD');
        
        die("no needed");
    }

    public function _crud_view($output = null) {
        $this->load->view('default_view.php', $output);
    }

    public function index($params = null) {
       // $this->output->set_template('theme1');
        //$moduleCodes = $this->session->userdata('moduleCodes');
       // $actionCodes = $this->session->userdata('actionCodes');
        //$actionNames = $this->session->userdata('actionNames');
        $moduleCodes = '';
        $actionCodes = '';
        $actionNames = '';

        
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('biller_setup');
        $crud->columns('billerName', 'cfId', 'billerCode', 'accNo', 'billTypeCode', 'isActive');


        $crud->display_as('billerName', 'Biller Name')
                ->display_as('cfId', 'CF ID')
                ->display_as('billerCode', 'Biller Code')
                ->display_as('accNo', 'Order')
                ->display_as('billTypeCode', 'Bill Type');


        $crud->unset_delete();
        $crud->unset_add();
        $crud->unset_edit();
        

        $crud->set_primary_key('billTypeCode', 'bill_type');
        $crud->set_relation('billTypeCode', 'bill_type', 'billTypeName');

        $crud->required_fields('billerName', 'cfId', 'billerCode', 'accNo', 'accountNo', 'billTypeCode');

        $output = $crud->render();
        $output->body_template = 'default_view.php';
        $this->load->view("site_template.php", $output);
            
    }

}
