<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Call_center extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('my_session');
        $this->my_session->checkSession();
    }

    function index() {
        $data['css'] = "";
        $data['js'] = "";

        $data['pageTitle'] = "Products";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "call_center/call_center.php";
        $this->load->view('site_template.php', $data);
    }

    function user_list() {
        $this->load->view('call_center/user_list.php', array());
    }

    function user_approve() {
        $this->load->view('call_center/user_approve.php', array());
    }

    function confirmation() {
        $this->load->view('call_center/confirmation.php', array());
    }

    function request_account() {
        $this->load->view('call_center/request_account_list.php', array());
    }

    function account_details() {
        $this->load->view('call_center/request_account_details.php', array());
    }

    function remove_user() {
        $this->load->view('call_center/remove_user.php', array());
    }

}
