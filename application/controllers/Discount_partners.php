<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Discount_partners extends CI_Controller {

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


        $moduleCodes = $this->session->userdata('contentSetupModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(benifit, $moduleCodes);
        if ($index > -1) {

            $crud = new grocery_CRUD();
            $crud->set_theme('flexigrid');
            $crud->set_table('discount_partners');
            $crud->required_fields('category', 'partner_name', 'offerType');
            $this->load->config('grocery_crud');
            $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'gif|jpeg|jpg|png');

            $crud->columns('category', 'partner_name', 'details', 'address', 'url', 'mobileno', 'DiscountUploadImage');


            $crud->field_type('offerType', 'dropdown', array('0' => 'Unlimited', '1' => 'Limited'));

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

            $crud->field_type("createdBy", "hidden");
            $crud->field_type("updatedBy", "hidden");
            $crud->field_type("creationDtTm", "hidden");
            $crud->field_type('updateDtTm', 'hidden');


            $crud->callback_after_upload(array($this, 'discount_callback_after_upload'));
            $crud->set_relation('category', 'partner_type_setup', 'discountPartners');
            $crud->set_field_upload('DiscountUploadImage', 'assets/uploads/discount');


            $crud->callback_before_insert(array($this, 'add_data'));
            $crud->callback_before_update(array($this, 'update_data'));

            $crud->callback_after_insert(array($this, 'imageTransfer'));
            $crud->callback_after_update(array($this, 'imageTransfer'));



            $output = $crud->render();

            if (isset($params)) {
                if ($params == 'read') {
                    $output->page_title = "EBL Discount Setup";
                    $this->_crud_view($output);
                    //$this->load->view('view_atm.php', $output);
                } else if ($params == 'add') {
                    $output->page_title = "Add New Discount";
                    $this->_crud_view($output);
                } else if ($params == 'edit') {
                    $output->page_title = "Edit Discount";
                    $this->_crud_view($output);
                } else {
                    $output->page_title = "EBL Discount ";
                    $this->_crud_view($output);
                }
            } else {
                $output->page_title = "EBL Discount Setup";
                $this->_crud_view($output);
            }
        } else {
            echo "not allowed";
            die();
        }
    }

    function discount_callback_after_upload($uploader_response, $field_info, $files_to_upload) {

        $this->load->library('image_moo');
        $file_uploaded = $field_info->upload_path . '/' . $uploader_response[0]->name;
        $this->image_moo->load($file_uploaded)->resize(200, 200)->save($file_uploaded, true);
        return true;
    }

    function imageTransfer($str) {

        if (!empty($_POST['DiscountUploadImage'])) {

            //$url = 'http://192.168.3.182:4443/eblapi/image_save/imageSave';
            $url = 'http://192.168.5.81/eblapi/image_save/imageSave';
            $postData = array('imageName' => $_POST['DiscountUploadImage'], 'folderName' => 'discount');

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
