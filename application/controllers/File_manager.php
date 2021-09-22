<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class File_manager extends CI_Controller {

    function __construct() {
        parent::__construct();

        //$this->load->library("my_session");
        //$this->my_session->checkSession();
    }

    function index() {
        d("Not Allowed");
        //define('FM_EMBED', true);
        define('FM_SELF_URL', base_url() . "file_manager"); // must be set if URL to manager not equal PHP_SELF

        require APPPATH . 'third_party/filemanager.php';
    }

}
