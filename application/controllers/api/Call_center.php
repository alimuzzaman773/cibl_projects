<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Call_center extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('my_session');
        $this->my_session->checkSession();
    }

    function user_list() {
        $p['get_count'] = (bool) $this->input->get("get_count", true);
        $p['limit'] = $this->input->get('limit', true);
        $p['offset'] = $this->input->get('offset', true);
        $p['search'] = $this->input->get("search",true);
        $p['filter_by'] = $this->input->get("filter_by",true);
        
        if($p['filter_by'] == 'activation'):
            $p['skyIdOriginal'] = 0;
        endif;
        
        if($p['filter_by'] == 'passwordReset'):
            $p['passwordReset'] = 1;
        endif;
        
        if($p['filter_by'] == 'activationPending24'):
            $p['skyIdOriginal'] = 0;
            $p['activationPending24'] = 24*60*60;
        endif;
        
        if($p['filter_by'] == 'passwordResetPending24'):
            $p['passwordReset'] = 1;
            $p['passwordResetPending24'] = 24*60*60;
        endif;
        
        $this->load->model("call_center_model");

        $json['total'] = 0;
        $json['user_list'] = array();

        if ($p['get_count']) {
            $params = $p;
            unset($params['limit']);
            $result = $this->call_center_model->getAllUsers($params);
            if ($result):
                $json['total'] = $result->row()->total;
            endif;
            $json['q'][] = $this->db->last_query();
        }
        unset($p['get_count']);
        $result = $this->call_center_model->getAllUsers($p);
        if ($result):
            $json['user_list'] = $result->result();
        endif;
        $json['q'][] = $this->db->last_query();
        
        my_json_output($json);
    }

    function get_user_info($userId) {

        $userData = array();
        $this->load->model('call_center_model');

        $registrationInfo = false;
        $userAccounts = array();
        $user = $this->call_center_model->getUserInfo((int) $userId);
        if ($user) 
        {
            $userData = $user->row();
            
            /*$registrationres = $this->call_center_model->getRegistrationDetails($userData->skyId);
            if($registrationres):
                
            endif;*/
            
            
            $accounts = $this->call_center_model->getUserAccounts($userId, $userData->entityNumber);
            if($accounts):
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

    function user_approve($userId) 
    {
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
        
        //call cbs service from live server
        //get vw account summary from cbs 
        $this->load->library("push_to_cbs_service_library");
        //vw_account_summary
        $cdData = array(
            "accountNumber" => "",
            "customerId" => $userInfo->cfId
        );
        $AccountInfoResponse = $this->push_to_cbs_service_library->pushToCbsService("02", $cdData);
        $LaccountInfo = simplexml_load_string($AccountInfoResponse);
        //d($LaccountInfo);
        if ((string) $LaccountInfo->ISSUCCESS != 'Y'):                
            $json = array(
                "success" => false,
                "msg" => "CBS account information not found",
                'log' => $LaccountInfo
            );
            my_json_output($json);
        endif;

        $userInfo->userEmail = (string)$LaccountInfo->CUSTPERSONAL->EMAIL;
        $userInfo->userMobNo1 = (string)$LaccountInfo->CUSTPERSONAL->MOB1;            
        
        $appsGroupId = 0;//$this->input->post("appsGroupId", true);
        
        $otp_channel = $this->input->post("otp_channel", true);
        if($otp_channel == "email"):
            if(!filter_var($userInfo->userEmail, FILTER_VALIDATE_EMAIL)):
                $json = array(
                    'success' => false,
                    'msg' => 'Invalid email of the user. Please select a different otp channel'
                );
                my_json_output($json);
            endif;
        else:    
            if(strlen(trim($userInfo->userMobNo1)) < 11):
                $json = array(
                    'success' => false,
                    'msg' => 'Invalid mobile number of the user. Please select a different otp channel'
                );
                my_json_output($json);
            endif;
        endif;
        
        srand(rand(1000000, 9999999));  
        $pin = rand(1000000, 9999999);
        
        //assign default group
        $appsGroupInfo = $this->db->where("isActive", 1)
                                  ->where("userGroupName", default_limit_package)
                                  ->get('apps_users_group');
        if($appsGroupInfo->num_rows() > 0):
            $appsGroupId = $appsGroupInfo->row()->appsGroupId;
        endif;
                                  
        $user = $this->call_center_model->activateAppUserAccount($userInfo->skyId, $pin, $userInfo->raId, $appsGroupId);
        if (!$user['success']) 
        {            
            my_json_output($user);
        }
        
        $otpData = array(
            "pin" => $pin,
            "eblSkyId" => $userInfo->eblSkyId
        );
        
        $res = null;
        if($otp_channel == "email"):
            $mailData["to"] = $userInfo->userEmail;
            if(defined('dummy_email')):
                $mailData["to"] = dummy_email;
            endif;

            $mailData["from"] = "skybanking@ebl.com";
            $mailData["fromName"] = "EBL SKYBANK";
            $mailData["subject"] = "EBL SKYBANKING ID Activation";
            $mailData["body"] = $this->load->view("call_center/id_mail.php", $otpData, true);
            $res[] = $this->mailer_model->sendMail($mailData);
            
            $mailData["body"] = $this->load->view("call_center/pin_mail.php", $otpData, true);
            $res[] = $this->mailer_model->sendMail($mailData);
        else:
            $this->load->library("sms_service");
            $smsData = array(
                "mobileNo" => "88".ltrim($userInfo->userMobNo1,"88"),
                "message" => $this->load->view("call_center/id_sms.php", $otpData, true)
            );
            
            $res[] = $this->sms_service->smsService($smsData);
            
            $smsData = array(
                "mobileNo" => "88".ltrim($userInfo->userMobNo1,"88"),
                "message" => $this->load->view("call_center/pin_sms.php", $otpData, true)
            );
            
            //$this->load->library("sms_service");
            $res[] = $this->sms_service->smsService($smsData);
        endif;

        $json = array(
            "success" => true,
            //"userInfo" => $user['userInfo'],
            "otpChannelRes" => $res,
            //"pin" => $pin
        );
        my_json_output($json);
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

        if(!$confirmation["success"]){
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
    
    function confirm_password_reset()
    {
        $userId = (int)$this->input->post("skyId", true);
        if((int)$userId <= 0):
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
                "msg" => "User information not found"
            );
            my_json_output($json);
        }

        $userInfo = $getUserInfo->row();
        
        srand(rand(1000000, 9999999));  
        $pin = rand(1000000, 9999999);
        
        try
        {
            $this->db->trans_begin();
            
            $udata = array(
                "checkerAction" => 'confirm_password_reset',
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
            /*$mcData = array(
                "isActive" => 1,
                "isPublished" => 1,
                "isLocked" => 0,
                'remarks' => '',
                'isReset' => 1,
                'wrongAttempts' => 0,
                'passwordReset' => 0
            );*/
            
            $mcData = $udata;
            $this->db->where("skyId", $userInfo->skyId)
                    ->update("apps_users_mc", $mcData);
            
            $otpData = array(
                "pin" => $pin,
                "eblSkyId" => $userInfo->eblSkyId
            );

            $mailData["to"] = $userInfo->userEmail;
            if(defined('dummy_email')):
                $mailData["to"] = dummy_email;
            endif;

            $otpRes = false;
            if($otp_channel == "sms"):
                $smsData = array(
                    "mobileNo" => "88" . ltrim($userInfo->userMobNo1, "88"),
                    "message" => $this->load->view("call_center/pin_reset.php", $otpData, true)
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
                    'email' => $userInfo->userEmail,
                    'subject' => "EBL SKYBANKING",
                    'body' => $this->load->view("call_center/pin_reset.php", $otpData, true)
                );

                $otpRes = app_send_email($params);
            endif;    
            
            //if(!$otpRes):
                //throw new Exception("{$res['msg']} " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            //endif;
            
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
        }
        catch(Exception $ex)
        {
            $this->db->trans_rollback();
            $json = array(
                'success' => false,
                'msg' => $ex->getMessage()
            );
            my_json_output($json);
        }
    }
    
    function resend_user_pin() 
    {
        $userId = (int)$this->input->post("skyId", true);
        if((int)$userId <= 0):
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

        $uInfo = $this->db->where('skyId', (int)$userId)
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
            "pinExpiryReferenceTm" => date("Y-m-d H:i:s"),
            "isReset" => 1
        );

        $this->db->where("skyId", $userInfo->skyId)
                ->update("apps_users", $uData);
        
        $this->db->reset_query();
        $this->db->where("skyId", $userInfo->skyId)
                        ->update("apps_users_mc", $uData);
        
        $otpData = array(
            "pin" => $pin,
            "eblSkyId" => $userInfo->eblSkyId
        );

        $otpRes = false;
        if($otp_channel == "sms"):
            $smsData = array(
                "mobileNo" => "88" . ltrim($userInfo->userMobNo1, "88"),
                "message" => $this->load->view("call_center/pin_reset.php", $otpData, true)
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
                'email' => $userInfo->userEmail,
                'subject' => "EBL SKYBANKING",
                'body' => $this->load->view("call_center/pin_reset.php", $otpData, true)
            );

            $otpRes = app_send_email($params); //$this->email_service->emailService($params);
        endif;
        
        $json = array(
            "success" => true,
            "userInfo" => $userInfo,
            "otpRes" => $otpRes
        );
        my_json_output($json);
    }

}
