<?php

class Banking_request_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }

    public function get_single_request_array($id) {
        $query = $this->db->get_where('service_request_bank', array('serviceId' => $id));
        return $query->row_array();
    }

}
