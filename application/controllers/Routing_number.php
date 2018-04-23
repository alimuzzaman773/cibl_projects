<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Routing_number extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index($params = null) {
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Routing Number');
            $crud->set_table('routing_number');

            $crud->columns('bankName', 'districtName', 'branchName', 'routingNumber', 'creationDtTm', 'isActive');
            $crud->display_as('bankName', 'Bank Name')
                    ->display_as('districtName', 'District Name')
                    ->display_as('branchName', 'Branch Name')
                    ->display_as('routingNumber', 'Routing Number')
                    ->display_as('creationDtTm', 'Creation Date & Time')
                    ->display_as('isActive', 'Is Active');

            $crud->unset_delete();

            if (!ci_check_permission("canAddRoutingNumber")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditRoutingNumber")):
                $crud->unset_edit();
            endif;
            if (!ci_check_permission("canDetailsRoutingNumber")):
                $crud->unset_read();
            endif;
            if (!ci_check_permission("canExportRoutingNumber")):
                $crud->unset_export();
            endif;
            if (!ci_check_permission("canPrintRoutingNumber")):
                $crud->unset_print();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Routing Number";
            $output->base_url = base_url();

            $output->body_template = "crud/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
