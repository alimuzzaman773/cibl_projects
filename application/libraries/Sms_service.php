<?php

class Sms_service {

    public function smsService($smsData) {
        include_once APPPATH . "libraries/Requests.php";
        Requests::register_autoloader();

        $params = array(
            "user" => "pblib",
            "pass" => "PBLib5m5",
            "sms[0][0]" => ltrim($smsData['mobileNo']),
            "sms[0][1]" => urlencode($smsData['message']),
            "sms[0][2]" => "123456",
            "sid" => "PBLIB"
        );

        try {
            $url = "http://192.168.246.2/pushapi/dynamic/server.php";
            $request = Requests::post($url, array(), http_build_query($params));

            $result = array(
                "success" => $request->success,
                "data" => $request->body,
                "params" => $params,
                "request" => $request
            );
            return $result;
        } catch (Exception $e) {
            $error = array(
                "success" => false,
                "msg" => $e->getMessage()
            );
            return $error;
        }
    }
}

/*
 $sms = array(
            "userName" => "pblib",
            "password" => "PBLib5m5",
            "mobileNo" => $smsData['mobileNo'],
            "message" => $smsData['message'],
            "sid" => "PBLIB"
        );

$params = "user=" . $sms["userName"] . "&pass=" . $sms["password"] . "&sms[0][0]=" . $sms['mobileNo'] . "&sms[0][1]=" . urlencode($sms['message']) . "&sms[0][2]=123456&sid=" . $sms["sid"] . "";
*/