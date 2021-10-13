<?php

class Merchant_accounts_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getUnapprovedMerchantAccounts(){
    	$this->db->select('*')
    			->where('mcStatus =', 0)
    			->from(TBL_MERCHANT_ACCOUNTS_MC)
    			->order_by('makerActionDt', 'DESC')
    			->order_by('makerActionTm', 'DESC');
    	$query = $this->db->get();
    	return $query->result();
    }

    function getMerchantById($id){
        $this->db->select('*');
        $this->db->from(TBL_MERCHANT_ACCOUNTS);
        $this->db->where('merchantId =', $id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row_array() : false;
    }

    function getMerchantAccountById($id){
    	$this->db->select('m.*,
    		c.merchantId as merchantId_c,
    		c.merchantCode as merchantCode_c,
    		c.merchantName as merchantName_c,
    		c.merchantAccountNo as merchantAccountNo_c,
    		c.merchantAddress as merchantAddress_c,
    		c.merchantPhone as merchantPhone_c,
    		c.merchantWebsite as merchantWebsite_c,
    		c.merchantLogo as merchantLogo_c,
    		c.mcStatus as mcStatus_c,
			c.makerAction as makerAction_c,
			c.makerActionDt as makerActionDt_c,
			c.makerActionTm as makerActionTm_c,
			c.makerActionBy as makerActioBy_c,
			c.checkerActionComment as checkerActionComment_c,
			c.checkerActionDt as checkerActionDt_c,
			c.checkerActionTm as checkerActionTm_c,
			c.checkerActionBy as checkerActionBy_c'
    	);
    	$this->db->from(TBL_MERCHANT_ACCOUNTS_MC . ' m');
    	$this->db->join(TBL_MERCHANT_ACCOUNTS . ' c', 'c.merchantId = m.merchantId', 'left');
    	$this->db->where('m.merchantId', $id);
    	$this->db->where('m.mcStatus', 0);
    	$query = $this->db->get();
    	return $query->num_rows() > 0 ? $query->row_array() : false;;
    }

    function updateInsertCheckerApprove($id, $data) {
    	$this->db->where('merchantId', $id);
    	$this->db->update('merchant_accounts_mc', $data);
    	$this->db->select('*');
    	$this->db->from(TBL_MERCHANT_ACCOUNTS_MC);
    	$this->db->where('merchantId', $id);
    	$query = $this->db->get();

    	$tableData = $query->row_array();
    	$this->db->insert(TBL_MERCHANT_ACCOUNTS, $tableData);
    }

    function updateUpdateCheckerApprove($id, $data) {
    	$this->db->where('merchantId', $id);
    	$this->db->update(TBL_MERCHANT_ACCOUNTS_MC, $data);

    	$query = $this->db->get_where(TBL_MERCHANT_ACCOUNTS_MC, array('merchantId' => $id));
        $result = $query->row_array();

        $this->db->where('merchantId', $id);
        $this->db->update(TBL_MERCHANT_ACCOUNTS, $result);
    }

    function checkerReject($id, $data) {
        $this->db->where('merchantId', $id);
        $this->db->update(TBL_MERCHANT_ACCOUNTS_MC, $data);
    }
    
    function getMerchantAccounts()
    {
        $this->db->select('*')
    			->from(TBL_MERCHANT_ACCOUNTS);
    	$query = $this->db->get();
    	return $query->num_rows() > 0 ? $query : false;
    }
}