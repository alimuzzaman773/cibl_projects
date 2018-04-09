
<?php

class Sms_service {

    public function smsService($smsData) {
        $smsWebService = 'http://192.168.5.77/EasySMS/webservices/SmsManager.asmx?WSDL';

        $param = array('user_id' => 'EBLSKY',
            'password' => '123',
            'sms_tmpl_id' => '002',
            'mobile_no' => $smsData['mobileNo'],
            'sms' => $smsData['message']);

        // $client = new SoapClient($smsWebService,array("trace" => 1, "exception" => 0));
        // $result = $client->SendSms($param)->SendSmsResult;


        $result = "SUCCESS";

        return $result;
    }

}
?>



