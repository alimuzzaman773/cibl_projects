<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_request_mail extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');

        $this->load->model('login_model');
        $this->load->library('session');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }

        $this->load->model('product_request_process_model');
        $this->load->model('service_type_model');
        $this->output->set_template('theme2');
    }

    public function index() {

        if (isset($_GET['id'])) {
            $data = $_GET['id'];
        }



        $data = $this->product_request_process_model->get_single_request_array($data);

        if ($data == null) {
            redirect('/invalidAccess/');
        }

        $m = unserialize(PRODUCT_REQUEST_REPORTING_MAIL);
        $emails = implode(",", $m);

        $udata['to'] = $emails;
        $udata['body'] = "";
        $udata['subject'] = "";

        $data1['01'] = "productId,name,contactNo,email,myLocation,productName,preferredCallDtTm";
        // $data1['02']="typeCode,referenceNo,customerID,name,contactNo,cardNo,requestDtTm,noOfPeople,altContact,remarks,jAirLineName,jFlightNo,jReportingDtTm,rAirLineName,rFlightNo,rReportingDtTm";
        // $data1['03']="typeCode,referenceNo,customerID,name,contactNo,cardNo,requestDtTm,noOfPeople,altContact,remarks,jAirLineName,jFlightNo,jReportingDtTm";
        // $data1['04']="typeCode,referenceNo,customerID,name,contactNo,email,myLocation,preferredCallDtTm,callBackFor";


        $udata['subject'] = "Product Request";
        foreach ($data as $key => $value) {
            if (strpos($data1['01'], $key) > -1) {
                $udata['body'] .= "\r\n" . $key . ':  ' . $value;
            }
        }

        $this->load->view('product_request_process/product_request_mail_form', $udata);
    }

}
