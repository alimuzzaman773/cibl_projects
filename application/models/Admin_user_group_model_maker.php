<?php

class Admin_user_group_model_maker extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getAllGroups() {
        $this->db->where('userGroupId !=', 1);
        $this->db->where('makerActionBy =', $this->my_session->userId);
        $this->db->or_where('mcStatus =', 1);
        $query = $this->db->get(TBL_ADMIN_USERS_GROUP_MC);
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

    public function getAllAdminGroup($params = array()) {

        if (isset($params['get_count']) && $params['get_count'] == true) {
            $this->db->select("COUNT(userGroupId) as total");
        } else {
            $this->db->select('*', FALSE);
        }

        $this->db->from('admin_users_group_mc');

        if (isset($params['group_name']) && trim($params['group_name']) != "") {
            $this->db->like("userGroupName", $params['group_name'], "both");
        }

        if (isset($params['lock_status']) && (int) $params['lock_status'] >= 1) {
            $this->db->where("isLocked", $params['lock_status']);
        }

        if (isset($params['limit']) && (int) $params['limit'] > 0) {
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        }
        $this->db->order_by("userGroupId", "desc");

        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query : false;
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
        $this->db->from(TBL_ADMIN_USERS_GROUP_MC);
        $count = $this->db->count_all_results();
        return $count;
    }

    public function insertAdminUserGroup($data) {
        $this->db->insert(TBL_ADMIN_USERS_GROUP_MC, $data);
    }

    public function updateAdminUserGroup($data, $id) {
        $this->db->where('userGroupId', $id);
        $this->db->update(TBL_ADMIN_USERS_GROUP_MC, $data);
    }

    public function getUserGroupByName($data) {
        $query = $this->db->get_where(TBL_ADMIN_USERS_GROUP_MC, array('userGroupName' => $data));
        return $query->row_array();
    }

    public function getUserGroupById($data) {
        $query = $this->db->get_where(TBL_ADMIN_USERS_GROUP_MC, array('userGroupId' => $data));
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

    function getUGinfoMC($gpid) {
        $this->db->where("userGroupId", $gpid);
        $result = $this->db->get(TBL_ADMIN_USERS_GROUP_MC);

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function updatePermission($ugid, $permission) {
        $data['permissions'] = $permission;
        $data['mcStatus'] = 0;
        $this->db->where("userGroupId", $ugid)
                ->update(TBL_ADMIN_USERS_GROUP_MC, $data);
        return true;
    }

}
