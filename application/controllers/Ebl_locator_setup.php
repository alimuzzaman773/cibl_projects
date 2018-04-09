<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ebl_locator_setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');

        $this->load->model('login_model');
        $this->load->library('session');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }


        $this->load->library('grocery_CRUD');
        $this->output->set_template('theme1');
    }

    public function _crud_view($output = null) {
        $this->load->view('default_view.php', $output);
    }

    public function index($params = null, $id = null) {


        $moduleCodes = $this->session->userdata('contentSetupModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(location, $moduleCodes);
        if ($index > -1) {


            $crud = new grocery_CRUD();
            $crud->set_theme('flexigrid');
            $crud->set_table('atms');
            $crud->required_fields('eblNearYou', 'eblDivision', 'ATMName', 'AddressLine1', 'AddressLine2', 'City');


            $crud->columns('eblNearYou', 'branchCode', 'ATMName', 'eblDivision', 'AddressLine1', 'AddressLine2', 'ManagerName', 'Email', 'Mobile', 'PriorityManager', 'PriorityEmail', 'PriorityMobile');


            $crud->display_as('ATMName', 'ATM/Branch Name')
                    ->display_as('eblDivision', 'Division')
                    ->display_as('eblNearYou', 'EBL Near You')
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


            $crud->field_type("createdBy", "hidden");
            $crud->field_type("updatedBy", "hidden");
            $crud->field_type("creationDtTm", "hidden");
            $crud->field_type('updateDtTm', 'hidden');


            $crud->callback_before_insert(array($this, 'add_data'));
            $crud->callback_before_update(array($this, 'update_data'));



            $crud->set_rules('branchCode', 'Branch', 'callback_branch');

            $output = $crud->render();

            if (isset($params)) {
                if ($params == 'read') {
                    $output->page_title = "EBL Location Setup";
                    //$this->load->view('view_atm.php', $output);
                    $this->_crud_view($output);
                } else if ($params == 'add') {
                    $output->page_title = "Add New EBL Location Setup";
                    $this->_crud_view($output);
                } else if ($params == 'edit') {
                    $output->page_title = "Edit EBL Location Setup";
                    $this->_crud_view($output);
                } else {
                    $output->page_title = "EBL Location Setup";
                    $this->_crud_view($output);
                }
            } else {
                $output->page_title = "EBL Location Setup";
                $this->_crud_view($output);
            }
        } else {
            echo "not allowed";
            die();
        }
    }

    function branch($str) {
        $eblNearYou = $_POST['eblNearYou'];
        $branchCode = $_POST['branchCode'];
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
        ;
        $post_array['updatedBy'] = $this->session->userdata('adminUserId');
        ;
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
        ;
        $post_array['updateDtTm'] = input_date();
        return $post_array;
    }

}
