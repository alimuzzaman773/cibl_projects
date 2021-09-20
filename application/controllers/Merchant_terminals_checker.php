<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Merchant_terminals_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model('merchant_terminals_model');
    }

    public function index() {
        $this->my_session->authorize("canViewMerchantTerminalsAuthorization");
        $data['merchantTerminals'] = json_encode($this->merchant_terminals_model->getUnapprovedMerchantTerminals());
        $data['pageTitle'] = "Merchant Terminals Checker";
        $data['body_template'] = "merchant_terminals_checker/unapproved_merchant_terminals_view.php";
        $this->load->view('site_template.php', $data);
    }

    public function getMerchantTmForApproval($id) {
        $this->my_session->authorize("canApproveMerchantTerminals");

        $data['merchantId'] = "";
        $data['terminalName'] = "";
        $data['address'] = "";
        $data['city'] = "";
        $data['zip'] = "";
        $data['district'] = "";
        $data['currency'] = "";
        $data['accountNo'] = "";

        $data['merchantId_c'] = "";
        $data['terminalName_c'] = "";
        $data['address_c'] = "";
        $data['city_c'] = "";
        $data['zip_c'] = "";
        $data['district_c'] = "";
        $data['currency_c'] = "";
        $data['accountNo_c'] = "";

        $dbData = $this->merchant_terminals_model->getMerchantTerminalById($id);

        $data['makerActionDtTm'] = $dbData['makerActionDt'] . " " . $dbData['makerActionTm'];
        $data['checkerActionDtTm'] = $dbData['checkerActionDt'] . " " . $dbData['checkerActionTm'];

        if ($data['checkerActionDtTm'] == " ") {
            $data['checkerActionDtTm'] = "";
        }

        $data['makerActionDtTm_c'] = $dbData['makerActionDt_c'] . " " . $dbData['makerActionTm_c'];
        $data['checkerActionDtTm_c'] = $dbData['checkerActionDt_c'] . " " . $dbData['checkerActionTm_c'];
        if ($data['checkerActionDtTm_c'] == " ") {
            $data['checkerActionDtTm_c'] = "";
        }

        $data['checkerActionComment'] = $dbData['checkerActionComment'];
        if ($data['checkerActionComment'] != NULL) {
            $data['reasonModeOfDisplay'] = "display: block;";
        } else {
            $data['reasonModeOfDisplay'] = "display: none;";
        }

        $data['checkerActionComment_c'] = $dbData['checkerActionComment_c'];
        if ($data['checkerActionComment_c'] != NULL) {
            $data['reasonModeOfDisplay_c'] = "display: block;";
        } else {
            $data['reasonModeOfDisplay_c'] = "display: none;";
        }

        if ($dbData['merchantId_c'] != NULL) {
            $data['publishDataModeOfDisplay_c'] = "display: block;";
        } else {
            $data['publishDataModeOfDisplay_c'] = "display: none;";
        }

        $data['merchantTerminal'] = $dbData;

        $data["pageTitle"] = "Merchant Terminals Checker";
        $data["body_template"] = "merchant_terminals_checker/merchant_terminal_approve_form.php";
        $this->load->view('site_template.php', $data);
    }

    function getReason() {
        $data['checkerAction'] = $this->input->post("checkerAction");
        $id = $this->input->post("terminalId");
        $makerActionDtTm = $this->input->post("makerActionDtTm");
        $checkerActionDtTm = $this->input->post("checkerActionDtTm");
        $dbData = $this->merchant_terminals_model->getMerchantTerminalById($id);
        if ($data['checkerAction'] == "approve") {
            $chkdata['checkerActionDt'] = date("Y-m-d");
            $chkdata['checkerActionTm'] = date("G:i:s");
            $chkdata['isPublished'] = 1;
            $chkdata['checkerActionBy'] = $this->my_session->userId;
            $chkdata['checkerAction'] = "Approved";
            $chkdata['checkerActionComment'] = NULL;
            $chkdata['mcStatus'] = 1;

            $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);
            if ($res == 0) {
                if ($dbData['isPublished'] == 0) {
                    $this->merchant_terminals_model->updateInsertCheckerApprove($id, $chkdata);
                } elseif ($dbData['isPublished'] == 1) {
                    $this->merchant_terminals_model->updateUpdateCheckerApprove($id, $chkdata);
                }
            }
            redirect('merchant_terminals');
        } else if ($data['checkerAction'] == 'reject') {
            $data['checkerActionDt'] = date("Y-m-d");
            $data['checkerActionTm'] = date("G:i:s");
            $data['checkerActionBy'] = $this->my_session->userId;
            $data['checkerAction'] = "Rejected";
            $data['checkerActionComment'] = $this->input->post("newReason");
            $data['mcStatus'] = 2;

            $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

            if ($res == 0) {
                // update
                $this->merchant_terminals_model->checkerReject($id, $data);
                redirect('merchant_terminals');
            } else {
                // redirect
                echo "interaction";
            }
        }
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
        $this->my_session->authorize("canApproveAppsUser");
        $checkUserInteraction = 1;
        $actualdata = $this->merchant_terminals_model->getMerchantTerminalById($id);
        $makerActionDtTm = $actualdata['makerActionDt'] . " " . $actualdata['makerActionTm'];
        $checkerActionDtTm = $actualdata['checkerActionDt'] . " " . $actualdata['checkerActionTm'];
        if ($checkerActionDtTm == " ") {
            $checkerActionDtTm = "";
        }
        if ($makerActionDtTmPost == $makerActionDtTm && $checkerActionDtTmPost == $checkerActionDtTm) {
            $checkUserInteraction = 0;
        }
        return $checkUserInteraction;
    }

}
