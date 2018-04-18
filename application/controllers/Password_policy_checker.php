<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Password_policy_checker extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('password_policy_checker_model', 'login_model'));
    }

    public function index() {
        $data['passwordPolicy'] = json_encode($this->password_policy_checker_model->getUnapprovedPolicy());
        $data["body_template"]="password_policy_checker/unapproved_policy.php";
        $this->load->view('site_template.php', $data);

        /*
          $this->output->set_template('theme2');
          $authorizationModules = $this->session->userdata('authorizationModules');
          if (strpos($authorizationModules, password_policy_authorization) > -1) {
          $data['passwordPolicy'] = json_encode($this->password_policy_checker_model->getUnapprovedPolicy());
          $this->load->view('password_policy_checker/unapproved_policy', $data);
          } else {
          echo "Authorization Module Not Given";
          }
         */
    }

    public function getPasswordPolicyApproval($id) {
       // $this->output->set_template('theme2');
       // $authorizationModules = $this->session->userdata('authorizationModules');
       // if (strpos($authorizationModules, password_policy_authorization) > -1) {

            $dbData = $this->password_policy_checker_model->getPolicyById($id);

            $data['makerActionDtTm'] = $dbData['makerActionDt'] . " " . $dbData['makerActionTm'];
            $data['checkerActionDtTm'] = $dbData['checkerActionDt'] . " " . $dbData['checkerActionTm'];
            if ($data['checkerActionDtTm'] == " ") {
                $data['checkerActionDtTm'] = "";
            }

            $data['makerActionDtTm_ck'] = $dbData['makerActionDt_ck'] . " " . $dbData['makerActionTm_ck'];
            $data['checkerActionDtTm_ck'] = $dbData['checkerActionDt_ck'] . " " . $dbData['checkerActionTm_ck'];
            if ($data['checkerActionDtTm_ck'] == " ") {
                $data['checkerActionDtTm_ck'] = "";
            }

            // reason for changed data
            $data['checkerActionComment'] = $dbData['checkerActionComment'];
            if ($data['checkerActionComment'] != NULL) {
                $data['reasonModeOfDisplay'] = "display: block;";
            } else {
                $data['reasonModeOfDisplay'] = "display: none;";
            }

            // reason for published data
            $data['checkerActionComment_ck'] = $dbData['checkerActionComment_ck'];
            if ($data['checkerActionComment_ck'] != NULL) {
                $data['reasonModeOfDisplay_ck'] = "display: block;";
            } else {
                $data['reasonModeOfDisplay_ck'] = "display: none;";
            }

            if ($dbData['validationGroupId_ck'] != NULL) {
                $data['publishDataModeOfDisplay_ck'] = "display: block;";
            } else {
                $data['publishDataModeOfDisplay_ck'] = "display: none;";
            }
            $data['policy'] = $dbData;
            $data["pageTitle"]="Password Policy Checker";
            $data["body_template"]="password_policy_checker/approved_policy.php";
            $this->load->view('site_template.php', $data);
      //  } else {
        //    echo "Authorization Module Not Given";
       // }
    }

    public function getReason() {
      //  $authorizationModules = $this->session->userdata('authorizationModules');
      //  if (strpos($authorizationModules, password_policy_authorization) > -1) {
            $data['checkerAction'] = $this->input->post("checkerAction");
            $id = $this->input->post("policyId");
            $makerActionDtTm = $this->input->post("makerActionDtTm");
            $checkerActionDtTm =$this->input->post("checkerActionDtTm");

            $dbData = $this->password_policy_checker_model->getPolicyById($id);

            if ($dbData['makerActionBy'] == $this->my_session->userId) {
                echo "You can not authorize your own maker action";
            } else {
                if ($data['checkerAction'] == "approve") {
                    $chkdata['checkerActionDt'] = date("Y-m-d");
                    $chkdata['checkerActionTm'] = date("G:i:s");
                    $chkdata['isPublished'] = 1;
                    $chkdata['checkerActionBy'] =$this->my_session->userId;
                    $chkdata['checkerAction'] = "Approved";
                    $chkdata['checkerActionComment'] = NULL;
                    $chkdata['mcStatus'] = 1;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        if ($dbData['isPublished'] == 0) {
                            // update and insert
                            $this->password_policy_checker_model->UpdateInsertCheckerApprove($id, $chkdata);
                        } else if ($dbData['isPublished'] == 1) {
                            // update and update
                            $this->password_policy_checker_model->UpdateUpdateCheckerApprove($id, $chkdata);
                        }
                        // activity log starts here >> implemented in model
                        redirect('password_policy_checker');
                    } else {
                        // redirect
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
                        // update
                        $this->password_policy_checker_model->checkerReject($id, $data);
                        redirect('password_policy_checker');
                    } else {
                        // redirect
                        echo "interaction";
                    }
                }
            }
      //  } else {
       //     echo "Authorization module not given";
       // }
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
        $checkUserInteraction = 1;
        $actualdata = $this->password_policy_checker_model->getPolicyById($id);
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
