<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility_bill extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('my_session');
    }

    function get_utility_bill_list() {

        $params['limit'] = (int) $this->input->get("limit", true);
        $params['offset'] = (int) $this->input->get("offset", true);
        $params['search'] = $this->input->get("search", true);
        $params['payment_id'] = (int) $this->input->get("payment_id", true);
        $params['get_count'] = true;
        $params['fromdate'] = $this->input->get("fromdate", true);
        $params['todate'] = $this->input->get("todate", true);
        $params['utility'] = $this->input->get("utility", true);
        $params['status'] = $this->input->get("status", true);

        $this->load->model("utility_bill_model");
        $data['total'] = 0;

        if ((int) $params['get_count'] > 0) {
            $countParams = $params;
            unset($countParams['limit']);
            unset($countParams['offset']);
            $countParams['count'] = true;
            $countRes = $this->utility_bill_model->getUtilityBillList($countParams);
            if ($countRes):
                $data['total'] = $countRes->row()->total;
            endif;
        }

        $result = $this->utility_bill_model->getUtilityBillList($params);
        if (!$result):
            $json = array(
                "success" => false,
                "bill_list" => [],
                "total" => 0,
                "msg" => "No Info Found!"
            );

            echo json_encode($json);
            die();
        endif;

        $data['success'] = true;
        $data['bill_list'] = $result->result();

        my_json_output($data);
        }

    function reverse_transaction() {

        $params["transaction_id"] = (int) $this->input->post("transaction_id", true);

        $this->load->library('form_validation');
        $this->form_validation->set_data($params);

        $this->form_validation->set_rules('transaction_id', 'Transaction ID', 'trim|required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );
            my_json_output($json);
        endif;

        $this->load->model("transaction_model");
        $result = $this->transaction_model->reverseTransaction($params);

        if (!$result["success"]) {

            $json = array(
                "success" => false,
                "msg" => $result["msg"]
            );
            my_json_output($json);
        }

        my_json_output($result);
    }

}
