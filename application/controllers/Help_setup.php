<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help_setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('login_model');
        $this->load->library('grocery_CRUD');
        $this->output->set_template('theme1');

        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    public function _crud_view($output = null) {
        $this->load->view('default_view.php', $output);
    }

    public function index($params = null) {


        $moduleCodes = $this->session->userdata('contentSetupModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(help, $moduleCodes);
        if ($index > -1) {


            $crud = new grocery_CRUD();
            $crud->set_theme('flexigrid');
            $crud->set_table('help_center');
            $crud->columns('helpText');

            $crud->display_as('helpText', 'Help');

            $crud->field_type("createdBy", "hidden");
            $crud->field_type("updatedBy", "hidden");
            $crud->field_type("creationDtTm", "hidden");
            $crud->field_type('updateDtTm', 'hidden');

            $crud->callback_before_insert(array($this, 'add_data'));
            $crud->callback_before_update(array($this, 'update_data'));


            $output = $crud->render();
            $this->_crud_view($output);
        } else {
            echo "not allowed";
            die();
        }
    }

    function add_data($post_array) {
        $post_array['createdBy'] = $this->session->userdata('adminUserId');
        ;
        $post_array['updatedBy'] = $this->session->userdata('adminUserId');
        ;
        $post_array['creationDtTm'] = input_date();
        $post_array['updateDtTm'] = input_date();
        return $post_array;
    }

    function update_data($post_array) {
        $post_array['updatedBy'] = $this->session->userdata('adminUserId');
        ;
        $post_array['updateDtTm'] = input_date();
        return $post_array;
    }

    public function complaintInfo($params = null) {
        $moduleCodes = $this->session->userdata('contentSetupModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(help, $moduleCodes);
        if ($index > -1) {
            $crud = new grocery_CRUD();
            $crud->set_theme('flexigrid');
            $crud->set_table('complaint_info');
            $crud->columns('empName', 'designation', 'contactNo', 'contactDetails', 'isActive');
            $crud->display_as('empName', 'Employee Name')
                    ->display_as('designation', 'Designation')
                    ->display_as('contactNo', 'Contact No')
                    ->display_as('isActive', 'Is Active')
                    ->display_as('contactDetails', 'Contact Details');
            $crud->field_type("createdBy", "hidden");
            $crud->field_type("updatedBy", "hidden");
            $crud->field_type("creationDtTm", "hidden");
            $crud->field_type('updateDtTm', 'hidden');

            $crud->callback_before_insert(array($this, 'add_data'));
            $crud->callback_before_update(array($this, 'update_data'));

            $output = $crud->render();
            $output->page_title = "Complaint Info";
            $this->_crud_view($output);
        } else {
            echo "not allowed";
            die();
        }
    }

}
