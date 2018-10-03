<?php

class Push_notification_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
    }

    public function getAllMessages() {
        $query = $this->db->get('message');
        return $query->result();
    }

    public function getAllAppsUsersFroPush() {
        $this->db->order_by('skyId', 'asc');
        $this->db->select('apps_users.*, apps_users_group.userGroupName');
        $this->db->or_where('apps_users.mcStatus =', 1);
        $this->db->from('apps_users');
        $this->db->join('apps_users_group', 'apps_users.appsGroupId = apps_users_group.appsGroupId');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllGcmIds($ids) {
        $this->db->select('gcm_users.gcmRegId,device_info.osCode');
        $this->db->select('gcm_users.gcmRegId', 'gcm_users.deviceId');
        $this->db->from('gcm_users');
        $this->db->join('device_info', 'gcm_users.deviceId = device_info.deviceId');
        $this->db->where_in('gcm_users.skyId', $ids);
        $query = $this->db->get();
        return $query->result();
    }

    public function oldgetAllGcmIds($ids) {
        $this->db->select('gcmRegId');
        $this->db->from('gcm_users');
        $this->db->where_in('skyId', $ids);
        $query = $this->db->get();
        return $query->result();
    }

    public function storeMessage($data) {
        $this->db->insert('message', $data);
        $data = $this->db->insert_id();
        return $data;
    }
    
    function getMessageInfo($p = array())
    {
        $this->db->select("m.*")
                 ->from("message m");
        if(isset($p['messageId'])):
            $this->db->where("m.messageId", $p['messageId']);
        endif;
        
        if(isset($p['etFrom']) && isset($p['etTo'])){
            $this->db->where("m.executionTime between {$this->db->escape($p['etFrom'])} AND {$this->db->escape($p['etTo'])}", null, false);
        }
                 
        $result = $this->db->get();
        return $result->num_rows() > 0 ? $result : false;
    }

    function getAllAppsUsers($p) 
    {
        if(isset($p['get_count']) && (int)$p['get_count'] > 0):
            $this->db->select('count(*) as total', false);            
        else:
            $this->db->select('au.skyId, au.eblSkyId, au.userName, au.userEmail, au.dob, au.gender, aug.userGroupName, ml.messageLogId');
            
        endif;
            
        $this->db->from('apps_users au')
                 ->join('apps_users_group aug', 'au.appsGroupId = aug.appsGroupId', "left")
                 ->join('message_log ml', "ml.skyId = au.skyId and ml.messageId = {$this->db->escape($p['messageId'])}", "left");
        
        if(isset($p['limit']) && (int)$p['limit'] > 0)
        {
            $offset = (isset($p['offset']) && $p['offset'] != null) ? (int)$p['offset'] : 0;
            $this->db->limit($p['limit'], $offset);
        }
        
        $this->db->order_by('au.skyId', 'desc');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query : false;
    }
    
    function getMessageListAndUsers($p = array())
    {
        $this->db->select("m.*, ml.messageLogId, ml.skyId, di.gcmRegId", false)
                 ->from("message m")
                 ->join("message_log ml" ,"ml.messageId = m.messageId", "inner")
                 ->join('device_info di', "di.skyId = ml.skyId", "left")
                 ->where("ml.sent", 0);
        
        if(isset($p['messageId'])):
            $this->db->where("m.messageId", $p['messageId']);
        endif;
        
        if(isset($p['etFrom'])){
            $this->db->where("m.executionTime < {$this->db->escape($p['etFrom'])}", null, false);
        }
        
        if(isset($p['etFrom']) && isset($p['etTo'])){
            $this->db->where("m.executionTime between {$this->db->escape($p['etFrom'])} AND {$this->db->escape($p['etTo'])}", null, false);
        }
        
        $limit = 100;
        if(isset($p['limit'])){
            $limit = ((int)$p['limit'] > 0 ) ? (int)$p['limit'] : 100;
        }
        $this->db->limit($limit);
                 
        $result = $this->db->get();
        return $result->num_rows() > 0 ? $result : false;
    }
    
    function send_notification($gcmList,$headLine, $body, $imageUrl = NULL)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $message = array(
            'title' => $headLine,
            'subtitle' => '',
            'body' => $body,
            'vibrate' => 1,
            'sound' => 1,
            'image' => 'https://pay.google.com/about/static/images/social/og_image.jpg'
        );

        $fields = array(
            "registration_ids" => $gcmList,
            'data' => $message
        );
        $fields = json_encode($fields);

        $headers = array(
            'Authorization: key=AAAAn9utpPo:APA91bEmpM842xu2Pw6vAv8qtLZhj1KJlrdFFrYOU1jUPP3L6JTcnE80YLgTkeUnPSCwrKPb_PeDJLiatR2LvHq4OZ2BpX7pErBPk6RpXVgBQwhNamd4n-QX9VaViDfgbNXgKkIARXE0Grm6gatkzYKpoSJKnfi6lg',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        echo $result;
        curl_close($ch);
    }
    
    
}
