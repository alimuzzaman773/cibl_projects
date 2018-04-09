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
        $this->load->library('session');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
        $this->load->library('grocery_CRUD');
    }

    public function _crud_view($output = null) {
        $this->load->view('default_view.php', $output);
    }

    public function index($params = null) {
        $this->output->set_template('theme1');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $actionNames = $this->session->userdata('actionNames');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $actionNames = explode("#", $actionNames);

        foreach ($moduleCodes as $index => $value) {
            if ($moduleCodes[$index] == biller_setup_module) {
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
                $moduleWiseActionCodes = $actionCodes[$index];
                if (strpos($moduleWiseActionCodes, "add") <= -1) {
                    $crud->unset_add();
                }
                if (strpos($moduleWiseActionCodes, "edit") <= -1) {
                    $crud->unset_edit();
                }


                $crud->set_primary_key('billTypeCode', 'bill_type');
                $crud->set_relation('billTypeCode', 'bill_type', 'billTypeName');

                $crud->required_fields('billerName', 'cfId', 'billerCode', 'accNo', 'accountNo', 'billTypeCode');

                $output = $crud->render();

                if (isset($params)) {
                    if ($params == 'read') {
                        $output->page_title = "Biller Setup";
                        $this->_crud_view($output);
                    } else if ($params == 'add') {
                        $output->page_title = "Add New Biller";
                        $this->_crud_view($output);
                    } else if ($params == 'edit') {
                        $output->page_title = "Edit Biller";
                        $this->_crud_view($output);
                    } else {
                        $output->page_title = "Biller Setup";
                        $this->_crud_view($output);
                    }
                } else {
                    $output->page_title = "Biller Setup";
                    $this->_crud_view($output);
                }
            }
        }
    }

}
