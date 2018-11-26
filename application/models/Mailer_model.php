<?php

defined('BASEPATH') OR exit('No direct script access allowed');

#use PHPMailer\PHPMailer\PHPMailer;
#use PHPMailer\PHPMailer\Exception;

class Mailer_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function sendMail($mailData = array()) {
        include_once APPPATH . "libraries/phpmailer/phpmailer.php";
        $mailer = new PHPMailer(true);
        try {
            $mailer->isHTML(true);

            set_smtp_config($mailer);

            $mailer->addAddress($mailData["to"]);
            $mailer->From = $mailData["from"];
            $mailer->Subject = $mailData["subject"];
            $mailer->Body = $mailData["body"];

            $mailer->Send();

            return array(
                "success" => true
            );
        } catch (phpmailerException $pe) {
            return array(
                "success" => false,
                "msg" => $pe->getMessage()
            );
        } catch (Exception $ex) {
            return array(
                "success" => false,
                "msg" => $ex->getMessage()
            );
        }
    }

}

/** end of file **/