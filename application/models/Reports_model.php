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
        $query = $this->db->query("SELECT * FROM apps_users WHERE " . $value);

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
        $query = $this->db->select('apps_users.*, admin_users.adminUserName, atms.ATMName')
                ->where('apps_users.makerActionDt >=', $from_date)
                ->where('apps_users.makerActionDt <=', $to_date)
                ->where('apps_users.isPublished', 1)
                ->select('apps_users.*, admin_users.adminUserName, admin_users.fullName')
                ->from('apps_users')
                ->join('admin_users', 'apps_users.checkerActionBy = admin_users.adminUserId', 'left')
                ->join('atms', 'apps_users.homeBranchCode = atms.branchCode', 'left')
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
            $this->db->join('apps_users', 'apps_users.skyId=login_appsuser.skyId');
            $this->db->join('device_info', 'device_info.deviceId = login_appsuser.deviceId');
            $query = $this->db->get();
            return ($query->num_rows() > 0) ? $query->result() : false;
        } else {
            $this->db->select('*');
            $this->db->from('login_appsuser');
            $this->db->join('apps_users', 'apps_users.skyId=login_appsuser.skyId');
            $this->db->join('device_info', 'device_info.deviceId = login_appsuser.deviceId');
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
        $query = $this->db->select("am.*, adm.adminUserName as makerName, adm2.adminUserName as checkerName", false)
                ->from("apps_users am")
                ->join("admin_users adm", "adm.adminUserId = am.makerActionBy", "left")
                ->join("admin_users adm2", "adm2.adminUserId = am.checkerActionBy", "left")
                ->where("am.makerActionDt >=", $from_date)
                ->where("am.makerActionDt <=", $to_date)
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

    public function get_priority_mail($from_date = null, $to_date = null) {
        $this->db->select("prm.*,sr.serviceRequestID, sr.creationDtTm as serviceCreationDtTm", false)
                ->from("priority_request_mail prm")
                ->join("service_request sr", "sr.serviceRequestID = prm.requestApplyId", "left")
                ->order_by("requestApplyId", "ASC");

        if ($from_date != null && $to_date != null):
            $this->db->where("sr.creationDtTm between {$this->db->escape($from_date)} AND {$this->db->escape($to_date)}", null, false);
        endif;

        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    function getCardPayments($p = array()) {
        $this->db->select("abp.*, au.userName", FALSE)
                ->from("apps_bill_pay abp")
                ->join("apps_users au", "au.skyId = abp.skyId", "left")
                ->join("biller_setup bs", "bs.billerId = abp.billerId", "left");

        if (isset($p['fromdate']) && isset($p['todate'])):
            $this->db->group_start()
                    ->where("date(abp.creationDtTm) between {$this->db->escape($p['fromdate'])} AND {$this->db->escape($p['todate'])}", null, false)
                    ->group_end();
        endif;

        if (isset($p['billerId']) && (int) $p['billerId'] > 0):
            $this->db->where('abp.billerId', $p['billerId']);
        endif;

        if (isset($p['isSuccess'])):
            $this->db->where('abp.isSuccess', $p['isSuccess']);
        endif;

        $result = $this->db->order_by("abp.creationDtTm", "DESC")
                ->get();
        return $result->num_rows() > 0 ? $result : false;
    }

    function getMobilePaymentByCard($p = array()) {
        $this->db->select("abp.*, au.userName", FALSE)
                ->from("apps_bill_pay abp")
                ->join("apps_users au", "au.skyId = abp.skyId", "left")
                ->join("biller_setup bs", "bs.billerId = abp.billerId", "left");

        if (isset($p['fromdate']) && isset($p['todate'])):
            $this->db->group_start()
                    ->where("date(abp.creationDtTm) between {$this->db->escape($p['fromdate'])} AND {$this->db->escape($p['todate'])}", null, false)
                    ->group_end();
        endif;

        if (isset($p['billerId']) && (int) $p['billerId'] > 0):
            $this->db->where('abp.billerId', $p['billerId']);
        endif;

        if (isset($p['isSuccess'])):
            $this->db->where('abp.isSuccess', $p['isSuccess']);
        endif;

        $result = $this->db->order_by("abp.creationDtTm", "DESC")
                ->get();
        return $result->num_rows() > 0 ? $result : false;
    }

    function getCallCenterUserList($p = array()) {
        $dateTime = date("Y-m-d H:i:s");

        $this->db->select("aum.*, au.skyId as skyIdOriginal, ra.created_on as registrationDate, ra.entityType,"
                . " TIMESTAMPDIFF(SECOND, CONCAT(aum.makerActionDt, ' ', aum.makerActionTm), '{$dateTime}') as activationDiff,"
                . " TIMESTAMPDIFF(SECOND, aum.passwordResetDtTm, '{$dateTime}') as passwordResetDiff, adu.fullName as adminFullname, a.ATMName as branchName,", false);

        $this->db->from('apps_users_mc aum')
                ->join('apps_users au', "au.skyId = aum.skyId", "left")
                ->join('atms a', 'aum.homeBranchCode = a.branchCode', 'left')
                ->join('registration_attempts ra', 'ra.skyId = aum.skyId', 'left')
                ->join('admin_users adu', "adu.adminUserId = aum.checkerActionBy", "left")
                ->order_by('aum.skyId', 'desc');

        if (isset($p['skyIdOriginal']) && $p['skyIdOriginal'] == 0) {
            $this->db->having('(skyIdOriginal IS NULL OR skyIdOriginal <= 0)', null, false);
            if (isset($p['fromdate']) && isset($p['todate'])) {
                $this->db->where("ra.created_on between {$this->db->escape($p['fromdate'])} AND {$this->db->escape($p['todate'])}", null, false);
            }
        }

        if (isset($p['branch']) && trim($p['branch']) != ''):
            $this->db->where("a.branchCode", $p["branch"]);
        endif;

        if (isset($p['approved_by']) && (int) $p['approved_by'] > 0):
            $this->db->where("aum.checkerActionBy", $p['approved_by']);
        endif;

        if (isset($p['skyIdOriginal']) && (int) $p['skyIdOriginal'] > 0) {
            $this->db->having('(skyIdOriginal > 0)', null, false);
            if (isset($p['fromdate']) && isset($p['todate'])) {
                $this->db->where("ra.created_on between {$this->db->escape($p['fromdate'])} AND {$this->db->escape($p['todate'])}", null, false);
            }
        }

        if (isset($p['passwordReset']) && $p['passwordReset'] != NULL) {
            $this->db->where('aum.passwordReset', $p['passwordReset']);
        }

        if (isset($p['activationPending24']) && (int) $p['activationPending24'] > 0) {
            $this->db->having("activationDiff > {$this->db->escape($p['activationPending24'])}", null, false);
        }

        if (isset($p['passwordResetPending24']) && (int) $p['passwordResetPending24'] > 0) {
            $this->db->having("passwordResetDiff > {$this->db->escape($p['passwordResetPending24'])}", null, false);
        }

        if (isset($p['search']) && trim($p['search']) != ''):
            $this->db->group_start()
                    ->or_like('aum.skyId', $p['search'])
                    ->or_like('aum.eblSkyId', $p['search'])
                    ->or_like('aum.userName', $p['search'])
                    ->or_like('aum.cfId', $p['search'])
                    ->or_like('aum.clientId', $p['search'])
                    ->or_like('aum.prepaidId', $p['search'])
                    ->or_like('aum.userEmail', $p['search'])
                    ->or_like('aum.userMobNo1', $p['search'])
                    ->group_end();
        endif;

        if (isset($p['limit']) && (int) $p['limit'] > 0) {
            $offset = (isset($p['offset']) && $p['offset'] != null) ? (int) $p['offset'] : 0;
            $this->db->limit($p['limit'], $offset);
        }

        $sql = '';
        if (isset($p['get_count']) && (int) $p['get_count'] > 0):
            $sql = $this->db->get_compiled_select();
            $sql = "select count(*) as total from ({$sql}) as intbl";
        else:
            $sql = $this->db->get_compiled_select();
        endif;

        $query = $this->db->query($sql);
        return $query->num_rows() > 0 ? $query : false;
    }

    function getCustomerActivity($p = array()) {
        $this->db->select("au.*, al.commDtTm as registrationDate, ra.entityType,"
                . " al.actionName, al.actionCode,al.commDtTm", false);

        $this->db->from('apps_users au')
                ->join('registration_attempts ra', 'ra.skyId = au.skyId', 'left')
                ->join('app_user_activity_log al', 'al.skyId = au.skyId', 'left')
                ->order_by('au.skyId', 'desc');

        if (isset($p['eblSkyId']) && trim($p['eblSkyId']) != '') {
            $this->db->group_start()
                    ->where("au.eblSkyId", $p['eblSkyId'])
                    ->or_where("au.userName2", $p['eblSkyId'])
                    ->group_end();
        }

        if (isset($p['fromdate']) && isset($p['todate'])) {
            $this->db->where("date(al.commDtTm) between {$this->db->escape($p['fromdate'])} AND {$this->db->escape($p['todate'])}", $p['eblSkyId']);
        }
        $this->db->order_by("al.commDtTm", "DESC");
        $sql = $this->db->get_compiled_select();

        $query = $this->db->query($sql);
        return $query->num_rows() > 0 ? $query : false;
    }

    function getFundTransferDetails($params = array()) {
        $trn_type = array('05', '06', '07', '08', 'rtgs', 'nagad');
        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(t.transferId) as total");
        } else {
            $this->db->select('t.*, au.userName, au.userEmail', FALSE);
        }

        $this->db->from("apps_transaction" . " t")
                ->join("apps_users" . " au", "au.skyId = t.skyId", "left")
                ->where_in("t.trnType", $trn_type);

        if (isset($params['status']) && trim($params['status']) != ""):
            $this->db->where("t.isSuccess", $params['status']);
        endif;

        if (isset($params['trn_type']) && trim($params['trn_type']) != ""):
            $this->db->where("t.trnType", $params['trn_type']);
        endif;

        if (isset($params['fromdate']) && isset($params['todate']) && $params['fromdate'] != null && $params['todate'] != null):
            $this->db->where("(DATE(t.creationDtTm) between " . $this->db->escape($params['fromdate']) . " AND " . $this->db->escape($params['todate']) . ")");
        endif;

        if (isset($params['limit']) && (int) $params['limit'] > 0):
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        endif;

        $result = $this->db->order_by("t.transferId", "DESC")->get();
        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function getUtilityBillList($params = array()) {
        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(p.payment_id) as total");
        } else {
            $this->db->select('p.*, au.eblSkyId,au.userName, au.userMobNo1 as mobileNo, bpt.fromAccNo as bpt_from_ac, bpt.amount as bpt_amount, bpt.narration as bpt_narration, bpt.isSuccess as bpt_success,'
                    . 'vt.fromAccNo as vt_from_ac, vt.amount as vt_amount, st.fromAccNo as st_from_ac, st.amount as st_amount,'
                    . 'lt.fromAccNo as lt_from_ac, lt.amount as lt_amount, o1t.fromAccNo as o1t_from_ac, o1t.amount as o1t_amount,'
                    . 'o2t.fromAccNo as o2t_from_ac, o2t.amount as o2t_amount', FALSE);
        }

        $this->db->from(TBL_SSL_BILL_PAYMENT . " p")
                ->join(TBL_APP_USERS_MC . " au", "au.skyId = p.skyId", "inner")
                ->join(TBL_APPS_TRANSACTION . " bpt", "bpt.transferId = p.bp_transfer_id", "left")
                ->join(TBL_APPS_TRANSACTION . " vt", "vt.transferId = p.vat_transfer_id", "left")
                ->join(TBL_APPS_TRANSACTION . " st", "st.transferId = p.stamp_transfer_id", "left")
                ->join(TBL_APPS_TRANSACTION . " lt", "lt.transferId = p.lpc_transfer_id", "left")
                ->join(TBL_APPS_TRANSACTION . " o1t", "o1t.transferId = p.other1_transfer_id", "left")
                ->join(TBL_APPS_TRANSACTION . " o2t", "o2t.transferId = p.other2_transfer_id", "left");
//->where("p.isSuccess", "Y");

        if (isset($params['payment_id']) && (int) $params['payment_id']):
            $this->db->where("p.payment_id", $params['payment_id']);
        endif;

        if (isset($params['skyId']) && (int) $params['skyId']):
            $this->db->where("p.skyId", $params['skyId']);
        endif;

        if (isset($params['utility']) && trim($params['utility']) != ""):
            $this->db->where("p.utility_name", $params['utility']);
        endif;

        if (isset($params['status']) && trim($params['status']) != ""):
            $this->db->where("p.isSuccess", $params['status']);
        endif;

        if (isset($params['search']) && trim($params['search']) != ""):
            $this->db->group_start()
                    ->or_like("bpt.fromAccNo", $params['search'], 'both')
                    ->or_like("bpt.amount", $params['search'], 'both')
                    ->group_end();
        endif;

        if (isset($params['fromdate']) && isset($params['todate']) && $params['fromdate'] != null && $params['todate'] != null):
            $this->db->where("(DATE(p.created) between " . $this->db->escape($params['fromdate']) . " AND " . $this->db->escape($params['todate']) . ")");
        endif;

        if (isset($params['limit']) && (int) $params['limit'] > 0):
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        endif;

        $result = $this->db->order_by("p.payment_id", "DESC")->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function getRequestLogReport($params = array()) {
        $this->db->select("x.*", false);
        $this->db->from('xml_logs x');

        if (isset($params['search']) && trim($params['search']) != ""):
            $this->db->group_start()
                    ->or_like("x.requestXml", $params['search'], 'both')
                    ->or_like("x.responseXml", $params['search'], 'both')
                    ->group_end();
        endif;

        if (isset($params['fromdate']) && isset($params['todate']) && $params['fromdate'] != null && $params['todate'] != null):
            $this->db->where("(DATE(x.created) between " . $this->db->escape($params['fromdate']) . " AND " . $this->db->escape($params['todate']) . ")");
        endif;

        if (isset($params['limit']) && (int) $params['limit'] > 0):
            $this->db->limit($params['limit'], 0);
        endif;

        $this->db->order_by("x.id", "DESC");
        $sql = $this->db->get_compiled_select();

        $query = $this->db->query($sql);
        return $query->num_rows() > 0 ? $query : false;
    }

}
