<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Advertisement extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewAdvertisement");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Advertisement');
            $crud->set_table('advertisement');

            $crud->required_fields('advertisementName', 'addvertisementImage', 'advertisementCode');

            if ((int) $this->uri->segment(4) > 0):
                $crud->set_rules('advertisementName', 'Advertisement Name', 'trim|required');
                $crud->set_rules('advertisementCode', 'Advertisement Code', 'trim|required');
            else:
                $crud->set_rules("advertisementName", "Advertisement Name", "trim|required|is_unique[advertisement.advertisementName]");
                $crud->set_rules("advertisementCode", "Advertisement Code", "trim|required|is_unique[advertisement.advertisementCode]");
            endif;

            $this->load->config('grocery_crud');
            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

            $crud->columns('advertisementCode', 'advertisementName', 'addvertisementImage');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('advertisementCode', 'advertisementName', 'addvertisementImage', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('advertisementCode', 'advertisementName', 'addvertisementImage', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('advertisementName', 'Advertisement Name')
                    ->display_as('advertisementCode', 'Advertisement Code')
                    ->display_as('addvertisementImage', 'Advertisement Image');

            //$crud->callback_after_upload(array($this, 'addvertisementImage_callback_after_upload'));
            $crud->set_field_upload('addvertisementImage', 'assets/uploads/files');

            //$crud->callback_after_insert(array($this, 'imageTransfer'));
            //$crud->callback_after_update(array($this, 'imageTransfer'));

            $crud->unset_print();

            if (!ci_check_permission("canAddAdvertisement")):
                $crud->unset_add();
            endif;

            if (!ci_check_permission("canEditAdvertisement")):
                $crud->unset_edit();
            endif;

            if (!ci_check_permission("canDeleteAdvertisement")):
                $crud->unset_delete();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Advertisement";
            $output->base_url = base_url();

            $output->body_template = "advertisement/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function addvertisementImage_callback_after_upload($uploader_response, $field_info, $files_to_upload) {
        $this->load->library('image_moo');

        //Is only one file uploaded so it ok to use it with $uploader_response[0].
        $file_uploaded = $field_info->upload_path . '/' . $uploader_response[0]->name;

        $this->image_moo->load($file_uploaded)->resize(720, 90)->save($file_uploaded, true);

        return true;
    }

    function imageTransfer($str) {
        $uploadImage = $this->input->post('addvertisementImage');
        if (!empty($uploadImage)) {

            $url = API_SERVER_PATH;
            $postData = array('imageName' => $uploadImage, 'folderName' => 'advertisement');

            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $url);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $postData);
            curl_exec($handle);
            curl_close($handle);
            return true;
        } else {

            return true;
        }
    }

}
