<?php

class Biller_setup_model_maker extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
    }

    public function getAllBillers() {
        $this->db->select('biller_setup_mc.*, bill_type.billTypeName');
        //$this->db->where('makerActionBy =', $this->my_session->userId);
        $this->db->where_in('mcStatus', array(1,2));
        $this->db->from('biller_setup_mc');
        $this->db->join('bill_type', 'biller_setup_mc.billTypeCode = bill_type.billTypeCode');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllBillTypes() {
        $this->db->where('isActive =', 1);
        $query = $this->db->get('bill_type');
        return $query->result();
    }

    public function getBillerByCode($data) {
        $query = $this->db->get_where('biller_setup_mc', array('billerCode' => $data));
        return $query->result();
    }

    public function insertBillerInfo($data) {
        $this->db->insert('biller_setup_mc', $data);
    }

    public function getBillerById($data) {
        $this->db->select('biller_setup_mc.*, bill_type.billTypeName');
        $this->db->from('biller_setup_mc');
        $this->db->join('bill_type', 'biller_setup_mc.billTypeCode = bill_type.billTypeCode');
        $this->db->where('billerId', $data);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function checkBiller($id, $data) {
        $billerId = $id;
        $billerCode = $data['billerCode'];
        $this->db->where("billerCode = '$billerCode' AND billerId != '$billerId'");
        $this->db->from('biller_setup_mc');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function updateBillerInfo($data, $id) {
        $this->db->where('billerId', $id);
        $this->db->update('biller_setup_mc', $data);
    }

}
