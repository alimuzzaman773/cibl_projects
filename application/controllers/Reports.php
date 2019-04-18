<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports extends MX_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model('reports_model');
    }

    public function rt_test() {
        $this->db->select('bankName as bankName, districtName as distName, branchName as brnName, routingNumber as routingNumber');
        $this->db->from('routing_number');
        $this->db->where('isActive', 1);
        $this->db->order_by('bankName', 'ASC');
        $query = $this->db->get();
        print_r($query->result());
    }

//Active User Report Form Pick
    public function user_status() {
        //$this->my_session->authorize("canViewUserStatusReport");

        $status = $this->input->get('status', true);
        $data['rows'] = array();
        if (trim($status) != ''):
            $r = $this->reports_model->get_user_status($status);
            if ($r):
                $data['rows'] = $r;
            endif;
        endif;

        $data['pageTitle'] = "User Status Report";
        $data['body_template'] = 'reports/user_status.php';
        $this->load->view('site_template.php', $data);
    }

//Fund transfer Form Adding
    public function fund_transfer() {

        //$this->my_session->authorize("canViewFundTransferReport");

        $type = $this->input->get('type', true);
        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $data['rows'] = array();
        $value = range_validation($from, $to);
        if ($value == "ok") {
            if (trim($type) != '' && trim($from) != '' && trim($to) != ''):
                $r = $this->reports_model->get_fund_transfer($type, $from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
        }
        $data['msg'] = $value;
        $data['pageTitle'] = "Fund Transfer Report";
        $data['body_template'] = 'reports/fund_transfer.php';
        $this->load->view('site_template.php', $data);
    }

//Other Bank Fund transfer Form Adding
    public function other_fund_transfer() {

        //$this->my_session->authorize("canViewOtherFundTransferReport");

        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $data['rows'] = array();
        $value = range_validation($from, $to);
        if ($value == "ok") {
            if ($from && $to):
                $r = $this->reports_model->get_other_fund_transfer($from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
        }

        $data['msg'] = $value;
        $data['pageTitle'] = "Other Bank Transfer Report";
        $data['body_template'] = 'reports/other_fund_transfer.php';
        $this->load->view('site_template.php', $data);
    }

//Bill pay date by date
    public function bill_pay() {

        //$this->my_session->authorize("canViewBillPayReport");

        $type = $this->input->get('type', true);
        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $data['rows'] = array();
        $value = range_validation($from, $to);
        if ($value == "ok") {
            if ($type && $from && $to):
                $r = $this->reports_model->get_bill_pay($type, $from, $to);
                if ($r):
                    $data['rows'] = $r;
                    $data['type'] = $type;
                endif;
            endif;
        }
        $data['msg'] = $value;
        $data['pageTitle'] = "Bill Pay Report";
        $data['body_template'] = 'reports/bill_pay.php';
        $this->load->view('site_template.php', $data);
    }

//All customer information
    public function customer_info() {

        //$this->my_session->authorize("canViewCustomerInfoReport");

        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $data['rows'] = array();
        $value = range_validation($from, $to);
        if ($value == "ok") {
            if ($from && $to):
                $r = $this->reports_model->get_customer_info($from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
        }

        $data['msg'] = $value;
        $data['pageTitle'] = "Customer Information Report";
        $data['body_template'] = 'reports/customer_info.php';
        $this->load->view('site_template.php', $data);
    }

//User login information date to date
    public function user_login_info() {
        //$this->my_session->authorize("canViewUserLoginInfoReport");

        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $data['rows'] = array();
        $value = range_validation($from, $to);
        if ($value == "ok") {
            if (trim($from) != '' && trim($to) != ''):
                $r = $this->reports_model->get_login_info($from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
        }

        $data['msg'] = $value;
        $data['pageTitle'] = "User Login Information Report";
        $data['body_template'] = 'reports/user_login_info.php';
        $this->load->view('site_template.php', $data);
    }

//User ID modification from BO
    public function id_modification() {

        //$this->my_session->authorize("canViewIdModificationReport");

        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $data['rows'] = array();
        $value = range_validation($from, $to);
        if ($value == "ok") {
            if ($from && $to):
                $r = $this->reports_model->get_id_modification($from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
        }

        $data['msg'] = $value;
        $data['pageTitle'] = "ID Modification Report";
        $data['body_template'] = 'reports/id_modification.php';
        $this->load->view('site_template.php', $data);
    }

//Priority Request Form Adding
    public function priority_request() {
        //$this->my_session->authorize("canViewPriorityRequestReport");

        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $value = range_validation($from, $to);
        $views["rows"] = array();
        if ($value == "ok") {
            if ($from && $to) {
                $r = $this->reports_model->get_priority_request($from, $to);
                $mailData = $this->reports_model->get_priority_mail($from, $to);
                if ($r) {
                    foreach ($r as $item) {
                        $serviceId = $item->serviceRequestID;
                        $data["referenceNo"] = $item->referenceNo;
                        $data["eblSkyId"] = $item->eblSkyId;
                        $data["userEmail"] = $item->userEmail;
                        $data["status"] = $item->status;
                        $data["accTitle"] = $item->name;
                        $data["customerId"] = $item->customerID;
                        $data["requestDtTm"] = $item->requestDtTm;
                        $data["mobileNo"] = $item->userMobNo1;
                        $data["myLocation"] = $item->myLocation;
                        $data["serviceName"] = $item->serviceName;
                        $data["mailCountSummary"] = "";
                        if ($mailData) {
                            $mailCount = "";
                            foreach ($mailData as $counter) {
                                if ($serviceId == $counter->requestApplyId) {
                                    $mailCount = $mailCount . $counter->receivedMail . ", To : " . $counter->toCounter . ", CC : " . $counter->ccCounter . ", BCC : " . $counter->bccCounter . "<br>";
                                }
                            }
                            $data["mailCountSummary"] = $mailCount;
                        }
                        $viewData[] = $data;
                    }
                    $views["rows"] = $viewData;
                }
            }
        }

        $data['rows'] = $views['rows'];
        $data['msg'] = $value;
        $data['pageTitle'] = "Priority Request Report";
        $data['body_template'] = 'reports/priority_request.php';
        $this->load->view('site_template.php', $data);
    }

//Priority Request Form Adding
    public function product_request() {

        //$this->my_session->authorize("canViewProductRequestReport");

        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $value = range_validation($from, $to);
        $views["rows"] = array();
        if ($value == "ok") {
            if ($from && $to) {
                $r = $this->reports_model->get_product_request($from, $to);
                $mailData = $this->reports_model->get_product_mail($from, $to);

                if ($r) {
                    foreach ($r as $item) {
                        $applyId = $item->applyId;
                        $data["productId"] = $item->productId;
                        $data["status"] = $item->status;
                        $data["name"] = $item->name;
                        $data["contactNo"] = $item->contactNo;
                        $data["email"] = $item->email;
                        $data["myLocation"] = $item->myLocation;
                        $data["productName"] = $item->productName;
                        $data["creationDtTm"] = $item->creationDtTm;
                        $data["mailCountSummary"] = "";
                        if ($mailData) {
                            $mailCount = "";
                            foreach ($mailData as $counter) {
                                if ($applyId == $counter->requestApplyId) {
                                    $mailCount = $mailCount . $counter->receivedMail . ", To : " . $counter->toCounter . ", CC : " . $counter->ccCounter . ", BCC : " . $counter->bccCounter . "<br>";
                                }
                            }
                            $data["mailCountSummary"] = $mailCount;
                        }
                        $viewData[] = $data;
                    }
                    $views["rows"] = $viewData;
                }
            }
        }
        $data['rows'] = $views['rows'];
        $data['msg'] = $value;
        $data['pageTitle'] = "Product Requests Report";
        $data['body_template'] = 'reports/product_request.php';
        $this->load->view('site_template.php', $data);
    }

    public function banking_request() {

        //$this->my_session->authorize("canViewBankingRequestReport");

        $from = $this->input->get('from', true);
        $to = $this->input->get('to', true);

        $value = range_validation($from, $to);
        $views["rows"] = array();

        if ($value == "ok") {
            if ($from && $to) {
                $r = $this->reports_model->get_banking_request($from, $to);
                $mailData = $this->reports_model->get_banking_mail($from, $to);
                if ($r) {
                    foreach ($r as $item) {
                        $serviceId = $item->serviceId;
                        $data["referenceNo"] = $item->referenceNo;
                        $data["eblSkyId"] = $item->eblSkyId;
                        $data["userEmail"] = $item->userEmail;
                        $data["status"] = $item->status1;
                        $data["accTitle"] = $item->accTitle;
                        $data["customerId"] = $item->customerId;
                        $data["requestDtTm"] = $item->requestDtTm;
                        $data["mobileNo"] = $item->userMobNo1;
                        $data["accNo"] = $item->accNo;
                        $data["serviceName"] = $item->serviceName;
                        $data["mailCountSummary"] = "";
                        if ($mailData) {
                            $mailCount = "";
                            foreach ($mailData as $counter) {
                                if ($serviceId == $counter->requestApplyId) {
                                    $mailCount = $mailCount . $counter->receivedMail . ", To : " . $counter->toCounter . ", CC : " . $counter->ccCounter . ", BCC : " . $counter->bccCounter . "<br>";
                                }
                            }
                            $data["mailCountSummary"] = $mailCount;
                        }
                        $viewData[] = $data;
                    }
                    $views["rows"] = $viewData;
                }
            }
        }

        $views['msg'] = $value;
        $views['pageTitle'] = "Banking Requests Report";
        $views['body_template'] = 'reports/banking_request.php';
        $this->load->view('site_template.php', $views);
    }

    function card_payment_report() {
        /** initialization * */
        $data['css'] = "";

        $data['js'] = "";

        $data['pageTitle'] = "Card Payment Report";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['billerList'] = $this->db->get("biller_setup")->result();

        $data['body_template'] = "reports/card_payment_report.php";
        $this->load->view('site_template.php', $data);
    }

    function mobile_topup_card_report() {
        /** initialization * */
        $data['css'] = "";

        $data['js'] = "";

        $data['pageTitle'] = "Mobil Top Up From Card Report";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['billerList'] = $this->db->get("biller_setup")->result();

        $data['body_template'] = "reports/mobile_topup_card_report.php";
        $this->load->view('site_template.php', $data);
    }

    function call_center_report() {
        /** initialization * */
        $data['css'] = "";

        $data['js'] = "";

        $data['pageTitle'] = "Call Center User List Report";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "reports/call_center_report.php";
        $this->load->view('site_template.php', $data);
    }

    function customer_activity_report() {
        /** initialization * */
        $data['css'] = "";

        $data['js'] = "";

        $data['pageTitle'] = "Apps User activity Report";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "reports/customer_activity_report.php";
        $this->load->view('site_template.php', $data);
    }

    function fund_transfer_details() {
        /** initialization * */
        $data['css'] = "";

        $data['js'] = "";

        $data['pageTitle'] = "Fund Transfer Report";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "reports/fund_transfer_details.php";
        $this->load->view('site_template.php', $data);
    }

    function utility_bill_report(){
        /** initialization * */
        $data['css'] = "";

        $data['js'] = "";

        $data['pageTitle'] = "Fund Transfer Report";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "reports/utility_bill_report.php";
        $this->load->view('site_template.php', $data);
    }

}
