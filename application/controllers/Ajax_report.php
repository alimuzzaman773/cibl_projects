<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax_report extends MX_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('my_session');
        $this->load->library('BOcrypter');
        $this->load->model('login_model');
        $this->load->model('admin_users_model_maker');
        $this->load->model('common_model');

        $this->my_session->checkSession();
    }

    function __download($outputContent, $format, $paper_size = "A4") {
        Modules::load("utility");
        $utility = new Utility();

        $data = array();
        $data['base_url'] = base_url();
        $data['html'] = $outputContent;

        $download_format = array('pdf', 'excel', 'zip');

        if (!in_array($format, $download_format)) {
            $format = "pdf";
        }

        $utility->output($outputContent, $format);
    }

    function card_payment_report() {
        $params['fromdate'] = $this->input->get_post("fromdate", true);
        $params['todate'] = $this->input->get_post("todate", true);
        $params['billerId'] = $this->input->get_post("billerId", true);

        $this->load->model("reports_model");

        $data['result'] = array();


        $params['isSuccess'] = 'Y';
        $result = $this->reports_model->getCardPayments($params);
        if ($result):
            $data['result'] = $result->result();
        endif;

        $data['params'] = array("reportHeader" => 'Card Payments Report');
        if ($result):
            $result1 = $result->row();
            $data['params']['reportParams'] = array("From" => $params['fromdate'], "To" => $params['todate']);

            if ((int) $params['billerId'] > 0):
                $data['params']['reportParams']["Biller ID"] = $result1->billerName;
            endif;
        endif;

        $data['base_url'] = base_url();
        //var_dump($result->result());
        $report = $this->load->view("ajax_report/card_payment_report.php", $data, true);

        if ((int) $this->input->get_post("report_download_flag", true) == 1) {
            $this->__download($report, $this->input->get_post("report_download_format", true), $this->input->get_post("__layout__", true));
            exit();
        }

        $json['success'] = true;
        $json['msg'] = $report;
        $json['query'] = $this->db->last_query();
        echo json_encode($json);
        die();
    }

    function mobile_topup_card_report() {
        $params['fromdate'] = $this->input->get_post("fromdate", true);
        $params['todate'] = $this->input->get_post("todate", true);
        $params['billerId'] = $this->input->get_post("billerId", true);

        $this->load->model("reports_model");

        $data['result'] = array();


        $params['isSuccess'] = 'Y';
        $result = $this->reports_model->getMobilePaymentByCard($params);
        if ($result):
            $data['result'] = $result->result();
        endif;

        $data['params'] = array("reportHeader" => 'Bill Payment Report');
        if ($result):
            $result1 = $result->row();
            $data['params']['reportParams'] = array("From" => $params['fromdate'], "To" => $params['todate']);

            if ((int) $params['billerId'] > 0):
                $data['params']['reportParams']["Biller ID"] = $result1->billerName;
            endif;
        endif;

        $data['base_url'] = base_url();
        //var_dump($result->result());
        $report = $this->load->view("ajax_report/mobile_topup_card_report.php", $data, true);

        if ((int) $this->input->get_post("report_download_flag", true) == 1) {
            $this->__download($report, $this->input->get_post("report_download_format", true), $this->input->get_post("__layout__", true));
            exit();
        }

        $json['success'] = true;
        $json['msg'] = $report;
        $json['query'] = $this->db->last_query();
        echo json_encode($json);
        die();
    }

    function call_center_report() {
        $p['fromdate'] = $this->input->get_post("fromdate", true);
        $p['todate'] = $this->input->get_post("todate", true);
        $p['filter_by'] = $this->input->get_post("filterBy", true);
        $p['approved_by'] = $this->input->get_post("approved_by", true);
        $p['branch'] = $this->input->get_post('branch', true);

        if ($p['filter_by'] == 'activated'):
            $p['skyIdOriginal'] = 1;
        endif;

        if ($p['filter_by'] == 'activation'):
            $p['skyIdOriginal'] = 0;
        endif;

        if ($p['filter_by'] == 'passwordReset'):
            $p['passwordReset'] = 1;
            unset($p['fromdate']);
            unset($p['todate']);
        endif;

        if ($p['filter_by'] == 'activationPending24'):
            $p['skyIdOriginal'] = 0;
            $p['activationPending24'] = 24 * 60 * 60;
        endif;

        if ($p['filter_by'] == 'passwordResetPending24'):
            $p['passwordReset'] = 1;
            $p['passwordResetPending24'] = 24 * 60 * 60;
            unset($p['fromdate']);
            unset($p['todate']);
        endif;

        $this->load->model("reports_model");

        $data['result'] = array();


        $result = $this->reports_model->getCallCenterUserList($p);
        if ($result):
            $data['result'] = $result->result();
        endif;
        //d($data['result']);
        $data['params'] = array("reportHeader" => 'Call Center User List Report');
        if ($result):
            $result1 = $result->row();
            $data['params']['reportParams'] = array("From" => $p['fromdate'], "To" => $p['todate']);

            if ((int) $p['filter_by'] > 0):
                $data['params']['reportParams']["Filter By"] = $p['filter_by'];
            endif;
        endif;

        $data['base_url'] = base_url();
        //var_dump($result->result());
        $report = $this->load->view("ajax_report/call_center_report.php", $data, true);

        if ((int) $this->input->get_post("report_download_flag", true) == 1) {
            $this->__download($report, $this->input->get_post("report_download_format", true), $this->input->get_post("__layout__", true));
            exit();
        }

        $json['success'] = true;
        $json['msg'] = $report;
        $json['query'] = $this->db->last_query();
        echo json_encode($json);
        die();
    }

    function customer_activity_report() {
        $p['fromdate'] = $this->input->get_post("fromdate", true);
        $p['todate'] = $this->input->get_post("todate", true);
        $p['eblSkyId'] = $this->input->get_post("eblSkyId", true);

        $this->load->model("reports_model");

        $data['result'] = array();


        $result = $this->reports_model->getCustomerActivity($p);
        if ($result):
            $data['result'] = $result->result();
        endif;
        //d($data['result']);
        $data['params'] = array("reportHeader" => 'Apps User Activity Report');
        if ($result):
            $result1 = $result->row();
            $data['params']['reportParams'] = array("From" => $p['fromdate'], "To" => $p['todate']);

            if (trim($p['eblSkyId']) != ''):
                $data['params']['reportParams']["APPS ID"] = $p['eblSkyId'];
            endif;
        endif;

        $data['base_url'] = base_url();
        //var_dump($result->result());
        $report = $this->load->view("ajax_report/customer_activity_report.php", $data, true);

        if ((int) $this->input->get_post("report_download_flag", true) == 1) {
            $this->__download($report, $this->input->get_post("report_download_format", true), $this->input->get_post("__layout__", true));
            exit();
        }

        $json['success'] = true;
        $json['msg'] = $report;
        $json['query'] = $this->db->last_query();
        $json['p'] = $p;
        echo json_encode($json);
        die();
    }

    function fund_transfer_details() {
        $p['fromdate'] = $this->input->get_post("fromdate", true);
        $p['todate'] = $this->input->get_post("todate", true);
        $p['status'] = $this->input->get_post("status", true);
        $p['trn_type'] = $this->input->get_post("trn_type", true);

        $this->load->model("reports_model");

        $data['result'] = array();


        $result = $this->reports_model->getFundTransferDetails($p);
        if ($result):
            $data['result'] = $result->result();
        endif;
        //d($data['result']);
        $data['params'] = array("reportHeader" => 'Fund Transfer Report');
        if ($result):
            $result1 = $result->row();
            $data['params']['reportParams'] = array("From" => $p['fromdate'], "To" => $p['todate']);

            if (trim($p['status']) != ''):
                $data['params']['reportParams']["Status"] = $p['status'];
            endif;
        endif;

        $data['base_url'] = base_url();
        //var_dump($result->result());
        $report = $this->load->view("ajax_report/fund_transfer_details.php", $data, true);

        if ((int) $this->input->get_post("report_download_flag", true) == 1) {
            $this->__download($report, $this->input->get_post("report_download_format", true), $this->input->get_post("__layout__", true));
            exit();
        }

        $json['success'] = true;
        $json['msg'] = $report;
        $json['query'] = $this->db->last_query();
        $json['p'] = $p;
        echo json_encode($json);
        die();
    }

    function utility_bills_report() {
        $p['fromdate'] = $this->input->get_post("fromdate", true);
        $p['todate'] = $this->input->get_post("todate", true);
        $p['status'] = $this->input->get_post("status", true);
        $p['utility'] = $this->input->get_post("utility", true);

        $this->load->model("reports_model");

        $data['result'] = array();


        $result = $this->reports_model->getUtilityBillList($p);
        if ($result):
            $data['result'] = $result->result();
        endif;
        //d($data['result']);
        $data['params'] = array("reportHeader" => 'Utility Bill Report');
        if ($result):
            $result1 = $result->row();
            $data['params']['reportParams'] = array("From" => $p['fromdate'], "To" => $p['todate']);

            if (trim($p['status']) != ''):
                $data['params']['reportParams']["Status"] = $p['status'];
            endif;

            if (trim($p['utility'] != '')):
                $data['params']['reportParams']['Utility Name'] = $p['utility'];
            endif;
        endif;

        $data['base_url'] = base_url();
        //var_dump($result->result());
        $report = $this->load->view("ajax_report/utility_bill_report.php", $data, true);

        if ((int) $this->input->get_post("report_download_flag", true) == 1) {
            $this->__download($report, $this->input->get_post("report_download_format", true), $this->input->get_post("__layout__", true));
            exit();
        }

        $json['success'] = true;
        $json['msg'] = $report;
        $json['query'] = $this->db->last_query();
        $json['p'] = $p;
        echo json_encode($json);
        die();
    }

    function request_log_report() {
        $p['fromdate'] = $this->input->get_post("fromdate", true);
        $p['todate'] = $this->input->get_post("todate", true);

        $this->load->model("reports_model");

        $data['result'] = array();
        $result = $this->reports_model->getRequestLogReport($p);
        if ($result):
            $data['result'] = $result->result();
        endif;
        //d($data['result']);
        $data['params'] = array("reportHeader" => 'Request Log Report');
        if ($result):
            $result1 = $result->row();
            $data['params']['reportParams'] = array("From" => $p['fromdate'], "To" => $p['todate']);
        endif;

        $data['base_url'] = base_url();
        $report = $this->load->view("ajax_report/request_log_report.php", $data, true);

        if ((int) $this->input->get_post("report_download_flag", true) == 1) {
            $this->__download($report, $this->input->get_post("report_download_format", true), $this->input->get_post("__layout__", true));
            exit();
        }

        $json['success'] = true;
        $json['msg'] = $report;
        $json['query'] = $this->db->last_query();
        $json['p'] = $p;
        echo json_encode($json);
        die();
    }

}
