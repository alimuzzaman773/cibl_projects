<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help_setup extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewComplaintInfo");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Help');
            $crud->set_table('help_center');

            $crud->columns('helpText');

            $crud->display_as('helpText', 'Help');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('helpText', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('helpText', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->unset_delete();
            if (!ci_check_permission("canAddComplaintInfo")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canDeleteComplaintInfo")):
                $crud->unset_delete();
            endif;
            if (!ci_check_permission("canEditComplaintInfo")):
                $crud->unset_edit();
            endif;

            if (!ci_check_permission("canAddHelpSetup")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditHelpSetup")):
                $crud->unset_edit();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Help";
            $output->base_url = base_url();

            $output->body_template = "help/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function complaintInfo($params = null) {
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Complaint Info');
            $crud->set_table('complaint_info');

            $crud->required_fields('parentName', 'childName', 'productName');

            $crud->columns('empName', 'designation', 'contactNo', 'contactDetails', 'isActive');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('empName', 'designation', 'contactNo', 'contactDetails', 'isActive', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('empName', 'designation', 'contactNo', 'contactDetails', 'isActive', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('empName', 'Employee Name')
                    ->display_as('designation', 'Designation')
                    ->display_as('contactNo', 'Contact No')
                    ->display_as('isActive', 'Is Active')
                    ->display_as('contactDetails', 'Contact Details');

            $crud->unset_delete();

            if (!ci_check_permission("canAddHelpSetup")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditHelpSetup")):
                $crud->unset_edit();
            endif;
            if (!ci_check_permission("canDetailsHelpSetup")):
                $crud->unset_read();
            endif;
            if (!ci_check_permission("canExportHelpSetup")):
                $crud->unset_export();
            endif;
            if (!ci_check_permission("canPrintHelpSetup")):
                $crud->unset_print();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Complaint Info";
            $output->base_url = base_url();

            $output->body_template = "crud/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
