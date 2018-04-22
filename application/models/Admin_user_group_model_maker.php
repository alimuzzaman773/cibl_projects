<?php

class Admin_user_group_model_maker extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getAllGroups() {
        $this->db->where('userGroupId !=', 1);
        $this->db->where('makerActionBy =', $this->my_session->adminUserId);
        $this->db->or_where('mcStatus =', 1);
        $query = $this->db->get('admin_users_group_mc');
        return $query->result();
    }

    public function getAllModules() {
        $query = $this->db->get('module');
        return $query->result();
    }

    public function getModuleWiseAction($data) {

        $this->db->select('module.moduleId, module.moduleName, module.moduleCode, module.moduleDescription,
                           action.actionId, action.actionName, action.actionCode, action.actionDescription,
                           module_action.moduleActionId');

        $this->db->from('module_action');
        $this->db->join('module', 'module.moduleId = module_action.moduleId');
        $this->db->join('action', 'action.actionId = module_action.actionId');

        $this->db->where_in('module_action.moduleId', $data);


        $query = $this->db->get();
        return $query->result_array();
    }

    public function getModuleIdsByModuleCode($data) {
        $this->db->select('moduleId');
        $this->db->from('module');
        $this->db->where_in('moduleCode', $data);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function checkIfGroupExist($data) {
        $userGroupId = $data['userGroupId'];
        $userGroupName = $data['groupName'];
        $this->db->where("userGroupName = '$userGroupName' AND userGroupId != '$userGroupId'");
        $this->db->from('admin_users_group_mc');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function insertAdminUserGroup($data) {
        $this->db->insert('admin_users_group_mc', $data);
    }

    public function updateAdminUserGroup($data, $id) {
        $this->db->where('userGroupId', $id);
        $this->db->update('admin_users_group_mc', $data);
    }

    public function getUserGroupByName($data) {
        $query = $this->db->get_where('admin_users_group_mc', array('userGroupName' => $data));
        return $query->row_array();
    }

    public function getUserGroupById($data) {
        $query = $this->db->get_where('admin_users_group_mc', array('userGroupId' => $data));
        return $query->row_array();
    }

    function getUGinfo($gpid) {
        $this->db->where("userGroupId", $gpid);
        $result = $this->db->get('admin_users_group');

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function updatePermission($ugid, $permission) {
        $data['permissions'] = $permission;
        $this->db->where("userGroupId", $ugid)
                ->update('admin_users_group', $data);

        if ($this->db->affected_rows()):
            return $ugid;
        else:
            return false;
        endif;
    }

}
