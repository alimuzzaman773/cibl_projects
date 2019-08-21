<?php

class Client_registration_model_checker extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getUnapprovedAppsUsers($params = array()) {
        //$this->db->order_by("skyId", "desc");
        $this->db->where('apps_users_mc.mcStatus =', 0);
        //$this->db->where("apps_users_mc.salt2 IS Null");
        //$this->db->where('apps_users_mc.makerActionBy !=', $this->my_session->userId);
        $this->db->select('apps_users_mc.*, apps_users_group.userGroupName, atms.ATMName as branchName');
        $this->db->from('apps_users_mc');
        $this->db->join('apps_users au', "au.skyId = apps_users_mc.skyId", "inner");
        $this->db->join('apps_users_group', 'apps_users_mc.appsGroupId = apps_users_group.appsGroupId', "left");
        $this->db->join('atms', 'apps_users_mc.homeBranchCode = atms.branchCode', 'left');
        
        if (isset($params['search']) && trim($params['search']) != ''):
            $this->db->group_start()
                    ->or_like('apps_users_mc.skyId', $params['search'])
                    ->or_like('apps_users_mc.eblSkyId', $params['search'])
                    ->or_like('apps_users_mc.userName', $params['search'])
                    ->or_like('apps_users_mc.userName2', $params['search'])
                    ->or_like('apps_users_mc.cfId', $params['search'])
                    ->or_like('apps_users_mc.userEmail', $params['search'])
                    ->or_like('apps_users_mc.userMobNo1', $params['search'])
                    ->or_like('apps_users_mc.fatherName', $params['search'])
                    ->or_like('apps_users_mc.motherName', $params['search'])
                    // ->or_like('atms.ATMName', $params['search'])
                    ->group_end();
        endif;
        if (isset($params['isLocked']) && trim($params['isLocked']) != '') {
            $this->db->where('apps_users_mc.isLocked', $params['isLocked']);
        }

        if (isset($params['isActive']) && trim($params['isActive']) != '') {
            $this->db->where('apps_users_mc.isActive', $params['isActive']);
        }

        if (isset($params['limit']) && (int) $params['limit'] > 0) {
            $offset = (isset($params['offset']) && $params['offset'] != null) ? (int) $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        }
        
