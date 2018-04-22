<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generate_eblskyid_model_checker extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->load->model(array('common_model', 'login_model'));
    }

    //added 27 sep 2017
    public function wrongAttemptReset($eblSkyId) {
        $data["wrongAttempts"] = 0;

        $this->db->where("eblSkyId", $eblSkyId)
                ->update("apps_users", $data);

        $this->db->where("eblSkyId", $eblSkyId)
                ->update("apps_users_mc", $data);
    }

    public function getUnapprovedResetAction() {
        $this->db->order_by("generateId", "desc");
        $this->db->where('generate_eblskyid.mcStatus =', 0);
        $this->db->where('generate_eblskyid.isReset =', 1);
        $this->db->where('generate_eblskyid.makerActionBy !=', $this->my_session->userId);
        $this->db->from('generate_eblskyid');
        $query = $this->db->get();
        return $query->result();
    }

    public function getUnapprovedResetActionById($id) {
        $this->db->select('generate_eblskyid.*, apps_users_mc.userEmail, apps_users_mc.userMobNo1, apps_users_mc.userMobNo2');
        $this->db->from('generate_eblskyid');
        $this->db->join('apps_users_mc', 'generate_eblskyid.eblSkyId = apps_users_mc.eblSkyId', 'left');
        $this->db->where('generate_eblskyid.eblSkyId', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updateCheckerApprove($id, $chkData) {
        $this->db->update('generate_eblskyid', $chkData, array('eblSkyId' => $id));
        $this->db->where('eblSkyId', $id);
        $this->db->set('resetTms', 'resetTms + 1', FALSE);
        $this->db->update('generate_eblskyid');

        $result = $this->db->get_where('generate_eblskyid', array('eblSkyId' => $id));
        $jsonData = $result->row_array();
        unset($jsonData['pin'], $jsonData['salt']);

        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($jsonData),
            'adminUserId' => $this->my_session->userId,
            'adminUserName' => $this->my_session->userName,
            'tableName' => 'generate_eblskyid',
            'moduleName' => 'pin_module',
            'moduleCode' => '03',
            'actionCode' => $jsonData['makerActionCode'],
            'actionName' => $jsonData['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);


        $query = $this->db->get_where('apps_users_mc', array('eblSkyId' => $id));
        $dbData = $query->row_array();

        $pin = $this->login_model->decryptPin($chkData['pin']);

        // one way encryption and put the password in the apps_users and apps_users_mc
        $updateData['password'] = md5($pin);
        $updateData['isReset'] = 1;

        $this->db->update('apps_users_mc', $updateData, array('eblSkyId' => $id));

        if ($dbData['isPublished'] == 1) {
            $this->db->update('apps_users', $updateData, array('eblSkyId' => $id));
        }

        // update date time of generate_eblskyid table will be in the apps user table

        $this->db->where('eblSkyId', $id);
        $this->db->update('apps_users_mc', array('pinExpiryReferenceTm' => date("Y-m-d G:i:s")));

        $this->db->where('eblSkyId', $id);
        $this->db->update('apps_users', array('pinExpiryReferenceTm' => date("Y-m-d G:i:s")));
    }

    public function updateCheckerApproveUnusedPin($id, $chkData) {

        $this->db->update('generate_eblskyid', $chkData, array('eblSkyId' => $id));
        $this->db->where('eblSkyId', $id);
        $this->db->set('resetTms', 'resetTms + 1', FALSE);
        $this->db->update('generate_eblskyid');

        $result = $this->db->get_where('generate_eblskyid', array('eblSkyId' => $id));
        $jsonData = $result->row_array();
        unset($jsonData['pin'], $jsonData['salt']);

        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($jsonData),
            'adminUserId' => $this->my_session->userId,
            'adminUserName' => $this->my_session->userName,
            'tableName' => 'generate_eblskyid',
            'moduleName' => 'pin_module',
            'moduleCode' => '03',
            'actionCode' => $jsonData['makerActionCode'],
            'actionName' => $jsonData['makerAction'],
            'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }

    public function checkerReject($id, $data) {
        $this->db->where('eblSkyId', $id);
        $this->db->update('generate_eblskyid', $data);
    }

    public function getUnapprovedRequests() {
        $this->db->select('pin_generation_request.*,
                           admin_users.fullName,
                           admin_users.adminUserName');
        $this->db->from('pin_generation_request');
        $this->db->join('admin_users', 'pin_generation_request.makerActionBy = admin_users.adminUserId');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getRequestById($id) {
        $this->db->where('requestId', $id);
        $query = $this->db->get('pin_generation_request');
        return $query->row_array();
    }
}
