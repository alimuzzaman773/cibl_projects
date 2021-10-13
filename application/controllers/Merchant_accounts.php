<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Merchant_accounts extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewMerchantAccounts");
        try {
            $crud = new grocery_CRUD();

            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Merchant Accounts');

            $crud->set_table(TBL_MERCHANT_ACCOUNTS_MC);
            $crud->where('mcStatus in (1,2)', null, false);
            $crud->order_by('merchantId', 'desc');

            $crud->required_fields(array('username', 'merchantCode', 'merchantName', 'merchantAccountNo'));

            if ((int) $this->uri->segment(4) > 0):
                $crud->set_rules('merchantCode', 'Merchant Code', 'trim|required');
            else:
                $crud->set_rules("merchantCode", "Merchant Code", "trim|required|is_unique[" . TBL_MERCHANT_ACCOUNTS_MC . ".merchantCode]");
            endif;

            $crud->columns('accountStatus', 'merchantEmail', 'perTrnPercentage', 'merchantCode', 'merchantName', 'merchantAccountNo', 'merchantAddress', 'merchantPhone', 'merchantWebsite', 'merchantLogo');

            $time = date("Y-m-d H:i:s");

            $crud->set_field_upload('merchantLogo', 'assets/uploads/files');

            $crud->add_fields('accountStatus', 'merchantEmail', 'perTrnPercentage', 'merchantCode', 'merchantCategory', 'schoolSession', 'schoolTerms', 'merchantName', 'merchantAccountNo', 'merchantAddress', 'merchantPhone', 'merchantWebsite', 'merchantLogo', 'mcStatus', 'makerAction', 'makerActionCode', 'makerActionDt', 'makerActionTm', 'makerActionBy', 'creationDtTm', 'updateDtTm', 'createdBy', 'updatedBy');

            $crud->edit_fields('accountStatus', 'merchantEmail', 'perTrnPercentage', 'merchantCode', 'merchantCategory', 'schoolSession', 'schoolTerms', 'merchantName', 'merchantAccountNo', 'merchantAddress', 'merchantPhone', 'merchantWebsite', 'merchantLogo', 'mcStatus', 'makerAction', 'makerActionCode', 'makerActionDt', 'makerActionTm', 'makerActionBy', 'updateDtTm', 'updatedBy');

            $crud->callback_edit_field('password', array($this, 'callback_edit_password_field'));
            $crud->callback_before_update(array($this, 'set_update_callback'));
            $crud->callback_before_insert(array($this, 'set_insert_callback'));

            $crud->callback_add_field('schoolSession', function () {
                $cData['sessions'] = json_encode([]);
                return $this->load->view('merchant_accounts/school_session.php', $cData, true);
            });

            $crud->callback_edit_field('schoolSession', function ($value, $primaryKey) {
                $cData['sessions'] = $value;
                return $this->load->view('merchant_accounts/school_session.php', $cData, true);
            });

            $crud->callback_add_field('schoolTerms', function () {
                $cData['terms'] = json_encode([]);
                return $this->load->view('merchant_accounts/school_terms.php', $cData, true);
            });

            $crud->callback_edit_field('schoolTerms', function ($value, $primaryKey) {
                $cData['terms'] = $value;
                return $this->load->view('merchant_accounts/school_terms.php', $cData, true);
            });

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
            $crud->add_action('QR', $icon, base_url() . 'merchant_accounts/merchant_qr/');


            if (!ci_check_permission("canAddMerchantAccounts")):
                $crud->unset_add();
            endif;

            if (!ci_check_permission("canEditMerchantAccounts")):
                $crud->unset_edit();
            endif;

            if (!ci_check_permission("canDeleteMerchantAccounts")):
                $crud->unset_delete();
            endif;

            $crud->unset_texteditor('merchantAddress');
            $crud->unset_texteditor('schoolTerms');
            $crud->unset_texteditor('schoolSession');

            $crud->display_as('merchantCode', 'Merchant Code')
                    ->display_as('merchantName', 'Merchant Name')
                    ->display_as('merchantAccountNo', 'Account No')
                    ->display_as('merchantAddress', 'Address')
                    ->display_as('merchantPhone', 'Phone')
                    ->display_as('merchantWebsite', 'Website')
                    ->display_as('merchantLogo', 'Logo')
                    ->display_as('makerAction', 'Maker Action')
                    ->display_as('checkerAction', 'Checker Action')
                    ->display_as('checkerActionComment', 'Checker Action Comment');


            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Merchant Accounts";
            $output->base_url = base_url();

            $output->body_template = "merchant_accounts/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function callback_edit_password_field() {
        return '<input type="text" value="" name="password"> Leave blank if you do not wish to change password';
    }

    private function setSchoolInfo(&$post_array) {
        $sessions = [];
        foreach ($post_array['schoolSession']['sessionId'] as $k => $v):
            $sessions[] = array(
                'sessionId' => $v,
                'sessionName' => $post_array['schoolSession']['sessionName'][$k]
            );
        endforeach;

        $terms = [];
        foreach ($post_array['schoolTerms']['sessionId'] as $tk => $tv):
            $terms[] = array(
                'sessionId' => $tv,
                'termId' => $post_array['schoolTerms']['termId'][$tk],
                'termName' => $post_array['schoolTerms']['termName'][$tk]
            );
        endforeach;

        $post_array['schoolSession'] = json_encode($sessions);
        $post_array['schoolTerms'] = json_encode($terms);
    }

    function set_insert_callback($post_array) {
        if(isset($post_array['schoolSession']['sessionId'])):
            $this->setSchoolInfo($post_array);
        endif;

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
        $this->setSchoolInfo($post_array);

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
        $this->load->model('merchant_accounts_model');
        $data['merchant_details'] = array();
        $data['merchant_enc'] = '';
        $m_details = $this->merchant_accounts_model->getMerchantById($id);
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
                "merchantCode" => $m_details['merchantCode'],
                "merchantCategory" => $m_details['merchantCategory']
            );
            $m_summary = json_encode($merchantData);
            $data['merchant_enc'] = $this->bocrypter->Encrypt($m_summary, QR_PRIVATE_KEY) . '$$' . $iv;
        }

        $data['pageTitle'] = "Merchant Accounts QR";
        $data['body_template'] = "merchant_accounts/merchant_qr.php";
        $this->load->view('site_template.php', $data);
    }

}
