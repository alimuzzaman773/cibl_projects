<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
    }
    
    function index() {
        $data['css'] = "";
        $data['js'] = "";

        $data['pageTitle'] = "Tools";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "transaction/index.php";
        $this->load->view('site_template.php', $data);
    }

    function transaction_list(){
        $this->load->view('transaction/transaction_list.php', array());
    }
}
