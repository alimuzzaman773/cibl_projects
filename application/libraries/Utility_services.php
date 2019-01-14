<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility_services {

    public function getUtilityList() {

        $requestInfo = array(
            "url" => "service-list",
            "file" => "bill_info/btcl_bill_info"
        );
        return $this->pushToUtility($requestInfo, array());
    }

    //DPDC Bill services
    public function getDpdcBillInfo($data) {

        $requestData = array(
            "location_code" => $data["location_code"],
            "account_number" => $data["account_number"],
            "bill_years" => $data["bill_years"],
            "bill_months" => $data["bill_months"],
            "transaction_id" => $data["transaction_id"]
        );

        $requestInfo = array(
            "type" => "dpdc",
            "url" => "bill-info",
            "file" => "bill_info/dpdc_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function dpdcBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            "payment_mode" => $data["payment_mode"],
            "cheque_remarks" => $data["cheque_remarks"],
            "channel_id" => $data["channel_id"]
        );

        $requestInfo = array(
            "type" => "dpdc",
            "url" => "bill-payment",
            "file" => "bill_payment/dpdc_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    //DESCO Bill services
    public function getDescoBillInfo($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            "billno" => $data["bill_no"]
        );

        $requestInfo = array(
            "type" => "desco",
            "url" => "bill-info",
            "file" => "bill_info/desco_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function descoBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            //"mobile_no" => $data["mobile_no"],
            //"branch_id" => $data["branch_id"],
            //"payment_type" => $data["payment_type"],
            "remarks" => $data["remarks"],
                //"channel_type" => $data["channel_type"],
                //"bank_code" => $data["bank_code"],
                //"lpc_code" => $data["lpc_code"]
        );

        $requestInfo = array(
            "type" => "desco",
            "url" => "bill-payment",
            "file" => "bill_payment/desco_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    //TITAS METERED Bill services
    public function getTitasMeteredBillInfo($data) {

        $requestData = array(
            "invoiceNo" => $data["invoice_no"],
            "customerCode" => $data["customer_code"],
            "sourceTaxAmount" => $data["tax_amount"],
            "branchRoutingNo" => $data["routing_no"],
            "operator" => $data["operator"],
            "chalanNo" => $data["challan_no"],
            "chalanDate" => $data["challan_date"],
            "chalanBank" => $data["challan_bank"],
            "bill_type" => "METERED",
            "transaction_id" => $data["transaction_id"]
        );

        $requestInfo = array(
            "type" => "titas_metered",
            "url" => "bill-info",
            "file" => "bill_info/titas_metered_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function titasMeteredBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            "bill_type" => "METERED"
        );

        $requestInfo = array(
            "type" => "titas_metered",
            "url" => "bill-payment",
            "file" => "bill_payment/titas_metered_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    //TITAS NON METERED Bill services
    public function getTitasNonMeteredBillInfo($data) {

        $requestData = array(
            "customer" => $data["customer_code"],
            "amount" => $data["amount"],
            "surcharge" => $data["surcharge"],
            "particulars" => $data["particulars"],
            "bill_type" => "NON-METERED",
            "transaction_id" => $data["transaction_id"]
        );

        $requestInfo = array(
            "type" => "titas_non_metered",
            "url" => "bill-info",
            "file" => "bill_info/titas_non_metered_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function titasNonMeteredBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            "bill_type" => "NON-METERED",
        );

        $requestInfo = array(
            "type" => "titas_non_metered",
            "url" => "bill-payment",
            "file" => "bill_payment/titas_non_metered_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    //TITAS NON METERED Bill services
    public function getTitasDemandNoteBillInfo($data) {

        $requestData = array(
            "customer" => $data["customer"],
            "amount" => $data["amount"],
            "surcharge" => $data["surcharge"],
            "particulars" => $data["particulars"],
            "bill_type" => "DEMAND-NOTE",
            "transaction_id" => $data["transaction_id"]
        );

        $requestInfo = array(
            "type" => "titas_demand_note",
            "url" => "bill-info",
            "file" => "bill_info/titas_demand_note_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function titasDemandNoteBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            "bill_type" => "DEMAND-NOTE",
        );

        $requestInfo = array(
            "type" => "titas_demand_note",
            "url" => "bill-payment",
            "file" => "bill_payment/titas_demand_note_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    //WASA Bill services
    public function getWasaBillInfo($data) {

        $requestData = array(
            "billno" => $data["bill_no"],
            "account_no" => $data["account_no"],
            "transaction_id" => $data["transaction_id"]
        );

        $requestInfo = array(
            "type" => "wasa",
            "url" => "bill-info",
            "file" => "bill_info/wasa_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function wasaBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            "mobile_no" => $data["mobile_no"],
            "branch_id" => $data["branch_id"],
            "payment_type" => $data["payment_type"],
            "channel_type" => $data["channel_type"],
            "remarks" => $data["remarks"]
        );

        $requestInfo = array(
            "type" => "wasa",
            "url" => "bill-payment",
            "file" => "bill_payment/wasa_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    //TOP Up services
    public function getTopUpBillInfo($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            //"operator_id" => $data["operator_id"],
            "recipient_msisdn" => $data["recipient_msisdn"],
            "amount" => $data["amount"],
            "connection_type" => $data["connection_type"]
        );

        $authKey = $this->getTopupKey($data["operator_id"]);

        $requestInfo = array(
            "type" => "top_up",
            "url" => "bill-info",
            "file" => "bill_info/topup_bill_info"
        );

        $request = array_merge($requestData, $authKey);
        return $this->pushToUtility($requestInfo, $request);
    }

    public function topUpBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"]
        );

        $authKey = $this->getTopupKey($data["operator_id"]);

        $requestInfo = array(
            "type" => "top_up",
            "url" => "bill-payment",
            "file" => "bill_payment/topup_bill_payment"
        );

        $request = array_merge($requestData, $authKey);
        return $this->pushToUtility($requestInfo, $request);
    }

    //BUFT Bill Payment services
    public function getBuftBillInfo($data) {

        $requestData = array(
            "invoice_id" => $data["invoice_id"],
            "transaction_id" => $data["transaction_id"],
            "bill_type" => $data["bill_type"],
            "check_remarks" => $data["remarks"]
        );

        $requestInfo = array(
            "type" => "buft",
            "url" => "bill-info",
            "file" => "bill_info/buft_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function buftBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"],
            "branch" => $data["branch"],
            "payment_mode" => $data["payment_mode"],
            "particulars" => $data["particulars"],
            "remarks" => $data["remarks"]
        );

        $requestInfo = array(
            "type" => "buft",
            "url" => "bill-payment",
            "file" => "bill_payment/buft_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    //OIS Bill Payment services
    public function getOisBillInfo($data) {

        $requestData = array(
            "invoice_id" => $data["invoice_id"],
            "transaction_id" => $data["transaction_id"],
            "bank_details" => $data["bank_details"],
            "daily_serial_no" => $data["daily_serial_no"],
            "amount" => $data["amount"],
            "timestamp" => date("Y-m-d")
        );

        $requestInfo = array(
            "type" => "ois",
            "url" => "bill-info",
            "file" => "bill_info/ois_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function oisBillPayment($data) {

        $requestData = array(
            "invoice_id" => $data["invoice_id"],
            "utility_user" => $data["utility_user"],
            "utility_pass" => $data["utility_pass"],
            "transaction_id" => $data["transaction_id"],
            "bank" => $data["bank"],
            "branch" => $data["branch"],
            "stk_id" => $data["stk_id"],
            "bill_type" => $data["bill_type"],
            "student_name" => $data["student_name"],
            "student_id" => $data["student_id"],
            "payment_mode" => $data["payment_mode"],
            "check_remarks" => $data["check_remarks"],
            "particulars" => $data["particulars"]
        );

        $requestInfo = array(
            "type" => "ois",
            "url" => "bill-payment",
            "file" => "bill_payment/ois_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    //IVAC Bill Payment services
    public function getIvacBillInfo($data) {

        $requestData = array(
            "ivac_id" => $data["ivac_id"],
            "webfile_id" => $data["webfile_id"],
            "passport_no" => $data["passport_no"],
            "appoint_type" => $data["appoint_type"],
            "appoint_date" => $data["appoint_date"],
            "mobile_no" => $data["mobile_no"],
            "email_address" => $data["email_address"],
            "transaction_id" => $data["transaction_id"],
        );

        $requestInfo = array(
            "type" => "ivac",
            "url" => "bill-info",
            "file" => "bill_info/ivac_bill_info"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    public function ivacBillPayment($data) {

        $requestData = array(
            "transaction_id" => $data["transaction_id"]
        );

        $requestInfo = array(
            "type" => "ivac",
            "url" => "bill-payment",
            "file" => "bill_payment/ivac_bill_payment"
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }


    public function billCancel($data) {
        
        $requestData = array(
            "transaction_id" => $data["transaction_id"]
        );

        $requestInfo = array(
            "type" => $data["type"],
            "url" => "bill-cancel",
            "file" => ""
        );
        return $this->pushToUtility($requestInfo, $requestData);
    }

    private function pushToUtility($requestInfo, $data) {

        if (defined('cbs_data_from_dummy') && cbs_data_from_dummy):
            $json = file_get_contents(APPPATH . "/views/utility_result/" . $requestInfo["file"] . ".json");

            return array(
                "success" => true,
                "data" => json_decode($json, true)
            );
        endif;

        $header = array(
            "AUTH-KEY" => "XLVlwqLS7H1F42nr56ihYejgS8krAh8W",
            "STK-CODE" => "PBL"
        );

        $authKey = $this->getAuthKey($requestInfo["type"]);
        $requestData = array_merge($authKey, $data);

        include_once APPPATH . "libraries/Requests.php";
        Requests::register_autoloader();

        try {
            $url = UTILITY_URL . $requestInfo["url"];
            $request = Requests::post($url, $header, $requestData, array("timeout" => 200));

            return array(
                "success" => $request->success,
                "data" => json_decode($request->body, true),
            );
        } catch (Exception $e) {

            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

    private function getAuthKey($type) {

        $key = array();

        switch ($type) {

            case "btcl":
                $key = $auth = array(
                    "utility_auth_key" => "DP15147854699683",
                    "utility_secret_key" => "F1xvbrWzgPOtVKej"
                );
                break;

            case "dpdc":
                $key = $auth = array(
                    "utility_auth_key" => "DP15147854699683",
                    "utility_secret_key" => "F1xvbrWzgPOtVKej"
                );
                break;

            case "desco":
                $key = $auth = array(
                    "utility_auth_key" => "DE15147853658738",
                    "utility_secret_key" => "McjteFIIuM5RIhjt"
                );
                break;

            case "pdb":
                $key = $auth = array(
                    "utility_auth_key" => "PD15327736605029",
                    "utility_secret_key" => "wXpkWavjn6XjA3ba"
                );
                break;

            case "titas_metered":
                $key = $auth = array(
                    "utility_auth_key" => "TI15108145467546",
                    "utility_secret_key" => "TMs3SezZ/OGlVdp6"
                );
                break;

            case "titas_non_metered":
                $key = $auth = array(
                    "utility_auth_key" => "TI15108145974407",
                    "utility_secret_key" => "txLjDbBTRaqjEPV1"
                );
                break;

            case "titas_demand_note":
                $key = $auth = array(
                    "utility_auth_key" => "TI15108146472886",
                    "utility_secret_key" => "KcydQdN0DssANqdo"
                );
                break;

            case "wasa":
                $key = $auth = array(
                    "utility_auth_key" => "WA15414821132210",
                    "utility_secret_key" => "9RAfrELqFFtqpVqB"
                );
                break;

            case "ivac":
                $key = $auth = array(
                    "utility_auth_key" => "IV15202284900784",
                    "utility_secret_key" => "0UML5iFiqFWkvR76"
                );
                break;

            case "buft":
                $key = $auth = array(
                    "utility_auth_key" => "BU15202276054830",
                    "utility_secret_key" => "mrf4w5BxX0eOaY1z"
                );
                break;

            case "ois":
                $key = $auth = array(
                    "utility_auth_key" => "OI15202276840286",
                    "utility_secret_key" => "2zjpN8fUTCUdtPqp"
                );
                break;

            default :
                $key = array();
                break;
        }
        return $key;
    }

    private function getTopupKey($operator) {
        $key = array();

        switch ($operator) {

            case "1": //grameen 
                $key = $auth = array(
                    "utility_bill_type" => "Grameenphone",
                    "utility_auth_key" => "VR15414824169220",
                    "utility_secret_key" => "bKFlVwzpa1BPRIyX"
                );
                break;

            case "2": //banglalink
                $key = $auth = array(
                    "utility_bill_type" => "Banglalink",
                    "utility_auth_key" => "VR15414823810959",
                    "utility_secret_key" => "dEbpISTul1NsdsNE"
                );
                break;

            case "3": //robi
                $key = $auth = array(
                    "utility_bill_type" => "Robi",
                    "utility_auth_key" => "VR15414824387277",
                    "utility_secret_key" => "xXFQWCucfkCrxQGd"
                );
                break;

            case "5": //teletalk
                $key = $auth = array(
                    "utility_bill_type" => "TeleTalk",
                    "utility_auth_key" => "PD15327736605029",
                    "utility_secret_key" => "wXpkWavjn6XjA3ba"
                );
                break;

            case "6": //airtel
                $key = $auth = array(
                    "utility_bill_type" => "Airtel",
                    "utility_auth_key" => "VR15414823579777",
                    "utility_secret_key" => "HkGJjj4jSzM71Gaz"
                );
                break;

            default :
                $key = array();
                break;
        }
        return $key;
    }

}
