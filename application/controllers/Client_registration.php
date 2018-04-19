<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client_registration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('my_session');
        $this->load->model('client_registration_model');
        $this->load->model('common_model');
        $this->load->model('login_model'); // for formating user information with library function
        /*if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }*/
        
        $this->my_session->checkSession();
    }

    function index() 
    {        
        //$data['appsUsers'] = json_encode($this->client_registration_model->getAllAppsUsers());
        //$data['verifiedDevice'] = json_encode($this->client_registration_model->countVerifiedDevice());
        //$data['nonVerifiedDevice'] = json_encode($this->client_registration_model->countNonVerifiedDevice());
        //$data['totalDevice'] = json_encode($this->client_registration_model->countTotalDevice());
        $data['actionCodes'] = @$actionCodes;
        $data['actionNames'] = @$actionNames;
        
        $data['pageTitle'] = "Apps user list";
        
        $data['body_template'] = 'client_registration/client_registration_view.php';
        $this->load->view('site_template.php', $data);
        
    }
    
    function ajax_get_app_users()
    {
        $p['get_count'] = (bool)$this->input->get("get_count",true);
        $p['limit'] = $this->input->get('limit',true);
        $p['offset'] = $this->input->get('offset', true);
        
        $json = array();
        if($p['get_count']){
            $params['get_count'] = 1;
            $result = $this->client_registration_model->getAllAppsUsers($params);            
            //echo $this->db->last_query();
            if($result):
                $json['total'] = $result->row()->total;
            endif;
        }
        
        unset($p['get_count']);
        $result = $this->client_registration_model->getAllAppsUsers($p);
        if($result):
            $json['app_users'] = $result->result(); 
        endif;
        
        my_json_output($json);
    }

    function viewUser()
    {   
        if (isset($_GET['skyId'])) {
            $skyId = $_GET['skyId'];
            $data['userInfo'] = $this->client_registration_model->getAppsUsersById($skyId); // decision needed whether to show from main table or shadow
            $data['deviceInfo'] = json_encode($this->client_registration_model->getDeviceBySkyid($skyId));
            $data['accountInfo'] = json_encode($this->login_model->checkAccount($data['userInfo']));
            $data['body_template'] = 'client_registration/apps_user_detail_view.php';
            $this->load->view('site_template.php', $data);
        }
        else{
            show_404();
        }
            
    }

    function deviceInfo() 
    {        
            if (isset($_GET['skyId']) && isset($_GET['eblSkyId'])) {
                $data['skyId'] = $_GET['skyId'];
                $tableData = $this->client_registration_model->getDeviceBySkyid($data['skyId']);
                if (!empty($tableData)) {
                    $data['eblSkyId'] = $tableData[0]['eblSkyId'];
                } else {
                    $data['eblSkyId'] = $_GET['eblSkyId'];
                }
                $data['deviceInfo'] = json_encode($tableData);
            } else {
                $data['skyId'] = "";
                $data['eblSkyId'] = "";
                $data['deviceInfo'] = json_encode($this->client_registration_model->getDeviceBySkyid());
            }
            
            $data['body_template'] = 'client_registration/device_info_view.php';
            $this->load->view('site_template.php', $data);
        
    }

    function addDeviceInfo() {        
        if (isset($_GET['skyId']) && isset($_GET['eblSkyId'])) {
            $data['skyId'] = $_GET['skyId'];
            $data['eblSkyId'] = $_GET['eblSkyId'];
            $data['selectedActionName'] = 'Add';

            $data['message'] = "";
            $data['body_template'] = 'client_registration/enter_imei_view.php'; 
            $this->load->view('site_template.php', $data);
        }        
    }

    function insertDevice() 
    {
        $this->load->library("form_validation");
        //$this->form_validation = &$this;
        $this->form_validation->set_data($_POST);
        $this->form_validation->set_rules("skyId", "SKY ID", "trim|required");
        $this->form_validation->set_rules("eblSkyId", "EBL SKY ID", "trim|required");
        $this->form_validation->set_rules("imeiNo", "imeiNo", "trim|required");
        
        if($this->form_validation->run() === false):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );
            my_json_output($json);
        endif;
        
        $loginId = $this->my_session->userId;

        $data['skyId'] = $_POST['skyId'];
        $data['eblSkyId'] = $_POST['eblSkyId'];
        $data['imeiNo'] = $_POST['imeiNo'];
        $data['mcStatus'] = 0;
        $data['makerAction'] = $_POST['selectedActionName'];
        $data['makerActionCode'] = 'add';
        $data['makerActionDt'] = date("y-m-d");
        $data['makerActionTm'] = date("G:i:s");
        $data['makerActionBy'] = $loginId;
        $data['isPublished'] = 0;
        $data['isActive'] = 1;
        $data['creationDtTm'] = input_date();
        $data['createdBy'] = $loginId;

        $data['selectedActionName'] = $_POST['selectedActionName'];

        $checkImei = $this->client_registration_model->getImeiByNumber($data['imeiNo']);

        if ($checkImei) {
            $data['message'] = 'The IMEI No. "' . $data['imeiNo'] . '" is already assigned to ESB ID "' . $checkImei['eblSkyId'] . '"';
            $json = array(
                "success" => false,
                "msg" => $data['message']
            );
            my_json_output($json);
        } 

        // sending mail after first device add
        // $deviceData = $this->client_registration_model->getDeviceBySkyid($data['skyId']);
        // if(empty($deviceData)){
        //     $email = $this->client_registration_model->getAppsUsersById($data['skyId']);
        //     $maildata['to'] = $email['userEmail'];
        //     $maildata['subject'] = "Device Activation";
        //     $maildata['body'] = '<p>Dear Sir/Madam,</p>
        //                          <p>Your EBL SKYBANKING account registration process is completed.</p>
        //                          <p>Please activate your device using provided EBL SKYBANKING ID and Password to access the Banking / Bills Pay / Fund Transfer section.</p>
        //                          <p>Please ignore this email, if you have already activated your device.</p>
        //                          <p>Thanks & Regards, <br/>EBL SKY AdminPanel</p>';
        //     //$this->common_model->send_mail($maildata);
        // }
        $result = $this->client_registration_model->insertImeiNo($data);
        
        $json = array("success" => false);
        if((int)$result > 0){
            $json['success'] = true;
        }
        
        my_json_output($json);
    }

    function editDevice() 
    {   
        if (isset($_GET['skyId']) && isset($_GET['eblSkyId']) && isset($_GET['imeiNo'])) {
            $data['deviceId'] = $_GET['deviceId'];
            $data['skyId'] = $_GET['skyId'];
            $data['eblSkyId'] = $_GET['eblSkyId'];
            $data['imeiNo'] = $_GET['imeiNo'];
            $data['message'] = "";
            $data['selectedActionName'] = "Edit";

            $dbData = $this->client_registration_model->getImeiByNumber($data['imeiNo']);

            $data['checkerActionComment'] = $dbData['checkerActionComment'];

            if ($data['checkerActionComment'] != NULL) {
                $data['reasonModeOfDisplay'] = "display: block;";
            } else {
                $data['reasonModeOfDisplay'] = "display: none;";
            }

            $data['body_template'] = 'client_registration/edit_imei_view.php';
            $this->load->view('site_template.php', $data);
        }
            
    }

    function updateDevice() 
    {
        $this->load->library("form_validation");
        //$this->form_validation = &$this;
        $this->form_validation->set_data($_POST);
        $this->form_validation->set_rules("skyId", "SKY ID", "trim|required");
        $this->form_validation->set_rules("eblSkyId", "EBL SKY ID", "trim|required");
        $this->form_validation->set_rules("imeiNo", "imeiNo", "trim|required");
        $this->form_validation->set_rules("deviceId", "Device ID", "trim|required|integer|greater_than[0]");
        
        if($this->form_validation->run() === false):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );
            my_json_output($json);
        endif;
       
        $loginId = $this->my_session->userId;
        
        $data['deviceId'] = $_POST['deviceId'];
        $data['skyId'] = $_POST['skyId'];
        $data['eblSkyId'] = $_POST['eblSkyId'];
        $data['imeiNo'] = $_POST['imeiNo'];
        $data['selectedActionName'] = $_POST['selectedActionName'];

        $count = $this->client_registration_model->checkDuplicateImei($data);
        if ($count > 0) 
        {
            $checkImei = $this->client_registration_model->getImeiByNumber($data['imeiNo']);
            $data['msg'] = 'The IMEI No. "' . $data['imeiNo'] . '" is already assigned to ESB ID "' . $checkImei['eblSkyId'] . '"';            
            $data['success'] = false;
            my_json_output($data);
        } 
        
        $deviceId = $data['deviceId'];
        $updateData['imeiNo'] = $data['imeiNo'];
        $updateData['updateDtTm'] = date("Y-m-d G:i:s");

        $updateData['mcStatus'] = 0;
        $updateData['makerAction'] = $data['selectedActionName'];
        $updateData['makerActionCode'] = 'edit';
        $updateData['makerActionDt'] = date("y-m-d");
        $updateData['makerActionTm'] = date("G:i:s");
        $updateData['makerActionBy'] = $loginId;
        $updateData['updatedBy'] = $loginId;

        $this->client_registration_model->updateImeiNo($updateData, $deviceId);        
        $json = array(
            "success" => true,
            "msg" => "saved"
        );
        my_json_output($json);        
    }

    function userActive() 
    {        
        $eblSkyId = explode("|", $_POST['eblSkyId']);
        $skyId = explode("|", $_POST['skyId']);
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($skyId as $index => $value) {
            $updateData = array(
                "skyId" => $skyId[$index],
                "isActive" => 1,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'active',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->userId
            );
            $updateArray[] = $updateData;
        }
        $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');
        
        if(count($updateArray) > 0):
            $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;
        
        $json = array(
            "success" => false,
            "msg" => "error processing:: no data provided"
        );
        my_json_output($json);    
    }

    function userInactive() 
    {
        $eblSkyId = explode("|", $_POST['eblSkyId']);
        $skyId = explode("|", $_POST['skyId']);
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($skyId as $index => $value) {
            $updateData = array("skyId" => $skyId[$index],
                "isActive" => 0,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'inactive',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->userId
            );
            $updateArray[] = $updateData;
        }
        
        if (count($updateArray) > 0):
            $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;

        $json = array(
            "success" => false,
            "msg" => "error processing:: no data provided"
        );
        my_json_output($json);
    }

    function userLock()
    {   
        $eblSkyId = explode("|", $_POST['eblSkyId']);
        $skyId = explode("|", $_POST['skyId']);
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($skyId as $index => $value) {
            $updateData = array("skyId" => $skyId[$index],
                "isLocked" => 1,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'lock',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->userId
            );
            $updateArray[] = $updateData;
        }
        if(count($updateArray) > 0):
            $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');            
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;
        $json = array(
            "success" => false,
            "msg" => "error processing:: no data provided"
        );
        my_json_output($json);
    }

    function userUnlock() 
    {        
        $eblSkyId = explode("|", $_POST['eblSkyId']);
        $skyId = explode("|", $_POST['skyId']);
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($skyId as $index => $value) {
            $updateData = array("skyId" => $skyId[$index],
                "isLocked" => 0,
                "wrongAttempts" => 0,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'unlock',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->userId
            );
            $updateArray[] = $updateData;
        }
                
        if(count($updateArray) > 0):
            $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;
        $json = array(
            "success" => false,
            "msg" => "error processing:: no data provided"
        );
        my_json_output($json);            
    }

    function deviceActive() 
    {   
        $imeiNo = explode("|", $_POST['imeiNo']);
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($imeiNo as $index => $value) {
            $updateData = array(
                "imeiNo" => $imeiNo[$index],
                "isActive" => 1,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'active',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->userId
            );        
            $updateArray[] = $updateData;
        }
        
        if(count($updateArray) > 0):
            $this->db->update_batch('device_info_mc', $updateArray, 'imeiNo');
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;
        
        $json = array(
            "success" => false,
            "msg" => "error processing:: no data provided"
        );
        my_json_output($json);
    }

    function deviceInactive() 
    {        
        $imeiNo = explode("|", $_POST['imeiNo']);
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($imeiNo as $index => $value) {
            $updateData = array(
                "imeiNo" => $imeiNo[$index],
                "isActive" => 0,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'inactive',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->userId
            );
            $updateArray[] = $updateData;
        }
        
        if(count($updateArray) > 0):
            $this->db->update_batch('device_info_mc', $updateArray, 'imeiNo');  
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;
        $json = array(
            "success" => false,
            "msg" => "error processing:: no data provided"
        );
        my_json_output($json);
            
    }

    function deviceUnlock() 
    {        
        $imeiNo = explode("|", $_POST['imeiNo']);
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($imeiNo as $index => $value) {
            $updateData = array(
                "imeiNo" => $imeiNo[$index],
                "isLocked" => 0,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'unlock',
                "makerActionDt" => date("Y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->userId
            );
            $updateArray[] = $updateData;
        }
        
        if(count($updateArray) > 0):
            $this->db->update_batch('device_info_mc', $updateArray, 'imeiNo');
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;
        
        $json = array(
            "success" => false,
            "msg" => "error processing:: no data provided"
        );
        my_json_output($json);
            
    }

    function deviceLock() 
    {
        $imeiNo = explode("|", $_POST['imeiNo']);
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($imeiNo as $index => $value) {
            $updateData = array("imeiNo" => $imeiNo[$index],
                "isLocked" => 1,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'lock',
                "makerActionDt" => date("Y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->userId
            );
            $updateArray[] = $updateData;
        }
        
        if(count($updateArray) > 0):
            $this->db->update_batch('device_info_mc', $updateArray, 'imeiNo');
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;
        
        $json = array(
            "success" => false,
            "msg" => "error processing:: no data provided"
        );
        my_json_output($json);
        
            
    }

    function userDelete() 
    {   
        $skyId = (int) $this->input->post("skyId");
        if ($skyId > 0) {
            $res = $this->client_registration_model->countVerifiedDevice($skyId);
            if ($res == true) {
                echo 1;
            } else {
                $data["mcStatus"] = 0;
                $data["makerAction"] = "Delete";
                $data["salt2"] = "delete";
                $data["makerActionCode"] = 'delete';
                $data["makerActionDt"] = date("Y-m-d");
                $data["makerActionTm"] = date("G:i:s");
                $data["makerActionBy"] = $this->session->userdata('adminUserId');
                $this->client_registration_model->userDeleteChange($data, $skyId);
                echo 0;
            }
        }
           
    }

}
