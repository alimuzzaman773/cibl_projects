
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_users_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');
        $this->load->model('admin_users_model_checker');
        $this->load->library('session');
        $this->load->library('BOcrypter');

        $this->load->model('login_model');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    public function index() {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, admin_user_authorization) > -1) {
            $data['adminUsers'] = json_encode($this->admin_users_model_checker->getUnapprovedUsers());
            $this->load->view('admin_users_checker/all_user_view.php', $data);
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function getUserForApproval($id) {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, admin_user_authorization) > -1) {

            $dbData = $this->admin_users_model_checker->getUserById($id);

            // echo "<pre>";
            // print_r($dbData); die();


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
            $this->load->view('admin_users_checker/admin_user_approve_form.php', $data);
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function getReason() {
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, admin_user_authorization) > -1) {
            $data['checkerAction'] = $_POST['checkerAction'];
            $id = $_POST['adminUserId'];
            $makerActionDtTm = $_POST['makerActionDtTm'];
            $checkerActionDtTm = $_POST['checkerActionDtTm'];
            $dbData = $this->admin_users_model_checker->getUserById($id);

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
                    $data['checkerActionComment'] = $_POST['newReason'];
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
        } else {
            echo "Authorization module not given";
        }
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
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

    // public function UpdateUpdateCheckerApprove($id, $data)
    // {
    //     $this->db->where('deviceId', $id);
    //     $this->db->update('device_info_mc', $data);
    //     $query = $this->db->get_where('device_info_mc', array('deviceId' => $id));
    //     $result = $query->row_array();
    //     $this->db->where('deviceId', $id);
    //     $this->db->update('device_info', $result);
    // }
}
