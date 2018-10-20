<?php

class Client_registration_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
    }

    public function getAllAppsUsers($p) 
    {
        if(isset($p['get_count']) && (int)$p['get_count'] > 0):
            $this->db->select('count(*) as total', false);            
        else:
            $this->db->select('apps_users_mc.*, apps_users_group.userGroupName', false);
        endif;
            
        $this->db->from('apps_users_mc');
        $this->db->join('apps_users_group', 'apps_users_mc.appsGroupId = apps_users_group.appsGroupId', 'left');
        //$this->db->where("apps_users_mc.salt2 IS Null");
        //$this->db->where('apps_users_mc.makerActionBy', $this->my_session->userId);
        //$this->db->or_where('apps_users_mc.mcStatus =', 1);
        $this->db->order_by('skyId', 'desc');
        
        if(isset($p['limit']) && (int)$p['limit'] > 0){
            $offset = (isset($p['offset']) && $p['offset'] != null) ? (int)$p['offset'] : 0;
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
        if ($data != NULL) {
            $this->db->select('apps_users_mc.eblSkyId, device_info_mc.*');

        $this->db->where("(device_info_mc.makerActionBy =  {$this->db->escape($this->my_session->adminUserId)}
                     OR device_info_mc.mcStatus = 1) AND device_info_mc.skyId = " . $this->db->escape($data));

            $this->db->from('device_info_mc');
            $this->db->join('apps_users_mc', 'apps_users_mc.skyId = device_info_mc.skyId');
            $query = $this->db->get();
            return $query->result_array();
        } else {

            $this->db->select('apps_users_mc.eblSkyId, device_info_mc.*');
            $this->db->where('device_info_mc.makerActionBy =', $this->session->userdata('adminUserId'));
            $this->db->or_where('device_info_mc.mcStatus =', 1);
            $this->db->from('device_info_mc');
            $this->db->join('apps_users_mc', 'apps_users_mc.skyId = device_info_mc.skyId');
            $query = $this->db->get();
            return $query->result_array();
        }
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

}
