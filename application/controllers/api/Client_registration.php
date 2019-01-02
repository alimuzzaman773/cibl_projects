<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client_registration extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('my_session');
        $this->my_session->checkSession();
    }
    
    function update_user() {
        $params['skyId'] = $skyId = (int) $this->input->post('skyId', TRUE);
        $params['cfId'] = $this->input->post('cfId', TRUE);
        $params['clientId'] = $this->input->post('clientId', TRUE);
        $params['userName'] = $this->input->post('userName', TRUE);
        $params['currAddress'] = $this->input->post('currAddress', TRUE);
        $params['parmAddress'] = $this->input->post('parmAddress', TRUE);
        $params['billingAddress'] = $this->input->post('billingAddress', TRUE);

        $this->load->model('client_registration_model');
        $this->load->library('form_validation');
        $this->form_validation->set_data($params);
        
        $this->form_validation->set_rules('skyId', 'Category Name', 'xss_clean|integer|required');
        
        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );

            echo json_encode($json);
            die();
        endif;

        //$date = date("Y-m-d H:i:s");
        $params['makerActionDt'] = date("Y-m-d");
        $params['makerActionTm'] = date("H:i:s");
        $params['makerActionBy'] = $this->my_session->userId;
        $params['mcStatus'] = 0;
        $params['makerAction'] = "edit";

        //d($params);

        $result = $this->client_registration_model->updateUser($params);

        my_json_output($result);
    }
    
    // Get App User 
    function get_user($skyId = NULL) {
        if (empty($skyId)):
            $json["success"] = false;
            $json["msg"] = "Pleae check your URL";
            echo json_encode($json);
            die();
        endif;

        $params = array(
            "skyId" => $skyId
        );

        $this->load->model('client_registration_model');
        $result = $this->client_registration_model->getAppUsers($params);

        if (!$result):
            $json = array(
                "success" => false,
                "msg" => "Info Not Found"
            );
            echo json_encode($json);
            die();
        endif;

        $data = $result->row();
        
        $json = array(
            "success" => true,
            "data" => $data
        );

        my_json_output($json);
    }
    
}
