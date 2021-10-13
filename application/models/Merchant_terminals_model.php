<?php

class Merchant_terminals_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getUnapprovedMerchantTerminals(){
    	$this->db->select('mt.*,ma.merchantName')
    			->where('mt.mcStatus =', 0)
    			->from(TBL_MERCHANT_TERMINALS_MC." mt")
                        ->join(TBL_MERCHANT_ACCOUNTS . ' ma', 'mt.merchantId = ma.merchantId', 'left')
    			->order_by('mt.makerActionDt', 'DESC')
    			->order_by('mt.makerActionTm', 'DESC');
    	$query = $this->db->get();
    	return $query->result();
    }

    function getTerminalById($id){
        $this->db->select('m.*,ma.merchantName');
        $this->db->from(TBL_MERCHANT_TERMINALS_MC." m");
        $this->db->join(TBL_MERCHANT_ACCOUNTS . ' ma', 'm.merchantId = ma.merchantId', 'left');
        $this->db->where('m.terminalId =', $id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row_array() : false;
    }

    function getMerchantTerminalById($id){
    	$this->db->select('m.*,
    		c.terminalId as terminalId_c,
                c.merchantId as merchantId_c,
    		c.terminalName as terminalName_c,
    		c.address as address_c,
    		c.city as city_c,
    		c.district as district_c,
    		c.zip as zip_c,
    		c.accountNo as accountNo_c,
                c.currency as currency_c,
    		c.mcStatus as mcStatus_c,
			c.makerAction as makerAction_c,
			c.makerActionDt as makerActionDt_c,
			c.makerActionTm as makerActionTm_c,
			c.makerActionBy as makerActioBy_c,
			c.checkerActionComment as checkerActionComment_c,
			c.checkerActionDt as checkerActionDt_c,
			c.checkerActionTm as checkerActionTm_c,
			c.checkerActionBy as checkerActionBy_c,
                        ma.merchantName'
    	);
    	$this->db->from(TBL_MERCHANT_TERMINALS_MC . ' m');
    	$this->db->join(TBL_MERCHANT_TERMINALS . ' c', 'c.terminalId = m.terminalId', 'left');
        $this->db->join(TBL_MERCHANT_ACCOUNTS . ' ma', 'm.merchantId = ma.merchantId', 'left');
    	$this->db->where('m.terminalId', $id);
    	$this->db->where('m.mcStatus', 0);
    	$query = $this->db->get();
    	return $query->num_rows() > 0 ? $query->row_array() : false;;
    }

    function updateInsertCheckerApprove($id, $data) {
    	$this->db->where('terminalId', $id);
    	$this->db->update('merchant_terminals_mc', $data);
    	$this->db->select('*');
    	$this->db->from(TBL_MERCHANT_TERMINALS_MC);
    	$this->db->where('terminalId', $id);
    	$query = $this->db->get();

    	$tableData = $query->row_array();
    	$this->db->insert(TBL_MERCHANT_TERMINALS, $tableData);
    }

    function updateUpdateCheckerApprove($id, $data) {
    	$this->db->where('terminalId', $id);
    	$this->db->update(TBL_MERCHANT_TERMINALS_MC, $data);

    	$query = $this->db->get_where(TBL_MERCHANT_TERMINALS_MC, array('terminalId' => $id));
        $result = $query->row_array();

        $this->db->where('terminalId', $id);
        $this->db->update(TBL_MERCHANT_TERMINALS, $result);
    }

    function checkerReject($id, $data) {
        $this->db->where('terminalId', $id);
        $this->db->update(TBL_MERCHANT_TERMINALS_MC, $data);
    }
    
    function getMerchantTerminals()
    {
        $this->db->select('*')
    			->from(TBL_MERCHANT_TERMINALS);
    	$query = $this->db->get();
    	return $query->num_rows() > 0 ? $query : false;
    }
}