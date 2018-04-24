
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_users_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->model('admin_users_model_checker');
        $this->load->library('BOcrypter');
    }

    public function index() {
        $this->my_session->authorize("canViewAdminUserAuthorization");
        $data['adminUsers'] = json_encode($this->admin_users_model_checker->getUnapprovedUsers());
        $data['pageTitle'] = 'Admin User Checker';
        $data["body_template"] = "admin_users_checker/all_user_view.php";
        $this->load->view('site_template.php', $data);
    }

    public function getUserForApproval($id) {
        $this->my_session->authorize("canApproveAdminUser");
        $dbData = $this->admin_users_model_checker->getUserById($id);

        $dbData['email'] = $this->bocrypter->Decrypt($dbData['email']);
        $dbData['email_c'] = $this->bocrypter->Decrypt($dbData['email_c']);

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


        if ($dbData['adminUserId_c'] != NULL) {
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

        // lock & unlock
        if ($dbData['isLocked'] == "1") {
            $data['isLocked'] = "Locked";
        } else if ($dbData['isLocked'] == "0") {
            $data['isLocked'] = "Unlocked";
        }

        if ($dbData['isLocked_c'] == "1") {
            $data['isLocked_c'] = "Locked";
        } else if ($dbData['isLocked_c'] == "0") {
            $data['isLocked_c'] = "Unlocked";
        } else {
            $data['isLocked_c'] = "";
        }


        $data['adminUser'] = $dbData;
        $data['pageTitle'] = 'Admin User Approve';
        $data["body_template"] = "admin_users_checker/admin_user_approve_form.php";
        $this->load->view('site_template.php', $data);
    }

    public function getReason() {
            $data['checkerAction'] = $this->input->post('checkerAction');
            $id = $this->input->post('adminUserId');
            $makerActionDtTm = $this->input->post('makerActionDtTm');
            $checkerActionDtTm = $this->input->post('checkerActionDtTm');
            $dbData = $this->admin_users_model_checker->getUserById($id);

            if ($dbData['makerActionBy'] == $this->my_session->adminUserId) {
                echo "You can not authorize your own maker action";
            } else {
                if ($data['checkerAction'] == "approve") {
                    $chkdata['checkerActionDt'] = date("Y-m-d");
                    $chkdata['checkerActionTm'] = date("G:i:s");
                    $chkdata['isPublished'] = 1;
                    $chkdata['checkerActionBy'] = $this->my_session->adminUserId;
                    $chkdata['checkerAction'] = "Approved";
                    $chkdata['checkerActionComment'] = NULL;
                    $chkdata['mcStatus'] = 1;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        if ($dbData['isPublished'] == 0) {
                            // update and insert
                            $this->admin_users_model_checker->UpdateInsertCheckerApprove($id, $chkdata);
                        } else if ($dbData['isPublished'] == 1) {
                            // update and update
                            $this->admin_users_model_checker->UpdateUpdateCheckerApprove($id, $chkdata);
                        }

                        // activity log starts here >> implemented in model
                        redirect('admin_users_checker');
                    } else {
                        // redirect
                        echo "interaction";
                    }
                } else if ($data['checkerAction'] == 'reject') {
                    $data['checkerActionDt'] = date("Y-m-d");
                    $data['checkerActionTm'] = date("G:i:s");
                    $data['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $data['checkerAction'] = "Rejected";
                    $data['checkerActionComment'] = $this->input->post('newReason');
                    $data['mcStatus'] = 2;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        // update
                        $this->admin_users_model_checker->checkerReject($id, $data);
                        redirect('admin_users_checker');
                    } else {
                        // redirect
                        echo "interaction";
                    }
                }
            }
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
        $this->my_session->authorize("canApproveLimitPackage");
        $checkUserInteraction = 1;
        $actualdata = $this->admin_users_model_checker->getUserById($id);
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
