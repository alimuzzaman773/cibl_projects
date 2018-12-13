<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Au_model extends CI_Model {

    function __construct() {
        parent::__construct();        
    }

    public function getDefaultGroup($data) {
        $this->db->from('apps_users_group');
        $this->db->where('appsGroupId', $data);
        $query = $this->db->get();
        $row = $query->row_array();
        return $row;
    }

    public function getAppsUser($data) {
        $query = $this->db->get_where('apps_users', $data);
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
        $query = $this->db->get_where('apps_users', array('skyId' => $data));
        return $query->row_array();
    }

    public function getGroupById($data) {
        $query = $this->db->get_where('apps_users_group', array('appsGroupId' => $data));
        return $query->row_array();
    }

    public function cfIdCheck($data) {
        $query = $this->db->get_where('apps_users', array('cfId' => $data));
        return $query->row_array();
    }

    public function clientIdCheck($data) {
        $query = $this->db->get_where('apps_users', array('clientId' => $data));
        return $query->row_array();
    }

    public function clientIdCheckEdit($data) {

        $clientId = $data['clientId'];
        $skyId = $data['skyId'];

        $this->db->where("clientId = {$this->db->escape($clientId)} AND skyId != {$this->db->escape($skyId)}");
        $query = $this->db->get('apps_users');
        return $query->result_array();
    }
    
    public function getAppsUserGroup($p = array()) {
        $this->db->select('*',false);
        $this->db->from('apps_users_group');
        $this->db->order_by("userGroupName", "ASC");
        
        if(isset($p['isActive'])):
            $this->db->where("isActive", $p['isActive']);
        endif;
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function setAppsUserGroup($skyId, $groupId)
    {
        try {
            $groupData = array(
                "appsGroupId" => $groupId
            );

            $appsUserGroup = $this->db->where("appsGroupId", $groupId)
                                      ->get("apps_users_group");

            if ($appsUserGroup->num_rows() <= 0):
                return array(
                    'success' => false,
                    'msg' => "Package limit information not found"
                );
            endif;    
            
            $appsUserGroup = $appsUserGroup->row();

            $grpSuffix = array("oat", "pb", "eat", "obt");
            $groupArray = array(
                "MinTxnLim", "MaxTxnLim", "DayTxnLim", "NoOfTxn", /* "LastDtTm" */
            );

            $groupFields = array();
            foreach ($grpSuffix as $sf):
                foreach ($groupArray as $gp):
                    $gname = $sf . $gp;
                    $groupFields[$gname] = $gname;
                endforeach;
            endforeach;

            foreach ($groupFields as $k => $v):
                $groupData[$v] = $appsUserGroup->{$v};
            endforeach;
            //d($groupData);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users_mc", array("appsGroupId" => $appsUserGroup->appsGroupId));
            
            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users", $groupData);
            
            return array(
                'success' => true
            );
            
        } catch (Exception $ex) {
            return array(
                'success' => false,
                'msg' => $ex->getMessage()
            );
        }
    }

}
