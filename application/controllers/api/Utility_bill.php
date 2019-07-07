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

        $infoList = $result->result();
       
        // Loop
        $dataList = array();
        foreach ($infoList as $k => $i):
            $dataList[$k]['skyId'] = $i->skyId;
            $dataList[$k]['eblSkyId'] = $i->eblSkyId;
            $dataList[$k]['userName'] = $i->userName;
            $dataList[$k]['userName2'] = $i->userName2;
            $dataList[$k]['created'] = $i->created;
            $dataList[$k]['utility_name'] = $i->utility_name;
            $dataList[$k]['from_account'] = $i->from_account;
            $dataList[$k]['bpt_from_ac'] = $i->bpt_from_ac;
            $dataList[$k]['bpt_amount'] = $i->bpt_amount;
            $dataList[$k]['isSuccess'] = $i->isSuccess;
            $dataList[$k]['bill_response_formated'] = json_display_html($i->bill_response);
            $dataList[$k]['payment_response_formated'] = json_display_html($i->payment_response);
            $dataList[$k]['bill_response'] = $i->bill_response;
            $dataList[$k]['payment_id'] = $i->payment_id;
        endforeach;

        $data['success'] = true;
        $data['bill_list'] = $dataList;

        my_json_output($data);
    }

    function reverse_utility() {

        $params["payment_id"] = (int) $this->input->post("payment_id", true);

        $this->load->library('form_validation');
        $this->form_validation->set_data($params);

        $this->form_validation->set_rules('payment_id', 'Payment ID', 'trim|required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );
            my_json_output($json);
        endif;

        $this->load->model("utility_bill_model");
        $result = $this->utility_bill_model->reverseUtility($params);

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
