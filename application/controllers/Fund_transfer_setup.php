<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fund_transfer_setup extends CI_Controller {

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
            $crud->set_subject('Fund Transfer Setup');
            $crud->set_table(TBL_CBS_PRODUCT);
            $crud->order_by('ptId', 'desc');

            $crud->required_fields(array('productId', 'name', 'fundTransfer'));

            //$crud->unique_fields('productId');

            $crud->columns('productId','name', 'short_name', 'service_type', 'fundTransfer', 'debit', 'credit');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('productId','name', 'short_name', 'service_type', 'fundTransfer', 'debit', 'credit', 'creationDtTm', 'updateDtTm', 'createdBy', 'updatedBy');
            $crud->edit_fields('productId','name', 'short_name', 'service_type', 'fundTransfer', 'debit', 'credit', 'updateDtTm', 'updatedBy');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);
            
            $crud->display_as('fundTransfer', 'IsFundTransfer')
                    ->display_as('debit', 'IsDebit')
                    ->display_as('credit', 'IsCredit');

            $crud->unset_delete();

            //if (!ci_check_permission("canAddProduct")):
                //$crud->unset_add();
            //endif;

            //if (!ci_check_permission("canEditProduct")):
                //$crud->unset_edit();
            //endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Fund Transfer Setup";
            $output->base_url = base_url();

            $output->body_template = "crud/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
