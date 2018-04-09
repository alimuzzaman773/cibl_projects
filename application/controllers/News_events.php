<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News_events extends CI_Controller {

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

        $moduleCodes = $this->session->userdata('contentSetupModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(newsEvents, $moduleCodes);
        if ($index > -1) {


            $crud = new grocery_CRUD();
            $crud->set_theme('flexigrid');
            $crud->set_table('news_events');


            $crud->columns('newsEventsHeadline', 'newsEventsDetails', 'publishDate', 'expiryDate', 'isActive');


            $crud->display_as('newsEventsHeadline', 'News & Events Headline');
            $crud->display_as('newsEventsDetails', 'News & Events  Details');
            $crud->display_as('publishDate', 'Publish Date');
            $crud->display_as('expiryDate', 'Expiry Date');
            $crud->display_as('isActive', 'Is Active');


            $crud->field_type("createdBy", "hidden");
            $crud->field_type("updatedBy", "hidden");
            $crud->field_type("creationDtTm", "hidden");
            $crud->field_type('updateDtTm', 'hidden');


            $crud->callback_before_insert(array($this, 'add_data'));
            $crud->callback_before_update(array($this, 'update_data'));
            $crud->required_fields('newsEventsHeadline', 'newsEventsDetails', 'publishDate', 'expiryDate');


            $output = $crud->render();

            if (isset($params)) {
                if ($params == 'read') {
                    $output->page_title = "News & Events";
                    $this->_crud_view($output);
                } else if ($params == 'add') {
                    $output->page_title = "Add News & Events";
                    $this->_crud_view($output);
                } else if ($params == 'edit') {
                    $output->page_title = "Edit News & Events";
                    $this->_crud_view($output);
                } else {
                    $output->page_title = "News & Events";
                    $this->_crud_view($output);
                }
            } else {
                $output->page_title = "News & Events";
                $this->_crud_view($output);
            }
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

}
