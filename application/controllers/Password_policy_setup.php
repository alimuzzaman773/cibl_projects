<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Password_policy_setup extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewPasswordPolicySetup");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Password Policy Setup');
            $crud->set_table(TBL_VALIDATION);

            $crud->required_fields('validationName', 'vCode');
            $crud->columns('validationName', 'vCode', 'rule', 'notification', 'isActive');

            $crud->add_fields('validationName', 'vCode', 'rule', 'notification', 'isActive');
            $crud->edit_fields('validationName', 'vCode', 'rule', 'notification', 'isActive');
            
            $crud->unset_delete();
            $crud->unset_add();
            $crud->unset_edit();

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Password Policy Setup";
            $output->base_url = base_url();

            $output->body_template = "crud/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
