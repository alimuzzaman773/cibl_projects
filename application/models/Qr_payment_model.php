<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Qr_payment_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getTransactionList($params = array()) {

        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(tn.transferId) as total");
        } else {
            $this->db->select('tn.*, au.clientId, au.userName, qt.*, tn.amount, ad.adminUserName', FALSE);
        }

        $this->db->from("apps_transaction" . " tn")
                ->join("apps_users" . " au", "au.skyId = tn.skyId", "inner")
                ->join("qr_transactions" . " qt", "qt.transferId = tn.transferId", "inner")
                ->join("admin_users" . " ad", "ad.adminUserId = qt.createdBy", "left");

        if (isset($params['transaction_id']) && (int) $params['transaction_id']):
            $this->db->where("tn.transferId", $params['transaction_id']);
        endif;

        if (isset($params['status']) && trim($params['status']) != ""):
            $this->db->where("tn.isSuccess", $params['status']);
        endif;

        if (isset($params['payment_status']) && trim($params['payment_status']) != ""):
            $this->db->where("qt.paymentStatus", $params['payment_status']);
        endif;

        if (isset($params['from_account']) && trim($params['from_account']) != ""):
            $this->db->group_start()
                    ->or_like("tn.fromAccNo", $params['from_account'], 'both')
                    ->group_end();
        endif;

        if (isset($params['batch_number']) && trim($params['batch_number']) != ""):
            $this->db->where("tn.crossRefNo", $params['batch_number']);
        endif;

        if (isset($params['qr_cash_withdrawal']) && trim($params['qr_cash_withdrawal']) != ""):
            $this->db->where("tn.trnType", $params['qr_cash_withdrawal']);
        endif;

        if (isset($params['search']) && trim($params['search']) != ""):
            $this->db->group_start()
                    ->or_like("tn.fromAccNo", $params['search'], 'both')
                    ->or_like("tn.toAccNo", $params['search'], 'both')
                    ->or_like("tn.cfId", $params['search'], 'both')
                    ->or_like("au.userName", $params['search'], 'both')
                    ->group_end();
        endif;

        if (isset($params['fromdate']) && isset($params['todate']) && $params['fromdate'] != null && $params['todate'] != null):
            $this->db->where("(DATE(tn.creationDtTm) between " . $this->db->escape($params['fromdate']) . " AND " . $this->db->escape($params['todate']) . ")");
        endif;

        if (isset($params['limit']) && (int) $params['limit'] > 0):
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        endif;
        
        if(isset($params['merchantId']) && (int) $params['merchantId'] > 0):
            $this->db->where("qt.merchantId", $params['merchantId']);
        endif;

        $result = $this->db->order_by("qt.paymentStatus", "ASC")
                ->order_by("tn.transferId", "ASC")
                ->get();
        //echo $this->db->last_query();
        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function sendTrnRefrence($params = array()) {

        $query = $this->db->select("at.*, au.cfId, au.eblSkyId, au.userMobNo1, au.userEmail", false)
                ->from("apps_transaction at")
                ->join("apps_users au", "at.skyId = au.skyId", "inner")
                ->where("at.transferId", $params["transaction_id"])
                ->where('at.trnType', 'qr_cash_withdrawal')
                ->get();

        if ($query->num_rows() <= 0):
            return array(
                "success" => false,
                "msg" => "There are no transaction information found."
            );
        endif;

        $trnInfo = $query->row();

        $smsData = array(
            "mobileNo" => "88" . ltrim($trnInfo->userMobNo1, "88"),
            "message" => "You transaction reference no {$trnInfo->crossRefNo} for NRBC Planet Account : {$trnInfo->eblSkyId}"
        );

        $this->load->library("sms_service");
        $res = $this->sms_service->smsService($smsData);

        if (!$res["success"]) {
            return array(
                "success" => false,
                "msg" => $res["msg"]
            );
        }
        return array(
            "success" => true,
            "msg" => "Transaction Refrence No sent successfully."
        );
    }

    function confirmPayment($params = array()) {

        $query = $this->db->select("qt.*", false)
                ->from("qr_transactions qt")
                ->where("qt.isSuccess", "Y")
                ->where('qt.paymentStatus', '0')
                ->get();

        if ($query->num_rows() <= 0):
            return array(
                "success" => false,
                "msg" => "There are no transaction information found."
            );
        endif;

        try {
            $this->db->trans_begin();

            $updateInfo = array(
                "paymentStatus" => 1,
                "remarks" => $params["remarks"],
                "createdBy" => $this->my_session->userId,
                "updatedBy" => $this->my_session->userId,
                "updateDtTm" => date("Y-m-d h:i:s")
            );

            $this->db->where("qrtId", $params["payment_id"])
                    ->update("qr_transactions", $updateInfo);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("could not complete transaction in " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            }

            $this->db->trans_commit();

            return array(
                "success" => true,
                "msg" => "Transaction payment successfully completed."
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();

            return array(
                "success" => false,
                "msg" => $e->getMessage(),
                "status" => 500
            );
        }
    }

}
