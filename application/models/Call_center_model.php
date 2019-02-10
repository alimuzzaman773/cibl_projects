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
                return array(
                    "success" => false,
                    "msg" => "No Default transaction limit found, please set a default transaction limit."
                );
            endif;

            $gInfo = $gResult->row();
            $appsGroupId = (int) $gInfo->appsGroupId;
            /* End Default Transaction */

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

    public function getAllRequestAccount($p) {
        if (isset($p['get_count']) && (int) $p['get_count'] > 0):
            $this->db->select('count(*) as total', false);
        else:
            $this->db->select('ar.type, ar.entityNumber, ar.status as approve_status, au.*');
        endif;

        $this->db->from("account_add_requests ar")
                ->join("apps_users au", "ar.skyId=au.skyId", "inner");

        if (isset($p['skyId']) && (int) $p['skyId'] > 0) {
            $this->db->where("ar.skyId", $p["skyId"]);
        }

        $this->db->where("au.isPublished", 1)
                ->where("au.isActive", 1);

        if (isset($p['limit']) && (int) $p['limit'] > 0) {
            $offset = (isset($p['offset']) && $p['offset'] != null) ? (int) $p['offset'] : 0;
            $this->db->limit($p['limit'], $offset);
        }

        $query = $this->db->order_by('au.skyId', 'desc')
                ->get();

        return $query->num_rows() > 0 ? $query : false;
    }

    function getRequestAccountInfo($accountNumber) {

        $cbsData = array(
            "paccount_no" => $accountNumber,
            "Cust_id" => "" //$accountData['cust_id']
        );

        $this->load->library("cbs_services");
        $customerInfo = $this->cbs_services->getAccountSummary($cbsData);

        if (!$customerInfo["success"]) {
            $json = json_encode(array(
                "success" => false,
                "msg" => "Wrong Account information.",
            ));
            my_json_output($json);
        }

        $customerInfoResponse = json_decode($customerInfo['data'], true);
        $customerInfoResponse = $customerInfoResponse[0];

        if (isset($customerInfoResponse["ERROR_CODE"])) {
            $json = json_encode(array(
                "success" => false,
                "msg" => "wrong account information",
                "data" => $customerInfo
            ));
            my_json_output($json);
        }
        return array(
            "success" => true,
            "data" => $customerInfoResponse
        );
    }

    function getRequestCardInfo($cardNo) {

        $this->load->library("card_services");
        $cardRequestData = array(
            "pan" => $cardNo,
            "mbr" => 0,
            "required_data" => ""
        );

        $this->card_services->requestTaskChaining();
        $cardHolderInfo = $this->card_services->getCardInfoDynamic($cardRequestData);

        if (!$cardHolderInfo["success"]):
            $json = json_encode(array(
                "success" => false,
                "msg" => "wrong details information",
                'log' => $cardHolderInfo
            ));
            my_json_output($json);
        endif;

        if (count($cardHolderInfo['data']) <= 0 || !isset($cardHolderInfo['data'][0]['accNo'])):
            $json = json_encode(array(
                "success" => false,
                "msg" => "no account information found",
                'log' => $cardHolderInfo
            ));
            my_json_output($json);
        endif;

        $cardAccountInfo = $cardHolderInfo['data'][0];

        return array(
            "success" => true,
            "data" => $cardAccountInfo
        );
    }

    function approveAccount($accountNumber, $skyId) {

        $accountInfo = $this->getRequestAccountInfo($accountNumber);
        if (!$accountInfo["success"]) {
            return $accountInfo;
        }
        $account = $accountInfo["data"];

        $cbsData = array(
            "paccount_no" => "",
            "Cust_id" => $account["CUSTOMER_ID"]
        );

        $this->load->library("cbs_services");
        $accSummary = $this->cbs_services->getAccountSummary($cbsData);

        if (!$accSummary["success"]) {
            $json = json_encode(array(
                "success" => false,
                "msg" => "Wrong Account information.",
            ));
            my_json_output($json);
        }

        $accSummary = json_decode($accSummary['data'], true);

        $accountList = $accSummary;
        $accSummary = $accSummary[0];

        if (isset($accSummary["ERROR_CODE"])) {
            $json = json_encode(array(
                "success" => false,
                "msg" => "wrong account information"
            ));
            my_json_output($json);
        }

        $userAccountList = array();

        foreach ($accountList as $item) {
            $accArray = array(
                'skyId' => $skyId,
                'accCurrency' => $item["CURRENCY_NM"],
                'accName' => $item["PRODUCT_NM"],
                'accNo' => $item['ACCOUNT_NUMBER'],
                'accTypeCode' => $item["ACCOUNT_TYPE"],
                'accClientId' => NULL,
                'accBranchCode' => @$item['BRANCH_CODE'],
                "type" => "account_number",
                'accountData' => json_encode(array(
                    'EMAIL' => $item['EMAIL'],
                    "MOBILE" => $item['MOBILE']
                ))
            );
            $userAccountList[] = $accArray;
        }

        $query = $this->db->select("*")
                ->from("apps_users_mc amc")
                ->where("amc.cfId", $accSummary['CUSTOMER_ID'])
                ->get();

        if ($query->num_rows() > 0) {
            return array(
                "success" => false,
                "msg" => "The CF ID with this account already exists in our system."
            );
        }

        try {
            $this->db->trans_begin();

            $userInfo = array(
                "cfId" => $account["CUSTOMER_ID"]
            );

            $this->db->reset_query();
            $this->db->insert_batch("account_info", $userAccountList);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users", $userInfo);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users_mc", $userInfo);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("account_add_requests", array('status' => 'completed'));

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("could not update account in " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            }

            $this->db->trans_commit();

            return array(
                "success" => true
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

    function approveCard($cardNumber, $skyId) {

        $cardInfo = $this->getRequestCardInfo($cardNumber);
        if (!$cardInfo["success"]) {
            return $cardInfo;
        }

        $query = $this->db->select("ai.*, au.eblSkyId", false)
                ->from("account_info ai")
                ->join("apps_users_mc au", "au.skyId = ai.skyId", "left")
                ->where("accNo", $cardNumber)
                //->where("skyId", $skyId)
                ->get();

        if ($query->num_rows() > 0) {
            return array(
                "success" => false,
                "msg" => "Card already exist in our system with user: " . $query->row()->eblSkyId
            );
        }

        $card = $cardInfo["data"];

        $this->load->library("crypt_global");
        $this->load->model("common_model");

        $userAccountInfo = array(
            'skyId' => $skyId,
            'accCurrency' => '',
            'accName' => $this->common_model->numberMasking(MASK, $cardNumber),
            'accNo' => $cardNumber,
            'accTypeCode' => "CC",
            'accClientId' => $card["clientId"],
            'accBranchCode' => NULL,
            "type" => "credit_card",
            "accountData" => json_encode(array(
                "EMAIL" => $card['email'],
                "MOBILE" => $card['mobile_no']
            ))
        );

        try {
            $this->db->trans_begin();

            $userInfo = array(
                "clientId" => $card["clientId"]
            );

            $this->db->reset_query();
            $this->db->insert("account_info", $userAccountInfo);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users", $userInfo);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users_mc", $userInfo);

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("account_add_requests", array('status' => 'completed'));

            if ($this->db->trans_status() === FALSE) {
                throw new Exception("could not update account in " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            }

            $this->db->trans_commit();

            return array(
                "success" => true
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

    public function removeUser($skyId, $params = array()) {
        $documentId = rrn_no();

        $userMakerQry = $this->db->select("*")
                ->from("apps_users_mc")
                ->where("skyId", $skyId)
                ->get();

        $mkRow = $userMakerQry->num_rows();
        if ($mkRow <= 0) {
            return array(
                "success" => false,
                "msg" => "There are no user found"
            );
        }
        $userMaker = ($mkRow > 0) ? $userMakerQry->row() : false;

        $userCheckerQry = $this->db->select("*")
                ->from("apps_users")
                ->where("skyId", $skyId)
                ->get();

        $ckRow = $userCheckerQry->num_rows();
        if ($ckRow > 0) {
            return array(
                "success" => false,
                "msg" => "This user already activated from call center"
            );
        }
        $userChecker = ($ckRow > 0) ? $userCheckerQry->row() : false;

        $deviceMakerQry = $this->db->select("*")
                ->from("device_info_mc")
                ->where("skyId", $skyId)
                ->get();
        $deviceMaker = ($deviceMakerQry->num_rows() > 0) ? $deviceMakerQry->result() : false;

        $deviceCheckerQry = $this->db->select("*")
                ->from("device_info")
                ->where("skyId", $skyId)
                ->get();
        $deviceChecker = ($deviceCheckerQry->num_rows() > 0) ? $deviceCheckerQry->result() : false;

        $jsonData["apps_users_mc"] = $userMaker;
        $jsonData["apps_users"] = $userChecker;
        $jsonData["device_info_mc"] = $deviceMaker;
        $jsonData["device_info"] = $deviceChecker;

        $activityLog = array(
            'activityJson' => json_encode($jsonData),
            'adminUserId' => $this->my_session->userId,
            'adminUserName' => $this->my_session->userName,
            'tableName' => 'apps_users_mc',
            'moduleName' => 'apps_user_module',
            'moduleCode' => '01',
            'actionCode' => "delete",
            'actionName' => "Delete",
            'creationDtTm' => date("Y-m-d G:i:s")
        );

        $publishedData["isPublished"] = 0;
        $publishedData["isUsed"] = 0;
        $publishedData["makerActionDt"] = date("Y-m-d");
        $publishedData["makerActionTm"] = date("G:i:s");

        $deleteLog["createdBy"] = $this->my_session->userId;
        $deleteLog["createdDtTm"] = date("Y-m-d G:i:s");
        $deleteLog["actionCode"] = "delete";
        $deleteLog["moduleCode"] = "01";
        $deleteLog["documentId"] = $documentId;
        $deleteLog["reason"] = $params['reason'];

        try {
            $this->db->trans_begin();

            if ($userMaker) {
                $this->db->reset_query();
                $deleteLog["tableName"] = "apps_users_mc";
                $deleteLog["activityJson"] = json_encode($userMaker);
                $this->db->insert('delete_activity_log', $deleteLog);
            }
            if ($userChecker) {
                $this->db->reset_query();
                $deleteLog["tableName"] = "apps_users";
                $deleteLog["activityJson"] = json_encode($userChecker);
                $this->db->insert('delete_activity_log', $deleteLog);
            }

            if ($deviceMaker) {
                $this->db->reset_query();
                $deleteLog["tableName"] = "device_info_mc";
                $deleteLog["activityJson"] = json_encode($deviceMaker);
                $this->db->insert('delete_activity_log', $deleteLog);
            }

            if ($deviceChecker) {
                $this->db->reset_query();
                $deleteLog["tableName"] = "device_info";
                $deleteLog["activityJson"] = json_encode($deviceChecker);
                $this->db->insert('delete_activity_log', $deleteLog);
            }

            $this->db->insert('bo_activity_log', $activityLog);

            if (isset($params['eblSkyId']) && trim($params['eblSkyId']) != ''):
                $this->db->reset_query();
                $this->db->where("eblSkyId", $params['eblSkyId'])
                        ->update("generate_eblskyid", $publishedData);
            endif;

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->delete("apps_users_mc");

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->delete("apps_users");

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->delete("device_info_mc");

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->delete("device_info");

            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->delete("account_info");

            $this->db->trans_commit();

            return array(
                "success" => true
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();

            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
    }

}
