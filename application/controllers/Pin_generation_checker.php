<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pin_generation_checker extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library('sms_service');
        $this->load->model(array('generate_eblskyid_model_checker', 'common_model'));
    }

    public function index() {
        $data["pageTitle"] = "Pin Reset Checker";
        $data['resetAction'] = json_encode($this->generate_eblskyid_model_checker->getUnapprovedResetAction());
        $data["body_template"] = "generate_pin_checker/unapproved_reset_action.php";
        $this->load->view('site_template.php', $data);
    }

    public function getResetActionForApproval($id) {

       // $this->output->set_template('theme2');
      //  $authorizationModules = $this->session->userdata('authorizationModules');
      //  if (strpos($authorizationModules, pin_reset_authorization) > -1) {

            $dbData = $this->generate_eblskyid_model_checker->getUnapprovedResetActionById($id);

            $data['makerActionDtTm'] = $dbData['makerActionDt'] . " " . $dbData['makerActionTm'];
            $data['checkerActionDtTm'] = $dbData['checkerActionDt'] . " " . $dbData['checkerActionTm'];
            if ($data['checkerActionDtTm'] == " ") {
                $data['checkerActionDtTm'] = "";
            }

            $data['checkerActionComment'] = $dbData['checkerActionComment'];
            if ($data['checkerActionComment'] != NULL) {
                $data['reasonModeOfDisplay'] = "display: block;";
            } else {
                $data['reasonModeOfDisplay'] = "display: none;";
            }

            if ($dbData['isActive'] == "1") {
                $data['isActive'] = "Active";
            } else if ($dbData['isActive'] == "0") {
                $data['isActive'] = "Destroyed";
            }

            if ($dbData['isUsed'] == "1") {
                $data['isUsed'] = "Used";
            } else if ($dbData['isUsed'] == "0") {
                $data['isUsed'] = "Not Used";
            }


            if ($dbData['isPrinted'] == "1") {
                $data['isPrinted'] = "Printed";
            } else if ($dbData['isPrinted'] == "0") {
                $data['isPrinted'] = "Not Printed";
            }

            if ($dbData['isReset'] == "1") {
                $data['isReset'] = "Reset";
            } else if ($dbData['isReset'] == "0") {
                $data['isReset'] = "Not Reset";
            }


            $data['resetPin'] = $dbData;
            
            $data["pageTitle"]="PIN Reset Checker";
            $data["body_template"]="generate_pin_checker/reset_action_approve_form.php";
            $this->load->view('site_template.php', $data);
       // } else {
      //      echo "Authorization Module Not Given";
      //  }
    }

    public function approveOrReject() { // for reset action approval //
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, pin_reset_authorization) > -1) {
            $data['checkerAction'] = $_POST['checkerAction'];
            $id = $_POST['eblSkyId'];
            $makerActionDtTm = $_POST['makerActionDtTm'];
            $checkerActionDtTm = $_POST['checkerActionDtTm'];

            $dbData = $this->generate_eblskyid_model_checker->getUnapprovedResetActionById($id);


            if ($dbData['makerActionBy'] == $this->session->userdata('adminUserId')) {
                echo "You can not authorize your own maker action";
            } else {
                if ($data['checkerAction'] == "approve") {
                    $pin = $this->common_model->generateRandomString(); // 2 way encryption

                    $chkdata['pin'] = $pin;
                    $chkdata['salt'] = NULL;
                    $chkdata['checkerActionDt'] = date("Y-m-d");
                    $chkdata['checkerActionTm'] = date("G:i:s");
                    $chkdata['isPublished'] = 1;
                    $chkdata['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $chkdata['checkerAction'] = "Approved";
                    $chkdata['checkerActionComment'] = NULL;
                    $chkdata['mcStatus'] = 1;
                    $chkdata['isReset'] = 1;
                    $chkdata['isPrinted'] = 0;
                    $chkdata['updateDtTm'] = date("Y-m-d G:i:s");


                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        //added 27 sep 2017

                        $this->generate_eblskyid_model_checker->wrongAttemptReset($id);

                        if (empty($dbData['userEmail'])) {

                            // reset action for unused pin //
                            $this->generate_eblskyid_model_checker->updateCheckerApproveUnusedPin($id, $chkdata);
                        } else {

                            // reset action for used pin //
                            $this->generate_eblskyid_model_checker->updateCheckerApprove($id, $chkdata);

                            // SMS //
                            if (ctype_digit($dbData['userMobNo1'])) {
                                $smsData['mobileNo'] = $dbData['userMobNo1'];
                                $smsData['message'] = 'Your password has been reset.';
                                $smsServiceResponse = $this->sms_service->smsService($smsData);
                            }


                            if ($dbData['userEmail'] != 'Not Available') {

                                // Email //
                                $maildata['to'] = $dbData['userEmail'];
                                $maildata['subject'] = "Password Reset";
                                $maildata['body'] = '<p>Dear Sir/Madam,</p>
                                                     <p>Your EBL SKYBANKING password has been reset.</p>
                                                     <p>Thanks & Regards, <br/>EBL SKYBANKING AdminPanel</p>';
                                $this->common_model->send_mail($maildata);
                            }
                        }

                        redirect('pin_generation_checker');
                    } else {
                        // redirect
                        echo "interaction";
                    }
                } else if ($data['checkerAction'] == 'reject') {
                    $data['checkerActionDt'] = date("Y-m-d");
                    $data['checkerActionTm'] = date("G:i:s");
                    $data['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $data['checkerAction'] = "Rejected";
                    $data['checkerActionComment'] = $_POST['newReason'];
                    $data['mcStatus'] = 2;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        // update
                        $this->generate_eblskyid_model_checker->checkerReject($id, $data);
                        redirect('pin_generation_checker');
                    } else {
                        // redirect
                        echo "interaction";
                    }
                }
            }
        } else {
            echo "Authorization module not given";
        }
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
        $checkUserInteraction = 1;
        $actualdata = $this->generate_eblskyid_model_checker->getUnapprovedResetActionById($id);
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
