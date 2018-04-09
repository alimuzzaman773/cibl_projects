<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction_limit_setup_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');
        $this->load->model('transaction_limit_setup_model_checker');

        
        $this->load->model('login_model');
        $this->load->library('session');
        
        if($this->login_model->check_session()){
            redirect('/admin_login/index');
        }
    }



    public function index()
    {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if(strpos($authorizationModules, limit_package_authorization) > -1){
            $data['packages'] = json_encode($this->transaction_limit_setup_model_checker->getUnapprovedPackages());
            $this->load->view('transaction_limit_package_checker/unapproved_packages_view.php', $data);
        }
        else{
            echo "Authorization Module Not Given";
        }
    }


    public function getPackageForApproval($id)
    {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if(strpos($authorizationModules, limit_package_authorization) > -1){


            $dbData = $this->transaction_limit_setup_model_checker->getUnapprovedPackageById($id);
            $data['appsGroupId'] = $dbData['appsGroupId'];
            $data['userGroupName'] = $dbData['userGroupName'];
            $data['userGroupName_c'] = $dbData['userGroupName_c'];


            $data['makerAction'] = $dbData['makerAction'];
            $data['makerAction_c'] = $dbData['makerAction_c'];


            $data['makerActionDtTm'] = $dbData['makerActionDt'] . " " . $dbData['makerActionTm'];
            $data['checkerActionDtTm'] = $dbData['checkerActionDt'] . " " . $dbData['checkerActionTm'];
            if($data['checkerActionDtTm'] == " "){
                $data['checkerActionDtTm'] = "";
            }
            
            $data['makerActionDtTm_c'] = $dbData['makerActionDt_c'] . " " . $dbData['makerActionTm_c'];
            $data['checkerActionDtTm_c'] = $dbData['checkerActionDt_c'] . " " . $dbData['checkerActionTm_c'];
            if($data['checkerActionDtTm_c'] == " "){
                $data['checkerActionDtTm_c'] = "";
            }

            // reason for changed data
            $data['checkerActionComment'] = $dbData['checkerActionComment'];
            if($data['checkerActionComment'] != NULL){
                $data['reasonModeOfDisplay'] = "display: block;";
            }else{
                $data['reasonModeOfDisplay'] = "display: none;";
            }

            // reason for published data
            $data['checkerActionComment_c'] = $dbData['checkerActionComment_c'];
            if($data['checkerActionComment_c'] != NULL){
                $data['reasonModeOfDisplay_c'] = "display: block;";
            }else{
                $data['reasonModeOfDisplay_c'] = "display: none;";
            }


            if($dbData['appsGroupId_c'] != NULL){
                $data['publishDataModeOfDisplay_c'] = "display: block;";
            }else{
                $data['publishDataModeOfDisplay_c'] = "display: none;";
            }

            // active & inactive
            if($dbData['isActive'] == "1"){
                $data['isActive'] = "Active";
            }else if($dbData['isActive'] == "0"){
                $data['isActive'] = "Inactive";
            }

            if($dbData['isActive_c'] == "1"){
                $data['isActive_c'] = "Active";
            }else if($dbData['isActive_c'] == "0"){
                $data['isActive_c'] = "Inactive";
            }else {
                $data['isActive_c'] = "";
            }


        
            if($dbData['oatMinTxnLim'] != 0.00){
                $changedDataArray[] = array('packageId' => 1,
                                            'packageName' => "Own Account Transfer");
            }
            if($dbData['eatMinTxnLim'] != 0.00){
                $changedDataArray[] = array('packageId' => 2,
                                            'packageName' => "EBL Account Transfer");
            }
            if($dbData['obtMinTxnLim'] != 0.00){
                $changedDataArray[] = array('packageId' => 3,
                                            'packageName' => "Other Bank Transfer");
            }
            if($dbData['pbMinTxnLim'] != 0.00){
                $changedDataArray[] = array('packageId' => 4,
                                            'packageName' => "Bills Pay");
            }


            $PublisherDataArray = array();

            if($dbData['oatMinTxnLim_c'] > 0.00){
                $PublisherDataArray[] = array('packageId_c' => 1,
                                              'packageName_c' => "Own Account Transfer");
            }
            if($dbData['eatMinTxnLim_c'] > 0.00){
                $PublisherDataArray[] = array('packageId_c' => 2,
                                              'packageName_c' => "EBL Account Transfer");
            }
            if($dbData['obtMinTxnLim_c'] > 0.00){
                $PublisherDataArray[] = array('packageId_c' => 3,
                                              'packageName_c' => "Other Bank Transfer");
            }
            if($dbData['pbMinTxnLim_c'] > 0.00){
                $PublisherDataArray[] = array('packageId_c' => 4,
                                              'packageName_c' => "Bills Pay");
            }

            $data['changedPackages'] = json_encode($changedDataArray);
            $data['publishedPackages'] = json_encode($PublisherDataArray);
            $data['group'] = json_encode($dbData);

            $this->load->view('transaction_limit_package_checker/limit_package_approve_form.php', $data);
        }
        else{
            echo "Authorization Module Not Given";
        }
    }


    public function approveOrReject()
    {
        $authorizationModules = $this->session->userdata('authorizationModules');
        if(strpos($authorizationModules, limit_package_authorization) > -1)
        {
            $data['checkerAction'] = $_POST['checkerAction'];
            $id = $_POST['appsGroupId'];
            $makerActionDtTm = $_POST['makerActionDtTm'];
            $checkerActionDtTm = $_POST['checkerActionDtTm'];

            $dbData = $this->transaction_limit_setup_model_checker->getUnapprovedPackageById($id);

            if($dbData['makerActionBy'] == $this->session->userdata('adminUserId'))
            {
                echo "You can not authorize your own maker action";
            }

            else
            {
                if($data['checkerAction'] == "approve")
                {
                    $chkdata['checkerActionDt'] = date("Y-m-d");
                    $chkdata['checkerActionTm'] = date("G:i:s");
                    $chkdata['isPublished'] = 1;
                    $chkdata['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $chkdata['checkerAction'] = "Approved";
                    $chkdata['checkerActionComment'] = NULL;
                    $chkdata['mcStatus'] = 1;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if($res == 0){
                        if($dbData['isPublished'] == 0){
                            $this->transaction_limit_setup_model_checker->UpdateInsertCheckerApprove($id, $chkdata);
                        }
                        else if($dbData['isPublished'] == 1){
                            $this->transaction_limit_setup_model_checker->UpdateUpdateCheckerApprove($id, $chkdata);
                        }


                        // activity log starts here

                        redirect('transaction_limit_setup_checker');
                    }
                    else{
                        echo "interaction";
                    }
                }

                else if($data['checkerAction'] == 'reject')
                {
                    $data['checkerActionDt'] = date("Y-m-d");
                    $data['checkerActionTm'] = date("G:i:s");
                    $data['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $data['checkerAction'] = "Rejected";
                    $data['checkerActionComment'] = $_POST['newReason'];
                    $data['mcStatus'] = 2;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if($res == 0){
                        $this->transaction_limit_setup_model_checker->checkerReject($id, $data);
                        redirect('transaction_limit_setup_checker');
                    }
                    else{
                        echo "interaction";
                    }
                }
            }
        }
        else{
            echo "Authorization module not given";
        }
    }


    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost)
    {
        $checkUserInteraction = 1;
        $actualdata = $this->transaction_limit_setup_model_checker->getUnapprovedPackageById($id);
        $makerActionDtTm = $actualdata['makerActionDt']." ".$actualdata['makerActionTm'];
        $checkerActionDtTm = $actualdata['checkerActionDt']." ".$actualdata['checkerActionTm'];
        if($checkerActionDtTm == " "){
            $checkerActionDtTm = "";
        }
        if($makerActionDtTmPost == $makerActionDtTm && $checkerActionDtTmPost == $checkerActionDtTm){
            $checkUserInteraction = 0;
        }
        return $checkUserInteraction;
    }


}