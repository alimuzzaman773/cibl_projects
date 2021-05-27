<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client_registration extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('my_session');
        $this->load->model('client_registration_model');
        $this->load->model('common_model');
        $this->load->model('login_model');
        $this->my_session->checkSession();
    }

    function index() {
        $this->my_session->authorize("canViewAppUser");
        $data['pageTitle'] = "Apps user list";
        $data['body_template'] = 'client_registration/client_registration_view.php';
        $this->load->view('site_template.php', $data);
    }

    function ajax_get_app_users() {
        $this->my_session->authorize("canViewAppUser");
        $p['get_count'] = (bool) $this->input->get("get_count", true);
        $p['limit'] = $this->input->get('limit', true);
        $p['offset'] = $this->input->get('offset', true);
        $p['isOwnAccTransfer'] = $this->input->get('isOwnAccTransfer', true);
        $p['isInterAccTransfer'] = $this->input->get('isInterAccTransfer', true);
        $p['isOtherAccTransfer'] = $this->input->get('isOtherAccTransfer', true);
        $p['isAccToCardTransfer'] = $this->input->get('isAccToCardTransfer', true);
        $p['isCardToAccTransfer'] = $this->input->get('isCardToAccTransfer', true);
        $p['isUtilityTransfer'] = $this->input->get('isUtilityTransfer', true);
        $p['isQrPayment'] = $this->input->get('isQrPayment', true);
        $p['search'] = $this->input->get("search", true);

        $filter = array(
            'isLocked', 'isActive'
        );

        foreach ($filter as $k):
            if (trim($this->input->get($k, true)) != '') {
                $p[$k] = $this->input->get($k, true);
            }
        endforeach;

        $json = array();
        if ($p['get_count']) {
            $params = $p;
            unset($params['limit']);
            $result = $this->client_registration_model->getAllAppsUsers($params);
            //echo $this->db->last_query();
            if ($result):
                $json['total'] = $result->row()->total;
                $json['q'][] = $this->db->last_query();
            endif;
        }

        unset($p['get_count']);
        $result = $this->client_registration_model->getAllAppsUsers($p);
        if ($result):
            $json['app_users'] = $result->result();
        endif;
        $json['q'][] = $this->db->last_query();

        my_json_output($json);
    }

    // Edit App User: Shahid
    function edit() {
        $this->my_session->authorize("canEditAppUser");
        $data['pageTitle'] = "Edit apps user";
        $data['base_url'] = base_url();
        $data['body_template'] = 'client_registration/client_registration_edit_index.php';
        $this->load->view('site_template.php', $data);
    }

    function edit_form() {
        $this->load->view('client_registration/client_registration_edit.php', array());
    }

    function remove_user() {
        $this->load->view('client_registration/remove_user.php', array());
    }

    function update_limit_package($skyId) {
        //$this->my_session->authorize("canEditAppUserLimitPackage");
        $data = array(
            "userGroups" => array()
        );
        $this->load->model(array("apps_users_model"));
        $skyRes = $this->apps_users_model->getUserById($skyId);
        if (empty($skyRes)):
            show_error("No apps user information found");
            die();
        endif;

        $data['skyInfo'] = (object) $skyRes;
        $data['requestedGroupInfo'] = array();
        $data['serviceId'] = (int) $this->input->get("serviceId", true);

        $userGroup = $this->apps_users_model->getAppsUserGroup(array('isActive' => 1));
        if (count($userGroup)):
            $data['userGroups'] = $userGroup;
            $requestedId = (int) $this->input->get('requestedId', true);
            foreach ($userGroup as $ug):
                if ($requestedId == $ug->appsGroupId):
                    $data['requestedGroupInfo'] = $ug;
                endif;
            endforeach;
        endif;

        $data['pageTitle'] = "Apps Users - Limit Package Update";
        $data['body_template'] = 'client_registration/update_limit_package.php';
        $this->load->view('site_template.php', $data);
    }

    function save_limit_package() {
        $this->my_session->authorize("canEditAppUserLimitPackage");
        $skyId = (int) $this->input->post("skyId", true);
        $appsGroupId = (int) $this->input->post("appsGroupId", true);
        $serviceId = (int) $this->input->post("serviceId", true);

        $this->load->library("form_validation");
        //$this->form_validation = &$this;
        $this->form_validation->set_data($_POST);
        $this->form_validation->set_rules("skyId", "SKY ID", "trim|required");
        $this->form_validation->set_rules("appsGroupId", "Limit Package ID", "trim|required");

        if ($this->form_validation->run() === false):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );
            my_json_output($json);
        endif;

        $loginId = $this->my_session->userId;

        $data['appsGroupId'] = $appsGroupId;
        $data['mcStatus'] = 0;
        $data['makerAction'] = "LimitPackageUpdate";
        $data['makerActionCode'] = 'update';
        $data['makerActionDt'] = date("y-m-d");
        $data['makerActionTm'] = date("G:i:s");
        $data['makerActionBy'] = $loginId;

        try {
            $this->db->reset_query();
            $this->db->where("skyId", $skyId)
                    ->update("apps_users_mc", $data);

            $this->db->reset_query();
            if ($serviceId > 0):
                $this->db->where("serviceId", $serviceId)
                        ->update("service_request_bank", array('status2' => 1, 'status1' => 1));
            endif;
            //update user activity

            $json = array(
                'success' => true
            );
            my_json_output($json);
        } catch (Exception $e) {
            $json = array(
                'success' => false,
                'msg' => $e->getMessage()
            );
            my_json_output($json);
        }
    }

    function viewUser() {
        $this->my_session->authorize("canViewAppUser");
        if (isset($_GET['skyId'])) {
            $skyId = $_GET['skyId'];
            $data['userInfo'] = $this->client_registration_model->getAppsUsersById($skyId); // decision needed whether to show from main table or shadow            

            if (!$data['userInfo']):
                show_404();
            endif;

            $data['deviceInfo'] = $this->client_registration_model->getDeviceBySkyid($skyId);

            $accountInfo = $this->login_model->checkAccount($data['userInfo']);
            foreach ($accountInfo as $k => $a):
                if (strtolower($a['accType']) == 'pc'):
                    $accountInfo[$k]['accNo'] = $this->common_model->numberMasking(MASK, $a['accNo']);
                endif;
            endforeach;

            $data['accountInfo'] = $accountInfo;

            $data['body_template'] = 'client_registration/apps_user_detail_view.php';
            $this->load->view('site_template.php', $data);
        } else {
            show_404();
        }
    }

    function deviceInfo() {
        $this->my_session->authorize("canViewAppUserDevice");
        if (isset($_GET['skyId']) && isset($_GET['eblSkyId'])) {
            $data['skyId'] = $_GET['skyId'];
            $tableData = $this->client_registration_model->getDeviceBySkyid($data['skyId']);
            if (!empty($tableData)) {
                $data['eblSkyId'] = $tableData[0]['eblSkyId'];
            } else {
                $data['eblSkyId'] = $_GET['eblSkyId'];
            }
            $data['deviceInfo'] = $tableData;
        } else {
            $data['skyId'] = "";
            $data['eblSkyId'] = "";
            $data['deviceInfo'] = $this->client_registration_model->getDeviceBySkyid();
        }

        $data['body_template'] = 'client_registration/device_info_view.php';
        $this->load->view('site_template.php', $data);
    }

    function addDeviceInfo() {
        $this->my_session->authorize("canViewAddUserDevice");
        if (isset($_GET['skyId']) && isset($_GET['eblSkyId'])) {
            $data['skyId'] = $_GET['skyId'];
            $data['eblSkyId'] = $_GET['eblSkyId'];
            $data['selectedActionName'] = 'Add';

            $data['message'] = "";
            $data['body_template'] = 'client_registration/enter_imei_view.php';
            $this->load->view('site_template.php', $data);
        }
    }

    function insertDevice() {
        $this->my_session->authorize("canAddAppUserDevice");
        $this->load->library("form_validation");
        //$this->form_validation = &$this;
        $this->form_validation->set_data($_POST);
        $this->form_validation->set_rules("skyId", "SKY ID", "trim|required");
        $this->form_validation->set_rules("eblSkyId", "EBL SKY ID", "trim|required");
        $this->form_validation->set_rules("imeiNo", "imeiNo", "trim|required");

        if ($this->form_validation->run() === false):
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
        $result = $this->client_registration_model->insertImeiNo($data);

        $json = array("success" => false);
        if ((int) $result > 0) {
            $json['success'] = true;
        }

        my_json_output($json);
    }

    function editDevice() {
        $this->my_session->authorize("canEditAppUserDevice");
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

    function updateDevice() {
        $this->my_session->authorize("canEditAppUserDevice");
        $this->load->library("form_validation");
        //$this->form_validation = &$this;
        $this->form_validation->set_data($_POST);
        $this->form_validation->set_rules("skyId", "SKY ID", "trim|required");
        $this->form_validation->set_rules("eblSkyId", "EBL SKY ID", "trim|required");
        $this->form_validation->set_rules("imeiNo", "imeiNo", "trim|required");
        $this->form_validation->set_rules("deviceId", "Device ID", "trim|required|integer|greater_than[0]");

        if ($this->form_validation->run() === false):
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
        if ($count > 0) {
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

    function userActive() {
        $this->my_session->authorize("canActiveAppUser");
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
        //$this->db->update_batch('apps_users_mc', $updateArray, 'skyId');

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

    function userInactive() {
        $this->my_session->authorize("canInactiveAppUser");
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

    function userLock() {
        $this->my_session->authorize("canLockAppUser");
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

    function userUnlock() {
        $this->my_session->authorize("canUnlockAppUser");
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

    function deviceActive() {
        $this->my_session->authorize("canActiveAppUserDevice");
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

        if (count($updateArray) > 0):
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

    function deviceInactive() {
        $this->my_session->authorize("canInactiveAppUserDevice");
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

        if (count($updateArray) > 0):
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

    function deviceUnlock() {
        $this->my_session->authorize("canUnlockAppUserDevice");
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

        if (count($updateArray) > 0):
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

    function deviceLock() {
        $this->my_session->authorize("canLockAppUserDevice");
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

        if (count($updateArray) > 0):
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

    function userDelete() {
        $skyId = (int) $this->input->post("skyId");
        if ((int) $skyId <= 0) {
            $json = array(
                "msg" => "no sky id provided",
                "success" => false
            );
            my_json_output($json);
        }

        $res = $this->client_registration_model->countVerifiedDevice($skyId);
        if ($res) {
            $json = array(
                "msg" => "User cannot be deleted because user already has {$res->num_rows()} verified device/s",
                "devices" => $res->result(),
                "success" => false
            );
            my_json_output($json);
        }

        $data["mcStatus"] = 0;
        $data["makerAction"] = "Delete";
        $data["salt2"] = "delete";
        $data["makerActionCode"] = 'delete';
        $data["makerActionDt"] = date("Y-m-d");
        $data["makerActionTm"] = date("G:i:s");
        $data["makerActionBy"] = $this->my_session->userId;
        $this->client_registration_model->userDeleteChange($data, $skyId);

        $json = array(
            "success" => true
        );
        my_json_output($json);
    }

}
