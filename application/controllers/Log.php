<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('grocery_CRUD');
    }
    
    public function _crud_view($output = null) {
        $this->load->view('default_view.php', $output);
    }

    public function transactionLog($params = null, $id = null) {

        $this->output->set_template('theme1');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('apps_transaction');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        $output = $crud->render();
        $this->_crud_view($output);
    }

    public function billsPayLog($params = null, $id = null) {

        $this->output->set_template('theme1');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('apps_bill_pay');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        $output = $crud->render();
        $this->_crud_view($output);
    }

    public function appsUser($params = null, $id = null) {
        $this->output->set_template('theme1');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('app_user_activity_log');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        $output = $crud->render();
        $this->_crud_view($output);
    }

    public function adminUser() {
        $this->output->set_template('theme1');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('bo_activity_log');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        $output = $crud->render();
        $this->_crud_view($output);
    }

    public function externalService() {
        $this->output->set_template('theme1');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('external_service_log');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        $output = $crud->render();
        $this->_crud_view($output);
    }

}
