<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Files extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewFiles");
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Files');
            $crud->set_table(TBL_FILES);
            $crud->order_by('fileId', 'desc');

            $crud->required_fields('name', 'fileName');

            $crud->columns('name', 'fileName');

            $crud->set_field_upload('fileName', 'assets/uploads/files');

            $crud->callback_column('fileName', array($this, '_callback_file_name'));

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('name', 'fileName', 'createdBy', 'updatedBy', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('name', 'fileName', 'updatedBy', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('name', 'Name')
                    ->display_as('fileName', 'File');

            $crud->unset_print();

            if (!ci_check_permission("canAddFiles")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditFiles")):
                $crud->unset_edit();
            endif;
            if (!ci_check_permission("canDeleteFiles")):
                $crud->unset_delete();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Files";
            $output->base_url = base_url();

            $output->body_template = "files/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function _callback_file_name($value, $row) {
        $path = LIVE_URL . 'assets/uploads/files/' . $value;
        return "<a href='$path' target='_blank'>$path</a>";
    }

}
