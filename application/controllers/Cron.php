<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends MX_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
    }
    
    function send_notification()
    {
        $this->load->model("push_notification_model");
        
        $p = array(
            //'messageId' => 3,
            'etFrom' => date("Y-m-d H:i:s") /*,
            'etTo' => date(),
            'completed' => 0*/
        );
        
        $res = $this->push_notification_model->getMessageListAndUsers($p);
        if(!$res):
            die("no data found");
        endif;
        
        $updateItems = array();
        $gcms = array();
        foreach($res->result() as $r):
            //$gcms[] = $r->gcmRegId;
            if(!isset($updateItems[$r->messageId])):
                $updateItems[$r->messageId]['messageInfo'] = array(
                    'headLine' => $r->headLine,
                    'body' => $r->body,
                    'notifyImage' => $r->notifyImage
                );                
                $updateItems[$r->messageId]['gcmList'] = array();
                $updateItems[$r->messageId]['updateList'] = array();
            endif;
            
            $updateItems[$r->messageId]['gcmList'][] = $r->gcmRegId;
            
            $updateItems[$r->messageId]['updateList'][] = array(
                'messageLogId' => $r->messageLogId,
                'sent' => 1
            );
        endforeach;
        
        try {
            foreach($updateItems as $mid => $v):
                if(count($v['gcmList']) > 0):
                    $message = $v['messageInfo'];
                    $NotifRes = $this->push_notification_model->send_notification($v['gcmList'], $message['headLine'], $message['body'], $message['notifyImage']);                    
                    d($NotifRes,false);
                    $this->db->reset_query();
                    $this->db->where("messageId", $mid)
                             ->update_batch("message_log", $v['updateList'], 'messageLogId');
                endif;
            endforeach;
        }
        catch(Exception $e){
            d($e->getMessage());
        }
        
        d($res->result());
    }
    
    function index2() {

        $id = "dwtKWZaqg6Q:APA91bEgoMfwiWu6EhSvjdod0vY8t3ergmv7A-P9VEFM-1FOKMci4cHCqMQ5rociPAcepg9evUrQExz1lcqzr7cLZa27OxJ-_IdEpmK1gntlQaf9tjqxYxF7v_7dbdg7FeQueshIf8Jzj2OxCbclKsJz5mH67xd3bw";
        $url = 'https://fcm.googleapis.com/fcm/send';

        $message = array(
            'title' => 'Test Title',
            'subtitle' => 'test sub title',
            'body' => 'Test Body',
            'vibrate' => 1,
            'sound' => 1,
            'image' => 'https://pay.google.com/about/static/images/social/og_image.jpg'
        );

        $fields = array(
            //'to' => $id,
            //'notification' => $message,
            "registration_ids" => array(
                'cwrNE0RM-TU:APA91bHkitG59y1DB9u9LLhwPjHCRyN4EbPCwUIvK7i2MQ7Rm56AyEvilhOEe7FMzbMmFesYc20ftD8jw6Y3_P00rtbafdfJjGjwnotuUAL-rrhsSbm8dYCe4LRTWxOby0dmXu05m0oq',
                'ert3puQEj_4:APA91bFgzDN-HH0zv8k-afG2WICE_SHebKdqACGPvWdVcZxPehIRVFU3v-eDFTiMitq5EclPwPnQEkbqkgkEdJa8aazN5iC0-aSOeZKNR5pJnOl9wifuhCumcz50XHzgu6VVWl_Y2BpC'
            ), //["device_token_1", "device_token_2"],
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
