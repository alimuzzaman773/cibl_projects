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
        $p['from_date'] = $this->input->get('from_date', true);
        $p['to_date'] = $this->input->get('to_date', true);
        $p['search'] = $this->input->get('search', true);
        $p['branch'] = $this->input->get('branch', true);
        $p['status'] = $this->input->get('status', true);
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
        
        $json['branch_list']=array();
        $branch = $this->call_center_model->getAllBranch();
        $json['branch_list'] = $branch->result();
        //$json['q'] = $this->db->last_query();
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

    function resend_user_pin() {
        $userId = (int) $this->input->post("skyId", true);
        if ((int) $userId <= 0):
            $data = array(
                'success' => false,
                'msg' => 'no user id provided'
            );
            my_json_output($data);
        endif;

        $otp_channel = $this->input->post("otp_channel", true);
        if (!in_array($otp_channel, array('sms', 'email'))):
            $data = array(
                'success' => false,
                'msg' => 'No otp channel provided'
            );
            my_json_output($data);
        endif;

        $this->load->model(array('mailer_model', 'call_center_model'));
        $uInfo = $this->db->where('skyId', (int) $userId)
                ->where("isPublished", 1)
                ->get('apps_users');

        $getUserInfo = $uInfo->num_rows() > 0 ? $uInfo : false; //$this->call_center_model->getUserInfo((int) $userId);
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
            "eblSkyId" => $userInfo->eblSkyId,
            "username" => $userInfo->userName
        );

        $otpRes = false;
        if ($otp_channel == "sms"):
            $smsData = array(
                "mobileNo" => "88" . ltrim($userInfo->userMobNo1, "88"),
                "message" => "Your one time account activation pin is {$pin}"
            );

            $this->load->library("sms_service");
            $otpRes = $this->sms_service->smsService($smsData);
        endif;

        if ($otp_channel == "email"):
            $mailData["to"] = $userInfo->userEmail;
            if (defined('dummy_email')):
                $mailData["to"] = dummy_email;
            endif;

            $params = array(
                'email' => $userInfo->userEmail,
                'subject' => "Premier Bank Internet Banking - Forgot User ID / Password",
                'body' => $this->load->view("call_center/pin_reset.php", $otpData, true)
            );
            $otpRes = $this->email_service->emailService($params);
        endif;

        $json = array(
            "success" => true,
            "userInfo" => $userInfo,
            "otpRes" => $otpRes
        );
        my_json_output($json);
    }

    function user_approve($userId) {
        $this->load->model('call_center_model');

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
            "eblSkyId" => $userInfo->eblSkyId,
            "userName" => $userInfo->userName
        );

        $otp_channel = $this->input->post("otp_channel");

        $res = null;
        if ($otp_channel == "email"):
            $mailData["to"] = $userInfo->userEmail;
            if (defined('dummy_email')):
                $mailData["to"] = dummy_email;
            endif;

            $params = array(
                'email' => $mailData["to"],
                'subject' => "Premier Mobile APP - Account Activation PIN",
                'body' => $this->load->view("call_center/pin_mail.php", $otpData, true)
            );

            $this->load->library("email_service");
            $res = $this->email_service->emailService($params);
        else:
            $smsData = array(
                "mobileNo" => "88" . ltrim($userInfo->userMobNo1, "88"),
                "message" => "Welcome to the bank at your hand. Your Premier+ user has been created. Your User ID is {$userInfo->eblSkyId} and Temporary Password is {$pin}. You can change your User ID to your choice and this change can be done only once."
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

        $params = array(
            'email' => $userInfo->userEmail,
            'subject' => "Premier Mobile APP - OTP Code",
            'body' => $this->load->view("mail_body/approved_confirmation.php", $mailData, true)
        );

        $this->load->library("email_service");
        $res = $this->email_service->emailService($params);

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

        $otp_channel = $this->input->post("otp_channel", true);
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
                'wrongAttempts' => 0,
                'passwordReset' => 0
            );

            $this->db->reset_query();
            $this->db->where("skyId", $userInfo->skyId)
                    ->update("apps_users", $udata);

            $this->db->reset_query();

            $this->db->where("skyId", $userInfo->skyId)
                    ->update("apps_users_mc", $udata);

            $otpData = array(
                "pin" => $pin,
                "eblSkyId" => $userInfo->eblSkyId,
                "username" => $userInfo->userName
            );

            $otpRes = false;
            if ($otp_channel == "sms"):
                $smsData = array(
                    "mobileNo" => "88" . ltrim($userInfo->userMobNo1, "88"),
                    "message" => "Your one time account activation pin is {$pin}"
                );

                $this->load->library("sms_service");
                $otpRes = $this->sms_service->smsService($smsData);
            endif;

            if ($otp_channel == "email"):
                $mailData["to"] = $userInfo->userEmail;
                if (defined('dummy_email')):
                    $mailData["to"] = dummy_email;
                endif;

                $params = array(
                    'email' => $userInfo->userEmail,
                    'subject' => "Premier Bank Internet Banking - Forgot User ID / Password",
                    'body' => $this->load->view("call_center/pin_reset.php", $otpData, true)
                );

                $this->load->library("email_service");
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
                    //"pin" => $pin
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

    function request_account_list() {
        $p['get_count'] = (bool) $this->input->get("get_count", true);
        $p['limit'] = $this->input->get('limit', true);
        $p['offset'] = $this->input->get('offset', true);
        $this->load->model("call_center_model");

        $json['total'] = 0;
        $json['account_list'] = array();

        if ($p['get_count']) {
            $params['get_count'] = 1;
            $result = $this->call_center_model->getAllRequestAccount($params);
            if ($result):
                $json['total'] = $result->row()->total;
            endif;
        }

        unset($p['get_count']);
        $result = $this->call_center_model->getAllRequestAccount($p);
        if ($result):
            $json['account_list'] = $result->result();
        endif;

        my_json_output($json);
    }

    function get_request_account_info($userId) {

        $userData = array();
        $this->load->model('call_center_model');

        $user = $this->call_center_model->getAllRequestAccount(array("skyId" => (int) $userId));

        if (!$user) {
            $json = array(
                "success" => false,
                "msg" => "There are no user found"
            );
            my_json_output($json);
        }

        $userInfo = $user->row();
        $accountInfo = array();
        $cardInfo = array();

        $accountSearchResponse = array();
        switch ($userInfo->type) {
            case "account":
                $account = $this->call_center_model->getRequestAccountInfo($userInfo->entityNumber);
                if ($account["success"]) {
                    $accountInfo = $account["data"];
                }
                $accountSearchResponse = $account;
                break;

            case "card":
                $card = $this->call_center_model->getRequestCardInfo($userInfo->entityNumber);
                if ($card["success"]) {
                    $cardInfo = $card["data"];
                }
                $accountSearchResponse = $card;
                break;
        }

        $json = array(
            "success" => true,
            "user_info" => $userInfo,
            "user_accounts" => $accountInfo,
            "user_cards" => $cardInfo,
            "response" => $accountSearchResponse
        );
        my_json_output($json);
    }

    function account_approve($userId) {

        $this->load->model(array('mailer_model', 'call_center_model'));
        $user = $this->call_center_model->getAllRequestAccount(array("skyId" => (int) $userId));
        if (!$user) {
            $json = array(
                "success" => false,
                "msg" => "There are no user found"
            );
            my_json_output($json);
        }

        $userInfo = $user->row();

        switch ($userInfo->type) {
            case "account":
                $account = $this->call_center_model->approveAccount($userInfo->entityNumber, $userId);
                if (!$account["success"]) {
                    $accRes = array(
                        "success" => false,
                        "msg" => $account["msg"]
                    );
                    my_json_output($accRes);
                }
                break;

            case "card":
                $card = $this->call_center_model->approveCard($userInfo->entityNumber, $userId);
                if (!$card["success"]) {
                    $cardRes = array(
                        "success" => false,
                        "msg" => $card["msg"]
                    );
                    my_json_output($cardRes);
                }
                break;
        }
    }

    function reject_user() {
        $skyId = $this->input->post("skyId", true);
        $remarks = $this->input->post("remarks", true);

        $this->load->model(array('mailer_model', 'call_center_model'));

        $getUserInfo = $this->call_center_model->getUserInfo((int) $skyId);
        if (!$getUserInfo) {
            $json = array(
                "success" => false,
                "msg" => "User information not found"
            );
            my_json_output($json);
        }

        $userInfo = $getUserInfo->row();

        $uData = array(
            "isRejected" => 1,
            "remarks" => $remarks,
            'checkerAction' => 'rejected',
            'checkerActionComment' => $remarks,
            'checkerActionDt' => date("Y-m-d"),
            'checkerActionTm' => date("H:i:s"),
            'checkerActionBy' => $this->my_session->userId
        );

        try {
            $this->db->where("skyId", $userInfo->skyId)
                    ->where("isPublished", 0)
                    ->update("apps_users_mc", $uData);

            $this->db->reset_query();

            $this->db->where("skyId", $userInfo->skyId)
                    ->where("isPublished", 0)
                    ->update("apps_users", $uData);


            $json = array(
                "success" => true
            );
            my_json_output($json);
        } catch (Exception $e) {
            $json = array(
                'success' => false,
                'msg' => $e->getMessage()
            );
            my_json_output($json);
        }
    }

    function remove_user() {
        if (empty($this->my_session->userId) && $this->my_session->userId <= 0):
            $json = array(
                'success' => false,
                'msg' => 'You are not logged in'
            );
            my_json_output($json);
        endif;

        $params['skyId'] = (int) $this->input->post('skyId', TRUE);
        $params['reason'] = $this->input->post('reason', TRUE);
        $params['eblSkyId'] = $this->input->post('eblSkyId', TRUE);

        $this->load->model('call_center_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('skyId', 'skyId', 'xss_clean|integer|required');
        $this->form_validation->set_rules('reason', 'reason', 'xss_clean|required');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );

            echo json_encode($json);
            die();
        endif;

        $result = $this->call_center_model->removeUser($params['skyId'], $params);

        my_json_output($result);
    }

}
