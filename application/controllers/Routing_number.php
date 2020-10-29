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
        $this->my_session->authorize("canViewRoutingNumberMenu");
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

            //$crud->unset_add();
            //$crud->unset_edit();
            //$crud->unset_delete();

            if (!ci_check_permission("canAddRoutingNumber")):
                $crud->unset_add();
            endif;

            if (!ci_check_permission("canEditRoutingNumber")):
                $crud->unset_edit();
            endif;
            $crud->unset_print();
            //$crud->unset_add_fields('createdBy');
            
            $crud->field_type('createdBy', 'hidden', $this->my_session->userId);
            $crud->field_type('updatedBy', 'hidden', $this->my_session->userId);
            
            $crud->field_type('updateDtTm', 'hidden', date("Y-m-d H:i:s"));
            $crud->field_type('creationDtTm', 'hidden', date("Y-m-d H:i:s"));

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Routing Number";
            $output->base_url = base_url();

            $output->body_template = "routing_number/routing_list.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    function rtgs($params = null) {
        $this->my_session->authorize("canViewRoutingNumberMenu");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('RTGS Routing Number');
            $crud->set_table('rtgs_routing');

            $crud->columns('bankName', 'bankCode', 'branchName', 'branchCode', 'routingNo');
            $crud->display_as('bankName', 'Bank Name')
                    ->display_as('bankCode', 'Bank Code')
                    ->display_as('branchName', 'Branch Name')
                    ->display_as('branchCode', 'Branch Code')
                    ->display_as('routingNo', 'Routing Number');

            //$crud->set_rules('branchCode', 'Branch Code', 'trim|required|xss_clean|min_length[1]|callback__checkDuplicateBranchCode');
            $crud->set_rules('routingNo', 'Routing Number', 'trim|required|xss_clean|min_length[1]|callback__checkDuplicateRoutingNo');

            if (!ci_check_permission("canAddRoutingNumber")):
                $crud->unset_add();
            endif;

            if (!ci_check_permission("canEditRoutingNumber")):
                $crud->unset_edit();
            endif;
            $crud->unset_print();
            //$crud->unset_add_fields('createdBy');

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "RTGS Routing Number";
            $output->base_url = base_url();

            $output->body_template = "routing_number/rtgs_routing_list.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
//    function _checkDuplicateBranchCode($branchCode) {
//        if (trim($this->input->post("branchCode", true)) == "") {
//            $this->form_validation->set_message("_checkDuplicateBranchCode", "Branch Code is required");
//            return false;
//        }
//
//        $ugId = (int) $this->uri->segment(4);
//        $this->db->select("*")
//                ->from("rtgs_routing")
//                ->where("branchCode", $branchCode);
//        if ($ugId > 0):
//            $this->db->where_not_in("rrId", array($ugId));
//        endif;
//        $result = $this->db->get();
//        if ($result->num_rows() > 0):
//            $this->form_validation->set_message("_checkDuplicateBranchCode", "Please enter a different Branch Code.");
//            return false;
//        else:
//            return true;
//        endif;
//    }
    
    function _checkDuplicateRoutingNo($routingNo) {
        if (trim($this->input->post("routingNo", true)) == "") {
            $this->form_validation->set_message("_checkDuplicateRoutingNo", "Routing Number is required");
            return false;
        }

        $ugId = (int) $this->uri->segment(4);
        $this->db->select("*")
                ->from("rtgs_routing")
                ->where("routingNo", $routingNo);
        if ($ugId > 0):
            $this->db->where_not_in("rrId", array($ugId));
        endif;
        $result = $this->db->get();
        if ($result->num_rows() > 0):
            $this->form_validation->set_message("_checkDuplicateRoutingNo", "Please enter a different routing number.");
            return false;
        else:
            return true;
        endif;
    }

}
