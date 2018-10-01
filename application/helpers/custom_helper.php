<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//Get Card type from BIN Card List
function checkCardTypes($cardNumber) {
    $binNumber = substr($cardNumber, 0, 6);
    if (($binNumber == 434977) ||
            ($binNumber == 434978) ||
            ($binNumber == 434979) ||
            ($binNumber == 434980) ||
            ($binNumber == 467922) ||
            ($binNumber == 469821) ||
            ($binNumber == 437848) ||
            ($binNumber == 484071) ||
            ($binNumber == 436877) ||
            ($binNumber == 436878)
    ) {
        return "VISA";
    } else if (($binNumber == 360901) ||
            ($binNumber == 360902) ||
            ($binNumber == 360908)
    ) {
        return "DCI";
    } else if (($binNumber == 537683) ||
            ($binNumber == 559581)
    ) {
        return "Master";
    }
}

//Get admin user name by admin ID
function admin_name($admin_id) {
    $CI = & get_instance();
    $query = $CI->db->select('adminUserName')
            ->from('admin_users')
            ->where('adminUserId', $admin_id)
            ->get();
    return $query->row()->adminUserName;
}

//Global Date Format
function show_date($date) {
    if ($date) {
        $data = date("d/m/Y", strtotime($date));
    } else {
        $data = "";
    }
    return $data;
}

//Global date and time format
function show_date_time($date) {
    if ($date) {
        $data = date("d/m/y g:i A", strtotime($date));
    } else {
        $data = "";
    }
    return $data;
}

//Input global date format
function input_date() {
    return date("Y-m-d G:i:s");
}

//getting user home branch from atm table
function getBranchName($branchCode) {
    if ($branchCode) {
        $CI = & get_instance();
        $query = $CI->db->select('ATMName')
                ->from('atms')
                ->where('eblNearYou', 1)
                ->where('branchCode', $branchCode)
                ->get();
        return ($query->num_rows() > 0) ? $query->row()->ATMName : false;
    } else {
        return false;
    }
}

function range_validation($from_date, $to_date) {
    if ($from_date && $to_date) {

        $from_date .= " 00:00:00";
        $to_date .= " 23:59:59";

        if ($from_date > $to_date) {
            return "From date must be lower than To date";
        } else {
            return "ok";
        }
    } else {
        if (!$from_date && !$to_date) {
            return "ok";
        } else {
            return "From date and To date field must be fill up";
        }
    }
}

function text_match($before, $after) {
    if ($before === $after) {
        return $before;
    } else {
        return '<span style="color: red">' . $before . '</span>';
    }
}

function rrn_no() {
    return date("ymdHis") . substr(round(microtime(true) * 10000), -4);
}


if (!function_exists('set_smtp_config')) {

    function set_smtp_config(&$mailer) {
        /*
        $mailer->IsSMTP();
        $mailer->CharSet = 'UTF-8';
        $mailer->SMTPDebug = 1;
        $mailer->SMTPAuth = true;
        $mailer->Host = 'webmail.premierbankltd.com';
        $mailer->Port = 25;
        $mailer->Username = 'testuser103';
        $mailer->Password = 'Asdf1234';
        */
       
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = 'ssl';
        $mailer->Host = 'smtp.mailgun.org';
        $mailer->Port = 465;
        $mailer->Username = "postmaster@travelshoptours.com";
        $mailer->Password = '8a4a14930f9aeedd885ef8dbfddee654';
   
    }

}