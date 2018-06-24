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

        my_json_output($json);
    }

    function get_user_info($userId) {

        $userData = array();
        $this->load->model('call_center_model');

        $user = $this->call_center_model->getUserInfo((int) $userId);
        if ($user) {
            $userData = $user->row();
        }

        $json = array(
            "success" => true,
            "user_info" => $userData,
        );

        my_json_output($json);
    }

    function user_approve($userId) {

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

        $mailData = array(
            "otp" => "123456"
        );

        $mailBody = $this->load->view("mail_body/otp_body.php", $mailData, true);

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

        $user = $this->call_center_model->userApproved((int) $userId);

        if (!$user) {
            $json = array(
                "success" => false,
                "msg" => "User approved process failed, please try again.",
            );
            my_json_output($json);
        }

        $json = array(
            "success" => true,
            "user_id" => $userId,
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

}
