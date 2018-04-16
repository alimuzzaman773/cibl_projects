<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports extends CI_Controller {

//    function __construct() {
//        parent::__construct();
//        date_default_timezone_set('Asia/Dhaka');
//        $this->load->database();
//        $this->load->helper('url');
//        $this->load->model('reports_model');
//        $this->load->model('login_model');
//        $this->load->library('session');
//        if ($this->login_model->check_session()) {
//            redirect('/admin_login/index');
//        }
//    }
    
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
        //$moduleCodes = $this->session->userdata('reportTypeModules');
        //$moduleCodes = explode("|", $moduleCodes);
        //$index = array_search(user_status, $moduleCodes);
        //if ($index > -1) {
            //$this->output->set_template('theme2');
            $status = $this->input->get('status');
            try {
                $data['rows'] = array();
                if ($status):
                    $r = $this->reports_model->get_user_status($status);
                    if ($r):
                        $data['rows'] = $r;
                    endif;
                endif;
                $data['pageTitle'] = "Complaint Info";
                $data['body_template'] = 'reports/user_status.php';
                $this->load->view('site_template.php', $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        //} else {
           // echo "not allowed";
        //}
    }

    //Fund transfer Form Adding
    public function fund_transfer() {

        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(fund_transfer, $moduleCodes);
        if ($index > -1) {

            $this->output->set_template('theme2');
            $type = $this->input->get('type');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
                $data['rows'] = array();
                $value = range_validation($from, $to);
                if ($value == "ok") {
                    if ($type && $from && $to):
                        $r = $this->reports_model->get_fund_transfer($type, $from, $to);
                        if ($r):
                            $data['rows'] = $r;
                        endif;
                    endif;
                }
                $data['msg'] = $value;
                $this->load->view('reports/fund_transfer', $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

    //Other Bank Fund transfer Form Adding
    public function other_fund_transfer() {

        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(other_fund_transfer, $moduleCodes);
        if ($index > -1) {

            $this->output->set_template('theme2');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
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
                $this->load->view('reports/other_fund_transfer', $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

    //Bill pay date by date
    public function bill_pay() {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(billing_info, $moduleCodes);
        if ($index > -1) {
            $this->output->set_template('theme2');
            $type = $this->input->get('type');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
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
                $this->load->view('reports/bill_pay', $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

    //All customer information
    public function customer_info() {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(customer_info, $moduleCodes);
        if ($index > -1) {

            $this->output->set_template('theme2');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
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
                $this->load->view('reports/customer_info', $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

    //User login information date to date
    public function user_login_info() {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(user_login_info, $moduleCodes);
        if ($index > -1) {
            $this->output->set_template('theme2');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
                $data['rows'] = array();
                $value = range_validation($from, $to);
                if ($value == "ok") {
                    if ($from && $to):
                        $r = $this->reports_model->get_login_info($from, $to);
                        if ($r):
                            $data['rows'] = $r;
                        endif;
                    endif;
                }
                $data['msg'] = $value;

                $this->load->view('reports/user_login_info', $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

    //User ID modification from BO
    public function id_modification() {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(user_id_modification, $moduleCodes);
        if ($index > -1) {

            $this->output->set_template('theme2');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
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
                $this->load->view('reports/id_modification', $data);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

    //Priority Request Form Adding
    public function priority_request() {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(priority_request, $moduleCodes);
        if ($index > -1) {

            $this->output->set_template('theme2');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
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
                $views['msg'] = $value;
                $this->load->view('reports/priority_request', $views);
            } catch
            (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

    //Priority Request Form Adding
    public function product_request() {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(product_request, $moduleCodes);
        if ($index > -1) {

            $this->output->set_template('theme2');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
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
                $views['msg'] = $value;
                $this->load->view('reports/product_request', $views);
            } catch
            (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

    //Priority Request Form Adding
    public function banking_request() {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(banking_request, $moduleCodes);
        if ($index > -1) {

            $this->output->set_template('theme2');
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            try {
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
                $this->load->view('reports/banking_request', $views);
            } catch
            (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "not allowed";
        }
    }

}
