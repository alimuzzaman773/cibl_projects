<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Merchant_location extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        //$this->my_session->authorize("canViewMerchantLocation");
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Merchant Location');

            $crud->set_table(TBL_MERCHANT_LOCATION);

            $crud->required_fields(array('code', 'category'));

            $crud->columns('code', 'category', 'division', 'zone', 'partner_name','image');

            $time = date("Y-m-d H:i:s");

            $crud->set_field_upload('image', 'assets/uploads/files');

            $crud->add_fields('code', 'category', 'division', 'zone', 'partner_name', 'latitude', 'longitude', 'address', 'website', 'email', 'mobile', 'image', 'created', 'updated');
            $crud->edit_fields('code', 'category', 'division', 'zone', 'partner_name', 'latitude', 'longitude', 'address', 'website', 'email', 'mobile', 'image', 'updated');

            $crud->change_field_type('created', 'hidden', $time);
            $crud->change_field_type('updated', 'hidden', $time);

            //$crud->unset_delete();
            
            $crud->unset_texteditor('address');

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Merchant Location";
            $output->base_url = base_url();

            $output->body_template = "crud/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
