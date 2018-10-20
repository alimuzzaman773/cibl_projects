
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Device_info_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('device_info_model_checker', 'common_model'));
        $this->load->library('sms_service');
    }

    public function index() {
        $this->my_session->authorize("canViewDeviceAuthorization");
        $data['deviceInfo'] = json_encode($this->device_info_model_checker->getUnapprovedDevice());
        $data["pageTitle"] = "Device Iinformation Checker";
        $data["body_template"] = "device_info_checker/unapproved_device.php";
        $this->load->view('site_template.php', $data);
    }

    public function getDeviceForApproval($id) {
        $this->my_session->authorize("canApproveDevice");

        $dbData = $this->device_info_model_checker->getDeviceById($id);

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


        if ($dbData['deviceId_c'] != NULL) {
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
        $data['deviceInfo'] = $dbData;

        $data["pageTitle"] = "Device Information Checker";
        $data["body_template"] = "device_info_checker/device_approve_form.php";
        $this->load->view('site_template.php', $data);
        // } else {
        //  echo "Authorization Module Not Given";
        // }
    }

    public function approveOrRejectDevice() {
        $this->my_session->authorize("canApproveDevice");
        //$authorizationModules = $this->session->userdata('authorizationModules');
        //if (strpos($authorizationModules, device_authorization) > -1) {
        $data['checkerAction'] = $_POST['checkerAction'];
        $id = $_POST['deviceId'];
        $skyId = $_POST['skyId'];
        $makerActionDtTm = $_POST['makerActionDtTm'];
        $checkerActionDtTm = $_POST['checkerActionDtTm'];
        $dbData = $this->device_info_model_checker->getDeviceById($id);

        if ($data['checkerAction'] == "approve") 
        {
            $appsUserpublishChk = $this->device_info_model_checker->getUserPublishedInfo($skyId);

            if ((int)$appsUserpublishChk['isPublished'] != 1) 
            {
                show_error('Apps User in not published yet');
            }
            
            $chkdata['checkerActionDt'] = date("Y-m-d");
            $chkdata['checkerActionTm'] = date("G:i:s");
            $chkdata['isPublished'] = 1;
            $chkdata['checkerActionBy'] = $this->session->userdata('adminUserId');
            $chkdata['checkerAction'] = "Approved";
            $chkdata['checkerActionComment'] = NULL;
            $chkdata['mcStatus'] = 1;

            $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);
            if($res != 0):
                show_error("Meanwhine some action was performed on data.
                                    <br> Please go back to check the latest state.
                                     <a href='device_info_checker/getDeviceForApproval/".$id."'>Go Back</a>");
            endif;
            
            if ($dbData['isPublished'] == 0)
            {

                $deviceNumber = $this->device_info_model_checker->countDevice($skyId);  //checke for first device add. run a count on main table

                if ($deviceNumber == 0)
                {
                    if (ctype_digit($appsUserpublishChk['userMobNo1']))
                    {
                        $smsData['mobileNo'] = $appsUserpublishChk['userMobNo1'];
                        $smsData['message'] = 'Please activate your EBL SKYBANKING service with the ID & Password you have collected from an EBL Branch';
                        $smsServiceResponse = $this->sms_service->smsService($smsData); // *** notifying user *** // 
                    }


                    if ($appsUserpublishChk['userEmail'] != "Not Available")
                    {
                        $maildata['to'] = $appsUserpublishChk['userEmail'];
                        $maildata['subject'] = "Device Activation";
                        $maildata['body'] = '<p>Dear Sir/Madam,</p>
                                             <p>Your EBL SKYBANKING account registration process is completed.</p>
                                             <p>Please activate your device using provided EBL SKYBANKING ID and Password to access the Banking / Bills Pay / Fund Transfer section.</p>
                                             <p>Please ignore this email, if you have already activated your device.</p>
                                             <p>Thanks & Regards, <br/>EBL SKYBANKING AdminPanel</p>';
                        $this->common_model->send_mail($maildata);
                    }
                }

                // update and insert
                $this->device_info_model_checker->UpdateInsertCheckerApprove($id, $chkdata);
            } 
            else if ($dbData['isPublished'] == 1)
            {
                // update and update
                $this->device_info_model_checker->UpdateUpdateCheckerApprove($id, $chkdata);
            }


            // activity log starts here

            redirect('device_info_checker');
        } 
        else if ($data['checkerAction'] == 'reject') 
        {
            $data['checkerActionDt'] = date("Y-m-d");
            $data['checkerActionTm'] = date("G:i:s");
            $data['checkerActionBy'] = $this->session->userdata('adminUserId');
            $data['checkerAction'] = "Rejected";
            $data['checkerActionComment'] = $_POST['newReason'];
            $data['mcStatus'] = 2;

            $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

            if ($res == 0) {
                // update
                $this->device_info_model_checker->checkerReject($id, $data);
                redirect('device_info_checker');
            } else {
                // redirect
                // echo "interaction";
                $this->output->set_template('theme2');
                $data['message'] = "Meanwhine some action was performed on data.
                                    <br> Please go back to check the latest state.";
                $data['backUrl'] = "device_info_checker/getDeviceForApproval/" . $id;
                $this->load->view('warning/warning_view', $data);
            }
        }
        
        
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
        $checkUserInteraction = 1;
        $actualdata = $this->device_info_model_checker->getDeviceById($id);
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
