
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Push_notification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('push_notification_model');
        $this->load->library('session');
  
        $this->load->model('login_model');
        if($this->login_model->check_session()){
            redirect('/admin_login/index');
        }
    }


    public function index()
    {
        $data['sentMessages'] = json_encode($this->push_notification_model->getAllMessages());
        $this->output->set_template('theme2');
        $this->load->view('push_notification/view_all_messages.php', $data);
    }


    public function writeMessage()
    {
        $data['appsUsers'] = json_encode($this->push_notification_model->getAllAppsUsersFroPush());
        $this->output->set_template('theme2');
        $this->load->view('push_notification/write_message.php', $data);
    }


    public function sendMessage()
    {
        $data['headLine'] = $_POST['headLine'];
        $data['body'] = $_POST['body'];
        $data['recipient'] = $_POST['skyIds'];
        $skyIds = explode(",", $data['recipient']);
        $gcmIds = $this->push_notification_model->getAllGcmIds($skyIds);



        $gcmRegIds = array();
        foreach($gcmIds as $key => $value){
            array_push($gcmRegIds, $value->gcmRegId);
        }

        $message_array = array('message' => $data['headLine']);

        $url = 'http://192.168.5.81/eblapi/send_push_notification/sendPush';
        //$url = 'https://115.127.24.203/eblapi/send_push_notification/sendPush';

        $postData = array('gcmIds' => json_encode($gcmRegIds), 'msgHeadline' => $data['headLine']);

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($handle);
        if($result === false){
            die('Curl failed. Message >> ' . curl_error($handle));
        }
        curl_close($handle);


        $data['createdBy'] = 1;
        $data['updatedBy'] = 0;
        $data['creationDtTm'] = date("Y-m-d G:i:s");
        $data['isActive'] = 1;
        $messageId = $this->push_notification_model->storeMessage($data);

        foreach ($skyIds as $key => $value)
        {
            $insertArray = array('messageId' => $messageId,
                                 'skyId' => $value,
                                 'serverReferenceNo' => $result,
                                 'isActiveApps' => 1,
                                 'isActive' => 1,
                                 'createdBy' => 1,
                                 'updatedBy' => 0,
                                 'creationDtTm' => date("Y-m-d G:i:s"),
                                 'updateDtTm' => NULL);
            $batchArray[] = $insertArray;
        }
        $this->db->insert_batch('message_log', $batchArray);
        redirect('push_notification');
    }

}