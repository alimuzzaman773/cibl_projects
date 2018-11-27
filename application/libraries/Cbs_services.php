<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cbs_services {

    public function getAccountInfo($data) {
        $requestData = array(
            "account_no" => $data["account_no"],
            "branch_id" => $data["branch_id"]
        );

        $requestInfo = array(
            "url" => "XLinkGet/GetAccountInfoByBranchIdAndAccountNo",
            "file" => "account_information"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    public function getAccountSummary($data) {
        $requestData = array(
            "key" => "7mWnKmQopXVBQEV",
            "pcustomer_id" => $data["customer_id"]
        );

        $requestInfo = array(
            "ip" => true,
            "url" => "http://192.168.0.128:50912/api/UltimusInHouseAPI/GetAccSummaryByCustID",
            "file" => "account_summary"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    public function getCustomerInfo($data) {

        $requestData = array(
            "accNo" => getAccountNo($data["account_no"]),
            "branch_id" => getBranchId($data["account_no"])
        );

        $requestInfo = array(
            "url" => "XLinkGet/GetCustomerInfoByAccountNo",
            "file" => "customer_information"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    public function getAccountStatement($data) {

        $requestData = array(
            "account_no" => $data["account_no"],
            "branch_id" => $data["branch_id"],
            "date_from" => $data["from_date"],
            "date_to" => $data["to_date"]
        );

        $requestInfo = array(
            "url" => "XLinkGet/GetAccountStatementByBranchIdAndAccountNo",
            "file" => "account_statement"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    public function getTransactionHistory($data) {

        $requestData = array(
            "accNo" => $data["account_no"],
            "branchID" => $data["branch_id"],
            "dateFrom" => $data["from_date"],
            "dateTo" => $data["to_date"]
        );

        $requestInfo = array(
            "url" => "XLinkGet/GetTransHistoryByAccountNo",
            "file" => "transaction_history"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    function createFtTransaction($data) {
        $requestData = array(
            "acc_no" => $data["account_no"],
            "branch_id" => $data["branch_id"],
            "amount" => $data["amount"],
            "contra_br_id" => $data["to_branch_id"],
            "contra_ac" => $data["to_account"],
            "remarks" => $data["narration"],
            "dr_cr" => "D"
        );

        $requestInfo = array(
            "url" => "XLinkTransaction/CreateTransaction",
            "file" => "create_transaction"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    function authorizeFtTransaction($data) {
        $requestData = array(
            "pbatchNO" => $data["batch_no"],
            "pbranchID" => $data["branch_id"]
        );

        $requestInfo = array(
            "url" => "XLinkTransaction/AuthorizeTransaction",
            "file" => "authorize_transaction"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

     function reverseTransaction($data) {
        $requestData = array(
            "branch_id" => $data["branch_id"],
            "poriginal_trans_dt" => $data["transaction_date"],
            "poriginal_batch_no" => $data["batch_no"]
        );

        $requestInfo = array(
            "url" => "XLinkTransaction/ReverseTransaction",
            "file" => "reverse_transaction"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }


    private function pushToCbs($requestInfo, $data) {

        if (defined('cbs_data_from_dummy') && cbs_data_from_dummy):
            $json = file_get_contents(APPPATH . "/views/cbs_result/" . $requestInfo["file"] . ".json");

            return array(
                "success" => true,
                "data" => $json
            );
        endif;

        $header = array(
            "stakeHolder_id" => "Admin",
            "user_id" => "admin",
            "password" => "1",
        );


        include_once APPPATH . "libraries/Requests.php";
        Requests::register_autoloader();

        try {
            $cbsUrl = "";
            if (isset($requestInfo["ip"]) && $requestInfo["ip"]) {
                $cbsUrl = "";
            } else {
                $cbsUrl = CBS_URL;
            }
            $url = $cbsUrl . $requestInfo["url"] . "?" . http_build_query($data);
            $request = Requests::get($url, $header);

            return array(
                "success" => $request->success,
                "data" => $request->body
            );
        } catch (Exception $e) {

            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

}
