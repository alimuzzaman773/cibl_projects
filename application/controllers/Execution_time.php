<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Execution_time extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->library("session");
        $this->load->library('grocery_CRUD');
    }

    public function _crud_view($output = null) {
        $this->load->view('default_view.php', $output);
    }

    public function index($params = null, $id = null) {

        $this->output->set_template('theme1');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('execution_time');


        $crud->display_as("tagCode", "Tag Code")
                ->display_as("tagName", "Tag Name")
                ->display_as("execTime", "Execution Time (milliseconds)");

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        $output = $crud->render();
        $this->_crud_view($output);
    }

}
