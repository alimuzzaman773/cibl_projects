<?php

class Csv_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }

    // function get_addressbook() {     
    //     $query = $this->db->get('routing_number');
    //     if ($query->num_rows() > 0) {
    //         return $query->result_array();
    //     } else {
    //         return FALSE;
    //     }
    // }
    // function insert_csv($data) {
    //     //$this->db->truncate('addressbook');
    //     $this->db->insert('addressbook', $data);
    // }


    function insert_csv_batch($data) {
        $this->db->insert_batch('routing_number', $data);
    }

}
