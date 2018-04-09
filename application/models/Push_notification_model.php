<?php

class Push_notification_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }

    public function getAllMessages() {
        $query = $this->db->get('message');
        return $query->result();
    }

    public function getAllAppsUsersFroPush() {
        $this->db->order_by('skyId', 'asc');
        $this->db->select('apps_users.*, apps_users_group.userGroupName');
        $this->db->or_where('apps_users.mcStatus =', 1);
        $this->db->from('apps_users');
        $this->db->join('apps_users_group', 'apps_users.appsGroupId = apps_users_group.appsGroupId');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllGcmIds($ids) {
        $this->db->select('gcm_users.gcmRegId,device_info.osCode');
        $this->db->select('gcm_users.gcmRegId', 'gcm_users.deviceId');
        $this->db->from('gcm_users');
        $this->db->join('device_info', 'gcm_users.deviceId = device_info.deviceId');
        $this->db->where_in('gcm_users.skyId', $ids);
        $query = $this->db->get();
        return $query->result();
    }

    public function oldgetAllGcmIds($ids) {
        $this->db->select('gcmRegId');
        $this->db->from('gcm_users');
        $this->db->where_in('skyId', $ids);
        $query = $this->db->get();
        return $query->result();
    }

    public function storeMessage($data) {
        $this->db->insert('message', $data);
        $data = $this->db->insert_id();
        return $data;
    }

}
