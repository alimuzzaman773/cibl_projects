<?php

class Utility_bill_model extends CI_Model {

    function getUtilityBillList($params = array()) {

       if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(p.payment_id) as total");
        } else {
            $this->db->select('p.*, bpt.fromAccNo as bpt_from_ac, bpt.amount as bpt_amount, bpt.narration as bpt_narration, bpt.isSuccess as bpt_success,'
                    . 'vt.fromAccNo as vt_from_ac, vt.amount as vt_amount, st.fromAccNo as st_from_ac, st.amount as st_amount,'
                    . 'lt.fromAccNo as lt_from_ac, lt.amount as lt_amount, o1t.fromAccNo as o1t_from_ac, o1t.amount as o1t_amount,'
                    . 'o2t.fromAccNo as o2t_from_ac, o2t.amount as o2t_amount', FALSE);
        }

        $this->db->from("ssl_bill_payment" . " p")
                ->join("apps_transaction" . " bpt", "bpt.transferId = p.bp_transfer_id", "left")
                ->join("apps_transaction" . " vt", "vt.transferId = p.vat_transfer_id", "left")
                ->join("apps_transaction" . " st", "st.transferId = p.stamp_transfer_id", "left")
                ->join("apps_transaction" . " lt", "lt.transferId = p.lpc_transfer_id", "left")
                ->join("apps_transaction" . " o1t", "o1t.transferId = p.other1_transfer_id", "left")
                ->join("apps_transaction" . " o2t", "o2t.transferId = p.other2_transfer_id", "left");
        //->where("p.isSuccess", "Y");

        if (isset($params['payment_id']) && (int) $params['payment_id']):
            $this->db->where("p.payment_id", $params['payment_id']);
        endif;

        if (isset($params['utility']) && trim($params['utility']) != ""):
            $this->db->where("p.utility_name", $params['utility']);
        endif;

        if (isset($params['status']) && trim($params['status']) != ""):
            $this->db->where("p.isSuccess", $params['status']);
        endif;

        if (isset($params['search']) && trim($params['search']) != ""):
            $this->db->group_start()
                    ->or_like("bpt.fromAccNo", $params['search'], 'both')
                    ->or_like("bpt.amount", $params['search'], 'both')
                    ->group_end();
        endif;

        if (isset($params['fromdate']) && isset($params['todate']) && $params['fromdate'] != null && $params['todate'] != null):
            $this->db->where("(DATE(p.created) between " . $this->db->escape($params['fromdate']) . " AND " . $this->db->escape($params['todate']) . ")");
        endif;

        if (isset($params['limit']) && (int) $params['limit'] > 0):
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        endif;

        $result = $this->db->order_by("p.payment_id", "DESC")->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }
    
    function cancelBill($data=array()){
        
    }

    function reverseBill($data = array()) {

        $query = $this->db->select("at.*, ai.accBranchCode")
                ->from("apps_transaction at")
                ->join("account_info ai", "at.fromAccNo=ai.accNo", "inner")
                ->where("transferId", $data["transaction_id"])
                ->get();

        if ($query->num_rows() <= 0) {
            return array(
                "success" => false,
                "msg" => "There are no transaction found."
            );
        }

        $trnData = $query->row();

        $reverseData = array(
            "branch_id" => $trnData->accBranchCode,
            "transaction_date" => $trnData->creationDtTm,
            "batch_no" => $trnData->crossRefNo
        );

        $this->load->library("cbs_services");
        $cbsRes = $this->cbs_services->reverseTransaction($reverseData);

        if (!$cbsRes["success"]):

            $trnErrorData = array(
                "reason" => "",
                "warning" => $cbsRes['msg'],
                "status1" => 0,
                "data" => json_encode($cbsRes)
            );
            $this->updateTransaction($trnErrorData, $data["transaction_id"]);
            return array(
                "success" => false,
                "msg" => "Transaction reversed failed."
            );
        endif;

        //update transaction table with error
        $cbsResObject = json_decode($cbsRes['data'], true);

        if (isset($cbsResObject["ERROR_CODE"]) || isset($cbsResObject["ERROR_CODE"])):
            //update transaction table with error
            $trnErrorData = array(
                "reason" => @$cbsResObject["ERROR_CODE"],
                "warning" => @$cbsResObject["ERROR_MESSAGE"],
                "status1" => 0,
                "data" => json_encode($cbsResObject)
            );
            $this->updateTransaction($trnErrorData, $data["transaction_id"]);
            return array(
                "success" => false,
                "msg" => $trnErrorData['warning']
            );
        endif;

        $successData = array(
            "isSuccess" => "Y",
            "reverseBatch" => $cbsResObject["BATCH_NO"],
            "status1" => 0,
            "isReverse" => 1,
            "data" => json_encode($cbsResObject)
        );
        $this->updateTransaction($successData, $data["transaction_id"]);

        return array(
            "success" => true,
            "transaction_id" => $data["transaction_id"],
            "batch_no" => $cbsResObject["BATCH_NO"]
        );
    }

    function updateTransaction($data, $tranId) {
        try {
            if (!isset($data['updateDtTm'])):
                $data['updateDtTm'] = date("Y-m-d H:i:s");
            endif;

            $this->db->where("transferId", $tranId)
                    ->update("apps_transaction", $data);

            return array(
                'success' => true
            );
        } catch (Exception $e) {
            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

}