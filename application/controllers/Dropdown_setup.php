<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dropdown_setup extends CI_Controller {

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

    public function index($params = null) {

        $crud = new grocery_CRUD();
        $crud->set_theme('datatables');
        $crud->set_table('dropdown_setup');
        $crud->columns('name', 'type', 'isActive');

        $crud->field_type('type', 'dropdown', array('district' => 'District', 'bank' => 'Bank'));

        $crud->display_as('name', 'Name')
                ->display_as('type', 'Type')
                ->display_as('isActive', 'Is Active');


        $crud->required_fields('name', 'type', 'isActive');

        $output = $crud->render();
        $this->_crud_view($output);
    }

}
