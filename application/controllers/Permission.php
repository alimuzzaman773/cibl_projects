<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Permission extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        //$this->my_session->authorize("canViewPermission");
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Permission');

            $crud->set_table(TBL_PERMISSION);

            $crud->required_fields(array('name', 'groups'));

            $crud->set_rules('name', 'Permission Name', 'trim|required');
            $crud->set_rules('groups', 'Permission Groups', 'trim|required');

            $crud->set_relation('name', TBL_ACTION, 'actionName');

            $crud->columns('name', 'description', 'groups', 'updateDtTm', 'creationDtTm');

            $time = date("Y-m-d H:i:s");

            $crud->add_fields('name', 'groups', 'description', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('name', 'description', 'groups', 'description', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);

            $crud->unset_delete();

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Permission";
            $output->base_url = base_url();

            $output->body_template = "permission/permission_index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
