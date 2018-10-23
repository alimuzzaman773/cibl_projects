<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Call_center extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('my_session');
    }

    function user_list() {
        $p['get_count'] = (bool) $this->input->get("get_count", true);
        $p['limit'] = $this->input->get('limit', true);
        $p['offset'] = $this->input->get('offset', true);
        $this->load->model("call_center_model");

        $json['total'] = 0;
        $json['user_list'] = array();

        if ($p['get_count']) {
            $params['get_count'] = 1;
            $result = $this->call_center_model->getAllUsers($params);
            if ($result):
                $json['total'] = $result->row()->total;
            endif;
        }

        unset($p['get_count']);
        $result = $this->call_center_model->getAllUsers($p);
        if ($result):
            $json['user_list'] = $result->result();
        endif;

        //$json['q']=$this->db->last_query();
        my_json_output($json);
    }

    function get_user_info($userId) {

        $userData = array();
        $this->load->model('call_center_model');

        $registrationInfo = false;
        $userAccounts = array();
        $user = $this->call_center_model->getUserInfo((int) $userId);
        if ($user) {
            $userData = $user->row();

            /* $registrationres = $this->call_center_model->getRegistrationDetails($userData->skyId);
              if($registrationres):

              endif; */


            $accounts = $this->call_center_model->getUserAccounts($userId, $userData->entityNumber);
            if ($accounts):
                $userAccounts = $accounts->result();
            endif;
        }

        $json = array(
            "success" => true,
            "user_info" => $userData,
            "user_accounts" => $userAccounts
        );

        my_json_output($json);
    }

    function resend_user_pin($userId) {
        $otp_channel =  $this->input->post("otp_channel", true);
        if (!in_array($otp_channel, array('sms', 'email'))):
            $data = array(
                'success' => false,
                'msg' => 'No otp channel provided'
            );
            my_json_output($data);
        endif;
        
        $this->load->model(array('mailer_model', 'call_center_model'));

        $getUserInfo = $this->call_center_model->getUserInfo((int) $userId);
        if (!$getUserInfo) {
            $json = array(
                "success" => false,
                "msg" => "User information not found"
            );
            my_json_output($json);
        }

        $userInfo = $getUserInfo->row();

        srand(rand(1000000, 9999999));
        $pin = rand(1000000, 9999999);

        $uData = array(
            "passWord" => md5($pin),
            "pinExpiryReferenceTm" => date("Y-m-d H:i:s")
        );

        $this->db->where("skyId", $userInfo->skyId)
                ->update("apps_users", $uData);

        $otpData = array(
            "pin" => $pin,
            "eblSkyId" => $userInfo->eblSkyId
        );

        $otpRes = false;
        if($otp_channel == "sms"):
            $smsData = array(
                "mobileNo" => "88" . ltrim($userInfo->userMobNo1, "88"),
                "message" => "Your one time account activation pin is {$pin}"
            );

            $this->load->library("sms_service");
            $otpRes = $this->sms_service->smsService($smsData);                
        endif;

        if($otp_channel == "email"):
            $mailData["to"] = $userInfo->userEmail;
            if (defined('dummy_email')):
                $mailData["to"] = dummy_email;
            endif;

            $params = array(
                'email' => $userInfo['userEmail'],
                'subject' => "Your OTP for PREMIER Account Activation",
                'body' => $this->load->view("call_center/pin_reset.php", $otpData, true)
            );

            $otpRes = $this->email_service->emailService($params);
        endif;
        
        $json = array(
            "success" => true,
            "userInfo" => $user['userInfo'],
            "otpRes" => $otpRes
        );
        my_json_output($json);
    }

    function user_approve($userId) {
        $this->load->model(array('mailer_model', 'call_center_model'));

        $getUserInfo = $this->call_center_model->getUserInfo((int) $userId);
        if (!$getUserInfo) {
            $json = array(
                "success" => false,
                "msg" => "User information not found"
            );
            my_json_output($json);
        }

        $userInfo = $getUserInfo->row();

        srand(rand(1000000, 9999999));
        $pin = rand(1000000, 9999999);

        $user = $this->call_center_model->activateAppUserAccount($userInfo->skyId, $pin, $userInfo->raId);
        if (!$user['success']) {
            my_json_output($user);
        }

        $otpData = array(
            "pin" => $pin,
            "eblSkyId" => $userInfo->eblSkyId
        );

        $otp_channel = $this->input->post("otp_channel");

        $res = null;
        if ($otp_channel == "email"):
            $mailData["to"] = $userInfo->userEmail;
            if (defined('dummy_email')):
                $mailData["to"] = dummy_email;
            endif;

            $mailData["from"] = "mobapp@pbl.com";
            $mailData["fromName"] = "mobapp@pbl.com";
            $mailData["subject"] = "Premier Mobile APP - Account Activation PIN";
            $mailData["body"] = $this->load->view("call_center/pin_mail.php", $otpData, true);
            $res = $this->mailer_model->sendMail($mailData);
        else:
            $smsData = array(
                "mobileNo" => "88" . ltrim($userInfo->userMobNo1, "88"),
                "message" => "You one time account activation pin is {$pin} for Premier APP Account : {$userInfo->eblSkyId}"
            );

            $this->load->library("sms_service");
            $res = $this->sms_service->smsService($smsData);
        endif;


        /* if (!$res["success"]) {
          $json = array(
          "success" => false,
          "msg" => $res['msg'],
          );
          my_json_output($json);
          } */

        $json = array(
            "success" => true,
            "userInfo" => $user['userInfo'],
            "mailRes" => $res,
            "pin" => $pin
        );
        my_json_output($json);
    }

    function user_approve_checker($userId) {
        $this->load->model(array('call_center_model'));
        $updateInfo = $this->call_center_model->userApproveChecker((int) $userId);
        my_json_output($updateInfo);
    }

    function approve_confirmation($userId) {

        $this->load->model(array('mailer_model', 'call_center_model'));

        $getUserInfo = $this->call_center_model->getUserInfo((int) $userId);

        if (!$getUserInfo) {
            $json = array(
                "success" => false,
                "msg" => "There are no user found."
            );
            my_json_output($json);
        }

        $userInfo = $getUserInfo->row();

        if (($userInfo->callCenterApprove != 'approved')) {
            $json = array(
                "success" => false,
                "msg" => "User approval pending from mobile end."
            );
            my_json_output($json);
        }

        $confirmation = $this->call_center_model->approveConfirmation((int) $userId);

        if (!$confirmation["success"]) {
            $json = array(
                "success" => false,
                "msg" => "Some error occured, please try again."
            );
            my_json_output($json);
        }

        $mailData = array(
            "apps_id" => $userInfo->eblSkyId
        );

        $mailBody = $this->load->view("mail_body/approved_confirmation.php", $mailData, true);

        $mailData["to"] = $userInfo->userEmail;
        $mailData["from"] = "info@cibl-bd.com";
        $mailData["subject"] = "OTP Code";
        $mailData["body"] = $mailBody;
        $res = $this->mailer_model->sendMail($mailData);

        if (!$res["success"]) {
            $json = array(
                "success" => false,
                "msg" => "Some error occured in mail sending.",
            );
            my_json_output($json);
        }

        $json = array(
            "success" => true,
            "msg" => "successfully approved.",
        );
        my_json_output($json);
    }

    function confirm_password_reset() {
        $userId = (int) $this->input->post("skyId", true);
        if ((int) $userId <= 0):
            $data = array(
                'success' => false,
                'msg' => 'no user id provided'
            );
            my_json_output($data);
        endif;

        $otp_channel =  $this->input->post("otp_channel", true);
        if (!in_array($otp_channel, array('sms', 'email'))):
            $data = array(
                'success' => false,
                'msg' => 'No otp channel provided'
            );
            my_json_output($data);
        endif;
        
        $this->load->model(array('mailer_model', 'call_center_model'));

        $getUserInfo = $this->call_center_model->getUserInfoForPasswordReset((int) $userId);
        if (!$getUserInfo) {
            $json = array(
                "success" => false,
                "msg" => "User information not found",
                "q" => $this->db->last_query()
            );
            my_json_output($json);
        }

        $userInfo = $getUserInfo->row();

        srand(rand(1000000, 9999999));
        $pin = rand(1000000, 9999999);

        try {
            $this->db->trans_begin();

            $udata = array(
                "checkerAction" => 'Password Reset Pin Mail',
                "checkerActionDt" => date("Y-m-d"),
                "checkerActionTm" => date("H:i:s"),
                "checkerActionBy" => $this->my_session->userId,
                "passWord" => md5($pin),
                "pinExpiryReferenceTm" => date("Y-m-d H:i:s"),
                "isActive" => 1,
                "isPublished" => 1,
                "isLocked" => 0,
                'remarks' => '',
                'isReset' => 1,
                'wrongAttempts' => 0
            );

            $this->db->reset_query();
            $this->db->where("skyId", $userInfo->skyId)
                    ->update("apps_users", $udata);

            $this->db->reset_query();
            $mcData = array(
                "isActive" => 1,
                "isPublished" => 1,
                "isLocked" => 0,
                'remarks' => '',
                'isReset' => 1,
                'wrongAttempts' => 0
            );
            $this->db->where("skyId", $userInfo->skyId)
                    ->update("apps_users_mc", $mcData);
            
            $otpData = array(
                "pin" => $pin,
                "eblSkyId" => $userInfo->eblSkyId
            );

            $otpRes = false;
            if($otp_channel == "sms"):
                $smsData = array(
                    "mobileNo" => "88" . ltrim($userInfo->userMobNo1, "88"),
                    "message" => "Your one time account activation pin is {$pin}"
                );

                $this->load->library("sms_service");
                $otpRes = $this->sms_service->smsService($smsData);                
            endif;
            
            if($otp_channel == "email"):
                $mailData["to"] = $userInfo->userEmail;
                if (defined('dummy_email')):
                    $mailData["to"] = dummy_email;
                endif;

                $params = array(
                    'email' => $userInfo['userEmail'],
                    'subject' => "Your OTP for PREMIER Account Activation",
                    'body' => $this->load->view("call_center/pin_reset.php", $otpData, true)
                );

                $otpRes = $this->email_service->emailService($params);
            endif;
            
            

           // if (!$res['success']):
            //    throw new Exception("{$res['msg']} " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
           // endif;

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("could not activate apps user in " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            }

            $this->db->trans_commit();

            $json = array(
                "success" => true,
                "userInfo" => $userInfo,
                "mailRes" => $otpRes,
                "pin" => $pin
            );
            my_json_output($json);
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            $json = array(
                'success' => false,
                'msg' => $ex->getMessage()
            );
            my_json_output($json);
        }
    }

}
