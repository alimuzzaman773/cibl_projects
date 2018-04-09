<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bill_type_setup extends CI_Controller {

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
            if ($moduleCodes[$index] == bill_type_setup_module) {
                $crud = new grocery_CRUD();
                $crud->set_theme('flexigrid');
                $crud->set_table('bill_type');
                $crud->columns('billTypeCode', 'billTypeName', 'moduleName', 'isActive');

                $crud->display_as('billTypeCode', 'Bill Type Code')
                        ->display_as('billTypeName', 'Bill Type')
                        ->display_as('moduleName', 'Module')
                        ->display_as('isActive', 'Is Active');


                $crud->unset_delete();
                $moduleWiseActionCodes = $actionCodes[$index];
                if (strpos($moduleWiseActionCodes, "add") <= -1) {
                    $crud->unset_add();
                }
                if (strpos($moduleWiseActionCodes, "edit") <= -1) {
                    $crud->unset_edit();
                }


                $crud->required_fields('billTypeCode', 'billTypeName', 'moduleName', 'pBillTypeCode');
                $output = $crud->render();
                if (isset($params)) {
                    if ($params == 'read') {
                        $output->page_title = "Bill Type Setup";
                        $this->_crud_view($output);
                    } else if ($params == 'add') {
                        $output->page_title = "Add New Bill Type";
                        $this->_crud_view($output);
                    } else if ($params == 'edit') {
                        $output->page_title = "Edit Bill Type";
                        $this->_crud_view($output);
                    } else {
                        $output->page_title = "Bill Type Setup";
                        $this->_crud_view($output);
                    }
                } else {
                    $output->page_title = "Bill Type Setup";
                    $this->_crud_view($output);
                }
            }
        }
    }

}
