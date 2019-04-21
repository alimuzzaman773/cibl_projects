<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library('grocery_CRUD');
    }

    function bo_log() {
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('BO Activity log');
            $crud->set_table(TBL_BO_ACTIVITY_LOG);
            $crud->order_by('activityLogId', 'desc');

            $crud->columns('actionName', 'moduleName', 'adminUserName', 'creationDtTm');

            $crud->display_as('adminUserName', 'User Name');

            $crud->unset_delete();
            $crud->unset_add();
            $crud->unset_edit();
            $crud->unset_print();

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "BO Activity log";
            $output->base_url = base_url();

            $output->body_template = "crud/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
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
        $crud->unset_print();

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
        $crud->unset_print();

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
        $crud->unset_print();

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
        $crud->unset_print();

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
        $crud->unset_print();

        $output = $crud->render();
        $this->_crud_view($output);
    }

}
