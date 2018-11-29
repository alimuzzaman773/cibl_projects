<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Activity_log_type extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library('grocery_CRUD');
    }

    function index() {
        //$this->my_session->authorize("canViewProduct");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Activity log type');
            $crud->set_table(TBL_APP_USER_ACTIVITY_LOG_TYPE);
            $crud->order_by('activityLogTypeId', 'desc');

            $crud->required_fields(array('actionName', 'actionCode'));
            
            $crud->columns('actionName', 'actionCode', 'created', 'updated');

            $time = date("Y-m-d H:i:s");
            
            $crud->add_fields('actionName', 'actionCode', 'created', 'updated');
            $crud->edit_fields('actionName', 'actionCode', 'updated');

            $crud->change_field_type('created', 'hidden', $time);
            $crud->change_field_type('updated', 'hidden', $time);

            $crud->unset_delete();
            
            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Activity log type";
            $output->base_url = base_url();

            $output->body_template = "crud/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
