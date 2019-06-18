<?php

class Common_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->library('BOcrypter');
    }

    public function numberMasking($mask, $number) {
        return substr($number, 0, $mask) . str_repeat("*", strlen($number) - ($mask + 4)) . substr($number, ($mask - 10));
    }

    public function get_token_by_code($code) {
        $tokenno = "0";
        $query = $this->db->get_where('token', array('code' => $code));
        $row_array = $query->row_array();


        $exp_date = $row_array['Dt'];
        $todays_date = date('Ymd');
        $today = strtotime($todays_date);
        $expiration_date = strtotime($exp_date);


        if ($today > $expiration_date) {
            $qry = "UPDATE token SET Dt='" . date('Ymd') . "',tokenNo='0001' WHERE Dt='" . $exp_date . "'" . "AND code='" . $code . "'";
            $res1 = $this->db->query($qry);
            $tokenno = '0001';
        } else {
            $qry = "UPDATE token SET tokenNo='" . str_pad($row_array['tokenNo'] + 1, 4, "0", STR_PAD_LEFT) . "' WHERE Dt='" . $todays_date . "'" . "AND code='" . $code . "'";
            $res1 = $this->db->query($qry);
            $tokenno = str_pad($row_array['tokenNo'] + 1, 4, "0", STR_PAD_LEFT);
        }
        return $tokenno;
    }

    public function insert_service_request($data) {
        return $this->db->insert('service_request', $data);
    }

    public function generateRandomString($length = 6) {
        //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '12345';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        $data = $this->bocrypter->Encrypt($randomString);
        return $data;
    }

    public function send_service_mail($maildata) {
        //$this->load->library("phpmailer");
        include_once APPPATH.'libraries/phpmailer/phpmailer.php';
        
        $mail = new PHPMailer(true);

        try {
            
            set_smtp_config($mail);
            $mail->From = 'noreply@premierbankltd.com';
            $mail->FromName = 'pmoney Smart Banking Admin Panel';

            $recepients = explode(";", $maildata['to']);
            foreach ($recepients as $index => $value) {
                $mail->addAddress($value);
            }

            $ccRecepients = explode(";", $maildata['cc']);
            if ($ccRecepients) {
                foreach ($ccRecepients as $ccIndex => $ccValue) {
                    $mail->addCC($ccValue);
                }
            }

            $bccRecepients = explode(";", $maildata['bcc']);
            if ($bccRecepients) {
                foreach ($bccRecepients as $bccIndex => $bccValue) {
                    $mail->addBCC($value);
                }
            }

            $mail->isHTML(true);
            $mail->Subject = $maildata['subject'];
            $mail->Body = $maildata['body'];


            if (!$mail->send()) {
                error_log("could not send mail at ".__CLASS__." ".__METHOD__." ".__FILE__." ".__LINE__);                
                return array(
                    'success' => false,
                    'msg' => 'unknown error'
                );
            }

            return array('success' => true);
        } catch (phpmailerException $ex) {
            error_log($ex->getMessage());
            return array(
                'success' => false,
                'msg' => $ex->getMessage()
            );
            return 0;
        }
    }

    public function send_mail($maildata) {
        //$this->load->library("phpmailer");
        include_once APPPATH.'libraries/phpmailer/phpmailer.php';
        
        $mail = new PHPMailer(true);

        try {
            
            set_smtp_config($mail);
            
            $mail->FromName = 'EBL SKYBANKING Admin Panel';

            $recepients = explode(";", $maildata['to']);
            foreach ($recepients as $index => $value) {
                $mail->addAddress($value);
            }

            $mail->isHTML(true);
            $mail->Subject = $maildata['subject'];
            $mail->Body = $maildata['body'];


            if (!$mail->send()) {
                return 0;
            }

            return 1;
        } catch (phpmailerException $ex) {
            return 0;
        }
    }

}
