<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apps_users_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
    }

    public function insertUserInfo($data) {
        $this->db->insert('apps_users_mc', $data);
        $data = $this->db->insert_id();
        return $data;
    }

    public function getPin($data) {
        $this->db->from('generate_eblskyid');
        $this->db->where('eblSkyId', $data);
        $query = $this->db->get();
        $row = $query->row_array();
        return $row;
    }

    public function getDefaultGroup($data) {
        $this->db->from('apps_users_group');
        $this->db->where('appsGroupId', $data);
        $query = $this->db->get();
        $row = $query->row_array();
        return $row;
    }

    public function UpdateSkyId($eblskyid) {
        $data = array('isUsed' => 1, 'updateDtTm' => date("Y-m-d G:i:s"));
        $this->db->where('eblSkyId', $eblskyid);
        $this->db->update('generate_eblskyid', $data);
    }

    public function getAppsUser($data) {
        $query = $this->db->get_where('apps_users_mc', $data);
        return $query->row_array();
    }

    public function updateUserInfo($data) {
        $whereData['skyId'] = $data['skyId'];
        unset($data['skyId']);
        $this->db->where($whereData);
        $this->db->update('apps_users_mc', $data);
    }

    public function getAllGroups() {
        $this->db->select('appsGroupId, userGroupName');
        $this->db->from('apps_users_group');
        $this->db->order_by("userGroupName", "ASC");
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserById($data) {
        $query = $this->db->get_where('apps_users_mc', array('skyId' => $data));
        return $query->row_array();
    }

    public function getGroupById($data) {
        $query = $this->db->get_where('apps_users_group', array('appsGroupId' => $data));
        return $query->row_array();
    }

    public function cfIdCheck($data) {
        $query = $this->db->get_where('apps_users_mc', array('cfId' => $data));
        return $query->row_array();
    }

    public function clientIdCheck($data) {
        $query = $this->db->get_where('apps_users_mc', array('clientId' => $data));
        return $query->row_array();
    }

    public function clientIdCheckEdit($data) {

        $clientId = $data['clientId'];
        $skyId = $data['skyId'];

        $this->db->where("clientId = {$this->db->escape($clientId)} AND skyId != {$this->db->escape($skyId)}");
        $query = $this->db->get('apps_users_mc');
        return $query->result_array();
    }

    public function getAppsUserGroup($p = array()) {
        $this->db->select('*', false);
        $this->db->from('apps_users_group');
        $this->db->order_by("userGroupName", "ASC");

        if (isset($p['isActive'])):
            $this->db->where("isActive", $p['isActive']);
        endif;

        $query = $this->db->get();
        return $query->result();
    }
    
     public function getAccountSerialNumber() {
        $esbPrefix = "PBL";
        $esbIdOffset = 1000000;

        $sql = 'select max(oau.eblId) as eblId from (SELECT CAST(SUBSTRING(au.eblSkyId, 4) AS SIGNED) as eblId from apps_users_mc au) oau';
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0):
            $id = $result->row()->eblId;
        endif;

        $newEsbId = $esbIdOffset + 1;
        if ($id > $esbIdOffset):
            $newEsbId = $id + 1;
        endif;

        return $esbPrefix . $newEsbId;
    }

   
}
