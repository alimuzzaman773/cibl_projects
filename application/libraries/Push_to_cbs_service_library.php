<?php

class Push_to_cbs_service_library {

    public function pushToCbsService($req_type_code, $data) {
        $xml = "";
        $apprequest = "";
        $url = "http://192.168.3.159/webserver/coreEngin.php";

        $ci = & get_instance();
        switch ($req_type_code) {
            case "01":    // Account Details
                $apprequest = "vw_account_detail";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "02":   // ACCOUNT SUMMARY
                $apprequest = "vw_account_summary";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "03":   // TRN HISTORY 
                $apprequest = "vw_trn_history";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "04":  // TD INFO 
                $apprequest = "vw_td_info";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "05":  // LOAN INFO 
                $apprequest = "vw_cl_account";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "06":  // OWN ACCOUNT TRANSFER
                $apprequest = "req_own_account_fund_transfer";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "07":  // EBL ACCOUNT TRANSFER
                $apprequest = "req_ebl_account_transfer";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "08":  // OTHER BANK TRANSFER
                $apprequest = "req_other_bank_transfer";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "09": // BILLS PAY
                $apprequest = "req_bills_pay";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);
                $result = $this->pushtoservice($url, $apprequest, $xml);
                return $result;

            case "10": // CARD DOCS
                $apprequest = "vw_card_information";
                $data['loginName'] = "EBLSKYADM";
                $data['passWord'] = "ESBADM123";
                $xml = $ci->load->view('xml/cbs_service/' . $apprequest, $data, true);

                switch ($data['methodName']) {

                    case "CARD_STATUS":
                        $apprequest = "card_status";
                        $result = $this->pushtoservice($url, $apprequest, $xml);
                        return $result;

                    case "CARD_ACCOUNT_DETAILS":
                        $apprequest = "card_account_details";
                        $result = $this->pushtoservice($url, $apprequest, $xml);
                        return $result;

                    case "CARD_CUSTOMER_DETAILS":
                        $apprequest = "card_customer_details";
                        $result = $this->pushtoservice($url, $apprequest, $xml);
                        return $result;

                    case "CARD_LIMITS":
                        $apprequest = "card_limits";
                        $result = $this->pushtoservice($url, $apprequest, $xml);
                        return $result;

                    case "CARD_BALANCE":
                        $apprequest = "card_balance";
                        $result = $this->pushtoservice($url, $apprequest, $xml);
                        return $result;

                    case "CARD_ACCOUNT_STATEMENT":
                        $apprequest = "card_account_statement";
                        $result = $this->pushtoservice($url, $apprequest, $xml);
                        return $result;

                    case "CARD_INFORMATION_DETAILS":
                        $apprequest = "card_information_details";
                        $result = $this->pushtoservice($url, $apprequest, $xml);
                        return $result;
                }
                break;
        }
    }

    private function pushtoservice($url, $appreq, $xml) {
        $ci = & get_instance();

        if (defined('cbs_data_from_dummy') && cbs_data_from_dummy):
            return $ci->load->view('xml/cbs_service_result/' . $appreq, array(), true);
        endif;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $appreq . '=' . $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $server_output = curl_exec($ch);
        curl_close($ch);
        return $server_output;
//        $response = str_replace(array("&"), 'AND', $server_output);
//        return $response;
    }

}
