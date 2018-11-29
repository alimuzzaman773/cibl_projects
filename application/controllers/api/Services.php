<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Services extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function send_email() {

        $params['email'] = $this->input->post('email', TRUE);
        $params['subject'] = $this->input->post('subject');
        $params['body'] = $this->input->post('body');

        $this->load->library('form_validation');
        $this->form_validation->set_data($params);

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'required');
        $this->form_validation->set_rules('body', 'Body', 'required');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );
            my_json_output($json);
        endif;

        $this->load->model("mailer_model");
        $mailData["to"] = $params['email'];
        $mailData["from"] = "nopreply@premierbankltd.com";
        $mailData["subject"] = $params['subject'];
        $mailData["body"] = $params['body'];
        $emailResult = $this->mailer_model->sendMail($mailData);

        if ($emailResult) {
            $json = array(
                "success" => true
            );
            my_json_output($json);
        }

        $json = array(
            'success' => false,
            "msg" => $params['msg']
        );
        my_json_output($json);
    }

    function send_sms() {

        $params['mobileNo'] = $this->input->post('mobile_no', TRUE);
        $params['message'] = $this->input->post('message');

        $this->load->library('form_validation');
        $this->form_validation->set_data($params);

        $this->form_validation->set_rules('mobileNo', 'Mobile No', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );
            my_json_output($json);
        endif;

        $this->load->library("sms_service");

        $params = array(
            'mobileNo' => $params['mobileNo'],
            'message' => $params['message']
        );

        $smsResult = $this->sms_service->smsService($params);

        if ($smsResult) {
            $json = array(
                "success" => true
            );
            my_json_output($json);
        }

        $json = array(
            'success' => false,
            "body" => $params['message']
        );
        my_json_output($json);
    }
    
    function get_complaint_info_list() {
        $params['limit'] = (int) $this->input->get("limit", true);
        $params['offset'] = (int) $this->input->get("offset", true);
        $params['search'] = $this->input->get("search", true);
        $params['complaintInfoId'] = (int) $this->input->get("complaintInfoId", true);
        $params['get_count'] = true;

        $this->load->model("help_setup_model");
        $data['total'] = 0;
        $data['list'] = array();

        if ((int) $params['get_count'] > 0) {
            $countParams = $params;
            unset($countParams['limit']);
            unset($countParams['offset']);
            $countParams['count'] = true;
            $countRes = $this->help_setup_model->getComplaintInfoList($countParams);
            if ($countRes):
                $data['total'] = $countRes->row()->total;
            endif;
        }

        $result = $this->help_setup_model->getComplaintInfoList($params);
        if (!$result):
            $json = array(
                "success" => false,
                "list" => [],
                "total" => 0,
                "msg" => "No Info Found!"
            );

            echo json_encode($json);
            die();
        endif;

        $data['success'] = true;
        $data['list'] = $result->result();

        my_json_output($data);
    }
}
