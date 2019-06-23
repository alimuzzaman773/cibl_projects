<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Card_services {

    public function getCardDetails($data) {

        $session = $this->ticketGenerate();
        if (!$session["success"]) {
            return $session;
        }

        $session = $session["data"];
        $requestData = array(
            "card_no" => $data["card_no"],
            "message_id" => $session["message_id"],
            "ticket_id" => $session["ticket_id"]
        );

        $requestInfo = array(
            "file" => "card_details",
            "method" => "CardDetails"
        );

        $result = $this->pushToCms($requestInfo, $requestData);
        if (!$result["success"]) {
            return $result;
        }

        $res = $result["data"]["EntityInquiryResponse"]["EntityInquiryResult"];
        if (isset($res["Result"]["Code"]) && $res["Result"]["Code"] != '0') {
            return array(
                "success" => false,
                "msg" => $res["Result"]["Description"]
            );
        }

        if (!isset($res["Customer"])) {
            return array(
                "success" => false,
                "msg" => "Customer information not found"
            );
        }
        $customer = $res["Customer"];


        if (!isset($customer["Account"])) {
            return array(
                "success" => false,
                "msg" => "Account information not found"
            );
        }
        $account = $customer["Account"];


        if (!isset($account["Card"])) {
            return array(
                "success" => false,
                "msg" => "Card information not found"
            );
        }
        $cardInfo = $account["Card"];


        if (!isset($cardInfo["Data"])) {
            return array(
                "success" => false,
                "msg" => "Data information not found"
            );
        }
        $card = $cardInfo["Data"];


        if (!isset($cardInfo["People"]["PersonEntity"])) {
            return array(
                "success" => false,
                "msg" => "People information not found"
            );
        }
        $info = $cardInfo["People"]["PersonEntity"];


        if (!isset($info["Addresses"]["AdditionalAddressDetails"])) {
            return array(
                "success" => false,
                "msg" => "Address information not found"
            );
        }
        
        $address = $info["Addresses"]["AdditionalAddressDetails"];

        if (isset($info["Addresses"]["AdditionalAddressDetails"][0])) {
            $address = $info["Addresses"]["AdditionalAddressDetails"][0];
        }

        $details = array(
            "card_no" => $cardInfo["Number"],
            "clientId" => get_client_id(),
            "account_no" => $account["Number"],
            "product_name" => $card["ProductName"],
            "expiry_date" => $card["ExpDate"],
            "card_status" => $card["StGeneral"],
            "activated" => $card["Activated"],
            "card_type" => $card["CardType"],
            "product_code" => $card["ProductShortCode"],
            "created_date" => $card["CreateDate"],
            "name" => $info["Title"] . " " . $info["LastName"],
            "gender" => isset($info["Gender"]) ? $info["Gender"] : "",
            "marital_status" => $info["MaritalStatus"],
            "address" => isset($address["Address1"]) ? $address["Address1"] : "",
            "city" => isset($address["City"]) ? $address["City"] : "",
            "currency" => isset($address["Country"]) ? $address["Country"] : "",
            "mobile_no" => isset($address["Mobile"]) ? $address["Mobile"] : "",
            "email" => isset($address["Email"]) ? $address["Email"] : ""
        );

        return array(
            "success" => true,
            "data" => $details
        );
    }

    public function getCardBalance($data) {

        $session = $this->ticketGenerate();
        if (!$session["success"]) {
            return $session;
        }

        $session = $session["data"];
        $requestData = array(
            "account_no" => $data["account_no"],
            "message_id" => $session["message_id"],
            "ticket_id" => $session["ticket_id"]
        );

        $requestInfo = array(
            "file" => "card_balance",
            "method" => "CardBalance"
        );

        $result = $this->pushToCms($requestInfo, $requestData);
        if (!$result["success"]) {
            return $result;
        }

        $res = $result["data"]["EntityInquiryResponse"]["EntityInquiryResult"];
        if (isset($res["Result"]["Code"]) && $res["Result"]["Code"] != '0') {
            return array(
                "success" => false,
                "msg" => $res["Result"]["Description"]
            );
        }

        if (!isset($res["Customer"])) {
            return array(
                "success" => false,
                "msg" => "Customer information not found"
            );
        }
        $customer = $res["Customer"];

        if (!isset($customer["Account"])) {
            return array(
                "success" => false,
                "msg" => "Account information not found"
            );
        }
        $account = $customer["Account"];


        if (!isset($account["Card"])) {
            return array(
                "success" => false,
                "msg" => "Card information not found"
            );
        }
        $cardInfo = $account["Card"];

        if (isset($account["Card"][0])) {
            $cardInfo = $account["Card"][0];
        }

        if (!isset($account["Data"])) {
            return array(
                "success" => false,
                "msg" => "Card data not found"
            );
        }
        $card = $account["Data"];

        if (!isset($account["Statement"])) {
            return array(
                "success" => false,
                "msg" => "Statement information not found"
            );
        }
        $statement = $account["Statement"];

        if (!isset($cardInfo["People"]["PersonEntity"])) {
            return array(
                "success" => false,
                "msg" => "People information not found"
            );
        }
        $info = $cardInfo["People"]["PersonEntity"];


        if (!isset($info["Addresses"]["AdditionalAddressDetails"])) {
            return array(
                "success" => false,
                "msg" => "Address information not found"
            );
        }
        $address = $info["Addresses"]["AdditionalAddressDetails"];

        $details = array(
            "card_no" => $cardInfo["Number"],
            "account_no" => $account["Number"],
            "product_name" => $card["ProductName"],
            "credit_limit" => $card["CreditLimit"],
            "out_balance" => $card["Balance"] >= 0 ? $card["Balance"] . " (CR)" : $card["Balance"] . " (DR)",
            "balance" => $card["Balance"] + $card["CreditLimit"],
            "last_transaction" => isset($card["LastTrxnDate"]) ? $card["LastTrxnDate"] : "",
            "next_statment_date" => isset($card["NextStatementDate"]) ? $card["NextStatementDate"] : "",
            "expiry_date" => $cardInfo["Data"]["ExpDate"],
            "card_status" => $cardInfo["Data"]["StGeneral"],
            "activated" => $cardInfo["Data"]["Activated"],
            "card_type" => $cardInfo["Data"]["CardType"],
            "product_code" => $card["ProductShortCode"],
            "created_date" => $card["CreateDate"],
            "min_due" => $statement["MinDue"],
            "due_date" => isset($statement["DueDate"]) ? $statement["DueDate"] : "",
            "mr_point" => $statement["ClosingRewardPoints"],
            "name" => $info["Title"] . " " . $info["LastName"],
            "gender" => isset($info["Gender"]) ? $info["Gender"] : "",
            "marital_status" => isset($info["MaritalStatus"]) ? $info["MaritalStatus"] : "",
            "address" => isset($address["Address1"]) ? $address["Address1"] : "",
            "city" => isset($address["City"]) ? $address["City"] : "",
            "currency" => $card["Currency"],
            "mobile_no" => isset($address["Mobile"]) ? $address["Mobile"] : "",
            "email" => isset($address["Email"]) ? $address["Email"] : "",
        );

        return array(
            "success" => true,
            "data" => $details
        );
    }

    public function getCardStatement($data) {

        $session = $this->ticketGenerate();
        if (!$session["success"]) {
            return $session;
        }

        $session = $session["data"];
        $requestData = array(
            "card_no" => $data["card_no"],
            "from_date" => $data["from_date"],
            "to_date" => $data["to_date"],
            "message_id" => $session["message_id"],
            "ticket_id" => $session["ticket_id"]
        );

        $requestInfo = array(
            "file" => "card_statement",
            "method" => "CardStatement"
        );

        $result = $this->pushToCms($requestInfo, $requestData);
        if (!$result["success"]) {
            return $result;
        }

        $res = $result["data"]["StatementInquiryResponse"]["StatementInquiryResult"];
        if (isset($res["Result"]["Code"]) && $res["Result"]["Code"] != '0') {
            return array(
                "success" => false,
                "msg" => $res["Result"]["Description"]
            );
        }

        if (!isset($res["Transaction"])) {
            return array(
                "success" => false,
                "msg" => "There are no transaction found"
            );
        }
        $transaction = $res["Transaction"];

        if (count($transaction) <= 0) {
            return array(
                "success" => false,
                "msg" => "There are no data found"
            );
        }

        $trnData = array();
        foreach ($transaction as $row) {
            $items = $row["Details"];
            $trn = array(
                "transaction_id" => $items["TrxnSerno"],
                "transaction_date" => $items["TrxnDate"],
                "amount" => $items["TrxnAmount"],
                "currency" => $items["TransactionCurrency"],
                "narration" => $items["TransactionDescription"],
            );
            $trnData[] = $trn;
        }

        return array(
            "success" => true,
            "data" => $trnData
        );
    }

    public function creditTransaction($data) {

        $session = $this->ticketGenerate();
        if (!$session["success"]) {
            return $session;
        }

        $session = $session["data"];
        $requestData = array(
            "card_no" => $data["card_no"],
            "amount" => $data["amount"],
            "message_id" => $session["message_id"],
            "ticket_id" => $session["ticket_id"]
        );

        $requestInfo = array(
            "file" => "credit_transaction",
            "method" => "AccountToCardTransaction"
        );

        $result = $this->pushToCms($requestInfo, $requestData);
        if (!$result["success"]) {
            return $result;
        }

        $transaction = $result["data"]["PostTransactionResponse"]["PostTransactionResult"];
        if ($transaction["Result"]["Code"] != '0' || $transaction["TrxnStatus"] != 'POST') {
            return array(
                "success" => false,
                "msg" => $transaction["Result"]["Description"]
            );
        }

        return array(
            "success" => true,
            "data" => "Your transaction successfully completed"
        );
    }

    public function ticketGenerate() {

        $requestInfo = array(
            "file" => "ticket_generate",
            "method" => "SessionCreate"
        );

        $result = $this->pushToCms($requestInfo, array());
        if (!$result["success"]) {
            return $result;
        }

        $res = $result["data"]["AcquireTicketResponse"]["AcquireTicketResult"];
        return array(
            "success" => true,
            "data" => array(
                "message_id" => $res["Header"]["MessageID"],
                "ticket_id" => $res["Ticket"]
            )
        );
    }

    function pushToCms($requestInfo, $requestData) {

        $ci = & get_instance();
        if (defined('cbs_data_from_dummy') && cbs_data_from_dummy):
            $xml = file_get_contents(APPPATH . "/views/card_result/" . $requestInfo["file"] . ".php");
            return $this->renderCmsResponse($xml);
        endif;

        try {
            $requestXml = $ci->load->view("card_request/" . $requestInfo["file"] . ".php", $requestData, true);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, CARD_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
            curl_setopt($ch, CURLOPT_USERPWD, CARD_AUTH);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXml);

            $output = curl_exec($ch);

            $xmlLogData = array(
                'url' => CARD_URL,
                'source' => 'cms',
                'method_name' => $requestInfo["method"],
                'requestXml' => $requestXml,
                'responseXml' => $output,
                'errorText' => '',
                'created' => date("Y-m-d H:i:s")
            );

            $ci->load->model("log_model");
            $ci->log_model->addXmlLog($xmlLogData);

            if ($output == NULL) {
                return array(
                    "success" => false,
                    "msg" => "Http request proccessing failed"
                );
            }
            curl_close($ch);
            return $this->renderCmsResponse($output);
        } catch (Exception $e) {
            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

    private function renderCmsResponse($xml) {
        $getXml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $xmlElement = new SimpleXMLElement($getXml);
        $xmlBody = $xmlElement->xpath('//soapBody');
        $xmlArray = json_decode(json_encode((array) $xmlBody), TRUE);
        if (isset($xmlArray[0]["soapFault"]["soapReason"]["soapText"])) {
            return array(
                "success" => false,
                "msg" => $xmlArray[0]["soapFault"]["soapReason"]["soapText"]
            );
        }
        return array(
            "success" => true,
            "data" => $xmlArray[0]
        );
    }

}
