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
            $crud->set_subject('App Content');
            $crud->set_table('help_center');

            $crud->columns('machineName', 'title', 'helpText');

            $crud->display_as('machineName', 'Machine Name');
            $crud->display_as('title', 'Title');
            $crud->display_as('helpText', 'Content');


            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('machineName', 'title', 'helpText', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('machineName', 'title', 'helpText', 'updateDtTm');

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
            $output->pageTitle = "Settings";
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
            $crud->set_table(TBL_COMPLAINT_INFO);

            $crud->required_fields('empName', 'email');
            
            if ((int) $this->uri->segment(4) > 0):
                $crud->set_rules("email", "Email", "trim|xss_clean|required|valid_email");
            else:
                $crud->set_rules("email", "Email", "trim|xss_clean|required|valid_email|is_unique[" . TBL_COMPLAINT_INFO . ".email]");
            endif;

            $crud->columns('empName', 'designation', 'contactNo', 'contactDetails', 'email', 'isActive');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('empName', 'designation', 'contactNo', 'contactDetails', 'email', 'isActive', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('empName', 'designation', 'contactNo', 'contactDetails', 'email', 'isActive', 'updateDtTm');

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
            $crud->unset_add();

//            if (!ci_check_permission("canAddHelpSetup")):
//                $crud->unset_add();
//            endif;
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
