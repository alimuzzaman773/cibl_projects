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
                ->where('aum.isLocked', 0)
                ->group_start()
                ->where('callCenterApprove', 'unapproved')
                ->or_where('callCenterApprove', 'pending')
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

        $this->db->select('*')
                ->from('apps_users_mc')
                //->where('aum.isActive', 1)
                //->where('aum.isPublished', 0)
                ->where("skyId", $userId);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    function userApproved($userId) {

        $data = array(
            "callCenterApprove" => "pending"
        );

        $this->db->where("skyId", $userId)
                ->group_start()
                ->where("callCenterApprove", "unapproved")
                ->or_where("callCenterApprove", "pending")
                ->group_end()
                //->where("isActive",0)
                //->where('isPublished', 0)
                ->update("apps_users_mc", $data);

        return $userId;
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
