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

            $crud->unset_add();
            $crud->unset_delete();
            $crud->unset_edit();

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
