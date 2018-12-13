<?php

class Call_center_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAllUsers($p) 
    {
        $dateTime = date("Y-m-d H:i:s");
        
        
        $this->db->select("aum.*, au.skyId as skyIdOriginal,"
                        . " TIMESTAMPDIFF(SECOND, CONCAT(aum.makerActionDt, ' ', aum.makerActionTm), '{$dateTime}') as activationDiff,"
                        . " TIMESTAMPDIFF(SECOND, aum.passwordResetDtTm, '{$dateTime}') as passwordResetDiff");
        
        $this->db->from('apps_users_mc aum')
            //    ->group_start()
                //->where('aum.isLocked', 0)
            //->or_where("(isPublished = 0 AND isActive = 0)", null,false)
            //->or_where("(isPublished = 1 AND isActive = 1 AND isLocked = 1 AND remarks = 'Password Reset Request')", null,false)
                //->where("isActive", 0)
                //->where("appsGroupId", 0)
                //->where('callCenterApprove', 'unapproved')
                //->or_where('callCenterApprove', 'pending')
            //    ->group_end()
                //->where('aum.isActive', 1)
                //->where('aum.isPublished', 0)
                
                
                ->join('apps_users au', "au.skyId = aum.skyId", "left")
                ->order_by('aum.skyId', 'desc');

        if(isset($p['skyIdOriginal']) && $p['skyIdOriginal'] == 0){
            $this->db->having('(skyIdOriginal IS NULL OR skyIdOriginal <= 0)', null, false);
        }
        
        if(isset($p['passwordReset']) && $p['passwordReset'] != NULL){
            $this->db->where('aum.passwordReset', $p['passwordReset']);
        }
        
        if(isset($p['activationPending24']) && (int)$p['activationPending24']  > 0){
            $this->db->having("activationDiff > {$this->db->escape($p['activationPending24'])}", null,false);
        }
        
        if(isset($p['passwordResetPending24']) && (int)$p['passwordResetPending24']  > 0){            
            $this->db->having("passwordResetDiff > {$this->db->escape($p['passwordResetPending24'])}", null,false);
        }
        
        if(isset($p['search']) && trim($p['search']) != ''):
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

    function getUserInfo($userId) {

        $this->db->select('aum.*, ra.raId, ra.entityType, ra.entityNumber, ra.created_on',false)
                ->from('apps_users_mc aum')                
                ->join("registration_attempts ra", "ra.skyId = aum.skyId","inner")
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

        $this->db->select('aum.*, ra.raId, ra.entityType, ra.entityNumber, ra.created_on',false)
                ->from('apps_users aum')                
                ->join("registration_attempts ra", "ra.skyId = aum.skyId","left")
                ->where('aum.passwordReset', 1)
                ->where('aum.isLocked', 1)
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
    
    function getUserAccounts($userId, $accountNo = null) 
    {
        $this->db->select('ai.*, at.accTypeName', false)
                ->from('account_info ai')
                ->join("account_type at", "at.accTypeCode = ai.accTypeCode", "left")
                ->where("ai.skyId", $userId);

        if($accountNo != null):
            $this->db->where("ai.accNo", $accountNo);
        endif;
        
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }
    
    function getRegistrationDetails($skyId)
    {
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
    
    function activateAppUserAccount($skyId, $pin, $raId = null, $appsGroupId = NULL) 
    {
        try
        {
            $this->db->select("aum.*")
                     ->from("apps_users_mc aum")
                     ->where('aum.isLocked', 0)
                     ->where("aum.isPublished", 0)
                     ->where("aum.isActive", 0)
                     ->where("aum.appsGroupId", 0)
                     ->where("aum.skyId", $skyId);
            
            $result = $this->db->get();
            if($result->num_rows() <= 0):
                return array(
                    "success" => false,
                    "msg" => "no apps user found"
                );
            endif;
            
            $userInfo = $result->row_array();
                        
            $userInfoMerge = array(
                "checkerAction" => 'Account Activation',
                "checkerActionDt" => date("Y-m-d"),
                "checkerActionTm" => date("H:i:s"),
                "checkerActionBy" => $this->my_session->userId,
                "passWord" => md5($pin),
                "pinExpiryReferenceTm" => date("Y-m-d H:i:s"),
                "isActive" => 1,
                "isPublished" => 1,
                "isLocked" => 0
            );
            
            $userInfo = array_merge($userInfo,$userInfoMerge);
            
            $this->db->reset_query();
            $this->db->select("au.*",false)
                     ->from("apps_users au")
                     ->where("au.skyId", $userInfo['skyId']);
                    
            $appsUser = $this->db->get();
            if($appsUser->num_rows() > 0):
                return array(
                    "success" => false,
                    "msg" => "Apps User already exists"
                );
            endif;
            
            //set mcstatus = 1
            $userInfo['mcStatus'] = 1;
            
            $this->db->reset_query();
            
            $this->db->trans_begin();            
            $this->db->insert("apps_users", $userInfo);
            
            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                     ->update("device_info", array("isVaryfied" => 1, "varyfiedDtTm" => date("Y-m-d H:i:s")));
            
            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                     ->update("apps_users_mc", $userInfo);
            
            if((int)$appsGroupId > 0):
                $this->load->model(array('banking_service_request_model'));
                $response = $this->banking_service_request_model->setAppsUserGroup($skyId, $appsGroupId);
                if(!$response['success']){
                    throw new Exception($response['msg']);
                }                
            endif;
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception("could not activate apps user in " . __CLASS__ . "::" . __FUNCTION__ . "::" . __LINE__);
            }
            
            $this->db->trans_commit();

            return array(
                "success" => true,
                "userInfo" => $userInfo
            );
        }
        catch(Exception $ex)
        {
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
