<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Locator_setup extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('my_session');
        $this->load->library('grocery_CRUD');

        $this->my_session->checkSession();
    }

    function index($params = null, $id = null) {
        $this->my_session->authorize("canViewLocatorSetup");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Location');
            $crud->set_table('atms');
            $crud->required_fields('eblNearYou', 'eblDivision', 'ATMName', 'AddressLine1', 'AddressLine2', 'City');

            $crud->columns('eblNearYou', 'branchCode', 'ATMName', 'eblDivision', 'AddressLine1', 'AddressLine2', 'ManagerName', 'Email', 'Mobile', 'PriorityManager', 'PriorityEmail', 'PriorityMobile');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('eblNearYou', 'branchCode', 'ATMName', 'eblDivision', 'Priority', 'AddressLine1', 'AddressLine2', 'ManagerName', 'Email', 'Mobile', 'PriorityManager', 'PriorityEmail', 'PriorityMobile', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('eblNearYou', 'branchCode', 'ATMName', 'eblDivision', 'Priority','AddressLine1', 'AddressLine2', 'ManagerName', 'Email', 'Mobile', 'PriorityManager', 'PriorityEmail', 'PriorityMobile', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('ATMName', 'ATM/Branch Name')
                    ->display_as('eblDivision', 'Division')
                    ->display_as('eblNearYou', 'PBL Near You')
                    ->display_as('branchCode', 'Branch Code')
                    ->display_as('Priority', 'Priority Centers')
                    ->display_as('AddressLine1', 'Address Line 1')
                    ->display_as('AddressLine2', 'Address Line 2')
                    ->display_as('AddressLine3', 'Address Line 3')
                    ->display_as('ManagerName', 'Manager(Branch)')
                    ->display_as('Email', 'Email(Branch)')
                    ->display_as('Mobile', 'Mobile(Branch)')
                    ->display_as('PriorityManager', 'Manager(Priority)')
                    ->display_as('PriorityEmail', 'Email(Priority)')
                    ->display_as('BankID', 'Bank Name')
                    ->display_as('PriorityMobile', 'Mobile(Priority)');

            $crud->set_relation('BankID', 'banks', 'BankName');
            $crud->set_relation('eblDivision', 'ebl_location', 'eblDivision');
            $crud->set_relation('eblNearYou', 'ebl_location', 'eblNearYou');
            $crud->set_rules('branchCode', 'Branch', 'callback_branch');

            $crud->unset_delete();

            if (!ci_check_permission("canAddLocatorSetup")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditLocatorSetup")):
                $crud->unset_edit();
            endif;
            if (!ci_check_permission("canDetailsLocatorSetup")):
                $crud->unset_read();
            endif;
            if (!ci_check_permission("canExportLocatorSetup")):
                $crud->unset_export();
            endif;
            if (!ci_check_permission("canPrintLocatorSetup")):
                $crud->unset_print();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Locations";
            $output->base_url = base_url();

            $output->body_template = 'locator_setup/index.php';

            $this->load->view('site_template.php', $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function branch($str) {
        $eblNearYou = $this->input->post('eblNearYou');
        $branchCode = $this->input->post('branchCode');
        $addQuery = $this->db->get_where('atms', array('branchCode' => $branchCode));
        $id = $this->uri->segment(4);
        if (!empty($id) && is_numeric($id)) {
            $this->db->where("branchCode = '$branchCode' AND ATMID != '$id'");
            $editQuery = $this->db->get('atms');


            if (!empty($editQuery->result_array()) && $eblNearYou == "1") {
                $this->form_validation->set_message('branch', "The branch code - " . $branchCode . " is already inserted.");
                return false;
            } else {
                return true;
            }
        } else {
            if (!empty($addQuery->result_array()) && $eblNearYou == "1" && $branchCode != 0) {
                $this->form_validation->set_message('branch', "The branch code - " . $branchCode . " is already inserted.");
                return false;
            } else if ($eblNearYou == "1" && (empty($branchCode) || $branchCode == "0")) {
                $this->form_validation->set_message('branch', 'Branch code field is mendatory when "Branches" is selected against EBL Near You');
                return false;
            } else if ($eblNearYou != "1" && (!empty($branchCode))) {
                $this->form_validation->set_message('branch', 'Branch code field should be empty if "Branches" is not selected');
                return false;
            } else {
                return true;
            }
        }
    }

    function add_data($post_array) {
        $post_array['createdBy'] = $this->session->userdata('adminUserId');
        $post_array['updatedBy'] = $this->session->userdata('adminUserId');
        $post_array['creationDtTm'] = input_date();
        $post_array['updateDtTm'] = input_date();

        if (isset($post_array['Latitude']) && $post_array['Latitude'] != "" && isset($post_array['Longitude']) && $post_array['Longitude'] != "") {
            return $post_array;
        }

        $this->load->helper('my_helper');
        $post_array = getLatitudeLongitudeFromAddress($post_array);
        return $post_array;
    }

    function update_data($post_array) {

        $post_array['updatedBy'] = $this->session->userdata('adminUserId');
        $post_array['updateDtTm'] = input_date();
        return $post_array;
    }

}
