<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class News_events extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('News Events');
            $crud->set_table('news_events');

            $crud->required_fields('newsEventsHeadline', 'newsEventsDetails', 'publishDate', 'expiryDate');

            $crud->columns('newsEventsHeadline', 'newsEventsDetails', 'publishDate', 'expiryDate', 'isActive');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('newsEventsHeadline', 'newsEventsDetails', 'publishDate', 'expiryDate', 'isActive', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('newsEventsHeadline', 'newsEventsDetails', 'publishDate', 'expiryDate', 'isActive', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('newsEventsHeadline', 'News & Events Headline');
            $crud->display_as('newsEventsDetails', 'News & Events  Details');
            $crud->display_as('publishDate', 'Publish Date');
            $crud->display_as('expiryDate', 'Expiry Date');
            $crud->display_as('isActive', 'Is Active');

            $crud->unset_delete();

            if (!ci_check_permission("canAddNewsEvenet")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditNewsEvenet")):
                $crud->unset_edit();
            endif;
            if (!ci_check_permission("canDetailsNewsEvenet")):
                $crud->unset_read();
            endif;
            if (!ci_check_permission("canExportNewsEvenet")):
                $crud->unset_export();
            endif;
            if (!ci_check_permission("canPrintNewsEvenet")):
                $crud->unset_print();
            endif;
            
            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "News Events";
            $output->base_url = base_url();

            $output->body_template = "news_events/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
}
