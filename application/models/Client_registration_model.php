<?php

class Client_registration_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
    }

    public function getAllAppsUsers($p) {
        if (isset($p['get_count']) && (int) $p['get_count'] > 0):
            $this->db->select('count(*) as total', false);
        else:
            $this->db->select('apps_users_mc.*, apps_users_group.userGroupName, atms.ATMName as branchName');
        endif;

        $this->db->from('apps_users_mc');
        $this->db->join('apps_users_group', 'apps_users_mc.appsGroupId = apps_users_group.appsGroupId', 'left');
        $this->db->join('atms', 'apps_users_mc.homeBranchCode = atms.branchCode', 'left');
        //$this->db->where("apps_users_mc.salt2 IS Null");
        //$this->db->where('apps_users_mc.makerActionBy', $this->my_session->userId);
        $this->db->where_in('apps_users_mc.mcStatus', array(1, 2));

        $this->db->order_by('apps_users_mc.makerActionDt', 'desc');
        $this->db->order_by('apps_users_mc.makerActionTm', 'desc');
        $this->db->order_by('apps_users_mc.skyId', 'desc');

        if (isset($p['search']) && trim($p['search']) != ''):
            $this->db->group_start()
                    ->or_like('apps_users_mc.skyId', $p['search'])
                    ->or_like('apps_users_mc.eblSkyId', $p['search'])
                    ->or_like('apps_users_mc.userName', $p['search'])
                    ->or_like('apps_users_mc.userName2', $p['search'])
                    ->or_like('apps_users_mc.cfId', $p['search'])
                    ->or_like('apps_users_mc.clientId', $p['search'])
                    ->or_like('apps_users_mc.prepaidId', $p['search'])
                    ->or_like('apps_users_mc.userEmail', $p['search'])
                    ->or_like('apps_users_mc.userMobNo1', $p['search'])
                    ->or_like('apps_users_mc.fatherName', $p['search'])
                    ->or_like('apps_users_mc.motherName', $p['search'])
                    ->or_like('atms.ATMName', $p['search'])
                    ->group_end();
        endif;

        if (isset($p['isLocked']) && trim($p['isLocked']) != '') {
            $this->db->where('apps_users_mc.isLocked', $p['isLocked']);
        }

        if (isset($p['isActive']) && trim($p['isActive']) != '') {
            $this->db->where('apps_users_mc.isActive', $p['isActive']);
        }
        
        if (isset($p['isOwnAccTransfer']) && trim($p['isOwnAccTransfer']) != ''):
            $this->db->where('apps_users_mc.isOwnAccTransfer', $p['isOwnAccTransfer']);
        endif;
        
        if (isset($p['isInterAccTransfer']) && trim($p['isInterAccTransfer']) != ''):
            $this->db->where('apps_users_mc.isInterAccTransfer', $p['isInterAccTransfer']);
        endif;
        
        if (isset($p['isOtherAccTransfer']) && trim($p['isOtherAccTransfer']) != ''):
            $this->db->where('apps_users_mc.isOtherAccTransfer', $p['isOtherAccTransfer']);
        endif;
        
        if (isset($p['isAccToCardTransfer']) && trim($p['isAccToCardTransfer']) != ''):
            $this->db->where('apps_users_mc.isAccToCardTransfer', $p['isAccToCardTransfer']);
        endif;
        
        if (isset($p['isCardToAccTransfer']) && trim($p['isCardToAccTransfer']) != ''):
            $this->db->where('apps_users_mc.isCardToAccTransfer', $p['isCardToAccTransfer']);
        endif;
        
        if (isset($p['isUtilityTransfer']) && trim($p['isUtilityTransfer']) != ''):
            $this->db->where('apps_users_mc.isUtilityTransfer', $p['isUtilityTransfer']);
        endif;
        
        if (isset($p['isQrPayment']) && trim($p['isQrPayment']) != ''):
            $this->db->where('apps_users_mc.isQrPayment', $p['isQrPayment']);
        endif;

        if (isset($p['limit']) && (int) $p['limit'] > 0) {
            $offset = (isset($p['offset']) && $p['offset'] != null) ? (int) $p['offset'] : 0;
            $this->db->limit($p['limit'], $offset);
        }
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query : false;
    }

    public function countVerifiedDevice($skyId) {
        $query = $this->db->select("deviceId")
                ->from("device_info_mc")
                ->where("skyId", $skyId)
                ->where("isVaryfied", 1)
                ->get();
        return $query->num_rows() > 0 ? $query : false;
    }

    /*
      public function countVerifiedDevice()
      {
      $query = $this->db->query('SELECT count(deviceid) as deviceCount, skyId FROM `device_info_mc` WHERE `isVaryfied` = 1 GROUP BY skyId');
      return $query->result_array();
      }

      public function countNonVerifiedDevice()
      {
      $query = $this->db->query('SELECT count(deviceid) as deviceCount, skyId FROM `device_info_mc` WHERE `isVaryfied` = 0 GROUP BY skyId');
      return $query->result_array();
      }


      public function countTotalDevice()
      {
      $query = $this->db->query('SELECT count(deviceid) as deviceCount, skyId FROM `device_info_mc` GROUP BY skyId');
      return $query->result_array();
      }
     */

    public function getAppsUsersById($data) {

        $this->db->select('apps_users_mc.*, apps_users_group.userGroupName');
        $this->db->from('apps_users_mc');
        $this->db->join('apps_users_group', 'apps_users_group.appsGroupId = apps_users_mc.appsGroupId', 'left');
        $this->db->where('skyId', $data);
        return $this->db->get()->row_array();
    }

    public function getDeviceBySkyid($data = NULL) {
        $this->db->select("di.*, au.eblSkyId", false)
                ->from("device_info di")
                ->join("apps_users_mc au", "au.skyId = di.skyId", "inner");

        if ($data != NULL) {
            $this->db->where("di.skyId", $data);
        }

        return $this->db->get()->result_array();
    }

    public function getImeiByNumber($data) {

        $this->db->select('apps_users_mc.eblSkyId, device_info_mc.*');
        $this->db->from('device_info_mc');
        $this->db->join('apps_users_mc', 'apps_users_mc.skyId = device_info_mc.skyId');
        $this->db->where('imeiNo', $data);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function checkDuplicateImei($data) {
        $deviceId = $data['deviceId'];
        $imeiNo = $data['imeiNo'];
        $this->db->where("imeiNo = {$this->db->escape($imeiNo)} AND deviceId != {$this->db->escape($deviceId)}");
        $this->db->from('device_info_mc');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function insertImeiNo($data) {
        unset($data['eblSkyId']);
        unset($data['selectedActionName']);
        $this->db->insert('device_info_mc', $data);
        return $this->db->insert_id();
    }

    public function updateImeiNo($data, $id) {
        $this->db->where('deviceId', $id);
        $this->db->update('device_info_mc', $data);
    }

    public function userDeleteChange($data, $skyId) {
        $this->db->where("skyId", $skyId)
                ->update("apps_users_mc", $data);
        return true;
    }

    function setAppsUserGroup($skyId, $groupId) {
        try {
            $this->db->trans_begin();

            $groupData = array(
                "appsGroupId" => $groupId
            );

            $appsUserGroup = $this->db->where("appsGroupId", $groupId)
                    ->get("apps_users_group");

            if ($appsUserGroup->num_rows() <= 0):
                return array(
                    'success' => false,
                    'msg' => "Package limit information not found"
                );
            endif;

            $appsUserGroup = $appsUserGroup->row();

            $grpSuffix = array("oat", "pb", "eat", "obt");
            $groupArray = array(
                "MinTxnLim", "MaxTxnLim", "DayTxnLim", "NoOfTxn", /* "LastDtTm" */
            );

            $groupFields = array();
            foreach ($grpSuffix as $sf):
                foreach ($groupArray as $gp):
                    $gname = $sf . $gp;
                    $groupFields[$gname] = $gname;
                endforeach;
            endforeach;

            foreach ($groupFields as $k => $v):
                $groupData[$v] = $appsUserGroup->{$v};
            endforeach;
            //d($groupData);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users_mc", array("appsGroupId" => $appsUserGroup->appsGroupId));

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users", $groupData);

            if ($this->db->trans_status() == false):
                throw new Exception("error in transaction");
            endif;

            $this->db->trans_commit();

            return array(
                'success' => true
            );
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            return array(
                'success' => false,
                'msg' => $ex->getMessage()
            );
        }
    }

    function updateUser($data = array()) {
        try {
            $this->db->trans_begin();
            $skyId = NULL;

            if (isset($data['skyId']) && (int) $data['skyId'] > 0):
                $this->db->where('skyId', $data['skyId'])
                        ->update(TBL_APP_USERS_MC, $data);

                $skyId = $data['skyId'];
            endif;

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("could not save key in " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            }

            $this->db->trans_commit();

            return array(
                "success" => true,
                "skyId" => $skyId,
                "data" => $data
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();

            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

    function getAppUsers($params = array()) {
        $this->db->select('aum.*', FALSE)
                ->from(TBL_APP_USERS_MC . " aum")
                ->where("aum.skyId", $params['skyId']);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function checkAppsUserData($tblName, $p = array()) {
        $this->db->select("*")
                ->from($tblName);

        if (isset($p['cfId']) && trim($p['cfId']) != ''):
            $this->db->where("cfId", $p['cfId']);
        endif;

        if (isset($p['clientId']) && trim($p['clientId']) != ''):
            $this->db->where("clientId", $p['clientId']);
        endif;

        if (isset($p['skyIdNotIn']) && count($p['skyIdNotIn']) > 0):
            $this->db->where_not_in("skyId", $p['skyIdNotIn']);
        endif;

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function getUserTransaction($p = array()) {
        $query = $this->db->query("SELECT eblSkyId, 
                (SELECT COUNT(transferId) FROM  apps_transaction apt where apt.skyId=trn.skyId and apt.trnType='06' and isSuccess='Y') as 'own_count',
                (SELECT SUM(amount) FROM  apps_transaction apt where apt.skyId=trn.skyId and apt.trnType='06' and isSuccess='Y') as 'own_amount',
                (SELECT COUNT(transferId) FROM  apps_transaction apt where apt.skyId=trn.skyId and apt.trnType='07' and isSuccess='Y') as 'inter_count',
                (SELECT SUM(amount) FROM  apps_transaction apt where apt.skyId=trn.skyId and apt.trnType='07' and isSuccess='Y') as 'inter_amount',
                (SELECT COUNT(transferId) FROM  apps_transaction apt where apt.skyId=trn.skyId and apt.trnType='08' and isSuccess='Y') as 'other_count',
                (SELECT SUM(amount) FROM  apps_transaction apt where apt.skyId=trn.skyId and apt.trnType='08' and isSuccess='Y') as 'other_amount'
                from apps_transaction trn where trn.skyId={$this->db->escape($p['skyId'])}
                GROUP BY skyId");
        return $query;
    }

    function getUserBillTransaction($p = array()) {

        $query = $this->db->query("SELECT eblSkyId, 
                (SELECT COUNT(billPayId) FROM  apps_bill_pay abp where abp.skyId=mabp.skyId and abp.billTypeCode='01' and isSuccess='Y') as 'recharge_count',
                (SELECT SUM(amount) FROM  apps_bill_pay abp where abp.skyId=mabp.skyId and abp.billTypeCode='01' and isSuccess='Y') as 'recharge_amount',
                (SELECT COUNT(billPayId) FROM  apps_bill_pay abp where abp.skyId=mabp.skyId and abp.billTypeCode='02' and isSuccess='Y') as 'wimax_count',
                (SELECT SUM(amount) FROM  apps_bill_pay abp where abp.skyId=mabp.skyId and abp.billTypeCode='02' and isSuccess='Y') as 'wimax_amount',
                (SELECT COUNT(billPayId) FROM  apps_bill_pay abp where abp.skyId=mabp.skyId and abp.billTypeCode='03' and isSuccess='Y') as 'card_count',
                (SELECT SUM(amount) FROM  apps_bill_pay abp where abp.skyId=mabp.skyId and abp.billTypeCode='03' and isSuccess='Y') as 'card_amount'
                from apps_bill_pay mabp where mabp.skyId={$this->db->escape($p['skyId'])}
                GROUP BY skyId");
        return $query;
    }

}
