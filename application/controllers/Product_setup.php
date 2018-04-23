<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_setup extends CI_Controller {

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
            $crud->set_subject('Product');
            $crud->set_table(TBL_PRODUCT_SETUP);

            $crud->required_fields('parentName', 'childName', 'productName');

            $crud->unique_fields('productName');

            $crud->columns('parentName', 'childName', 'productName', 'productDetails', 'isActive', 'productOrder');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('parentName', 'childName', 'productName', 'productDetails', 'isActive', 'productOrder', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('parentName', 'childName', 'productName', 'productDetails', 'isActive', 'productOrder', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('parentName', 'Category');
            $crud->display_as('childName', 'Sub-Category');
            $crud->display_as('tagline', 'Tagline');
            $crud->display_as('productDetails', 'Description');
            $crud->display_as('productName', 'Product Name');
            $crud->display_as('productOrder', 'Product Order');
            $crud->display_as('isActive', 'Is Active');

            $crud->set_relation('parentName', TBL_PRODUCT_TYPE_SETUP, 'parentName');
            $crud->set_relation('childName', TBL_PRODUCT_TYPE_SETUP, 'childName');

            $crud->unset_delete();

            if (!ci_check_permission("canAddProduct")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditProduct")):
                $crud->unset_edit();
            endif;
            if (!ci_check_permission("canReadProduct")):
                $crud->unset_read();
            endif;
            if (!ci_check_permission("canExportProduct")):
                $crud->unset_export();
            endif;
            if (!ci_check_permission("canPrintProduct")):
                $crud->unset_print();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Product";
            $output->base_url = base_url();

            $output->body_template = "product/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
