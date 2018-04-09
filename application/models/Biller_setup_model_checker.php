<?php

class Biller_setup_model_checker extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }

    public function getUnapprovedBillers() {
        $this->db->order_by("billerId", "desc");
        $this->db->where('biller_setup_mc.mcStatus =', 0);
        $this->db->where('biller_setup_mc.makerActionBy !=', $this->session->userdata('adminUserId'));
        $this->db->select('biller_setup_mc.*, bill_type.billTypeName');
        $this->db->from('biller_setup_mc');
        $this->db->join('bill_type', 'biller_setup_mc.billTypeCode = bill_type.billTypeCode');
        $query = $this->db->get();
        return $query->result();
    }

    public function getBillerById($id) {
        $this->db->select('biller_setup_mc.*,'
                . 'billTypeNameForM.billTypeName as mainBillTypeName, billTypeNameForMC.billTypeName as mcBillTypeName,'
                . 'biller_setup.billerId as billerId_c,'
                . 'biller_setup.billerName as billerName_c, '
                . 'biller_setup.cfId as cfId_c, '
                . 'biller_setup.billerCode as billerCode_c, '
                . 'biller_setup.billerOrder as billerOrder_c, '
                . 'biller_setup.suggestion as suggestion_c, '
                . 'biller_setup.amountRegex as amountRegex_c, '
                . 'biller_setup.amountMatch as amountMatch_c, '
                . 'biller_setup.amountMessage as amountMessage_c, '
                . 'biller_setup.referenceRegex as referenceRegex_c, '
                . 'biller_setup.referenceMatch as referenceMatch_c, '
                . 'biller_setup.referenceMessage as referenceMessage_c, '
                . 'biller_setup.billTypeCode as billTypeCode_c, '
                . 'biller_setup.mcStatus as mcStatus_c, '
                . 'biller_setup.makerAction as makerAction_c, '
                . 'biller_setup.makerActionDt as makerActionDt_c,'
                . 'biller_setup.makerActionTm as makerActionTm_c,'
                . 'biller_setup.makerActionBy as makerActionBy_c,'
                . 'biller_setup.checkerAction as checkerAction_c,'
                . 'biller_setup.checkerActionComment as checkerActionComment_c,'
                . 'biller_setup.checkerActionDt as checkerActionDt_c,'
                . 'biller_setup.checkerActionTm as checkerActionTm_c, '
                . 'biller_setup.checkerActionBy as checkerActionBy_c, '
                . 'biller_setup.isPublished as isPublished_c, '
                . 'biller_setup.isActive as isActive_c');
        $this->db->from('biller_setup_mc');

        $this->db->join('biller_setup', 'biller_setup.billerId = biller_setup_mc.billerId', 'left');

        $this->db->join('bill_type as billTypeNameForMC', 'billTypeNameForMC.billTypeCode = biller_setup_mc.billTypeCode', 'left');
        $this->db->join('bill_type as billTypeNameForM', 'billTypeNameForM.billTypeCode = biller_setup.billTypeCode', 'left');

        $this->db->where('biller_setup_mc.billerId', $id);
        $this->db->where('biller_setup_mc.mcStatus', 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function UpdateInsertCheckerApprove($id, $data) {
        $this->db->where('billerId', $id);
        $this->db->update('biller_setup_mc', $data);
        $query = $this->db->get_where('biller_setup_mc', array('billerId' => $id));
        $result = $query->row_array();
        $this->db->insert('biller_setup', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->session->userdata('adminUserId'),
            'adminUserName' => $this->session->userdata('username'),
            'tableName' => 'biller_setup',
            'moduleName' => 'biller_setup_module',
            'moduleCode' => '08',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function UpdateUpdateCheckerApprove($id, $data) {
        $this->db->where('billerId', $id);
        $this->db->update('biller_setup_mc', $data);

        $query = $this->db->get_where('biller_setup_mc', array('billerId' => $id));
        $result = $query->row_array();

        $this->db->where('billerId', $id);
        $this->db->update('biller_setup', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
            'adminUserId' => $this->session->userdata('adminUserId'),
            'adminUserName' => $this->session->userdata('username'),
            'tableName' => 'biller_setup',
            'moduleName' => 'biller_setup_module',
            'moduleCode' => '08',
            'actionCode' => $result['makerActionCode'],
            'actionName' => $result['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function checkerReject($id, $data) {
        $this->db->where('billerId', $id);
        $this->db->update('biller_setup_mc', $data);
    }

}
