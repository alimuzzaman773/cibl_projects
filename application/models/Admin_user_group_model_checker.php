<?php

class Admin_user_group_model_checker extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
    }

    public function getUnapprovedGroups() {
        $this->db->order_by('userGroupId', 'desc');
        $this->db->where('mcStatus =', 0);
        $this->db->where('userGroupId !=', 1); // not showing super admin
        $this->db->where('makerActionBy !=', $this->session->userdata('adminUserId'));
        $this->db->select('admin_users_group_mc.*');
        $query = $this->db->get('admin_users_group_mc');
        return $query->result();
    }

    public function getModuleActions() {
        $this->db->order_by('moduleId', 'asc');
        $this->db->select('module.moduleId, module.moduleName, module.moduleCode, module.moduleDescription,
                           action.actionId, action.actionName, action.actionCode, action.actionDescription,
                           module_action.moduleActionId');

        $this->db->from('module_action');
        $this->db->join('module', 'module.moduleId = module_action.moduleId');
        $this->db->join('action', 'action.actionId = module_action.actionId');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllModules() {
        $this->db->select('moduleId');
        $query = $this->db->get('module');
        $array = array();

        foreach ($query->result_array() as $row) {
            $array[] = $row['moduleId'];
        }
        return $array;
    }

    public function getGroupById($id) {
        $this->db->select('admin_users_group_mc.*,'
                . 'admin_users_group.userGroupId as userGroupId_c, '
                . 'admin_users_group.userGroupName as userGroupName_c, '
                . 'admin_users_group.moduleActionId as moduleActionId_c, '
                . 'admin_users_group.moduleCodes as moduleCodes_c, '
                . 'admin_users_group.actionCodes as actionCodes_c, '
                . 'admin_users_group.authorizationModules as authorizationModules_c, '
                . 'admin_users_group.contentSetupModules as contentSetupModules_c, '
                . 'admin_users_group.serviceRequestModules as serviceRequestModules_c, '
                . 'admin_users_group.reportTypeModules as reportTypeModules_c, '
                . 'admin_users_group.mcStatus as mcStatus_c, '
                . 'admin_users_group.makerAction as makerAction_c, '
                . 'admin_users_group.makerActionDt as makerActionDt_c,'
                . 'admin_users_group.makerActionTm as makerActionTm_c,'
                . 'admin_users_group.makerActionBy as makerActionBy_c,'
                . 'admin_users_group.checkerAction as checkerAction_c,'
                . 'admin_users_group.checkerActionComment as checkerActionComment_c,'
                . 'admin_users_group.checkerActionDt as checkerActionDt_c,'
                . 'admin_users_group.checkerActionTm as checkerActionTm_c, '
                . 'admin_users_group.checkerActionBy as checkerActionBy_c, '
                . 'admin_users_group.isLocked as isLocked_c, '
                . 'admin_users_group.isPublished as isPublished_c, '
                . 'admin_users_group.creationDtTm as creationDtTm_c, '
                . 'admin_users_group.updateDtTm as updateDtTm_c, '
                . 'admin_users_group.createdBy as createdBy_c, '
                . 'admin_users_group.updatedBy as updatedBy_c, '
                . 'admin_users_group.isActive as isActive_c');
        $this->db->from('admin_users_group_mc');
        $this->db->join('admin_users_group', 'admin_users_group.userGroupId = admin_users_group_mc.userGroupId', 'left');
        $this->db->where('admin_users_group_mc.userGroupId', $id);
        $this->db->where('admin_users_group_mc.mcStatus', 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function checkerReject($id, $data) {
        $this->db->where('userGroupId', $id);
        $this->db->update('admin_users_group_mc', $data);
    }

    public function UpdateInsertCheckerApprove($id, $data) {
        $this->db->where('userGroupId', $id);
        $this->db->update('admin_users_group_mc', $data);
        $query = $this->db->get_where('admin_users_group_mc', array('userGroupId' => $id));
        $result = $query->row_array();
        $this->db->insert('admin_users_group', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->session->userdata('adminUserId'),
            'adminUserName' => $this->session->userdata('username'),
            'tableName' => 'admin_users_group',
            'moduleName' => 'admin_user_group_module',
            'moduleCode' => '05',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function UpdateUpdateCheckerApprove($id, $data) {
        $this->db->where('userGroupId', $id);
        $this->db->update('admin_users_group_mc', $data);

        $query = $this->db->get_where('admin_users_group_mc', array('userGroupId' => $id));
        $result = $query->row_array();

        $this->db->where('userGroupId', $id);
        $this->db->update('admin_users_group', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->session->userdata('adminUserId'),
            'adminUserName' => $this->session->userdata('username'),
            'tableName' => 'admin_users_group',
            'moduleName' => 'admin_user_group_module',
            'moduleCode' => '05',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

}
