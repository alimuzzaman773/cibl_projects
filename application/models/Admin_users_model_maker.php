<?php

class Admin_users_model_maker extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
    }

    public function getAllUsers() {

        $id = $this->session->userdata('adminUserId');

        $this->db->select('admin_users_mc.*, admin_users_group.userGroupName');

        $this->db->where('(admin_users_mc.adminUserId != 1 AND admin_users_mc.adminUserId != ' . $id . ') AND 
                          (admin_users_mc.makerActionBy = ' . $id . ' OR admin_users_mc.mcStatus = 1)');

        $this->db->from('admin_users_mc');
        $this->db->join('admin_users_group', 'admin_users_mc.adminUserGroup = admin_users_group.userGroupId');
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserByName($data) {
        $query = $this->db->get_where('admin_users_mc', array('adminUserName' => $data));
        return $query->result();
    }

    public function checkIfUserExist($id, $data) {
        $adminUserId = $id;
        $adminUserName = $data['adminUserName'];
        $this->db->where("adminUserName = '$adminUserName' AND adminUserId != '$adminUserId'");
        $this->db->from('admin_users_mc');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function getAdminUserById($data) {
        $this->db->select('admin_users_mc.*, admin_users_group.userGroupName');
        $this->db->from('admin_users_mc');
        $this->db->join('admin_users_group', 'admin_users_mc.adminUserGroup = admin_users_group.userGroupId');
        $this->db->where('adminUserId', $data);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function checkUsernamePassword($data) {
        $this->db->select('admin_users.*, admin_users_group.*');
        $this->db->from('admin_users');
        $this->db->join('admin_users_group', 'admin_users.adminUserGroup = admin_users_group.userGroupId');
        $this->db->where('adminUserName', $data);
        $this->db->where('admin_users.isActive', 1);
        $this->db->where('admin_users_group.isActive', 1);
        $query = $this->db->get();
        return $query->num_rows() > 0 ?$query : false;
    }

    public function getUserEmail($username, $email) {
        $data = $this->db->get_where('admin_users', array('adminUserName' => $username, 'email' => $email));
        return $data->row_array();
    }

    public function getUserRoles($data) {
        $this->db->order_by('moduleId', 'asc');
        $this->db->select('module.moduleId, module.moduleName, module.moduleCode, module.moduleDescription,
                           action.actionId, action.actionName, action.actionCode, action.actionDescription,
                           module_action.moduleActionId');

        $this->db->from('module_action');
        $this->db->join('module', 'module.moduleId = module_action.moduleId');
        $this->db->join('action', 'action.actionId = module_action.actionId');

        $this->db->where_in('module_action.moduleActionId', $data);


        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllGroups() {
        $this->db->where('userGroupId !=', 1);
        $this->db->where('isActive =', 1);
        $query = $this->db->get('admin_users_group');
        return $query->result();
    }

    public function insertAdminUserInfo($data) {
        $this->db->insert('admin_users_mc', $data);
    }

    public function updateAdminUserInfo($data, $id) {
        $this->db->where('adminUserId', $id);
        $this->db->update('admin_users_mc', $data);
    }

}
