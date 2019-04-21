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
        
        if(isset($p['isActive'])){
            $this->db->where("m.isActive", $p['isActive']);
        }
        
        if(isset($p['etFrom']) && isset($p['etTo'])){
            $this->db->where("m.executionTime between {$this->db->escape($p['etFrom'])} AND {$this->db->escape($p['etTo'])}", null, false);
        }
        
        if(isset($p['etFrom']) && !isset($p['etTo'])){
            $this->db->where("m.executionTime <= {$this->db->escape($p['etFrom'])}", null, false);
        }
        
        if(isset($p['completed']) && trim($p['completed']) != ''){
            $this->db->where("m.completed", $p['completed']);
        }
        
        if(isset($p['limit']) && (int)$p['limit'] > 0){
            $this->db->limit($p['limit']);
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
    
    function getAllDevices()
    {
        $this->db->select("au.skyId, di.gcmRegId,di.osCode", false)
                 ->from("apps_users au")
                 ->join("device_info di", "di.skyId = au.skyId", "inner");
        
        $result = $this->db->get();
        return $result->num_rows() > 0 ? $result : false;
    }
    
    function getMessageListAndUsers($p = array())
    {
        $this->db->select("m.*, ml.messageLogId, ml.skyId, di.gcmRegId,di.osCode", false)
                 ->from("message m")
                 ->join("message_log ml" ,"ml.messageId = m.messageId", "inner")
                 ->join('device_info di', "di.skyId = ml.skyId", "left");
        
        if(isset($p['sent']) && trim($p['sent']) != ''):
            $this->db->where("ml.sent", $p['sent']);
        endif;
        
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
            $this->db->limit($limit);
        }
                 
        $result = $this->db->get();
        return $result->num_rows() > 0 ? $result : false;
    }
    
    function send_notification($gcmList,$headLine, $body, $messageId, $imageUrl = NULL, $osCode = 'android')
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        //$imageUrl = 'https://adsofbd.com/wp-content/uploads/2015/12/EBL-Sky-Banking-1050x403.jpg?fbclid=IwAR0DdZnNTpAifL_8rfk7zdR9gKWzGfbm2yLOGmgdJ_dQFIKHdJ96_9vO-dk';
        $message = array(
            'title' => $headLine,
            //'subtitle' => '',
            'body' => $body,
            'vibrate' => 1,
            'sound' => 'default',
            'image' => $imageUrl,
            'messageId' => $messageId
        );

        /*$gcmList = array(
            'f7PnN8--X6Y:APA91bGvuK2-CRB79jW6HYGDzsMciUI4STO2_7_evMeTJm1vyyYNYUqXol5iCsvmv5t8GqIElFTZUr_IRBde8HT7PIdZcMMCeEiSSg9jHNj7fBB38RR94K2vYnTbAWhiZ1tVuxmprHB-',
            "c8L3VyzfQtg:APA91bGyrwsmOqhQRi9YLIvm4CD87JsQBy3cLesYJIMnq8SDcMhTo6jdI8TSdrQff2EUBESU4SPUwRU96MpKRwZ0L_pXvpX0xz198byseaRmL-jNJM5cMtLvppvr5d_0wayc0l5WTdID"
        );*/
        
        $fields = array(
            //"to" => "ew81ju1UGL8:APA91bG07uGRESihKfTE9AdFYN4_MDqo1lYogodyY2_AbygCmc8pvzfLUVSWwbKds6isbHhA_L3Y0ZfjVHeEgxhO1Zms0DMcN8NSZ2z0fEKI5YYETSGsbKMwViJdBSC3iy9xp3qaiSyb",
            "registration_ids" => $gcmList,
            'data' => $message,
            //'notification' => $message
        );
        
        if($osCode == 'ios'):
            $fields['notification'] = $message;
            unset($fields['data']);
        endif;
            
        
        $fields = json_encode($fields);
        
        $headers = array(
            'Authorization: key=AAAAn9utpPo:APA91bEmpM842xu2Pw6vAv8qtLZhj1KJlrdFFrYOU1jUPP3L6JTcnE80YLgTkeUnPSCwrKPb_PeDJLiatR2LvHq4OZ2BpX7pErBPk6RpXVgBQwhNamd4n-QX9VaViDfgbNXgKkIARXE0Grm6gatkzYKpoSJKnfi6lg',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_PROXY, '192.168.5.172:8080');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        $result = curl_exec($ch);
        if ($result === FALSE) {
            $err = curl_error($ch);
            curl_close($ch);
            return $err;
        }
        
        curl_close($ch);
        return $result;
    }
    
    
}
