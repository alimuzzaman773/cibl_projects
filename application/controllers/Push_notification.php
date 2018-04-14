
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Push_notification extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->model('push_notification_model');
    }

    public function index() {
        
        $data['pageTitle'] = "Push Notification";
        $data['sentMessages'] = json_encode($this->push_notification_model->getAllMessages());
        $data['body_template'] = "push_notification/view_all_messages.php";
        $this->load->view("site_template.php", $data);

//        $moduleCodes = $this->session->userdata('contentSetupModules');
//        $moduleCodes = explode("|", $moduleCodes);
//        $index = array_search(notification, $moduleCodes);
//        if ($index > -1) {
//
//
//            $data['sentMessages'] = json_encode($this->push_notification_model->getAllMessages());
//            $this->output->set_template('theme2');
//            $this->load->view('push_notification/view_all_messages.php', $data);
//        } else {
//            echo "not allowed";
//            die();
//        }
    }

    public function writeMessage() {
        $moduleCodes = $this->session->userdata('contentSetupModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(notification, $moduleCodes);
        if ($index > -1) {


            $data['appsUsers'] = json_encode($this->push_notification_model->getAllAppsUsersFroPush());
            $this->output->set_template('theme2');
            $this->load->view('push_notification/write_message.php', $data);
        } else {
            echo "not allowed";
            die();
        }
    }

    public function sendMessage() {
        $data['headLine'] = $_POST['headLine'];
        $data['body'] = $_POST['body'];
        $data['recipient'] = $_POST['skyIds'];
        $skyIds = explode(",", $data['recipient']);
        $gcmIds = $this->push_notification_model->getAllGcmIds($skyIds);

        /*     new    */
        $gcmRegIdsAndroid = array();
        $gcmRegIdsIos = array();
        foreach ($gcmIds as $key => $value) {
            if ($value->osCode == "01") {  //for android
                array_push($gcmRegIdsAndroid, $value->gcmRegId);
            } elseif ($value->osCode == "02") { //for ios
                array_push($gcmRegIdsIos, $value->gcmRegId);
            }
        }
        //---------------- end new------------//



        $message_array = array('message' => $data['headLine']);

        $url = 'http://192.168.5.81/eblapi/send_push_notification/sendPush';



        //$postData = array('gcmIds' => json_encode($gcmRegIds), 'msgHeadline' => $data['headLine']);





        /*    new    */
        $postData = array('gcmIdsAndroid' => json_encode($gcmRegIdsAndroid), 'gcmIdsIos' => json_encode($gcmRegIdsIos), 'msgHeadline' => $data['headLine']);
        //---------------- end new --------------------//





        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($handle);
        if ($result === false) {
            die('Curl failed. Message >> ' . curl_error($handle));
        }
        curl_close($handle);

        $createdUpdatedBy = $this->session->userdata('adminUserId');
        $dtTm = date("Y-m-d G:i:s");

        $data['createdBy'] = $createdUpdatedBy;
        $data['updatedBy'] = $createdUpdatedBy;
        $data['creationDtTm'] = $dtTm;
        $data['updateDtTm'] = $dtTm;

        $data['isActive'] = 1;
        $messageId = $this->push_notification_model->storeMessage($data);


        foreach ($skyIds as $key => $value) {
            $insertArray = array('messageId' => $messageId,
                'skyId' => $value,
                'serverReferenceNo' => $result,
                'isActiveApps' => 1,
                'isActive' => 1,
                'createdBy' => $createdUpdatedBy,
                'updatedBy' => $createdUpdatedBy,
                'creationDtTm' => $dtTm,
                'updateDtTm' => $dtTm);
            $batchArray[] = $insertArray;
        }
        $this->db->insert_batch('message_log', $batchArray);
        redirect('push_notification');
    }

    public function oldsendMessage() {

        $moduleCodes = $this->session->userdata('contentSetupModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(notification, $moduleCodes);
        if ($index > -1) {

            $data['headLine'] = $_POST['headLine'];
            $data['body'] = $_POST['body'];
            $data['recipient'] = $_POST['skyIds'];
            $skyIds = explode(",", $data['recipient']);
            $gcmIds = $this->push_notification_model->getAllGcmIds($skyIds);



            $gcmRegIds = array();
            foreach ($gcmIds as $key => $value) {
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
            if ($result === false) {
                die('Curl failed. Message >> ' . curl_error($handle));
            }
            curl_close($handle);


            $admin_id = $this->session->userdata('adminUserId');
            $current_date = input_date();

            $data['createdBy'] = $admin_id;
            $data['updatedBy'] = $admin_id;
            $data['creationDtTm'] = $current_date;
            $data['updateDtTm'] = $current_date;
            $data['isActive'] = 1;
            $messageId = $this->push_notification_model->storeMessage($data);

            foreach ($skyIds as $key => $value) {
                $insertArray = array('messageId' => $messageId,
                    'skyId' => $value,
                    'serverReferenceNo' => $result,
                    'isActiveApps' => 1,
                    'isActive' => 1,
                    'createdBy' => $admin_id,
                    'updatedBy' => $admin_id,
                    'creationDtTm' => $current_date,
                    'updateDtTm' => $current_date
                );
                $batchArray[] = $insertArray;
            }
            $this->db->insert_batch('message_log', $batchArray);
            redirect('push_notification');
        } else {
            echo "not allowed";
            die();
        }
    }

}
