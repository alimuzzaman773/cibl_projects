<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Priority_products extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewPriorityProduct");
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Priority Products');

            $crud->set_table('priority_products');

            //$crud->required_fields('parentName', 'childName', 'productName');

            //$crud->unique_fields('productName');

            $crud->columns('pageNumber', 'header', 'body');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('pageNumber', 'header', 'body', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('pageNumber', 'header', 'body', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('pageNumber', 'Page Number');
            $crud->display_as('header', 'Header');
            $crud->display_as('body', 'Body');

            $crud->unset_delete();

            if (!ci_check_permission("canAddPriorityProduct")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditPriorityProduct")):
                $crud->unset_edit();
            endif;
            $crud->unset_print();

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Priority Product";
            $output->base_url = base_url();

            $output->body_template = "priority_products/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
