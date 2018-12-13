<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_request_process extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('product_request_process_model', 'common_model', 'login_model'));
    }

    public function index() {
        $this->my_session->authorize("canViewProductRequest");
        $data["pageTitle"] = "Product Request";
        $data["body_template"] = "product_request_process/show_product_request.php";
        $this->load->view('site_template.php', $data);
    }

    function get_requests_ajax() {
        $this->my_session->authorize("canViewProductRequest");
        $params['limit'] = (int) $this->input->get("limit", true);
        $params['offset'] = (int) $this->input->get("offset", true);
        $params['get_count'] = (bool) $this->input->get("get_count", true);
        $params['from_date'] = $this->input->get("from_date", true);
        $params['to_date'] = $this->input->get("to_date", true);

        $data['total'] = array();
        $data['product_list'] = array();

        if ((int) $params['get_count'] > 0) {
            $countParams = $params;
            unset($countParams['limit']);
            unset($countParams['offset']);
            $countParams['count'] = true;
            $countRes = $this->product_request_process_model->getProductRequestByDate($countParams);
            if ($countRes):
                $data['total'] = $countRes->row()->total;
            endif;
        }
        $result = $this->product_request_process_model->getProductRequestByDate($params);
        if ($result) {
            $data['product_list'] = $result->result();
        }
        my_json_output($data);
    }

    /*
      public function getRequest() {
      $data["fromDate"] = $this->input->get("fromDate");
      $data["toDate"] = $this->input->get("toDate");
      if ($data["fromDate"] && $data["toDate"]) {
      $data["pageTitle"] = "Product Request";
      $data['productRequest'] = json_encode($this->product_request_process_model->getProductRequestByDate($data));
      $data["body_template"] = "product_request_process/show_product_request.php";
      $this->load->view('site_template.php', $data);
      }
      }
     */

    public function processRequestById($id) {
        $this->my_session->authorize("canEmailProductRequest");
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

        $mailData["pageTitle"] = "Product Request Mail";
        $mailData["body_template"] = "product_request_process/mail_form.php";
        $this->load->view('site_template.php', $mailData);
    }

    public function sendMail() {
        $this->my_session->authorize("canEmailProductRequest");
        $applyId = $this->input->post("applyId");
        $maildata['to'] = $this->input->post("to");
        $maildata['cc'] = $this->input->post("cc");
        $maildata['bcc'] = $this->input->post("bcc");
        $maildata['subject'] = $this->input->post("subject");
        $bodyInstruction = $this->input->post("bodyInstruction");
        $maildata['body'] = $this->input->post("body") . "<br></br>" . $bodyInstruction;
        $isSuccess = $this->common_model->send_service_mail($maildata);

        if ($isSuccess['success']) {
            $this->product_request_process_model->statusChange($applyId, $maildata, $bodyInstruction);
            redirect('product_request_process');
        }
        
        echo "Could not send email due to :: ".@$isSuccess['msg'];
    }

}
