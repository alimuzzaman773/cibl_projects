<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Biller_setup_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->helper('url');
        $this->load->model('biller_setup_model_checker');
        $this->load->library('session');

        $this->load->model('login_model');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    public function index() {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, biller_setup_authorization) > -1) {
            $data['unapprovedBillers'] = json_encode($this->biller_setup_model_checker->getUnapprovedBillers());
            $this->load->view('biller_setup_checker/unapproved_billers.php', $data);
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function getBillerFroApproval($id) {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, biller_setup_authorization) > -1) {

            $dbData = $this->biller_setup_model_checker->getBillerById($id);

            // echo "<pre>";
            // print_r($dbData); die();

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


            if ($dbData['billerId_c'] != NULL) {
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



            $data['biller'] = $dbData;
            $this->load->view('biller_setup_checker/approve_form.php', $data);
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function getReason() {
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, biller_setup_authorization) > -1) {
            $data['checkerAction'] = $_POST['checkerAction'];
            $id = $_POST['billerId'];
            $makerActionDtTm = $_POST['makerActionDtTm'];
            $checkerActionDtTm = $_POST['checkerActionDtTm'];
            $dbData = $this->biller_setup_model_checker->getBillerById($id);

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
                            $this->biller_setup_model_checker->UpdateInsertCheckerApprove($id, $chkdata);
                        } else if ($dbData['isPublished'] == 1) {
                            // update and update
                            $this->biller_setup_model_checker->UpdateUpdateCheckerApprove($id, $chkdata);
                        }

                        // activity log starts here >> implemented in model
                        redirect('biller_setup_checker');
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
                        $this->biller_setup_model_checker->checkerReject($id, $data);
                        redirect('biller_setup_checker');
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
        $actualdata = $this->biller_setup_model_checker->getBillerById($id);
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
