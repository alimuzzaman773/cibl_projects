<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
*/
class Mailer_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function sendMail($mailData = array()) {
        include_once APPPATH.'libraries/phpmailer/5.2.9/PHPMailerAutoload.php';        
        try {
            $mailer = new PHPMailer(true);
            $mailer->isHTML(true);
            $mailer->addAddress($mailData["to"]);
            $mailer->setFrom($mailData["from"], "Premier Bank Ltd.");
            $mailer->Subject = $mailData["subject"];
            $mailer->Body = $mailData["body"];
            set_smtp_config($mailer);

            if (!$mailer->send()) {
                return array(
                    "success" => false,
                    "msg" => "unknown error"
                );
            }
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