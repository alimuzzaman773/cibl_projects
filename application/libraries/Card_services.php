<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Card_services {

    var $sessionId = NULL;

    function getCardHolderDetails($data) {
        $requestData = array_merge($data, array("session" => $this->sessionId));
        $result = $this->pushToCms("card_holder_details", $requestData);

        if (!$result["success"]) {
            return $result;
        }
        //return $result['data'];
        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                ->Body->children('m1', true)
                ->UserDefinedRp->Response
                ->children('m0', true)
                ->Result
                ->children('m2', true);

        $email = NULL;
        $mobile = NULL;
        if (isset($soapResponse->ABONENT)):
            for ($i = 0; $i < count($soapResponse->ABONENT); $i++) {
                if (filter_var($soapResponse->ABONENT[$i]->attributes()["ADDRESS"], FILTER_VALIDATE_EMAIL)) {
                    if ($email === NULL):
                        $email = (string) $soapResponse->ABONENT[$i]->attributes()["ADDRESS"];
                    endif;
                }
                else {
                    $checkMb = (string) $soapResponse->ABONENT[$i]->attributes()["ADDRESS"];
                    $checkMb = ltrim($checkMb, "+");
                    if ($mobile == NULL && is_numeric($checkMb)):
                        $mobile = (string) $soapResponse->ABONENT[$i]->attributes()["ADDRESS"];
                    endif;
                }
            }
        endif;

        $details = array(
            "clientId" => (string) $soapResponse->PERSONID,
            "cardHolderName" => (string) $soapResponse->NAMEONCARD,
            "mobileNo" => $mobile,
            "emailId" => $email,
            "cardNo" => (string) $soapResponse->PAN
        );

        return array(
            "success" => true,
            "data" => $details,
            'source' => $result['data']
        );
    }

    function getCardInfo($data) {
        $requestData = array_merge($data, array("session" => $this->sessionId));
        $result = $this->pushToCms("card_info", $requestData);

        if (!$result["success"]) {
            return $result;
        }

        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                        ->Body->children('m1', true)
                        ->GetCardInfoRp->Response
                        ->children('m0', true)
                ->Accounts;

        $response = array();

        foreach ($soapResponse->Row as $post) {
            $item = array(
                "accNo" => (string) $post->AcctNo,
                "status" => (string) $post->Status,
                "ledgerBalance" => (string) $post->LedgerBalance,
                "currency" => (string) $post->Currency
            );
            /* $infoArr = array(
              "pan" => (string) $post->PAN,
              "personName" => (string) $post->PersonFIO,
              "clientId" => (string) $post->PersonID,
              "expirationDate" => (string) $post->ExpirationDate
              ); */
            $response[] = $item;
        }
        return array(
            "success" => true,
            "data" => $response,
            'source' => $result['data']
        );
    }

    public function posDebit($data) {

        $requestData = array_merge($data, array("session" => $this->sessionId));
        $result = $this->pushToCms("pos_debit", $requestData);

        if (!$result["success"]) {
            return $result;
        }

        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                ->Body->children('m1', true)
                ->POSRequestRp->Response
                ->children('m0', true);


        $debitArr = array(
            "approvalCode" => (string) $soapResponse->ApprovalCode,
            "authRespCode" => (string) $soapResponse->AuthRespCode,
            "availableBalance" => (string) $soapResponse->AvailBalance,
            "balanceCurrency" => (string) $soapResponse->BalanceCurrency,
            "bonusDebt" => (string) $soapResponse->BonusDebt,
            "cVxOK" => (string) $soapResponse->CVxOK,
            "currency" => (string) $soapResponse->Currency,
            "fee" => (string) $soapResponse->Fee,
            "fromAccount" => (string) $soapResponse->FromAcct,
            "ledgerBalance" => (string) $soapResponse->LedgerBalance,
            "transactionId" => (string) $soapResponse->ThisTranId,
        );

        return array(
            "success" => true,
            "data" => $debitArr
        );
    }

    public function posCredit($data) {

        $requestData = array_merge($data, array("session" => $this->sessionId));
        $result = $this->pushToCms("pos_credit", $requestData);

        if (!$result["success"]) {
            return $result;
        }

        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                ->Body->children('m1', true)
                ->POSRequestRp->Response
                ->children('m0', true);

        $creditArr = array(
            "approvalCode" => (string) $soapResponse->ApprovalCode,
            "authRespCode" => (string) $soapResponse->AuthRespCode,
            "availableBalance" => (string) $soapResponse->AvailBalance,
            "balanceCurrency" => (string) $soapResponse->BalanceCurrency,
            "bonusDebt" => (string) $soapResponse->BonusDebt,
            "cVxOK" => (string) $soapResponse->CVxOK,
            "currency" => (string) $soapResponse->Currency,
            "fee" => (string) $soapResponse->Fee,
            "ledgerBalance" => (string) $soapResponse->LedgerBalance,
            "maskBalances" => (string) $soapResponse->MaskBalances,
            "transactionId" => (string) $soapResponse->ThisTranId,
            "toAccount" => (string) $soapResponse->ToAcct,
            "transactionId" => (string) $soapResponse->ThisTranId,
        );

        return array(
            "success" => true,
            "data" => $creditArr
        );
    }

    public function balanceInquiry($data) {

        $requestData = array_merge($data, array("session" => $this->sessionId));
        $result = $this->pushToCms("balance_inquiry", $requestData);

        if (!$result["success"]) {
            return $result;
        }

        //return $result['data'];

        $xml = simplexml_load_string($result["data"]);
        $soapResponse = $xml->children('env', true)
                ->Body->children('m1', true)
                ->BalanceInquiryRp->Response
                ->children('m0', true);

        $inquiryArr = array(
            "availableBalance" => (string) $soapResponse->Avail,
            "currency" => (string) $soapResponse->Currency,
            "ledger" => (string) $soapResponse->Ledger,
            "overdraft" => (string) $soapResponse->OverdraftOn
        );

        return array(
            "success" => true,
            "data" => $inquiryArr
        );
    }

    function getCardStatement($data) {
        $requestData = array_merge($data, array("session" => $this->sessionId));
        $result = $this->pushToCms("get_card_statement", $requestData);

        if (!$result["success"]) {
            return $result;
        }

        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                        ->Body->children('m1', true)
                        ->GetCardStatementRp->Response
                        ->children('m0', true)
                ->Statement;
        $response = array();

        foreach ($soapResponse->Row as $p) {
            $transactionTime = "";
            $trDt = new DateTime((string) $p->TranTime);
            if ($trDt):
                $transactionTime = $trDt->format("Y-m-d H:ia");
            endif;
            $item = array(
                "Amount" => (string) $p->Amount,
                "TranTime" => $transactionTime, //(string)$p->TranTime,
                "Currency" => (string) $p->Currency,
                "TermName" => (string) $p->TermName,
                "TermLocation" => (string) $p->TermLocation,
                "PAN" => (string) $p->PAN,
            );
            $response[] = $item;
        }
        return array(
            "success" => true,
            "data" => $response
        );
    }
    
    function getCardInfoDynamic($data, $sessionId = null) {
        if ($sessionId != NULL):
            $this->sessionId = $sessionId;
        endif;
        $requestData = array_merge($data, array("session" => $this->sessionId));
        $result = $this->pushToCms("card_info_dynamic", $requestData);

        if (!$result["success"]) {
            return $result;
        }
        //d($result   );
        //return $result['data'];
        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                ->Body->children('m1', true)
                ->GetCardInfoRp->Response
                ->children('m0', true);

        $accountList = $soapResponse->Accounts;

        $email = NULL;
        $mobile = NULL;
        if (isset($soapResponse->AlternativeMessaging->Row)):
            foreach ($soapResponse->AlternativeMessaging->Row as $am):
                $address = (string) $am->Address;
                if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
                    if ($email === NULL):
                        $email = (string) $address;
                    endif;
                }
                else {
                    $checkMb = ltrim($address, "+");
                    if ($mobile === NULL && is_numeric($checkMb)):
                        $mobile = (string) $checkMb;
                    endif;
                }
            endforeach;
        endif;

        $response = array();

        foreach ($accountList->Row as $post) {
            $item = array(
                "accNo" => (string) $post->AcctNo,
                "status" => (string) $post->Status,
                "ledgerBalance" => (string) $post->LedgerBalance,
                "currency" => (string) $post->Currency,
                "clientId" => (string) $soapResponse->PersonId,
                "expiry" => (string) $soapResponse->ExpDate,
                "name" => (string) $soapResponse->NameOnCard,
                "mobile_no" => $mobile,
                "email" => $email
            );
            $response[] = $item;
        }
        return array(
            "success" => true,
            "data" => $response,
            "source" => $result["data"]
        );
    }

    function getCardLimit($data) {
        $requestData = array_merge($data, array("session" => $this->sessionId));
        $result = $this->pushToCms("card_limit", $requestData);

        if (!$result["success"]) {
            return $result;
        }

        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                ->Body->children('m1', true)
                ->GetCardLimitsRp->Response
                ->children('m0', true);

        $limitList = $soapResponse->Limits;

        $response = array();
        foreach ($limitList->Row as $post) {
            $item = array(
                "limit_id" => (string) $post->LimitId,
                "current_limit" => (string) $post->Current,
                "max_limit" => (string) $post->MaxPresentedOnCard
            );
            $response[] = $item;
        }
        $limitId = array_column($response, 'limit_id');

        $ci = & get_instance();
        $query = $ci->db->select("*")
                ->from("itcl_card_limit")
                ->where_in("limit_id", $limitId)
                ->get();

        $mergedArray = [];
        foreach ($query->result_array() as $key => $value) {
            if ($value["limit_id"] == $response[$key]["limit_id"]) {
                $it = array(
                    "limitId" => $response[$key]["limit_id"],
                    "limitName" => $value["limit_name"],
                    "currentLimit" => $response[$key]["current_limit"],
                    "maxLimit" => $response[$key]["max_limit"]
                );
                $mergedArray[] = $it;
            }
        }
        return array(
            "success" => true,
            "data" => $mergedArray,
            "source" => $result["data"]
        );
    }

    public function createSession() 
    {
        $method = "create_session";
        $result = $this->pushToCms($method, array());
        
        if (!$result["success"]) {
            //d(__FUNCTION__, false);
            //d($result);
            error_log(json_encode($result));
            return $result;
        }

        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                        ->Body->children('m1', true)
                        ->InitSessionRp->Response
                        ->children('m0', true)
                        ->Id;
        return (string) $soapResponse;
    }
    
    public function createSessionNew() 
    {
        //check in db
        $ci =& get_instance();
        $ci->load->database();
        //d($ci);
        $sessionRes = $ci->db->get('fimi_sessions');
        if($sessionRes->num_rows() > 0):
            $sessionInfo = $sessionRes->row();
            return $sessionInfo;
        endif;
        
        $method = "create_session";
        $result = $this->pushToCms($method, array());
        
        if (!$result["success"]) {
            d(__FUNCTION__, false);
            d($result);
            error_log(json_encode($result));
            return $result;
        }

        $xml = simplexml_load_string($result["data"]);
        $soapResponse = $xml->children('s', true)
                        ->Body->children('m1', true)
                        ->InitSessionRp->Response
                        ->children('m0', true)
                        ->Id;
        $date = date("Y-m-d H:i:s");
        $d = array(
            'sessionId' => (int)$soapResponse,
            'sessionResponse' => $result['data'],
            'loginResponse' => '',
            'created' => $date,
            'updated' => $date
        );
        
        $ci->db->reset_query();
        $ci->db->insert('fimi_sessions', $d);
        
        $login = array("session" => (int)$soapResponse);
        $loginData = $this->loginNew($login);
        
        return (object) $d;
    }
    
    public function loginNew($data) 
    {
        $method = "login";
        $result = $this->pushToCms($method, $data);

        if (!$result["success"]) {
            //d($data, false);
            //d(__FUNCTION__, false);
            //d($result);
            error_log(json_encode($result));
            return $result;
        }
        
        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                        ->Body->children('m1', true)
                        ->LogonRp->Response
                        ->children('m0', true)
                ->Operations;

        $response = array();

        foreach ($soapResponse->Row as $post) {
            $response[] = $post;
        }
        return array(
            'response' => $response,
            'data' => $result['data']
        );
    }

    public function login($data) 
    {
        $method = "login";
        $result = $this->pushToCms($method, $data);

        if (!$result["success"]) {
            //d($data, false);
            //d(__FUNCTION__, false);
            //d($result);
            error_log(json_encode($result));
            return $result;
        }

        $xml = simplexml_load_string($result["data"]);

        $soapResponse = $xml->children('env', true)
                        ->Body->children('m1', true)
                        ->LogonRp->Response
                        ->children('m0', true)
                ->Operations;

        $response = array();

        foreach ($soapResponse->Row as $post) {
            $response[] = $post;
        }
        return $response;
    }

    function requestTask() {

        $sessionId = $this->createSession();
        $this->login(array("session" => $sessionId));
        return $sessionId;
    }

    public function requestTaskChaining() 
    {
        $sessionId = $this->createSession();
        $this->sessionId = $sessionId;
        $this->login(array("session" => $sessionId));
        return $this;
    }
    
    public function getSession() 
    {
        $sessionInfo = $this->createSessionNew();        
        $this->sessionId = $sessionInfo->sessionId;
        $this->loginNew(array("session" => $sessionInfo->sessionId));
        return $this;
    }

    private function pushToCms($method, $data) {

        $ci = & get_instance();

        if (defined('cbs_data_from_dummy') && cbs_data_from_dummy):
            $xml = $ci->load->view('xml/cards_result/' . $method, array(), true);
            return array(
                "success" => true,
                "data" => $xml
            );
        endif;

        $credential = array(
            "version" => "3.1",
            "address" => "180.210.151.230",
            "password" => "nrbc123456",
            "clerk" => "NRBCSOAP"
        );
        $resquestData = array_merge($credential, $data);

        $xml = $ci->load->view('xml/cards/' . $method, $resquestData, true);

        include_once APPPATH . "libraries/Requests.php";
        Requests::register_autoloader();

        try {
            $url = "http://172.16.16.8:5959";
            $request = Requests::post($url, array('Content-type' => 'text/xml'), $xml);

            return array(
                "success" => $request->success,
                "data" => $request->body,
                "request" => $xml
            );
        } catch (Exception $e) {
            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

}
