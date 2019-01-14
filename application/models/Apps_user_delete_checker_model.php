<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apps_user_delete_checker_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
    }

    public function getUnapprovedDeleteUser() {
        $query = $this->db->select("*")
                ->order_by("skyId", "desc")
                ->where('apps_users_mc.salt2', 'delete')
                ->where('apps_users_mc.mcStatus =', 0)
                ->where('apps_users_mc.makerActionBy !=', $this->my_session->userId)
                ->select('apps_users_mc.*, apps_users_group.userGroupName')
                ->from('apps_users_mc')
                ->join('apps_users_group', 'apps_users_mc.appsGroupId = apps_users_group.appsGroupId')
                ->get();
        return ($query->num_rows() > 0) ? $query->result() : false;
    }

    public function checkerReject($id, $data) {
        $this->db->where('skyId', $id);
        $this->db->update('apps_users_mc', $data);
    }

    public function deleteCheckerApproval($skyId, $params = array()) {
        $documentId = rrn_no();

        $userMakerQry = $this->db->select("*")
                ->from("apps_users_mc")
                ->where("skyId", $skyId)
                ->get();
        $userMaker = ($userMakerQry->num_rows() > 0) ? $userMakerQry->row() : false;

        $userCheckerQry = $this->db->select("*")
                ->from("apps_users")
                ->where("skyId", $skyId)
                ->get();
        $userChecker = ($userCheckerQry->num_rows() > 0) ? $userCheckerQry->row() : false;

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

        $activityLog = array('activityJson' => json_encode($jsonData),
            'adminUserId' => $this->my_session->userId,
            'adminUserName' => $this->my_session->userName,
            'tableName' => 'apps_users_mc',
            'moduleName' => 'apps_user_module',
            'moduleCode' => '01',
            'actionCode' => "delete",
            'actionName' => "Delete",
            'creationDtTm' => date("Y-m-d G:i:s"));

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
                $deleteLog["tableName"] = "apps_users_mc";
                $deleteLog["activityJson"] = json_encode($userMaker);
                $this->db->insert('delete_activity_log', $deleteLog);
            }
            if ($userChecker) {
                $deleteLog["tableName"] = "apps_users";
                $deleteLog["activityJson"] = json_encode($userChecker);
                $this->db->insert('delete_activity_log', $deleteLog);
            }

            if ($deviceMaker) {
                $deleteLog["tableName"] = "device_info_mc";
                $deleteLog["activityJson"] = json_encode($deviceMaker);
                $this->db->insert('delete_activity_log', $deleteLog);
            }

            if ($deviceChecker) {
                $deleteLog["tableName"] = "device_info";
                $deleteLog["activityJson"] = json_encode($deviceChecker);
                $this->db->insert('delete_activity_log', $deleteLog);
            }

            $this->db->insert('bo_activity_log', $activityLog);

            if (isset($params['eblSkyId']) && trim($params['eblSkyId']) != ''):
                $this->db->where("eblSkyId", $params['eblSkyId'])
                        ->update("generate_eblskyid", $publishedData);
            endif;

            $this->db->where("skyId", $skyId)
                    ->delete("apps_users_mc");

            $this->db->where("skyId", $skyId)
                    ->delete("apps_users");

            $this->db->where("skyId", $skyId)
                    ->delete("device_info_mc");

            $this->db->where("skyId", $skyId)
                    ->delete("device_info");

            $this->db->trans_commit();

            return array(
                "success" => true,
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();

            return array(
                "success" => false,
                "msg" => $e->getMessage()
            );
        }
        return false;
    }

}
