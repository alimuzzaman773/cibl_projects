<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Merchant_terminals extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewMerchantTerminals");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Merchant Terminals');
            $crud->set_table(TBL_MERCHANT_TERMINALS_MC);
//            $crud->where('mcStatus in (1,2)', null, false);
            $crud->order_by('terminalId', 'desc');
            $crud->set_relation("merchantId", "merchant_accounts", "{merchantName} - {merchantCode}");

            $crud->required_fields(array('terminalName', 'address', 'currency', 'accountNo', 'merchantId'));

            $crud->columns('terminalName', 'merchantId', 'address', 'city', 'zip', 'district', 'currency', 'accountNo');

            $time = date("Y-m-d H:i:s");

            $crud->add_fields('merchantId', 'terminalName', 'address', 'currency', 'accountNo', 'city', 'zip', 'district', 'mcStatus', 'makerAction', 'makerActionCode', 'makerActionDt', 'makerActionTm', 'makerActionBy', 'creationDtTm', 'updateDtTm', 'createdBy', 'updatedBy');
            $crud->edit_fields('merchantId', 'terminalName', 'address', 'currency', 'accountNo', 'city', 'zip', 'district', 'mcStatus', 'makerAction', 'makerActionCode', 'makerActionDt', 'makerActionTm', 'makerActionBy', 'creationDtTm', 'updateDtTm', 'createdBy', 'updatedBy');

            $crud->callback_before_update(array($this, 'set_update_callback'));
            $crud->callback_before_insert(array($this, 'set_insert_callback'));

            $crud->change_field_type('createdBy', 'hidden', $this->my_session->userId)
                    ->change_field_type('updatedBy', 'hidden', $this->my_session->userId)
                    ->change_field_type('creationDtTm', 'hidden', $time)
                    ->change_field_type('updateDtTm', 'hidden', $time)
                    ->change_field_type('mcStatus', 'hidden')
                    ->change_field_type('makerActionDt', 'hidden')
                    ->change_field_type('makerActionTm', 'hidden')
                    ->change_field_type('mcStatus', 'hidden')
                    ->change_field_type('makerAction', 'hidden')
                    ->change_field_type('makerActionCode', 'hidden')
                    ->change_field_type('makerActionBy', 'hidden');

            $icon = base_url() . "/assets/images/qrcode.png";
            $crud->add_action('QR', $icon, base_url() . 'merchant_terminals/merchant_qr/');


            if (!ci_check_permission("canAddMerchantTerminals")):
                $crud->unset_add();
            endif;

            if (!ci_check_permission("canEditMerchantTerminals")):
                $crud->unset_edit();
            endif;

            if (!ci_check_permission("canDeleteMerchantTerminals")):
                $crud->unset_delete();
            endif;

            $crud->display_as('terminalName', 'Terminal Name')
                    ->display_as('merchantId', 'Merchant Name')
                    ->display_as('accountNo', 'Account No')
                    ->display_as('address', 'Address')
                    ->display_as('city', 'City')
                    ->display_as('district', 'District')
                    ->display_as('zip', 'zip')
                    ->display_as('currency', 'Currency')
                    ->display_as('makerAction', 'Maker Action')
                    ->display_as('checkerAction', 'Checker Action')
                    ->display_as('checkerActionComment', 'Checker Action Comment');


            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Merchant Terminals";
            $output->base_url = base_url();

            $output->body_template = "merchant_terminals/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function set_insert_callback($post_array) {
        $this->load->library("BOcrypter");

        $post_array['mcStatus'] = 0;
        $post_array['makerAction'] = "add";
        $post_array['makerActionCode'] = '01';
        $post_array['makerActionDt'] = date('Y-m-d');
        $post_array['makerActionTm'] = date('H:i:s');
        $post_array['makerActionBy'] = $this->my_session->userId;

        return $post_array;
    }

    function set_update_callback($post_array, $primary_key) {
        $this->load->library("BOcrypter");

        $post_array['mcStatus'] = 0;
        $post_array['makerAction'] = "edit";
        $post_array['makerActionCode'] = '02';
        $post_array['makerActionDt'] = date('Y-m-d');
        $post_array['makerActionTm'] = date('H:i:s');
        $post_array['makerActionBy'] = $this->my_session->userId;


        return $post_array;
    }

    function merchant_qr($id = NULL) {
        $this->load->library("BOcrypter");
        $this->load->model('merchant_terminals_model');
        $data['merchant_details'] = array();
        $data['merchant_enc'] = '';
        $m_details = $this->merchant_terminals_model->getTerminalById($id);
        $ivlen = openssl_cipher_iv_length('bf-cbc');
        $iv = '';
        while ($ivlen > 0) {
            $iv .= "0";
            $ivlen--;
        }
        $iv = base64_encode($iv);
        if ($m_details) {
            $data['merchant_details'] = $m_details;

            $merchantData = array(
                "terminalId" => $m_details['terminalId'],
                "terminalName" => $m_details['terminalName']
            );
            $m_summary = json_encode($merchantData);
            $data['merchant_enc'] = $this->bocrypter->Encrypt($m_summary, QR_PRIVATE_KEY) . '$$' . $iv;
        }

        $data['pageTitle'] = "Merchant Terminals QR";
        $data['body_template'] = "merchant_terminals/merchant_qr.php";
        $this->load->view('site_template.php', $data);
    }

}
