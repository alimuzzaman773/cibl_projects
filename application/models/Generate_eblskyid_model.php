<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generate_eblskyid_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function getAllPin() {
        $query = $this->db->get('generate_eblskyid');
        return $query->result();
    }

    public function getPinToDestroy() {
        $query = $this->db->get_where('generate_eblskyid', array('isActive' => 1, 'isUsed' => 0));
        return $query->result();
    }

    public function getPinToPrint() {
        //$this->db->where('isPrinted', 0); previously commented
        // $this->db->where('isactive', 1);
        // $this->db->where('isUsed', 0);
        // $this->db->or_where('isReset', 1);


        $query = $this->db->query('SELECT * FROM `generate_eblskyid` WHERE (`isUsed` = 0 OR `isReset` = 1) AND `isPrinted` = 0');
        return $query->result();
    }

    public function getPinToReset() {
        // $query = $this->db->get_where('generate_eblskyid', array('isReset' => 0, 'isUsed' => 1));
        // $query = $this->db->get_where('generate_eblskyid', array('isPrinted !=' => 1));


        $query = $this->db->get('generate_eblskyid');

        return $query->result();
    }

    public function getAllPinRequests() {
        $this->db->select('pin_generation_request.*,
                           admin_users.fullName,
                           admin_users.adminUserName');
        $this->db->where('pin_generation_request.makerActionBy =', $this->my_session->adminUserId);
        $this->db->from('pin_generation_request');
        $this->db->join('admin_users', 'pin_generation_request.makerActionBy = admin_users.adminUserId');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPinRequestById($id) {
        $this->db->where('requestId', $id);
        $query = $this->db->get('pin_generation_request');
        return $query->row_array();
    }

}
