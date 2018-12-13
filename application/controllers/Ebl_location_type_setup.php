<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ebl_location_type_setup extends CI_Controller {

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

        $crud = new grocery_CRUD();
        $crud->set_theme('datatables');
        $crud->set_table('ebl_location');
        //$crud->required_fields('eblDivision', 'AddressLine1','AddressLine2','City');


        $crud->display_as('eblDivision', 'Division')
                ->display_as('eblDistrict', 'District')
                ->display_as('eblNearYou', 'EBL Near You');






        $output = $crud->render();
        $this->_crud_view($output);

        // if (isset($params)) {
        //     if ($params == 'read') {
        //         $output->page_title = "EBL Location Setup";
        //         $this->load->view('viewLocations.php', $output);
        //     } else if ($params == 'add') {
        //         $output->page_title = "EBL Location Setup";
        //         $this->_crud_view($output);
        //     } else if ($params == 'edit') {
        //         $output->page_title = "EBL Location Setup";
        //         $this->_crud_view($output);
        //     } else {
        //         $output->page_title = "EBL Location Setup";
        //         $this->_crud_view($output);
        //     }
        // } else {
        //     $output->page_title = "EBL Location Setup";
        //     $this->_crud_view($output);
        // }
    }

}
