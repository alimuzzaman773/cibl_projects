<?php

class Transaction_model extends CI_Model {

    function getAllTransaction($params = array()) {

        if (isset($params['get_count']) && $params['get_count'] == true) {
            $this->db->select("COUNT(at.transferId) as total");
        } else {
            $this->db->select('at.*', FALSE);
        }

        $this->db->from("apps_transaction" . " at");
        
        if (isset($params['transferId']) && (int) $params['transferId']):
            $this->db->where("at.transferId", $params['transferId']);
        endif;

        if (isset($params['limit']) && (int) $params['limit'] > 0):
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        endif;

        $result = $this->db->
                        order_by("at.transferId", "DESC")->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function reverseTransaction($data = array()) {

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
