<?php

class Admin_users_model_checker extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }

    public function getUnapprovedUsers() {
        $this->db->order_by("adminUserId", "desc");
        $this->db->where('admin_users_mc.mcStatus =', 0);
        $this->db->where('adminUserId !=', 1);
        $this->db->where('admin_users_mc.makerActionBy !=', $this->session->userdata('adminUserId'));
        $this->db->select('admin_users_mc.*, admin_users_group.userGroupName');
        $this->db->from('admin_users_mc');
        $this->db->join('admin_users_group', 'admin_users_mc.adminUserGroup = admin_users_group.userGroupId');
        $query = $this->db->get();
        return $query->result();
    }

    public function getUserById($id) {
        $this->db->select('admin_users_mc.*,'
                . 'AdminUserGroupForM.userGroupName as mainAdminUserGroupName, AdminUserGroupForMC.userGroupName as mcAdminUserGroupName,'
                . 'admin_users.fullName as fullName_c, '
                . 'admin_users.adminUserId as adminUserId_c, '
                . 'admin_users.adminUserName as adminUserName_c, '
                . 'admin_users.email as email_c, '
                . 'admin_users.dob as dob_c, '
                . 'admin_users.mcStatus as mcStatus_c, '
                . 'admin_users.makerAction as makerAction_c, '
                . 'admin_users.makerActionDt as makerActionDt_c,'
                . 'admin_users.makerActionTm as makerActionTm_c,'
                . 'admin_users.makerActionBy as makerActionBy_c,'
                . 'admin_users.checkerAction as checkerAction_c,'
                . 'admin_users.checkerActionComment as checkerActionComment_c,'
                . 'admin_users.checkerActionDt as checkerActionDt_c,'
                . 'admin_users.checkerActionTm as checkerActionTm_c, '
                . 'admin_users.checkerActionBy as checkerActionBy_c, '
                . 'admin_users.isLocked as isLocked_c, '
                . 'admin_users.isPublished as isPublished_c, '
                . 'admin_users.isActive as isActive_c');
        $this->db->from('admin_users_mc');

        $this->db->join('admin_users', 'admin_users.adminUserId = admin_users_mc.adminUserId', 'left');

        $this->db->join('admin_users_group as AdminUserGroupForMC', 'AdminUserGroupForMC.userGroupId = admin_users_mc.adminUserGroup', 'left');
        $this->db->join('admin_users_group as AdminUserGroupForM', 'AdminUserGroupForM.userGroupId = admin_users.adminUserGroup', 'left');

        $this->db->where('admin_users_mc.adminUserId', $id);
        $this->db->where('admin_users_mc.mcStatus', 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function UpdateInsertCheckerApprove($id, $data) {
        $this->db->where('adminUserId', $id);
        $this->db->update('admin_users_mc', $data);
        $query = $this->db->get_where('admin_users_mc', array('adminUserId' => $id));
        $result = $query->row_array();
        $this->db->insert('admin_users', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->session->userdata('adminUserId'),
            'adminUserName' => $this->session->userdata('username'),
            'tableName' => 'admin_users',
            'moduleName' => 'admin_user_module',
            'moduleCode' => '04',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function UpdateUpdateCheckerApprove($id, $data) {
        $this->db->where('adminUserId', $id);
        $this->db->update('admin_users_mc', $data);

        $query = $this->db->get_where('admin_users_mc', array('adminUserId' => $id));
        $result = $query->row_array();

        $this->db->where('adminUserId', $id);
        $this->db->update('admin_users', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->session->userdata('adminUserId'),
            'adminUserName' => $this->session->userdata('username'),
            'tableName' => 'admin_users',
            'moduleName' => 'admin_user_module',
            'moduleCode' => '04',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function checkerReject($id, $data) {
        $this->db->where('adminUserId', $id);
        $this->db->update('admin_users_mc', $data);
    }

}
