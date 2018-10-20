<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bill_type_setup extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    public function index($params = null) {
        $this->my_session->authorize("canViewBillType");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Bill Type Setup');
            $crud->set_table('bill_type');

            $crud->required_fields('billTypeCode', 'billTypeName', 'moduleName', 'pBillTypeCode');

            $crud->columns('billTypeCode', 'billTypeName', 'moduleName', 'isActive');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('billTypeCode', 'billTypeName', 'moduleName', 'isActive', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('billTypeCode', 'billTypeName', 'moduleName', 'isActive', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('billTypeCode', 'Bill Type Code')
                    ->display_as('billTypeName', 'Bill Type')
                    ->display_as('moduleName', 'Module')
                    ->display_as('isActive', 'Is Active');

            $crud->unset_delete();

            if (!ci_check_permission("canAddBillType")):
                $crud->unset_add();
            endif;

            if (!ci_check_permission("canEditBillType")):
                $crud->unset_edit();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Bill Type Setup";
            $output->base_url = base_url();

            $output->body_template = "bill_type/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
