
<?php

class Email_service {

    public function emailService($emailData) {

        include_once APPPATH . "libraries/Requests.php";
        Requests::register_autoloader();

        $params = array(
            "email" => $emailData["email"],
            "subject" => $emailData["subject"],
            "body" => $emailData["body"]
        );

        if (defined('dummy_email') && dummy_email):
            $params["email"] = dummy_email;
        endif;

        try {
             $url = base_url()."api/services/send_email";
             
            //using for mailgun
           // $url = base_url() . "services/send_email";

            $request = Requests::post($url, array(), $params);

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
