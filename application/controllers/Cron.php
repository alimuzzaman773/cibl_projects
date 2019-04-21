<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends MX_Controller
{
    function __construct() {
        parent::__construct();
        //$this->load->library("my_session");
    }
    
    function send_notification($messageId = NULL, $completed = NULL)
    {
        if(!is_cli()):
            die("Not allowed from browser");
        endif;
        
        $this->load->model("push_notification_model");
        
        //1. get message limit = 1, completed = 0, 
        //2. if segmented - get users from message log with sent = 0
        //3. if all - get all the devices from the system
        //4. start sending batch notifications
        
        $p = array(
            //'messageId' => 6,
            'etFrom' => date("Y-m-d H:i:s"),
            /*'etTo' => date(),*/
            'completed' => 0,
            'limit' => 1,
            'isActive' => 1
        );
        
        if((int)$messageId > 0):
            $p['messageId'] = $messageId;
        endif;
        
        if($completed !== NULL && is_numeric($completed)):
            $p['completed'] = $completed;
        endif;
        
        $messageInfoResponse = $this->push_notification_model->getMessageInfo($p);
        if(!$messageInfoResponse):
            die("no data found");
        endif;
        
        $receipients = array();
        $messageInfo = $messageInfoResponse->row();
        if($messageInfo->receivers == 'all'):
            //get all system users from the devices table
            $res = $this->push_notification_model->getAllDevices();
            if($res):
                $receipients = $res->result();
            endif;
        else:
            //get all users where sent = 0
            $mp = array(
                'messageId' => $messageInfo->messageId,
                'sent' => 0
            );
            $res = $this->push_notification_model->getMessageListAndUsers($mp);
            if($res):
                $receipients = $res->result();
            endif;         
        endif;
        
        $receipientThreshold = 50;
        $gcms = array(
            'ios' => array(),
            'android' => array()
        );
        $i = 0;
        $counter = 0;
        foreach($receipients as $r):
            $osCode = (int)$r->osCode;
            if(!in_array($osCode, array(1,2))):
                continue;
            endif;
        
            $osCode = ((int)$r->osCode == 2) ? 'ios' : 'android';
        
            if(!isset($gcms[$osCode][$i])):
                $gcms[$osCode][$i] = array();
            endif;
            if(is_null($r->gcmRegId) || strlen($r->gcmRegId) < 100):
                continue;
            endif;
            
            $gcms[$osCode][$i][] = $r->gcmRegId;
            
            $counter++;
            if($counter == $receipientThreshold):
                $counter = 0;
                $i++;
            endif;
        endforeach;
        
        /*$gcmList = array(
            'f7PnN8--X6Y:APA91bGvuK2-CRB79jW6HYGDzsMciUI4STO2_7_evMeTJm1vyyYNYUqXol5iCsvmv5t8GqIElFTZUr_IRBde8HT7PIdZcMMCeEiSSg9jHNj7fBB38RR94K2vYnTbAWhiZ1tVuxmprHB-',
            "c8L3VyzfQtg:APA91bGyrwsmOqhQRi9YLIvm4CD87JsQBy3cLesYJIMnq8SDcMhTo6jdI8TSdrQff2EUBESU4SPUwRU96MpKRwZ0L_pXvpX0xz198byseaRmL-jNJM5cMtLvppvr5d_0wayc0l5WTdID"
        );
        
        $gcms['ios'][0][] = $gcmList[0]; 
        $gcms['android'][0][] = $gcmList[1];
        */
        
        //d($gcms);
        $this->load->helper("text");
        $notifyRes = array();
        try {
            if(@$messageInfo->preview == NULL):
                $messageInfo->preview = $messageInfo->headLine;
            endif;
            $body = @$messageInfo->preview;//character_limiter(trim(strip_tags(html_entity_decode(preg_replace('/\s+/', ' ', trim($messageInfo->body))))), 100);
            foreach($gcms['ios'] as $k => $v):
                if(count($v) > 0):
                    $notifyRes[] = $this->push_notification_model->send_notification($v, $messageInfo->headLine, $body, $messageInfo->messageId, base_url().'assets/uploads/files/'.$messageInfo->notifyImage, 'ios');
                endif;
            endforeach;
            
            foreach($gcms['android'] as $k => $v):
                if(count($v) > 0):
                    $notifyRes[] = $this->push_notification_model->send_notification($v, $messageInfo->headLine, $body, $messageInfo->messageId, base_url().'assets/uploads/files/'.$messageInfo->notifyImage, 'android');
                endif;
            endforeach;
            
            echo "sending notification done. ";
            $this->db->reset_query();
            $this->db->where("messageId", $messageInfo->messageId)
                     ->update("message", array('completed' => 1));
            
            echo 'Notification sent';
            d($notifyRes,false);
            die();
        }
        catch(Exception $e){
            echo $e->getMessage();
            //var_dump($notifyRes);
            die();
        }
    }    
}
