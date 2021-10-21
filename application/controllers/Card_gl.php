<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Card_gl extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Card GL');
            $crud->set_table('card_gl_new');

            $crud->required_fields('bin_number','type');

            $crud->columns('bin_number', 'gl_account', 'currency','type','feeAccountNo','vatAccountNo');

            $crud->add_fields('bin_number', 'gl_account', 'currency','type','feeAccountNo','vatAccountNo');
            $crud->edit_fields('bin_number', 'gl_account', 'currency','type','feeAccountNo','vatAccountNo');

            $crud->display_as('bin_number', 'Bin Number');
            $crud->display_as('gl_account', 'GL Account');
            $crud->display_as('currency', 'Currency');
            $crud->display_as('type', 'Type');
            $crud->display_as('feeAccountNo', 'Fee Account Number');
            $crud->display_as('vatAccountNo', 'Vat Account Number');

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Card GL";
            $output->base_url = base_url();

            $output->body_template = "card_gl/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
