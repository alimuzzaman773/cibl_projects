<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Discount_partners extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewDiscountPartners");
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('EBL Discount Partners');
            $crud->set_table('discount_partners');

            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

            $crud->columns('category', 'partner_name', 'details', 'address', 'url', 'mobileno', 'DiscountUploadImage');

            $crud->field_type('offerType', 'dropdown', array('0' => 'Unlimited', '1' => 'Limited'));

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('category', 'partner_name', 'type', 'offerType', 'fromDate', 'toDate', 'details', 'discountPercentage', 'address', 'url', 'mobileno', 'DiscountUploadImage', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('category', 'partner_name', 'type','offerType', 'fromDate', 'toDate','details', 'discountPercentage', 'address', 'url', 'mobileno', 'DiscountUploadImage', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);

            $crud->display_as('category', 'Category')
                    ->display_as('partner_name', 'Partner Name')
                    ->display_as('type', 'Type')
                    ->display_as('details', 'Discount/Offer Detail')
                    ->display_as('offerType', 'Offer Type (Date range must be given in case of limited offer)')
                    ->display_as('fromDate', 'From Date')
                    ->display_as('toDate', 'To Date')
                    ->display_as('address', 'Location/Address')
                    ->display_as('url', 'Web site')
                    ->display_as('mobileno', 'Mobile No')
                    ->display_as('DiscountUploadImage', 'Upload Image');

            $crud->callback_after_upload(array($this, 'discount_callback_after_upload'));
            $crud->set_relation('category', 'partner_type_setup', 'discountPartners');
            $crud->set_field_upload('DiscountUploadImage', 'assets/uploads/files');

            //$crud->callback_after_insert(array($this, 'imageTransfer'));
            //$crud->callback_after_update(array($this, 'imageTransfer'));

            $crud->unset_delete();

            if (!ci_check_permission("canAddDiscountPartner")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditDiscountPartner")):
                $crud->unset_edit();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Discount Partners";
            $output->base_url = base_url();

            $output->body_template = "discount_partners/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }
    
    function discount_callback_after_upload($uploader_response, $field_info, $files_to_upload) {

        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path . '/' . $uploader_response[0]->name;
        $this->image_moo->load($file_uploaded)->resize(200, 200)->save($file_uploaded, true);
        return true;
    }

    function imageTransfer($str) {
        $uploadImage = $this->input->post('DiscountUploadImage');

        if (!empty($uploadImage)) {

            $url = API_SERVER_PATH;
            $postData = array('imageName' => $uploadImage, 'folderName' => 'discount');

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