        $this->db->order_by('apps_users_mc.makerActionDt', 'DESC');
        $this->db->order_by('apps_users_mc.makerActionTm', 'DESC');
        //$this->db->order_by('apps_users_mc.skyId', 'desc');
        
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query : false;
    }

    //userGroupForM.userGroupName as mainUserGroupName, userGroupForMC.userGroupName as mcUserGroupName, 

    public function getAppsUserById($id) {
        $this->db->select('apps_users_mc.*, userGroupForM.userGroupName as mainUserGroupName, userGroupForMC.userGroupName as mcUserGroupName,

                          apps_users.skyId as skyId_c,
                          apps_users.eblSkyId as eblSkyId_c,
                          apps_users.cfId as cfId_c,
                          apps_users.clientId as clientId_c,
                          apps_users.gender as gender_c,
                          apps_users.dob as dob_c,
                          apps_users.userName as userName_c,
                          apps_users.userEmail as userEmail_c,
                          apps_users.fatherName as fatherName_c,
                          apps_users.motherName as motherName_c,
                          apps_users.userMobNo1 as userMobNo1_c,
                          apps_users.userMobNo2 as userMobNo2_c,
                          apps_users.currAddress as currAddress_c,
                          apps_users.parmAddress as parmAddress_c,
                          apps_users.billingAddress as billingAddress_c,
                          apps_users.appsGroupId as appsGroupId_c,
                          apps_users.mcStatus as mcStatus_c,
                          apps_users.makerAction as makerAction_c,
                          apps_users.makerActionDt as makerActionDt_c,
                          apps_users.makerActionTm as makerActionTm_c,
                          apps_users.makerActionBy as makerActioBy_c,
                          apps_users.checkerActionComment as checkerActionComment_c,
                          apps_users.checkerActionDt as checkerActionDt_c,
                          apps_users.checkerActionTm as checkerActionTm_c,
                          apps_users.checkerActionBy as checkerActionBy_c,
                          apps_users.isPublished as isPublished_c,
                          apps_users.isLocked as isLocked_c,
                          apps_users.isActive as isActive_c,
                          apps_users.isOwnAccTransfer as isOwnAccTransfer_c,
                          apps_users.isEnterAccTransfer as isEnterAccTransfer_c,
                          apps_users.isOtherAccTransfer as isOtherAccTransfer_c,
                          apps_users.isAccToCardTransfer as isAccToCardTransfer_c,
                          apps_users.isCardToAccTransfer as isCardToAccTransfer_c,
                          apps_users.isUtilityTransfer as isUtilityTransfer_c,
                          apps_users.isQrPayment as isQrPayment_c,
                          apps_users.homeBranchCode as homeBranchCode_c'
        );

        $this->db->from('apps_users_mc');
        $this->db->join('apps_users', 'apps_users.skyId = apps_users_mc.skyId', 'left');

        $this->db->join('apps_users_group as userGroupForMC', 'userGroupForMC.appsGroupId = apps_users_mc.appsGroupId', 'left');
        $this->db->join('apps_users_group as userGroupForM', 'userGroupForM.appsGroupId = apps_users.appsGroupId', 'left');

        $this->db->where('apps_users_mc.skyId', $id);
        $this->db->where('apps_users_mc.mcStatus', 0);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getApprovedAccountsById($id) {

        $this->db->select('accountNo, accountType, accountName, accountCurrency, accountIsLocked');
        $this->db->where('apps_users.skyId', $id);
        $query = $this->db->get('apps_users');
        return $query->row_array();
    }

    public function UpdateInsertCheckerApprove($id, $data, $p = array()) {
        $this->db->where('skyId', $id);
        $this->db->update('apps_users_mc', $data);

        $this->db->select('apps_users_mc.*,

                           apps_users_group.oatMinTxnLim,
                           apps_users_group.oatMaxTxnLim,
                           apps_users_group.oatDayTxnLim,
                           apps_users_group.oatNoOfTxn,

                           apps_users_group.pbMinTxnLim,
                           apps_users_group.pbMaxTxnLim,
                           apps_users_group.pbDayTxnLim,
                           apps_users_group.pbNoOfTxn,


                           apps_users_group.eatMinTxnLim,
                           apps_users_group.eatMaxTxnLim,
                           apps_users_group.eatDayTxnLim,
                           apps_users_group.eatNoOfTxn,


                           apps_users_group.obtMinTxnLim,
                           apps_users_group.obtMaxTxnLim,
                           apps_users_group.obtDayTxnLim,
                           apps_users_group.obtNoOfTxn');

        $this->db->from('apps_users_mc');
        $this->db->join('apps_users_group', 'apps_users_group.appsGroupId = apps_users_mc.appsGroupId');
        $this->db->where('apps_users_mc.skyId', $id);
        $query = $this->db->get();

        $tableData = $query->row_array();
        $this->db->insert('apps_users', $tableData);
        
        // Remove Account Number
        if (isset($p['account_delete']) && is_array($p['account_delete'])):
            $this->db->where_in('accountInfoID', $p['account_delete']);
            $this->db->delete('account_info');
        endif;

        // prepare data for activity log //
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
    }

    public function UpdateUpdateCheckerApprove($id, $data, $descision, $p = array()) {
        $this->db->where('skyId', $id);
        $this->db->update('apps_users_mc', $data);


        if ($descision == 1) {

            $query = $this->db->get_where('apps_users_mc', array('skyId' => $id));
            $result = $query->row_array();

            $this->db->where('skyId', $id);
            $this->db->update('apps_users', $result);
        } else {

            $this->db->select('apps_users_mc.*,

                           apps_users_group.oatMinTxnLim,
                           apps_users_group.oatMaxTxnLim,
                           apps_users_group.oatDayTxnLim,
                           apps_users_group.oatNoOfTxn,

                           apps_users_group.pbMinTxnLim,
                           apps_users_group.pbMaxTxnLim,
                           apps_users_group.pbDayTxnLim,
                           apps_users_group.pbNoOfTxn,


                           apps_users_group.eatMinTxnLim,
                           apps_users_group.eatMaxTxnLim,
                           apps_users_group.eatDayTxnLim,
                           apps_users_group.eatNoOfTxn,


                           apps_users_group.obtMinTxnLim,
                           apps_users_group.obtMaxTxnLim,
                           apps_users_group.obtDayTxnLim,
                           apps_users_group.obtNoOfTxn');

            $this->db->from('apps_users_mc');
            $this->db->join('apps_users_group', 'apps_users_group.appsGroupId = apps_users_mc.appsGroupId');
            $this->db->where('apps_users_mc.skyId', $id);
            $query = $this->db->get();

            $tableData = $query->row_array();
            $this->db->where('skyId', $id);
            $this->db->update('apps_users', $tableData);
        }

        $query = $this->db->get_where('apps_users', array('skyId' => $id));
        $jsonData = $query->row_array();
        
        // Remove Account Number
        if (isset($p['account_delete']) && is_array($p['account_delete'])):
            $this->db->where_in('accountInfoID', $p['account_delete']);
            $this->db->delete('account_info');
        endif;

        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($jsonData),
            'adminUserId' => $this->my_session->userId,
            'adminUserName' => $this->my_session->userName,
            'tableName' => 'apps_users',
            'moduleName' => 'apps_user_module',
            'moduleCode' => '01',
            'actionCode' => $jsonData['makerActionCode'],
            'actionName' => $jsonData['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function checkerReject($id, $data) {
        $this->db->where('skyId', $id);
        $this->db->update('apps_users_mc', $data);
    }

}
