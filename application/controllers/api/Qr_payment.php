<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Qr_payment extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
    }

    function get_transaction_list() {
        $this->my_session->authorize("canViewQRPayment");
        $params['limit'] = (int) $this->input->get("limit", true);
        $params['offset'] = (int) $this->input->get("offset", true);
        $params['search'] = $this->input->get("search", true);
        $params['transaction_id'] = (int) $this->input->get("transaction_id", true);
        $params['get_count'] = true;
        $params['fromdate'] = $this->input->get("fromdate", true);
        $params['todate'] = $this->input->get("todate", true);
        $params['status'] = "Y"; //$this->input->get("status", true);
        $params['from_account'] = $this->input->get("from_account", true);
        $params['batch_number'] = $this->input->get("batch_number", true);
        $params['payment_status'] = $this->input->get("payment_status", true);
        $params['qr_cash_withdrawal'] = "qr_cash_withdrawal";
        $params['merchantId'] = $this->my_session->merchantId;

        $this->load->model("qr_payment_model");
        $data['total'] = 0;

        if ((int) $params['get_count'] > 0) {
            $countParams = $params;
            unset($countParams['limit']);
            unset($countParams['offset']);
            $countParams['count'] = true;
            $countRes = $this->qr_payment_model->getTransactionList($countParams);
            if ($countRes):
                $data['total'] = $countRes->row()->total;
            endif;
        }


        $result = $this->qr_payment_model->getTransactionList($params);
        if (!$result):
            $json = array(
                "success" => false,
                "transaction_list" => [],
                "total" => 0,
                "msg" => "No data found!",
                'q' => $this->db->last_query()
            );
            my_json_output($json);
        endif;

        $data['success'] = true;
        $data['transaction_list'] = $result->result();
        $data['q'] = $this->db->last_query();

        my_json_output($data);
    }

    function send_trn_reference() {
        $this->my_session->authorize("canSendTrnReference");
        $params['transaction_id'] = (int) $this->input->get('transaction_id', TRUE);

        $this->load->library('form_validation');
        $this->form_validation->set_data($params);
        $this->form_validation->set_rules('transaction_id', 'Transaction ID', 'trim|required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );

            echo json_encode($json);
            die();
        endif;

        $this->load->model("banking_model");
        $result = $this->banking_model->sendTrnRefrence($params);

        if (!$result):
            $json = array(
                'success' => false,
                "msg" => $result['msg']
            );
            my_json_output($json);
        endif;

        $json = array(
            'success' => true,
            "msg" => "Transaction Refrence ID sent successfully."
        );
        my_json_output($json);
    }

    function confirm_payment() {
        $this->my_session->authorize("canUpdateQrPaymentStatus");
        $params['payment_id'] = (int) $this->input->post('payment_id', TRUE);
        $params['remarks'] = $this->input->post('remarks', TRUE);

        $this->load->library('form_validation');
        $this->form_validation->set_data($params);
        
        $this->form_validation->set_rules('payment_id', 'Transaction ID', 'trim|required|numeric|greater_than[0]');
        $this->form_validation->set_rules('remarks', 'Reamrks', 'trim|required');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );

            echo json_encode($json);
            die();
        endif;

        $this->load->model("qr_payment_model");
        $result = $this->qr_payment_model->confirmPayment($params);

        if (!$result):
            $json = array(
                'success' => false,
                "msg" => $result['msg']
            );
            my_json_output($json);
        endif;

        $json = array(
            'success' => true,
            "msg" => "Your payment successfully completed."
        );
        my_json_output($json);
    }

}
