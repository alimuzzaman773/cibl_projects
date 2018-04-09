<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Password_policy_checker_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }

    public function getUnapprovedPolicy() {
        $query = $this->db->select("*")
                ->from("validation_group_mc")
                ->where('mcStatus =', 0)
                ->where('makerActionBy !=', $this->session->userdata('adminUserId'))
                ->order_by('validationGroupId', 'desc')
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function getPolicyById($id) {
        $query = $this->db->select('mk.*,
                ck.validationGroupId as validationGroupId_ck,
                ck.validationGroupName as validationGroupName_ck,
                ck.message as message_ck,
                ck.example as example_ck,
                ck.vgCode as vgCode_ck,
                ck.vCodes as vCodes_ck,
                ck.wrongAttempts as wrongAttempts_ck,
                ck.passHistorySize as passHistorySize_ck,
                ck.passExpiryPeriod as passExpiryPeriod_ck,
                ck.warningPeriod as warningPeriod_ck,
                ck.hibernationPeriod as hibernationPeriod_ck,
                ck.pinExpiryPeriod as pinExpiryPeriod_ck,
                ck.makerAction as makerAction_ck,
                ck.makerActionDt as makerActionDt_ck,
                ck.makerActionTm as makerActionTm_ck,
                ck.checkerActionDt as checkerActionDt_ck,
                ck.checkerActionTm as checkerActionTm_ck,
                ck.checkerActionComment as checkerActionComment_ck
                ')
                ->from('validation_group_mc mk')
                ->where('mk.validationGroupId', $id)
                ->where('mk.mcStatus', 0)
                ->join('validation_group ck', 'ck.validationGroupId = mk.validationGroupId', 'left')
                ->get();
        return $query->row_array();
    }

    public function UpdateInsertCheckerApprove($id, $data) {

        $this->db->where('validationGroupId', $id);
        $this->db->update('validation_group_mc', $data);
        $query = $this->db->get_where('validation_group_mc', array('validationGroupId' => $id));
        $result = $query->row_array();
        $this->db->insert('validation_group', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->session->userdata('adminUserId'),
            'adminUserName' => $this->session->userdata('username'),
            'tableName' => 'validation_group',
            'moduleName' => 'password_policy_module',
            'moduleCode' => '10',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function UpdateUpdateCheckerApprove($id, $data) {
        $this->db->where('validationGroupId', $id);
        $this->db->update('validation_group_mc', $data);

        $query = $this->db->get_where('validation_group_mc', array('validationGroupId' => $id));
        $result = $query->row_array();

        $this->db->where('validationGroupId', $id);
        $this->db->update('validation_group', $result);

        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->session->userdata('adminUserId'),
            'adminUserName' => $this->session->userdata('username'),
            'tableName' => 'validation_group',
            'moduleName' => 'password_policy_module',
            'moduleCode' => '10',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function checkerReject($id, $data) {
        $this->db->where('validationGroupId', $id);
        $this->db->update('validation_group_mc', $data);
    }

}
