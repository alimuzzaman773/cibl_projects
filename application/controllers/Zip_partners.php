<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Zip_partners extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index($params = null, $id = null) {
        $this->my_session->authorize("canViewZipPartner");
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Zip Partners');

            $crud->set_table('zip_partners');

            $crud->columns('mechant_name', 'tenor', 'merchant_web_site', 'mobile', 'uploadImage');

            $crud->required_fields('type', 'parentName', 'childName', 'category', 'mechant_name', 'offerType');
            $this->load->config('grocery_crud');
            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');


            $crud->field_type('offerType', 'dropdown', array('0' => 'Unlimited', '1' => 'Limited'));

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('pc_id', 'type', 'parentName', 'childName', 'mechant_name', 'mobile', 'tenor', 'offerType', 'fromDate', 'toDate', 'remarks', 'merchant_web_site', 'uploadImage', 'banner', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('pc_id', 'type', 'parentName', 'childName', 'mechant_name', 'mobile', 'tenor', 'offerType', 'fromDate', 'toDate', 'remarks', 'merchant_web_site', 'uploadImage', 'banner', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);
            $crud->change_field_type('pc_id', 'hidden', '-1');

            $crud->display_as('type', 'Type')
                    ->display_as('parentName', 'Category')
                    ->display_as('childName', 'Sub-Category')
                    ->display_as('mechant_name', 'Mechant Name')
                    ->display_as('tenor', 'Tenor')
                    ->display_as('offerType', 'Offer Type (Date range must be given in case of limited offer)')
                    ->display_as('fromDate', 'From Date')
                    ->display_as('toDate', 'To Date')
                    ->display_as('remarks', 'Remarks')
                    ->display_as('merchant_web_site', 'Merchant Web Site')
                    ->display_as('uploadImage', 'Upload Image');

            $crud->set_field_upload('uploadImage', 'assets/uploads/files');
            $crud->callback_after_upload(array($this, 'zip_callback_after_upload'));

            $categoryList = array(
                NULL => ''
            );

            $typeList = array('product' => 'Products', 'partner' => 'EMI Partners', 'benefit' => 'Discount Partners');
            $crud->change_field_type('type', 'dropdown', $typeList);
            $crud->change_field_type('parentName', 'dropdown', $categoryList);
            $crud->change_field_type('childName', 'dropdown', $categoryList);

            $this->db->select("*")
                    ->from(TBL_PRODUCT_CATEGORIES);
            $cRes = $this->db->get();
            foreach ($cRes->result() as $r):
                $categoryList[] = $r;
            endforeach;

            $crud->unset_delete();

            if (!ci_check_permission("canAddZipPartner")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditZipPartner")):
                $crud->unset_edit();
            endif;
            $crud->unset_print();

            $zipId = (int) $this->uri->segment(4);
            $resP = $this->getZipInfo($zipId);

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Zip Partners";
            $output->base_url = base_url();

            $output->categories = $categoryList;

            $output->productInfo = array();
            if ($resP) {
                $output->zipInfo = $resP->row();
            }

            $output->crudState = $crud->getState();

            $output->body_template = "zip_partners/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    //Is only one file uploaded so it ok to use it with $uploader_response[0].

    function zip_callback_after_upload($uploader_response, $field_info, $files_to_upload) {

        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path . '/' . $uploader_response[0]->name;
        $this->image_moo->load($file_uploaded)->resize(200, 200)->save($file_uploaded, true);

        return TRUE;
    }

    function imageTransfer($str) {
        $uploadImage = $this->input->post('uploadImage');

        if (!empty($uploadImage)) {
            $url = API_SERVER_PATH;
            $postData = array('imageName' => $uploadImage, 'folderName' => 'zip');

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

    function getZipInfo($zid) {

        $this->db->select("*")
                ->from(TBL_ZIP_PARTNERS)
                ->where("zipId", $zid);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

}
