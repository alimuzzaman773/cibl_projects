<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cbs_services {
    /*
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
      } */

    function getAccountInfo($data) {
        $requestData = array(
            "key" => "7mWnKmQopXVBQEV",
            "paccno" => $data["account_no"],
            "pbranch_id" => $data["branch_id"]
        );

        $requestInfo = array(
            "ip" => true,
            "url" => INHOUSE_CBS_URL . "UltimusInHouseAPIControllerStatment/GetAccountDetails",
            "file" => "account_information",
            "method" => "GetAccountDetails"
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
            "url" => INHOUSE_CBS_URL . "UltimusInHouseAPI/GetAccSummaryByCustID",
            "file" => "account_summary",
            "method" => "GetAccSummaryByCustID"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    public function getCustomerInfo($data) {

        $requestData = array(
            "accNo" => $data["account_no"],
            "branch_id" => $data["branch_id"]
        );

        $requestInfo = array(
            "url" => "XLinkGet/GetCustomerInfoByAccountNo",
            "file" => "customer_information",
            "method" => "GetCustomerInfoByAccountNo"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    public function getAccountStatement($data) {
        $requestData = array(
            "key" => "7mWnKmQopXVBQEV",
            "pbranch_id" => $data["branch_id"],
            "paccno" => $data["account_no"],
            "pdate_from" => $data["from_date"],
            "pdate_to" => $data["to_date"]
        );

        $requestInfo = array(
            "ip" => true,
            "url" => INHOUSE_CBS_URL . "UltimusInHouseAPIControllerStatment/GetAccountStatementHistoryID",
            "file" => "transaction_list",
            "method" => "GetAccountStatementHistoryID"
        );
        return $this->pushToCbs($requestInfo, $requestData);

        /*
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
         */
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
            "file" => "transaction_history",
            "method" => "GetTransHistoryByAccountNo"
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
            "file" => "create_transaction",
            "method" => "CreateTransaction"
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
            "file" => "authorize_transaction",
            "method" => "AuthorizeTransaction"
        );
        //return $this->pushToCbs($requestInfo, $requestData);
        //without authorized transaction 
        $result = array(
            "ISSUCCESS" => "true",
            "MESSAGE" => "Authorized successfully."
        );
        return array(
            "success" => true,
            "data" => json_encode($result)
        );
    }

    public function getAccountBalance($data) {
        $requestData = array(
            "account_no" => $data["account_no"],
            "branch_id" => $data["branch_id"]
        );

        $requestInfo = array(
            "url" => "XLinkGet/GetAccountBalanceInfoByBranchIdAndAccountNo",
            "file" => "account_balance",
            "method" => "GetAccountBalanceInfoByBranchIdAndAccountNo"
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
            "file" => "reverse_transaction",
            "method" => "ReverseTransaction"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    public function getCurrencyRate($data) {
        $requestData = array(
            "currencyId" => $data["currency_id"],
            "branchid" => $data["branch_id"]
        );

        $requestInfo = array(
            "url" => "XLinkGet/GetExchangeRate",
            "file" => "currency_rate",
            "method" => "GetExchangeRate"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    function fcTransaction($data) {
        $requestData = array(
            "from_acc" => $data["from_account"],
            "branch_id" => $data["branch_id"],
            "amount_ccy" => $data["amount"],
            "contra_br_id" => $data["to_branch_id"],
            "to_acc" => $data["to_account"],
            "narration" => $data["narration"]
        );

        $requestInfo = array(
            "url" => "XLinkTransaction/CreateFCTransaction",
            "file" => "create_transaction",
            "method" => "CreateFCTransaction"
        );
        return $this->pushToCbs($requestInfo, $requestData);
    }

    /*
      public function getTransactionHistory($data) {

      $requestInfo = array(
      "url" => "GetAccountStatementByBranchIdAndAccountNo",
      "file" => "transaction_history"
      );
      return $this->pushToCbs($requestInfo, $data);
      }

      public function getCustomerInfo($data) {

      $requestInfo = array(
      "url" => "GetCustomerInfoByAccountNo",
      "file" => "customer_information"
      );
      return $this->pushToCbs($requestInfo, $data);
      }

      public function getFdrAccountInfo($data) {

      $requestInfo = array(
      "url" => "GetFDRAccountInfoStatementByBranchIdAndAccountNo",
      "file" => "fdr_account_info"
      );
      return $this->pushToCbs($requestInfo, $data);
      }
     */

    private function pushToCbs($requestInfo, $data) {
        $ci = & get_instance();

        if (defined('cbs_data_from_dummy') && cbs_data_from_dummy):
            $json = file_get_contents(APPPATH . "/views/cbs_result/" . $requestInfo["file"] . ".json");

            return array(
                "success" => true,
                "data" => $json
            );
        endif;

        $header = array(
            "stakeHolder_id" => CBS_STACK_HOLDER,
            "user_id" => CBS_USER_ID,
            "password" => CBS_PASSWORD,
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
            $request = Requests::get($url, $header, array("timeout" => 200));

            $xmlLogData = array(
                'url' => $url,
                'source' => 'cbs',
                'method_name' => $requestInfo["method"],
                'requestXml' => json_encode($data),
                'responseXml' => $request->body,
                'errorText' => '',
                'created' => date("Y-m-d H:i:s")
            );

            $ci->load->model("log_model");
            $ci->log_model->addXmlLog($xmlLogData);

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
