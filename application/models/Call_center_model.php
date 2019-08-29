<?php

class Call_center_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function getAllUsers($params = array()) {
        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select('count(*) as total', false);
        } else {
            $this->db->select('aum.*, adm.adminUserName makerName, atms.ATMName as branchName, adc.adminUserName checkerName, au.skyId as skyIdOriginal, ra.raId, ra.entityType, ra.created as created_on, ra.otpChannel, ra.entityNumber', false);
        }

        $this->db->from('apps_users_mc aum')
                ->join('apps_users au', "au.skyId = aum.skyId", "left")
                ->join('atms', 'aum.homeBranchCode = atms.branchCode', 'left')
                ->join('registration_attempts ra', "ra.skyId = aum.skyId", "inner")
                ->join('admin_users adm', 'adm.adminUserId = aum.makerActionBy', 'left')
                ->join('admin_users adc', 'adc.adminUserId = aum.checkerActionBy', 'left')
                ->where('aum.isLocked', 0);

        if (ci_check_permission("callCenterChecker")):
            $this->db->where("aum.isPublished", 0)
                    ->where("aum.makerActionBy >", 0);
        endif;

        if (isset($params['search']) && trim($params['search']) != ''):
            $this->db->group_start()
                    ->or_like("aum.cfId", $params['search'])
                    ->or_like("aum.eblSkyId", $params['search'])
                    ->or_like("aum.clientId", $params['search'])
                    ->or_like("aum.userName", $params['search'])
                    ->or_like("aum.userEmail", $params['search'])
                    ->or_like("aum.userMobNo1", $params['search'])
                    ->or_like("ra.entityNumber", $params['search'])
                    ->or_like("ra.entityType", $params['search'])
                    ->or_like("aum.concentrationName", $params['search'])
                    ->group_end();
        endif;

        if (isset($params['branch']) && trim($params['branch']) != ''):
            $this->db->where("atms.branchCode", $params["branch"]);
        endif;

        if (isset($params['from_date']) && trim($params['from_date']) != '' && isset($params['to_date']) && trim($params['to_date']) != ''):
            $this->db->where("ra.created_on between {$this->db->escape($params['from_date'])} AND {$this->db->escape($params['to_date'])}", null, false);
        endif;

        if (isset($params['limit']) && (int) $params['limit'] > 0):
            $offset = (isset($params['offset']) && $params['offset'] != null) ? (int) $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        endif;

        if (isset($params['is_regester']) && $params['is_regester'] == 0):
            $this->db->where("aum.isPublished", "0");
        endif;

        if (isset($params['is_regester']) && $params['is_regester'] == 1):
            $this->db->where("aum.isPublished", "1");
        endif;
        
        if (isset($params['password_reset']) && $params['password_reset'] == 0):
            $this->db->where("aum.passwordReset", "0");
        endif;

        if (isset($params['password_reset']) && $params['password_reset'] == 1):
            $this->db->where("aum.passwordReset", "1");
        endif;

        if(isset($params['transferOptions']) && trim($params['transferOptions']) != '' &&
            isset($params['viewOnlyBool']) && (int)$params['viewOnlyBool'] >= 0) {
            $this->db->where('aum.'.$params['transferOptions'], $params['viewOnlyBool']);
        }

        $query = $this->db->order_by('aum.passwordReset', 'desc')
                ->order_by('ra.created_on', 'desc')
                ->get();
        return $query->num_rows() > 0 ? $query : false;
    }

    function getUserInfo($userId) {

        $this->db->select('aum.*, ra.raId, ra.entityType, ra.entityNumber, ra.created_on, ra.otpChannel', false)
                ->from('apps_users_mc aum')
                ->join("registration_attempts ra", "ra.skyId = aum.skyId", "inner")
                ->where('aum.isLocked', 0)
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

        $this->db->select('aum.*, ra.raId, ra.entityType, ra.entityNumber, ra.created_on', false)
                ->from('apps_users aum')
                ->join("registration_attempts ra", "ra.skyId = aum.skyId", "inner")
                //->where('aum.remarks', 'Password Reset Request')
                //->where('aum.isLocked', 1)
                ->where("aum.passwordReset", 1)
                ->where("aum.isPublished", 1)
                ->where("aum.isActive", 1)
                ->where("aum.skyId", $userId)
                ->order_by("ra.raId", "DESC")
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

    function userApproveChecker($userId, $params=array()) {
        $userInfo = array(
            "makerAction" => 'Account Activation',
            "makerActionDt" => date("Y-m-d"),
            "makerActionTm" => date("H:i:s"),
            "makerActionBy" => $this->my_session->userId
        );
        $userInfo['isOwnAccTransfer'] = isset($params['isOwnAccTransfer'])? $params['isOwnAccTransfer'] : '1';
        $userInfo['isInterAccTransfer'] = isset($params['isInterAccTransfer'])? $params['isInterAccTransfer'] : '1';
        $userInfo['isOtherAccTransfer'] = isset($params['isOtherAccTransfer'])? $params['isOtherAccTransfer'] : '1';
        $userInfo['isAccToCardTransfer'] = isset($params['isAccToCardTransfer'])? $params['isAccToCardTransfer'] : '1';
        $userInfo['isCardToAccTransfer'] = isset($params['isCardToAccTransfer'])? $params['isCardToAccTransfer'] : '1';
        $userInfo['isUtilityTransfer'] = isset($params['isUtilityTransfer'])? $params['isUtilityTransfer'] : '1';
        $userInfo['isQrPayment'] = isset($params['isQrPayment'])? $params['isQrPayment'] : '1';
        
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
                "checkerAction" => 'Account Activation',
                "checkerActionDt" => date("Y-m-d"),
                "checkerActionTm" => date("H:i:s"),
                "checkerActionBy" => $this->my_session->userId,
                "passWord" => md5($pin),
                "pinExpiryReferenceTm" => date("Y-m-d H:i:s"),
                "isActive" => 1,
                "isPublished" => 1,
                "isLocked" => 0,
                "isReset" => 1,
                "mcStatus" => 1
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
            $this->db->select('ar.type, ar.entityNumber, ar.status as approve_status, ar.created, au.*');
        endif;

        $this->db->from("account_add_requests ar")
                ->join("apps_users au", "ar.skyId=au.skyId", "inner");

        if (isset($p['skyId']) && (int) $p['skyId'] > 0) {
            $this->db->where("ar.skyId", $p["skyId"]);
        }

        $this->db->where("au.isPublished", 1)
                ->where("au.isActive", 1);

        if (isset($p['search']) && trim($p['search']) != ''):
            $this->db->group_start()
                    ->or_like("au.cfId", $p['search'])
                    ->or_like("au.eblSkyId", $p['search'])
                    ->or_like("au.clientId", $p['search'])
                    ->or_like("au.userName", $p['search'])
                    ->or_like("au.userName2", $p['search'])
                    ->or_like("au.userEmail", $p['search'])
                    ->or_like("au.userMobNo1", $p['search'])
                    ->or_like("au.fatherName", $p['search'])
                    ->or_like("au.motherName", $p['search'])
                    ->or_like("ar.entityNumber", $p['search'])
                    ->group_end();
        endif;

        if (isset($p['approved_status']) && trim($p['approved_status']) != ''):
            $this->db->where("ar.status", $p["approved_status"]);
        endif;

        if (isset($p['request_type']) && trim($p['request_type']) != ''):
            $this->db->where("ar.type", $p["request_type"]);
        endif;

        if (isset($p['from_date']) && trim($p['from_date']) != '' && isset($p['to_date']) && trim($p['to_date']) != ''):
            $this->db->where("ar.created between {$this->db->escape($p['from_date'])} AND {$this->db->escape($p['to_date'])}", null, false);
        endif;

        if (isset($p['limit']) && (int) $p['limit'] > 0) {
            $offset = (isset($p['offset']) && $p['offset'] != null) ? (int) $p['offset'] : 0;
            $this->db->limit($p['limit'], $offset);
        }

        $query = $this->db->order_by('ar.id', 'desc')
                ->get();

        return $query->num_rows() > 0 ? $query : false;
    }

    function getRequestAccountInfo($accountNumber) {

        $cbsData = array(
            "account_no" => getAccountNo($accountNumber),
            "branch_id" => getBranchId($accountNumber),
        );

        $this->load->library("cbs_services");
        $customerInfo = $this->cbs_services->getCustomerInfo($cbsData);

        if (!$customerInfo["success"]) {
            return array(
                "success" => false,
                "msg" => "Wrong account information"
            );
        }

        $customerInfo = json_decode($customerInfo['data'], true);
        $customerInfo = $customerInfo[0];

        if (isset($customerInfo['ERROR_CODE']) || isset($customerInfo['ERROR_MESSAGE'])) {
            return array(
                "success" => false,
                "msg" => "wrong account information"
            );
        }

        return array(
            "success" => true,
            "data" => $customerInfo
        );
    }

    function getRequestCardInfo($cardNo) {

        $this->load->library("card_services");
        $cardRequestData = array(
            "card_no" => $cardNo
        );

        $cardHolderInfo = $this->card_services->getCardDetails($cardRequestData);
        if (!$cardHolderInfo["success"]):
            $json = json_encode(array(
                "success" => false,
                "msg" => "wrong details information",
                'log' => $cardHolderInfo
            ));
            my_json_output($json);
        endif;

        $cardAccountInfo = $cardHolderInfo['data'];
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
            "customer_id" => $account["CUSTOMER_ID"]
        );

        $this->load->library("cbs_services");
        $summaryInfo = $this->cbs_services->getAccountSummary($cbsData);
        if (!$summaryInfo["success"]) {
            $json = array(
                "success" => false,
                "msg" => "wrong account information",
            );
            my_json_output($json);
        }

        $accountList = array();
        $accountList = json_decode($summaryInfo['data'], true);
        $accountList = $accountList[0];

        if (!isset($accountList['issuccess']) && (bool) $accountList['issuccess'] != True) {
            $json = array(
                "success" => false,
                "msg" => "There are no account list",
            );
            my_json_output($json);
        }

        foreach ($accountList["getAccountSummary"] as $item) {

            $xmlToArray[] = array(
                'skyId' => $skyId,
                'accCurrency' => $item["currencY_NM"],
                'accName' => $item["producT_NM"],
                'accNo' => $item["accounT_NUMBER"],
                'accTypeCode' => $item["appL_TYPE"],
                'accClientId' => NULL,
                "type" => "account_number",
                'accBranchCode' => $item['brancH_ID'],
                'accountData' => json_encode($item),
                'productId' => (int) $item['producT_ID'],
                'service_type' => $item['service_type_id']
            );
        }

        $query = $this->db->select("*")
                ->from("apps_users_mc amc")
                ->where("amc.cfId", $account['CUSTOMER_ID'])
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
            $this->db->insert_batch("account_info", $xmlToArray);

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

        $this->load->model("common_model");

        $userAccountInfo = array(
            'skyId' => $skyId,
            'accCurrency' => '',
            'accName' => $this->common_model->numberMasking(MASK, $cardNumber),
            'accNo' => $cardNumber,
            'accTypeCode' => "CC",
            'accClientId' => $card["clientId"],
            'accBranchCode' => NULL,
            'cardAccNo' => $card['account_no'],
            "type" => "credit_card",
            "accountData" => json_encode(array(
                "EMAIL" => $card['email'],
                "MOBILE" => $card['mobile_no']
            ))
        );

        try {
            $this->db->trans_begin();

            $this->db->reset_query();
            $this->db->insert("account_info", $userAccountInfo);

            $clientId = get_client_id();

            $this->db->reset_query();
            $this->db->where('skyId', $skyId)
                    ->update("apps_users", array('clientId' => $clientId));

            $this->db->reset_query();
            $this->db->where('skyId', $skyId)
                    ->update("apps_users_mc", array('clientId' => $clientId));

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

        $regAttemptQry = $this->db->select("*")
                ->from("registration_attempts")
                ->where("skyId", $skyId)
                ->get();
        $regAttempt = ($regAttemptQry->num_rows() > 0) ? $regAttemptQry->result() : false;

        $jsonData["apps_users_mc"] = $userMaker;
        $jsonData["apps_users"] = $userChecker;
        $jsonData["device_info_mc"] = $deviceMaker;
        $jsonData["device_info"] = $deviceChecker;
        $jsonData["registration_attempt"] = $regAttempt;

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

            if ($regAttempt) {
                $this->db->reset_query();
                $deleteLog["tableName"] = "registration_attempts";
                $deleteLog["activityJson"] = json_encode($regAttempt);
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

    public function getAllBranch() {

        $this->db->select('ATMID, ATMName, branchCode', false)
                ->from('atms')
                ->where("eblNearYou", 1)
                ->order_by("ATMName", "ASC");

        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query : false;
    }

}
