<?php

class Transaction_limit_setup_model_maker extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAllPackages() {
        $this->db->where('makerActionBy', $this->my_session->userId);
        $this->db->or_where('mcStatus', 1);
        $this->db->order_by("userGroupName", "ASC");
        $query = $this->db->get('apps_users_group_mc');
        return $query->result();
    }

    public function getGroupByName($data) {
        $query = $this->db->get_where('apps_users_group_mc', array('userGroupName' => $data));
        return $query->result();
    }

    public function checkIfGroupExist($data) {
        $appsGroupId = $data['appsGroupId'];
        $userGroupName = $data['userGroupName'];
        $this->db->where("userGroupName = '$userGroupName' AND appsGroupId != '$appsGroupId'");
        $this->db->from('apps_users_group_mc');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function insertUserGroupInfo($data) {
        $this->db->insert('apps_users_group_mc', $data);
        $data = $this->db->insert_id();
        return $data;
    }

    public function getGroupById($data) {
        $query = $this->db->get_where('apps_users_group_mc', array('appsGroupId' => $data));
        return $query->row_array();
    }

    public function updateUserGroupInfo($data, $id) {
        $this->db->where('appsGroupId', $id);
        $this->db->update('apps_users_group_mc', $data);
    }

}
