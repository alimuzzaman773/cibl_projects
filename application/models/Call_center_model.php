<?php

class Call_center_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAllUsers($p) {
        if (isset($p['get_count']) && (int) $p['get_count'] > 0):
            $this->db->select('count(*) as total', false);
        else:
            $this->db->select('aum.*');
        endif;

        $this->db->from('apps_users_mc aum')
                ->group_start()
                //->where('aum.isLocked', 0)
                ->or_where("(isPublished = 0 AND isActive = 0 AND appsGroupId = 0)", null, false)
                //->where("isActive", 0)
                //->where("appsGroupId", 0)
                //->where('callCenterApprove', 'unapproved')
                //->or_where('callCenterApprove', 'pending')
                ->or_where('(isLocked = 1 AND remarks = "password reset request")', null, false)
                ->group_end()
                //->where('aum.isActive', 1)
                //->where('aum.isPublished', 0)
                ->order_by('skyId', 'desc');

        if (isset($p['limit']) && (int) $p['limit'] > 0) {
            $offset = (isset($p['offset']) && $p['offset'] != null) ? (int) $p['offset'] : 0;
            $this->db->limit($p['limit'], $offset);
        }

        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query : false;
    }

    function getUserInfo($userId) {

        $this->db->select('aum.*, ra.raId, ra.entityType, ra.entityNumber, ra.created_on', false)
                ->from('apps_users_mc aum')
                ->join("registration_attempts ra", "ra.skyId = aum.skyId", "left")
                //->where('aum.isLocked', 0)
                ->where("aum.isPublished", 0)
                ->where("aum.isActive", 0)
                ->where("aum.appsGroupId", 0)
                ->where("aum.skyId", $userId)
                ->order_by("ra.raId", "DESC")
                ->limit(1);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function getUserInfoForPasswordReset($userId) {

        $this->db->select('aum.*', false)
                ->from('apps_users aum')
                ->where('aum.remarks', 'Password Reset Request')
                ->where('aum.isLocked', 1)
                //->where("aum.isPublished", 1)
                ->where("aum.isActive", 1)
                ->where("aum.skyId", $userId)
                ->limit(1);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function getUserAccounts($userId, $accountNo = null) {
        $this->db->select('ai.*, at.accTypeName', false)
                ->from('account_info ai')
                ->join("account_type at", "at.accTypeCode = ai.accTypeCode", "left")
                ->where("ai.skyId", $userId);

        if ($accountNo != null):
            $this->db->where("ai.accNo", $accountNo);
        endif;

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function getRegistrationDetails($skyId) {
        $this->db->select("ra.*", false)
                ->from("registration_attempts ra")
                ->join("apps_users_mc au", "au.skyId = ra.skyId", "inner")
                ->where("ra.skyId", $skyId)
                ->order_by("ra.raId", "DESC")
                ->limit(1);

        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function userApproveChecker($userId) {
        $userInfo = array(
            "checkerAction" => 'Account Activation',
            "checkerActionDt" => date("Y-m-d"),
            "checkerActionTm" => date("H:i:s"),
            "checkerActionBy" => $this->my_session->userId,
        );
        $this->db->where("skyId", $userId)
                ->update("apps_users_mc", $userInfo);
        return array(
            "success" => true,
            "msg" => "success"
        );
    }

    function activateAppUserAccount($skyId, $pin, $raId = null) {
        try {
            $this->db->select("aum.*")
                    ->from("apps_users_mc aum")
                    ->where('aum.isLocked', 0)
                    ->where("aum.isPublished", 0)
                    ->where("aum.isActive", 0)
                    ->where("aum.appsGroupId", 0)
                    ->where("aum.skyId", $skyId);

            $result = $this->db->get();
            if ($result->num_rows() <= 0):
                return array(
                    "success" => false,
                    "msg" => "no apps user found"
                );
            endif;

            $userInfo = $result->row_array();

            // Get Default Transaction Limit ID
            $this->db->select("augm.appsGroupId")
                    ->from("apps_users_group_mc augm")
                    ->where('augm.userGroupName', "Default");

            $gResult = $this->db->get();
            if ($gResult->num_rows() <= 0):
                d($gResult);
                return array(
                    "success" => false,
                    "msg" => "No Default transaction limit found, please set a default transaction limit."
                );
            endif;

            $gInfo = $result->row();
            $appsGroupId = (int) $gInfo->appsGroupId;
            
            d($appsGroupId);
            
            /*End Default Transaction*/

            $userInfoMerge = array(
                "appsGroupId" => $appsGroupId,
                "makerAction" => 'Account Activation',
                "makerActionDt" => date("Y-m-d"),
                "makerActionTm" => date("H:i:s"),
                "makerActionBy" => $this->my_session->userId,
                "passWord" => md5($pin),
                "pinExpiryReferenceTm" => date("Y-m-d H:i:s"),
                "isActive" => 1,
                "isPublished" => 1,
                "isLocked" => 0,
                "isReset" => 1
            );

            $userInfo = array_merge($userInfo, $userInfoMerge);

            $this->db->reset_query();
            $this->db->select("au.*", false)
                    ->from("apps_users au")
                    ->where("au.skyId", $userInfo['skyId']);

            $appsUser = $this->db->get();
            if ($appsUser->num_rows() > 0):
                return array(
                    "success" => false,
                    "msg" => "Apps User already exists"
                );
            endif;


            $this->db->trans_begin();

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users_mc", $userInfoMerge);

            $this->db->reset_query();
            $this->db->insert("apps_users", $userInfo);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("device_info", array("isVaryfied" => 1));

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("could not activate apps user in " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            }

            $this->db->trans_commit();

            return array(
                "success" => true,
                "userInfo" => $userInfo
            );
        } catch (Exception $ex) {
            $this->db->trans_rollback();

            return array(
                "success" => false,
                "msg" => $ex->getMessage()
            );
        }
    }

    function approveConfirmation($userId) {

        $this->db->select("*")
                ->from("apps_users_mc")
                ->where('skyId', $userId);

        $query = $this->db->get();

        $tableData = $query->row_array();

        try {
            $this->db->trans_begin();

            $this->db->insert('apps_users', $tableData);

            $activityLog = array('activityJson' => json_encode($tableData),
                'adminUserId' => $this->my_session->userId,
                'adminUserName' => $this->my_session->userName,
                'tableName' => 'apps_users',
                'moduleName' => 'apps_user_module',
                'moduleCode' => '01',
                'actionCode' => $tableData['makerActionCode'],
                'actionName' => $tableData['makerAction'],
                'creationDtTm' => date("Y-m-d G:i:s"));

            $this->db->insert('bo_activity_log', $activityLog);

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("could not approved account in " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            }

            $this->db->trans_commit();

            return array(
                "success" => true,
                "user_id" => $userId
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();

            return array(
                "success" => false,
                "msg" => $e->getMessage(),
                "status" => 500
            );
        }


        $this->db->where('skyId', $id);
        $this->db->update('apps_users', $result);
    }

}
