<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class validation_setup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        
        $this->load->database();
        $this->load->helper('url');

        $this->load->model('login_model');
        $this->load->library('session');
        if($this->login_model->check_session()){
            redirect('/admin_login/index');
        }

        $this->load->library('grocery_CRUD');
        $this->output->set_template('theme1');
    }

    public function _crud_view($output = null)
    {
        $this->load->view('default_view.php',$output);
    }

    public function index($params = null) {
        
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('validation_group');

        $codes = $this->getValidationCodes();
        $crud->field_type('vCodes','multiselect', $codes);
        $crud->columns('validationGroupName', 'message', 'example', 
            'wrongAttempts', 'passHistorySize', 'passExpiryPeriod', 
            'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod', 'isActive');

        $crud->display_as('validationGroupName', 'Group Name')
             ->display_as('message', 'Message')
             ->display_as('example', 'Example')
             ->display_as('wrongAttempts', 'Unsessful Attempts Allowed')
             ->display_as('passHistorySize', 'Password History Size')
             ->display_as('passExpiryPeriod', 'Password Expiry Period (Days)')
             ->display_as('warningPeriod', 'Warning Period (days)')
             ->display_as('hibernationPeriod', 'Hibernation Period (Days)')
             ->display_as('pinExpiryPeriod', 'Pin Expiry Period (Days)')
             ->display_as('isActive', 'Is Active');

	$crud->unset_add();
	$crud->unset_edit();
	$crud->unset_delete();
	
        $output = $crud->render();

        if (isset($params)) {
            if ($params == 'read') {
                $output->page_title = "Validation Setup";
                $this->_crud_view($output);
            } else if ($params == 'add') {
                $output->page_title = "Add Validation";
                $this->_crud_view($output);
            } else if ($params == 'edit') {
                $output->page_title = "Edit Validation";
                $this->_crud_view($output);
            } else {
                $output->page_title = "Validation Setup";
                $this->_crud_view($output);
            }
        } else {
            $output->page_title = "Validation Setup";
            $this->_crud_view($output);
        }
    }

    function getValidationCodes()
    {
        $this->db->select('vCode, validationName');
        $this->db->from('validation');
        $this->db->where('isActive', 1);
        $query = $this->db->get();
        $codes = $query->result();

        if($codes){
            foreach($codes as $key => $value){
                $data[$value->vCode] = $value->validationName;
            }
            return $data;
        }

        else{
            $data[] = "No Validation Created";
            return $data;
        }
    }
}