<?php

class Transaction_limit_setup_model_checker extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getUnapprovedPackages() {
        $this->db->order_by("appsGroupId", "desc");
        $this->db->where('apps_users_group_mc.mcStatus =', 0);
        //$this->db->where('apps_users_group_mc.makerActionBy !=', $this->my_session->userId);
        $this->db->from('apps_users_group_mc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getUnapprovedPackageById($id) {
        $this->db->select('apps_users_group_mc.*,

                           apps_users_group.appsGroupId as appsGroupId_c,
                           apps_users_group.userGroupName as userGroupName_c,

                           apps_users_group.oatMinTxnLim as oatMinTxnLim_c,
                           apps_users_group.oatMaxTxnLim as oatMaxTxnLim_c,
                           apps_users_group.oatDayTxnLim as oatDayTxnLim_c,
                           apps_users_group.oatNoOfTxn as oatNoOfTxn_c,

                           apps_users_group.pbMinTxnLim as pbMinTxnLim_c,
                           apps_users_group.pbMaxTxnLim as pbMaxTxnLim_c,
                           apps_users_group.pbDayTxnLim as pbDayTxnLim_c,
                           apps_users_group.pbNoOfTxn as pbNoOfTxn_c,


                           apps_users_group.eatMinTxnLim as eatMinTxnLim_c,
                           apps_users_group.eatMaxTxnLim as eatMaxTxnLim_c,
                           apps_users_group.eatDayTxnLim as eatDayTxnLim_c,
                           apps_users_group.eatNoOfTxn as eatNoOfTxn_c,


                           apps_users_group.obtMinTxnLim as obtMinTxnLim_c,
                           apps_users_group.obtMaxTxnLim as obtMaxTxnLim_c,
                           apps_users_group.obtDayTxnLim as obtDayTxnLim_c,
                           apps_users_group.obtNoOfTxn as obtNoOfTxn_c,

                           apps_users_group.mcStatus as mcStatus_c,
                           apps_users_group.makerAction as makerAction_c,
                           apps_users_group.makerActionDt as makerActionDt_c,
                           apps_users_group.makerActionTm as makerActionTm_c,
                           apps_users_group.makerActionBy as makerActionBy_c,
                           apps_users_group.checkerAction as checkerAction_c,
                           apps_users_group.checkerActionComment as checkerActionComment_c,
                           apps_users_group.checkerActionDt as checkerActionDt_c,
                           apps_users_group.checkerActionTm as checkerActionTm_c,
                           apps_users_group.checkerActionBy as checkerActionBy_c,
                           apps_users_group.isPublished as isPublished_c,
                           apps_users_group.isActive as isActive_c');

        $this->db->from('apps_users_group_mc');
        $this->db->join('apps_users_group', 'apps_users_group.appsGroupId = apps_users_group_mc.appsGroupId', 'left');
        $this->db->where('apps_users_group_mc.appsGroupId', $id);
        $this->db->where('apps_users_group_mc.mcStatus', 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function UpdateInsertCheckerApprove($id, $data) {
        $this->db->where('appsGroupId', $id);
        $this->db->update('apps_users_group_mc', $data);

        $query = $this->db->get_where('apps_users_group_mc', array('appsGroupId' => $id));
        $result = $query->row_array();
        $this->db->insert('apps_users_group', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->my_session->userId,
            'adminUserName' => $this->my_session->userName,
            'tableName' => 'apps_users_group',
            'moduleName' => 'limit_package_module',
            'moduleCode' => '06',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function UpdateUpdateCheckerApprove($id, $data) {
        $this->db->where('appsGroupId', $id);
        $this->db->update('apps_users_group_mc', $data);

        $query = $this->db->get_where('apps_users_group_mc', array('appsGroupId' => $id));
        $result = $query->row_array();

        $this->db->where('appsGroupId', $id);
        $this->db->update('apps_users_group', $result);

        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->my_session->userId,
            'adminUserName' =>  $this->my_session->userName,
            'tableName' => 'apps_users_group',
            'moduleName' => 'limit_package_module',
            'moduleCode' => '06',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function checkerReject($id, $data) {
        $this->db->where('appsGroupId', $id);
        $this->db->update('apps_users_group_mc', $data);
    }
}
