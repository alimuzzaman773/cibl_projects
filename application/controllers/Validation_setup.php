<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Validation_setup extends CI_Controller {

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
            $crud->set_subject('Complaint Info');
            $crud->set_table('validation_group_mc');

            $codes = $this->getValidationCodes();
            $crud->field_type('vCodes', 'multiselect', $codes);

            $crud->fields('validationGroupName', 'message', 'example', 'vgCode', 'vCodes', 'wrongAttempts', 'passHistorySize', 'passExpiryPeriod', 'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod', 'isActive', 'createdBy', 'createdDtTm', 'mcStatus', 'makerAction', 'makerActionCode', 'makerActionDt', 'makerActionTm', 'checkerActionDt', 'checkerActionTm', 'makerActionBy', "checkerAction", "checkerActionComment", "checkerActionBy", 'isPublished');

            $crud->columns('validationGroupName', 'message', 'example', 'wrongAttempts', 'passHistorySize', 'passExpiryPeriod', 'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod', 'makerAction');

            $crud->required_fields('validationGroupName', 'message', 'example', 'wrongAttempts', 'passHistorySize', 'passExpiryPeriod', 'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod'
            );
            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('validationGroupName', 'message', 'example', 'wrongAttempts', 'passHistorySize', 'passExpiryPeriod', 'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod', 'makerAction', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('validationGroupName', 'message', 'example', 'wrongAttempts', 'passHistorySize', 'passExpiryPeriod', 'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod', 'makerAction', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);
            $crud->field_type("isActive", "hidden");
            $crud->field_type("makerActionBy", "hidden");
            $crud->field_type("makerAction", "hidden");
            $crud->field_type("makerActionDt", "hidden");
            $crud->field_type("makerActionTm", "hidden");
            $crud->field_type("makerActionCode", "hidden");
            $crud->field_type("mcStatus", "hidden");
            $crud->field_type("checkerActionDt", "hidden");
            $crud->field_type("checkerActionComment", "hidden");
            $crud->field_type("checkerAction", "hidden");
            $crud->field_type("checkerActionTm", "hidden");
            $crud->field_type("checkerActionBy", "hidden");
            $crud->field_type("isPublished", "hidden");

            $crud->display_as('validationGroupName', 'Group Name')
                    ->display_as('message', 'Message')
                    ->display_as('example', 'Example')
                    ->display_as('wrongAttempts', 'Unsuccessful Attempts Allowed')
                    ->display_as('passHistorySize', 'Password History Size')
                    ->display_as('passExpiryPeriod', 'Password Expiry Period (Days)')
                    ->display_as('warningPeriod', 'Warning Period (days)')
                    ->display_as('hibernationPeriod', 'Hibernation Period (Days)')
                    ->display_as('pinExpiryPeriod', 'Pin Expiry Period (Days)');

            $crud->unset_add();
            $crud->unset_delete();
            //$crud->unset_edit();

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

    function getValidationCodes() {
        $this->db->select('vCode, validationName');
        $this->db->from('validation');
        $this->db->where('isActive', 1);
        $query = $this->db->get();
        $codes = $query->result();
        $data = array();
        if ($codes) {
            foreach ($codes as $key => $value) {
                $data[$value->vCode] = $value->validationName;
            }
            return $data;
        } else {
            $data[] = "No Validation Created";
            return $data;
        }
    }

}
