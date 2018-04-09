<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_request_process extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->helper('url');
        $this->load->model('product_request_process_model');
        $this->load->model('common_model'); // for sending mail

        $this->load->model('login_model');
        $this->load->library('session');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    public function index() {
        $moduleCodes = $this->session->userdata('serviceRequestModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(product_sr, $moduleCodes);
        if ($index > -1) {

            $this->output->set_template('theme2');
            $data['fromDate'] = "";
            $data['toDate'] = "";
            $data['productRequest'] = "";
            $this->load->view('product_request_process/show_product_request.php', $data);
        } else {
            echo "not allowed";
            die();
        }
    }

    public function getRequest() {
        $moduleCodes = $this->session->userdata('serviceRequestModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(product_sr, $moduleCodes);
        if ($index > -1) {

            if (isset($_GET['fromDate']) && isset($_GET['toDate'])) {
                $data['fromDate'] = $_GET['fromDate'];
                $data['toDate'] = $_GET['toDate'];
                $data['productRequest'] = json_encode($this->product_request_process_model->getProductRequestByDate($data));
                $this->output->set_template('theme2');
                $this->load->view('product_request_process/show_product_request.php', $data);
            }
        } else {
            echo "not allowed";
            die();
        }
    }

    public function processRequestById($id, $fromDate, $toDate) {
        $moduleCodes = $this->session->userdata('serviceRequestModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(product_sr, $moduleCodes);
        if ($index > -1) {

            $productRequest = $this->product_request_process_model->getSingleRequestById($id);

            $mailData['to'] = "";
            $mailData['body'] = "";
            $mailData['subject'] = $productRequest['productName'];

            $strings['productRequest'] = "name,contactNo,email,myLocation,productName,creationDtTm";

            $replaceArray = array('applyId' => 'Apply ID',
                'productId' => 'Product ID',
                'name' => 'Name',
                'contactNo' => 'Contact No.',
                'email' => 'Email',
                'myLocation' => 'Location',
                'productName' => 'Product Name',
                'preferredCallDtTm' => 'Preferred Call Date',
                'creationDtTm' => 'Request Date');



            foreach ($productRequest as $key => $value) {
                if (strpos($strings['productRequest'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }

            $mailData['applyId'] = $id;
            $mailData['fromDate'] = $fromDate;
            $mailData['toDate'] = $toDate;
            $this->output->set_template('theme2');
            $this->load->view('product_request_process/mail_form', $mailData);
        } else {
            echo "not allowed";
            die();
        }
    }

    public function sendMail() {
        $moduleCodes = $this->session->userdata('serviceRequestModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(product_sr, $moduleCodes);
        if ($index > -1) {
            $applyId = $_GET['applyId'];
            $maildata['to'] = $_GET['to'];
            //----new-----//
            $ccAddress = "";
            $ccAddress = $_GET['cc'];
            $maildata['cc'] = $ccAddress;
            $bccAddress = "";
            $bccAddress = $_GET['bcc'];
            $maildata['bcc'] = $bccAddress;
            //-------------//
            $maildata['subject'] = $_GET['subject'];
            $bodyInstruction = "";
            $bodyInstruction = $_GET['bodyInstruction'];
            $maildata['body'] = $_GET['body'] . "<br></br>" . $bodyInstruction;
            $isSuccess = $this->common_model->send_service_mail($maildata);

            if ($isSuccess) {
                $this->product_request_process_model->statusChange($applyId, $maildata, $bodyInstruction);
                redirect('product_request_process');
            }
        } else {
            echo "not allowed";
            die();
        }
    }

}
