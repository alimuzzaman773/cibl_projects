<?php

class Ft_model extends CI_Model {

    function fund_transfer($data = array()) {
        $uAccount = $this->db->select("ac.*, au.cfId, au.eblSkyId", false)
                ->from("account_info ac")
                ->join("apps_users au", "ac.skyId = au.skyId", "inner")
                ->where("ac.skyId", $data['skyId'])
                ->where("ac.accountInfoID", $data['fromAcc'])
                ->get();

        if ($uAccount->num_rows() <= 0):
            return array(
                "success" => false,
                "msg" => "No user account founds"
            );
        endif;

        $accountInfo = $uAccount->row();

        $aParams = array(
            "account_no" => $accountInfo->accNo,
            "branch_id" => $accountInfo->accBranchCode,
            "to_account" => getAccountNo($data["toAcc"]),
            "to_branch_id" => getBranchId($data["toAcc"]),
            "amount" => (float) $data["amount"],
            "narration" => $data["narration"]
        );

        //create transaction in our table - return id
        $trnData = array(
            "cfId" => $accountInfo->cfId,
            "eblSkyId" => $accountInfo->eblSkyId,
            "fromAccNo" => $aParams['account_no'],
            "toAccNo" => $aParams['to_account'],
            "amount" => $aParams['amount'],
            "narration" => $aParams['narration'],
            "trnType" => '',
            "isSuccess" => "N",
            "createdBy" => $data["skyId"],
            "status1" => 1,
            "source" => "mbanking"
        );

        $this->load->model("ft_model");
        $tranId = $this->createTransaction($trnData);
        if (!$tranId['success']):
            return array(
                "success" => false,
                "msg" => "transaction could not be created at our end"
            );
        endif;

        $tranId = $tranId['id'];

        $aParams['narration'] = $aParams['narration'] . " :: " . $tranId;

        $this->load->library("cbs_services");
        $cbsRes = $this->cbs_services->createFtTransaction($aParams);

        if (!$cbsRes["success"]):

            $trnErrorData = array(
                "reason" => "",
                "warning" => $cbsRes['msg'],
                "status1" => 0,
                "data" => json_encode($cbsRes)
            );
            $this->ft_model->updateTransaction($trnErrorData, $tranId);
            return array(
                "success" => false,
                "msg" => "Account number not found"
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
            $this->ft_model->updateTransaction($trnErrorData, $tranId);
            return array(
                "success" => false,
                "msg" => $trnErrorData['warning'],
            );
        endif;

        $authRequest = array(
            "batch_no" => $cbsResObject["BATCH_NO"],
            "branch_id" => $accountInfo->accBranchCode
        );

        $authRes = $this->cbs_services->authorizeFtTransaction($authRequest);

        if (!$authRes["success"]):

            $trnErrorData = array(
                "reason" => "",
                "warning" => $authRes['msg'],
                "status1" => 0,
                "data" => json_encode($authRes)
            );
            $this->ft_model->updateTransaction($trnErrorData, $tranId);
            return array(
                "success" => false,
                "msg" => "Transaction authorization failed"
            );
        endif;

        //update transaction table with error
        $authResObject = json_decode($authRes['data'], true);
        if (isset($authResObject["ERROR_CODE"]) || isset($authResObject["ERROR_MESSAGE"])):
            //update transaction table with error
            $trnErrorData = array(
                "reason" => @$authResObject["ERROR_CODE"],
                "warning" => @$authResObject["ERROR_MESSAGE"],
                "status1" => 0,
                "data" => json_encode($authResObject)
            );
            $this->ft_model->updateTransaction($trnErrorData, $tranId);
            return array(
                "success" => false,
                "msg" => $trnErrorData['warning']
            );
        endif;

        $trnErrorData = array(
            "isSuccess" => "Y",
            "crossRefNo" => $cbsResObject["BATCH_NO"],
            "status1" => 0,
            "data" => json_encode($authResObject)
        );
        $this->ft_model->updateTransaction($trnErrorData, $tranId);

        return array(
            "success" => true,
            "transaction_id" => $tranId,
            "batch_no" => $cbsResObject["BATCH_NO"]
        );
    }

    function createTransaction($data) {
        try {
            if (!isset($data['creationDtTm'])):
                $data['creationDtTm'] = date("Y-m-d H:i:s");
            endif;

            $this->db->insert("apps_transaction", $data);

            return array(
                'success' => true,
                'id' => $this->db->insert_id()
            );
        } catch (Exception $e) {
            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
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
