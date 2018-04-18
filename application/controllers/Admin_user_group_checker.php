<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_user_group_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('admin_user_group_model_checker', 'login_model'));
    }

    public function index() {
        $data['groups'] = json_encode($this->admin_user_group_model_checker->getUnapprovedGroups());
        $data["pageTitle"] = "Priority Request";
        $data["body_template"] = "admin_user_group_checker/overall_view.php";
        $this->load->view('site_template.php', $data);
        /*
          $this->output->set_template('theme2');
          $authorizationModules = $this->session->userdata('authorizationModules');
          if (strpos($authorizationModules, admin_user_group_authorization) > -1) {
          $data['groups'] = json_encode($this->admin_user_group_model_checker->getUnapprovedGroups());
          $this->load->view('admin_user_group_checker/overall_view.php', $data);
          } else {
          echo "Authorization Module Not Given";
          }
         */
    }

    public function getGroupForApproval($id) {
        //  $this->output->set_template('theme2');
        // $authorizationModules = $this->session->userdata('authorizationModules');
        //  if (strpos($authorizationModules, admin_user_group_authorization) > -1) {

        $dbData = $this->admin_user_group_model_checker->getGroupById($id);
        if (empty($dbData)) {
            redirect('admin_user_group_checker');
        }

        $data['modulesActions'] = $this->admin_user_group_model_checker->getModuleActions();
        $data['moduleIds'] = $this->admin_user_group_model_checker->getAllModules();
        $data['userGroup'] = $dbData;


        $data['authorizationModules'] = array('01' => 'Apps Users Authorization',
            '02' => 'Device Authorization',
            '03' => 'Pin Reset Authorization',
            '04' => 'Admin User Authorization',
            '05' => 'Admin User Group Authorization',
            '06' => 'Limit Package Authorization',
            '07' => 'Biller Setup Authorization',
            '08' => 'Pin Create Authorization',
            '09' => 'Password Policy Authorization',
            '10' => 'Apps User Delete Authorization');


        $data['contentSetupModules'] = array('01' => 'Product Setup',
            '02' => 'Location Setup',
            '03' => 'Zip Partners',
            '04' => 'Priority Setup',
            '05' => 'Benifit Setup',
            '06' => 'News And Events',
            '07' => 'Notification',
            '08' => 'Advertisement',
            '09' => 'Help Setup');

        $data['serviceRequestModules'] = array('01' => 'Priority',
            '02' => 'Product',
            '03' => 'Banking');


        $data['reportTypeModules'] = array('01' => 'Apps Users' . "'" . ' Status',
            '02' => 'Customer Information',
            '03' => 'User Login Information',
            '04' => 'Fund Transfer',
            '05' => 'Other Fund Transfer',
            '06' => 'User ID Modification',
            '07' => 'Billing Information',
            '08' => 'Priority Request',
            '09' => 'Product Request',
            '10' => 'Banking Request');


        $data['authorizationModuleCodes'] = json_encode(explode("|", $dbData['authorizationModules']));
        $data['authorizationModuleCodes_c'] = json_encode(explode("|", $dbData['authorizationModules_c']));

        $data['contentSetupModuleCodes'] = json_encode(explode("|", $dbData['contentSetupModules']));
        $data['contentSetupModuleCodes_c'] = json_encode(explode("|", $dbData['contentSetupModules_c']));

        $data['serviceRequestModuleCodes'] = json_encode(explode("|", $dbData['serviceRequestModules']));
        $data['serviceRequestModuleCodes_c'] = json_encode(explode("|", $dbData['serviceRequestModules_c']));


        $data['reportTypeModuleCodes'] = json_encode(explode("|", $dbData['reportTypeModules']));
        $data['reportTypeModuleCodes_c'] = json_encode(explode("|", $dbData['reportTypeModules_c']));


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
        $data['checkerActionComment_c'] = $dbData['checkerActionComment_c'];

        if ($data['checkerActionComment'] != NULL) {
            $data['reasonModeOfDisplay'] = "display: block;";
        } else {
            $data['reasonModeOfDisplay'] = "display: none;";
        }

        if ($data['checkerActionComment_c'] != NULL) {
            $data['reasonModeOfDisplay_c'] = "display: block;";
        } else {
            $data['reasonModeOfDisplay_c'] = "display: none;";
        }

        if ($dbData['userGroupId_c'] != NULL) {
            $data['publishDataOfDisplay_c'] = "display: block;";
        } else {
            $data['publishDataOfDisplay_c'] = "display: none;";
        }

        $data['moduleActionIds'] = json_encode(explode(",", $data['userGroup']['moduleActionId']));
        $data['moduleActionIds_c'] = json_encode(explode(",", $data['userGroup']['moduleActionId_c']));

        $data["pageTitle"] = "Admin User Group";
        $data["body_template"] = "admin_user_group_checker/approve_form.php";
        $this->load->view('site_template.php', $data);
        // } else {
        //     echo "Authorization Module Not Given";
        //}
    }

    public function getReason() {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, admin_user_group_authorization) > -1) {

            $data['checkerAction'] = $_POST['checkerAction'];
            $id = $_POST['userGroupId'];
            $makerActionDtTm = $_POST['makerActionDtTm'];
            $checkerActionDtTm = $_POST['checkerActionDtTm'];

            $dbData = $this->admin_user_group_model_checker->getGroupById($id);


            if ($dbData['makerActionBy'] == $this->session->userdata('adminUserId')) {
                echo "You can not authorize your own maker action";
            } else {
                if ($data['checkerAction'] == "approve") {

                    $chkdata['checkerActionDt'] = date("Y-m-d");
                    $chkdata['checkerActionTm'] = date("G:i:s");
                    $chkdata['isPublished'] = 1;
                    $chkdata['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $chkdata['checkerAction'] = "Approved";
                    $chkdata['checkerActionComment'] = NULL;
                    $chkdata['mcStatus'] = 1;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        if ($dbData['isPublished'] == 0) {
                            // update and insert
                            $this->admin_user_group_model_checker->UpdateInsertCheckerApprove($id, $chkdata);
                        } else if ($dbData['isPublished'] == 1) {
                            // update and update
                            $this->admin_user_group_model_checker->UpdateUpdateCheckerApprove($id, $chkdata);
                        }


                        // activity log starts here >> implemented in model
                        redirect('admin_user_group_checker');
                    } else {
                        // redirect
                        echo "interaction";
                    }
                } else if ($data['checkerAction'] == "reject") {


                    $data['checkerActionDt'] = date("Y-m-d");
                    $data['checkerActionTm'] = date("G:i:s");
                    $data['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $data['checkerAction'] = "Rejected";
                    $data['checkerActionComment'] = $_POST['newReason'];
                    $data['mcStatus'] = 2;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        // update
                        $this->admin_user_group_model_checker->checkerReject($id, $data);
                        redirect('admin_user_group_checker');
                    } else {
                        // redirect
                        echo "interaction";
                    }
                }
            }
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
        $checkUserInteraction = 1;
        $actualdata = $this->admin_user_group_model_checker->getGroupById($id);
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
