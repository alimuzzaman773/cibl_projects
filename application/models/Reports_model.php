<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports_model extends CI_Model {

    // Active user report
    public function get_user_status($status) {
        if ($status == "all") {
            $value = "isPublished=1";
        } else if ($status == "active") {
            $value = "isActive=1 AND isPublished=1";
        } else if ($status == "inactive") {
            $value = "isActive=0 AND isPublished=1";
        } else if ($status == "unlocked") {
            $value = "isLocked=0 AND isPublished=1";
        } else if ($status == "locked") {
            $value = "isLocked=1 AND isPublished=1";
        }
        $query = $this->db->query("SELECT * FROM apps_users_mc WHERE " . $value);

        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    // Type of Fund transfer report separated by Transaction type Column
    public function get_fund_transfer($transaction_type, $from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $query = $this->db->select('a.cfid, a.eblSkyId, a.creationDtTm, a.fromAccNo, a.toAccNo, a.amount, a.isSuccess, a.trnReference, b.userMobNo1, b.userEmail, b.userName')
                ->from('apps_transaction a')
                ->where('trnType', $transaction_type)
                ->where('creationDtTm >=', $from_date)
                ->where('creationDtTm <=', $to_date)
                ->join('apps_users b', 'a.skyId=b.skyId')
                ->get();
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    // Type of Fund transfer report separated by Transaction type Column
    public function get_other_fund_transfer($from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $query = $this->db->select('cfid, eblSkyId, creationDtTm, fromAccNo, toAccNo, amount, rcvrName, rcvrRtNo, rcvrBankName, rcvrBrunName, trnReference, isSuccess')
                ->from('apps_transaction')
                ->where('trnType', '08')
                ->where('creationDtTm >=', $from_date)
                ->where('creationDtTm <=', $to_date)
                ->get();
        return $query->num_rows() > 0 ? $query->result() : false;
    }

    // Type of bill pay report separated by report type
    public function get_bill_pay($type, $from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        switch ($type) {
            case 1:
                $query = $this->db->select('billerName, billerCode as billCode')
                        ->from('apps_bill_pay')
                        ->group_by('billerName')
                        ->order_by('creationDtTm', 'DESC')
                        ->get();
                $result = $query->result_array();
                foreach ($result as $key => $row) {
                    $qry = $this->db->select('creationDtTm, sourceAccNo, isSuccess, apps_bill_pay.eblSkyId, cfId, userName, billTypeName, amount')
                            ->from('apps_bill_pay')
                            ->join('apps_users', 'apps_bill_pay.skyId=apps_users.skyId', 'left')
                            ->where('billerCode', $row['billCode'])
                            ->where('creationDtTm >=', $from_date)
                            ->where('creationDtTm <=', $to_date)
                            ->get();
                    $row['billerCode'] = $qry->result_array();
                    $result[$key] = $row;
                }
                break;
            case 2:
                $query = $this->db->select('skyId as sky_id, eblSkyId, cfId, userName')
                        ->from('apps_users')
                        ->group_by('skyId')
                        ->order_by('skyId', 'DESC')
                        ->get();
                $result = $query->result_array();
                foreach ($result as $key => $row) {
                    $qry = $this->db->select('creationDtTm, billerName, billerCode, sourceAccNo, isSuccess, billTypeName, amount')
                            ->from('apps_bill_pay')
                            ->where('skyId', $row['sky_id'])
                            ->where('creationDtTm >=', $from_date)
                            ->where('creationDtTm <=', $to_date)
                            ->get();
                    $row['skyId'] = $qry->result_array();
                    $result[$key] = $row;
                }
        }
        return $result;
    }

    // Customer record with creation date
    public function get_customer_info($from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $query = $this->db->select('apps_users_mc.*, admin_users.adminUserName, atms.ATMName')
                ->where('apps_users_mc.createdDtTm >=', $from_date)
                ->where('apps_users_mc.createdDtTm <=', $to_date)
                ->where('apps_users_mc.isPublished', 1)
                ->select('apps_users_mc.*, admin_users.adminUserName, admin_users.fullName')
                ->from('apps_users_mc')
                ->join('admin_users', 'apps_users_mc.createdBy = admin_users.adminUserId', 'left')
                ->join('atms', 'apps_users_mc.homeBranchCode = atms.branchCode', 'left')
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    //Login record by date
    public function get_login_info($from_date, $to_date) {
        if ($from_date && $to_date) {
            $from_date = $from_date . " 00:00:00";
            $to_date = $to_date . " 23:59:59";
            $this->db->select('*');
            $this->db->from('login_appsuser');
            $this->db->where('login_appsuser.loginDtTm >=', $from_date);
            $this->db->where('login_appsuser.loginDtTm <=', $to_date);
            $this->db->join('apps_users_mc', 'apps_users_mc.skyId=login_appsuser.skyId');
            $this->db->join('device_info_mc', 'device_info_mc.deviceId = login_appsuser.deviceId');
            $query = $this->db->get();
            return ($query->num_rows() > 0) ? $query->result() : false;
        } else {
            $this->db->select('*');
            $this->db->from('login_appsuser');
            $this->db->join('apps_users_mc', 'apps_users_mc.skyId=login_appsuser.skyId');
            $this->db->join('device_info_mc', 'device_info_mc.deviceId = login_appsuser.deviceId');
            $query = $this->db->get();
            return ($query->num_rows() > 0) ? $query->result() : false;
        }
    }

    //get user information by date range
    public function get_id_creation_info($from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $query = $this->db->select('*')
                ->from('apps_users')
                ->where('apps_users.createdDtTm >=', $from_date)
                ->where('apps_users.createdDtTm <=', $to_date)
                ->join('admin_users', 'admin_users.adminUserId = apps_users.createdBy')
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    //User data get by lock status column
    public function get_user_lock_status($report_type, $from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $query = $this->db->select("activityJson")
                ->from("bo_activity_log")
                ->where("tableName", "apps_users")
                ->where("actionCode", $report_type)
                ->where("creationDtTm >=", $from_date)
                ->where("creationDtTm <=", $to_date)
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    //User data modification by admin
    public function get_id_modification($from_date, $to_date) {
        $query = $this->db->select("*")
                ->from("apps_users_mc")
                ->where("makerActionDt >=", $from_date)
                ->where("makerActionDt <=", $to_date)
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    //User data get by is active status column
    public function get_id_inactive($from_date, $to_date) {
        $query = $this->db->select('cfId, eblSkyId, apps_users.checkerActionDt, adminUserName, userEmail')
                ->from('apps_users')
                ->where('apps_users.checkerActionDt >=', $from_date)
                ->where('apps_users.checkerActionDt <=', $to_date)
                ->where('apps_users.isActive', 0)
                ->join('admin_users', 'apps_users.makerActionBy = admin_users.adminUserId AND apps_users.checkerActionBy = admin_users.adminUserId', 'left')
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    //Product request report
    public function get_product_request($from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $query = $this->db->select("*")
                ->from("product_apply_request")
                ->where("creationDtTm >=", $from_date)
                ->where("creationDtTm <=", $to_date)
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function get_product_mail() {
        $query = $this->db->select("*")
                ->from("product_request_mail")
                ->order_by("requestApplyId", "ASC")
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    //Banking request report
    public function get_banking_request($from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $query = $this->db->select("*")
                ->from("service_request_bank")
                ->where("service_request_bank.creationDtTm >=", $from_date)
                ->where("service_request_bank.creationDtTm <=", $to_date)
                ->join("apps_users", "service_request_bank.skyId=apps_users.skyId", "left")
                ->join("service_type", "service_request_bank.typeCode=service_type.serviceTypeCode", "left")
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function get_banking_mail() {
        $query = $this->db->select("*")
                ->from("banking_request_mail")
                ->order_by("requestApplyId", "ASC")
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    //Banking request report
    public function get_priority_request($from_date, $to_date) {
        $from_date = $from_date . " 00:00:00";
        $to_date = $to_date . " 23:59:59";
        $query = $this->db->select("*")
                ->from("service_request")
                ->where("service_request.creationDtTm >=", $from_date)
                ->where("service_request.creationDtTm <=", $to_date)
                ->join("apps_users", "service_request.skyId=apps_users.skyId", "left")
                ->join("service_type", "service_request.typeCode=service_type.serviceTypeCode", "left")
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function get_priority_mail() {
        $query = $this->db->select("*")
                ->from("priority_request_mail")
                ->order_by("requestApplyId", "ASC")
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

}
