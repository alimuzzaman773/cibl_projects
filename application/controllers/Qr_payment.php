<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Qr_payment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('my_session');
        $this->my_session->checkSession();
    }

    function index() {
        $this->my_session->authorize("canViewQRPayment");
        $data['css'] = "";
        $data['js'] = "";

        $data['pageTitle'] = "QR Payment";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "qr_payment/qr_payment.php";
        $this->load->view('site_template.php', $data);
    }

    function transaction_list() {
        $this->load->view('qr_payment/transaction_list.php', array());
    }

}
