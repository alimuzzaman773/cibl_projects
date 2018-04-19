<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generate_eblskyid_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAllPinRequests($params = array()) {
        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(requestId) as total");
        } else {
            $this->db->select('pin_generation_request.*, admin_users.fullName, admin_users.adminUserName', FALSE);
        }

        $this->db->from('pin_generation_request');
        $this->db->join('admin_users', 'pin_generation_request.makerActionBy = admin_users.adminUserId');
        $this->db->where('pin_generation_request.makerActionBy =', $this->my_session->adminUserId);

        if (isset($params['limit']) && (int) $params['limit'] > 0) {
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        }

        $result = $this->db->order_by("requestId", "DESC")->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function getPinList($params = array()) {
        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(generateId) as total");
        } else {
            $this->db->select('generate_eblskyid.*', FALSE);
        }

        $this->db->from('generate_eblskyid');

        if (isset($params['limit']) && (int) $params['limit'] > 0) {
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        }

        $result = $this->db->order_by("generateId", "DESC")->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
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

    public function getPinRequestById($id) {
        $this->db->where('requestId', $id);
        $query = $this->db->get('pin_generation_request');
        return $query->row_array();
    }

}
