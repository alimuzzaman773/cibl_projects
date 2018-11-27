<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('my_session');
    }

    function transaction_list() {

        $p['get_count'] = (bool) $this->input->get("get_count", true);
        $p['limit'] = $this->input->get('limit', true);
        $p['offset'] = $this->input->get('offset', true);
        $this->load->model("transaction_model");

        $json['total'] = 0;
        $json['transaction_list'] = array();

        if ($p['get_count']) {
            $params['get_count'] = 1;
            $result = $this->transaction_model->getAllTransaction($params);
            if ($result):
                $json['total'] = $result->row()->total;
            endif;
        }

        unset($p['get_count']);
        $result = $this->transaction_model->getAllTransaction($p);
        if ($result):
            $json['transaction_list'] = $result->result();
        endif;

        my_json_output($json);
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
