<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification extends CI_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
    }
    
    function index()
    {
        //$this->my_session->authorize("canViewNotification");
        try
        {
            /* This is only for the autocompletion */
            $crud = new grocery_CRUD();
            $crud->unset_jquery();
            $crud->set_theme(TABLE_THEME);
            $crud->set_table("message");
            
            $crud->set_subject('Notification');
            
            $crud->required_fields(array('headLine','body', 'executionTime', 'receivers'));
            
            $crud->set_rules("headLine", "Headline", "trim|xss_clean");
            $crud->set_rules("body", "Body", "trim|xss_clean");
                        
            $crud->unset_delete();
            if(!$this->my_session->checkPermission("canAddNotification")):
                $crud->unset_add();
            endif;
            
            if(!$this->my_session->checkPermission("canEditNotification")):
                $crud->unset_edit();
            endif;
             
            $crud->unset_texteditor("address");
            
            $crud->columns('headLine','body','notifyImage', 'receivers', 'executionTime','completed');
            $crud->display_as('notifyImage','Notification Image')
                 ->display_as("executionTime", "Execution Time"); 
            
            $time = date("Y-m-d H:i:s");
            
            $crud->set_field_upload('notifyImage','assets/uploads/files');
            
            $crud->add_fields('headLine','body','notifyImage', 'receivers', 'executionTime', 'isActive', 'updateDtTm', 'creationDtTm');
            $crud->edit_fields('headLine','body','notifyImage', 'receivers', 'executionTime', 'isActive', 'updateDtTm');
            
            $receiversOptions = array("all", "segmented");
            
            $crud->change_field_type('receivers', 'enum', $receiversOptions);
            
            $crud->change_field_type('updateDtTm', 'hidden', $time);            
            $crud->change_field_type('creationDtTm', 'hidden', $time);            
            
            $crud->add_action("Receivers", '', "notification/users", 'glyphicon glyphicon-user');
            
            $crud->unset_clone();
            
            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Notification";
            $output->base_url = base_url();
            
            $output->crudState = $crud->getState();
            
            $output->body_template = 'notification/index.php';
            $this->load->view("site_template.php",$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    
    function users($messageId)
    {
        if((int)$messageId <= 0):
            show_error("No message id defined");
            die();
        endif;
        
        $this->load->model("push_notification_model");
        $res = $this->push_notification_model->getMessageInfo(array("messageId" => $messageId));
        if(!$res):
            show_404();
            die();
        endif;
        
        $data['messageInfo'] = $res->row();
        
        $data['pageTitle'] = "Notification Users";
        $data['base_url'] = base_url();
        $data['body_template'] = "notification/users.php";
        $this->load->view("site_template.php", $data);
    }
    
    function ajax_get_app_users()
    {
        //$this->my_session->authorize("canViewAppUser");
        $p['get_count'] = (bool) $this->input->get("get_count", true);
        $p['limit'] = $this->input->get('limit', true);
        $p['offset'] = $this->input->get('offset', true);
        $p['messageId'] = (int)$this->input->get('messageId', true);

        $this->load->model("push_notification_model");
        
        $json = array();
        if ($p['get_count']) {
            $params['get_count'] = 1;
            $params['messageId'] = $p['messageId'];
            $result = $this->push_notification_model->getAllAppsUsers($params);
            //echo $this->db->last_query();
            if ($result):
                $json['total'] = $result->row()->total;
            endif;
        }

        unset($p['get_count']);
        $result = $this->push_notification_model->getAllAppsUsers($p);
        if ($result):
            $json['app_users'] = $result->result();
        endif;

        my_json_output($json);
    }
    
    function save_message_users()
    {
        $messageId = (int)$this->input->post("messageId", true);
        $items = $this->input->post("items");
        
        $date = date("Y-m-d H:i:s");
        
        $logs = array(
            'delete' => array(),
            'add' => array()
        );
        $itemList = json_decode($items);
        foreach($itemList as $i):
            if((int)$i->checked == 1 && (int)$i->messageLogId <= 0 && (int)$i->skyId > 0):
                $logs['add'][] = array(
                    "messageId" => $messageId,
                    "skyId" => (int)$i->skyId,
                    "createdBy" => $this->my_session->userId,
                    "creationDtTm" => $date,
                    "updateDtTm" => $date
                );
            endif;
            
            if((int)$i->checked == 0 && (int)$i->messageLogId > 0 && (int)$i->skyId > 0):
                $logs['delete'][] = array(
                    "messageId" => $messageId,
                    "messageLogId" => (int)$i->messageLogId,
                    "skyId" => (int)$i->skyId
                );
            endif;
        endforeach;
        
        if(count($logs['add']) > 0)
        {
            $this->db->reset_query();
            $this->db->insert_batch("message_log", $logs['add']);
        }
        
        if(count($logs['delete']) > 0)
        {
            $id = array();
            foreach($logs['delete'] as $d):
                $id[] = $d['messageLogId'];
            endforeach;
            
            $this->db->reset_query();
            $this->db->where("messageId")
                     ->where_in("messageLogId", $id)
                     ->delete("message_log");
        }
        
        $json = array(
            'success' => true,
            //'items' => json_decode($items),
            //'messageId' => $messageId,
            //'logs' => $logs
        );
        
        my_json_output($json);
    }
    
    public function index2() {

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