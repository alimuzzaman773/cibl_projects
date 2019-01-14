<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility_bill extends CI_Controller {

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

        $data['body_template'] = "utility_bill/index.php";
        $this->load->view('site_template.php', $data);
    }

    function utility_list(){
        $this->load->view('utility_bill/utility_list.php', array());
    }
}
