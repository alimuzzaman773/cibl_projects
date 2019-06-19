<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }
    
    function addXmlLog($data)
    {
        try {
            $this->db->insert("xml_logs", $data);
        }
        catch (Exception $e)
        {
            error_log($e->getMessage()." at class-".__CLASS__." line-".__LINE__);
        }
    }
}
