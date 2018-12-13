<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction_limit_setup_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('transaction_limit_setup_model_checker', 'login_model'));
    }

    public function index() {
        $this->my_session->authorize("canViewLimitSetupAuthorization");
        $data["pageTitle"] = "Limit Packgae Setup Checker";
        $data['packages'] = json_encode($this->transaction_limit_setup_model_checker->getUnapprovedPackages());
        $data["body_template"] = "transaction_limit_package_checker/unapproved_packages_view.php";
        $this->load->view('site_template.php', $data);
    }

    public function getPackageForApproval($id) {
        $this->my_session->authorize("canApproveLimitPackage");
        
        $dbData = $this->transaction_limit_setup_model_checker->getUnapprovedPackageById($id);
        $data['appsGroupId'] = $dbData['appsGroupId'];
        $data['userGroupName'] = $dbData['userGroupName'];
        $data['userGroupName_c'] = $dbData['userGroupName_c'];
        $data['makerAction'] = $dbData['makerAction'];
        $data['makerAction_c'] = $dbData['makerAction_c'];
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
        // reason for changed data
        $data['checkerActionComment'] = $dbData['checkerActionComment'];
        if ($data['checkerActionComment'] != NULL) {
            $data['reasonModeOfDisplay'] = "display: block;";
        } else {
            $data['reasonModeOfDisplay'] = "display: none;";
        }

        // reason for published data
        $data['checkerActionComment_c'] = $dbData['checkerActionComment_c'];
        if ($data['checkerActionComment_c'] != NULL) {
            $data['reasonModeOfDisplay_c'] = "display: block;";
        } else {
            $data['reasonModeOfDisplay_c'] = "display: none;";
        }

        if ($dbData['appsGroupId_c'] != NULL) {
            $data['publishDataModeOfDisplay_c'] = "display: block;";
        } else {
            $data['publishDataModeOfDisplay_c'] = "display: none;";
        }

        // active & inactive
        if ($dbData['isActive'] == "1") {
            $data['isActive'] = "Active";
        } else if ($dbData['isActive'] == "0") {
            $data['isActive'] = "Inactive";
        }

        if ($dbData['isActive_c'] == "1") {
            $data['isActive_c'] = "Active";
        } else if ($dbData['isActive_c'] == "0") {
            $data['isActive_c'] = "Inactive";
        } else {
            $data['isActive_c'] = "";
        }

        if ($dbData['oatMinTxnLim'] != 0.00) {
            $changedDataArray[] = array('packageId' => 1,
                'packageName' => "Own Account Transfer");
        }
        if ($dbData['eatMinTxnLim'] != 0.00) {
            $changedDataArray[] = array('packageId' => 2,
                'packageName' => "EBL Account Transfer");
        }
        if ($dbData['obtMinTxnLim'] != 0.00) {
            $changedDataArray[] = array('packageId' => 3,
                'packageName' => "Other Bank Transfer");
        }
        if ($dbData['pbMinTxnLim'] != 0.00) {
            $changedDataArray[] = array('packageId' => 4,
                'packageName' => "Bills Pay");
        }

        $PublisherDataArray = array();

        if ($dbData['oatMinTxnLim_c'] > 0.00) {
            $PublisherDataArray[] = array('packageId_c' => 1,
                'packageName_c' => "Own Account Transfer");
        }
        if ($dbData['eatMinTxnLim_c'] > 0.00) {
            $PublisherDataArray[] = array('packageId_c' => 2,
                'packageName_c' => "EBL Account Transfer");
        }
        if ($dbData['obtMinTxnLim_c'] > 0.00) {
            $PublisherDataArray[] = array('packageId_c' => 3,
                'packageName_c' => "Other Bank Transfer");
        }
        if ($dbData['pbMinTxnLim_c'] > 0.00) {
            $PublisherDataArray[] = array('packageId_c' => 4,
                'packageName_c' => "Bills Pay");
        }

        $data['changedPackages'] = json_encode($changedDataArray);
        $data['publishedPackages'] = json_encode($PublisherDataArray);
        $data['group'] = json_encode($dbData);

        $data["pageTitle"] = "Limit Packgae Checker";
        $data["body_template"] = "transaction_limit_package_checker/limit_package_approve_form.php";
        $this->load->view('site_template.php', $data);
    }

    public function approveOrReject() {
        $this->my_session->authorize("canApproveLimitPackage");

        $data['checkerAction'] = $this->input->post("checkerAction");
        $id = $this->input->post("appsGroupId");
        $makerActionDtTm =$this->input->post("makerActionDtTm");
        $checkerActionDtTm =$this->input->post("checkerActionDtTm");

        $dbData = $this->transaction_limit_setup_model_checker->getUnapprovedPackageById($id);

        /*if ($dbData['makerActionBy'] == $this->my_session->userId) {
            echo "You can not authorize your own maker action";
        } 
        else
        {*/
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
                        $this->transaction_limit_setup_model_checker->UpdateInsertCheckerApprove($id, $chkdata);
                    } else if ($dbData['isPublished'] == 1) {
                        $this->transaction_limit_setup_model_checker->UpdateUpdateCheckerApprove($id, $chkdata);
                    }
                    // activity log starts here
                    redirect('transaction_limit_setup_checker');
                } else {
                    echo "interaction";
                }
            } else if ($data['checkerAction'] == 'reject') {
                $data['checkerActionDt'] = date("Y-m-d");
                $data['checkerActionTm'] = date("G:i:s");
                $data['checkerActionBy'] = $this->my_session->userId;
                $data['checkerAction'] = "Rejected";
                $data['checkerActionComment'] = $this->input->post("newReason"); 
                $data['mcStatus'] = 2;

                $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);
                if ($res == 0) {
                    $this->transaction_limit_setup_model_checker->checkerReject($id, $data);
                    redirect('transaction_limit_setup_checker');
                } else {
                    echo "interaction";
                }
            }
        //}
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
        $this->my_session->authorize("canApproveLimitPackage");
        $checkUserInteraction = 1;
        $actualdata = $this->transaction_limit_setup_model_checker->getUnapprovedPackageById($id);
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
