<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction_limit_setup_maker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');
        $this->load->model('transaction_limit_setup_model_maker');


        $this->load->model('login_model');
        $this->load->library('my_session');

        $this->my_session->checkSession();
    }

    function index() {
        $data['packages'] = json_encode($this->transaction_limit_setup_model_maker->getAllPackages());
        $data['body_template'] = 'transaction_limit_package_maker/overall_view.php';
        $this->load->view('site_template.php', $data);
    }

    function createGroup($selectedActionName = NULL) {
        $data['message'] = "";
        $data['selectedActionName'] = $selectedActionName;
        $data["body_template"] = "transaction_limit_package_maker/add_package_view.php";
        $this->load->view('site_template.php', $data);
    }

    public function assignGroup() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(limit_package_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "add") > -1) {
                $this->output->set_template('theme2');
                $data['selectedActionName'] = $_POST['selectedActionName'];
                if (isset($_POST['packageName'])) {
                    $data['groupName'] = $_POST['groupName'];
                    $groupNameCheck = $this->transaction_limit_setup_model_maker->getGroupByName($data['groupName']); // To check if group exists
                    if ($groupNameCheck) {
                        $data['message'] = 'The group "' . $data['groupName'] . '" already exists';
                        $this->load->view('transaction_limit_package_maker/add_package_view.php', $data);
                    } else {
                        $var = $_POST['packageName'];
                        foreach ($var as $index => $value) {
                            if ($value == 1) {
                                $array[] = array('packageId' => $value,
                                    'packageName' => "Own Account Transfer");
                            }
                            if ($value == 2) {
                                $array[] = array('packageId' => $value,
                                    'packageName' => "EBL Account Transfer");
                            }
                            if ($value == 3) {
                                $array[] = array('packageId' => $value,
                                    'packageName' => "Other Bank Transfer");
                            }
                            if ($value == 4) {
                                $array[] = array('packageId' => $value,
                                    'packageName' => "Bills Pay");
                            }
                        }
                        $data['packages'] = json_encode($array);
                        $this->load->view('transaction_limit_package_maker/add_limit_view.php', $data);
                    }
                } else {
                    $data['message'] = "At least One Package Must Be Selected";
                    $this->load->view('transaction_limit_package_maker/add_package_view.php', $data);
                }
            } else {
                echo "Action Not given";
            }
        } else {
            echo "not allowed";
        }
    }

    public function saveGroup() {
        $groupName = $_POST['group_name'];
        $ownAccount = isset($_POST['own_acc_transfer']) ? $_POST['own_acc_transfer'] : array();
        $eblAccount = isset($_POST['ebl_acc_transfer']) ? $_POST['ebl_acc_transfer'] : array();
        $otherBank = isset($_POST['other_bank_transfer']) ? $_POST['other_bank_transfer'] : array();
        $billsPay = isset($_POST['bills_pay']) ? $_POST['bills_pay'] : array();
        $ownAccount = array_slice($ownAccount, 2);
        $eblAccount = array_slice($eblAccount, 2);
        $otherBank = array_slice($otherBank, 2);
        $billsPay = array_slice($billsPay, 2);

        $dbData['userGroupName'] = $groupName;

        $dbData['oatMinTxnLim'] = 0.00;
        $dbData['oatMaxTxnLim'] = 0.00;
        $dbData['oatDayTxnLim'] = 0.00;
        $dbData['oatNoOfTxn'] = 0;
        $dbData['oatEffectiveDate'] = "";

        $dbData['pbMinTxnLim'] = 0.00;
        $dbData['pbMaxTxnLim'] = 0.00;
        $dbData['pbDayTxnLim'] = 0.00;
        $dbData['pbNoOfTxn'] = 0;
        $dbData['pbEffectiveDate'] = "";

        $dbData['eatMinTxnLim'] = 0.00;
        $dbData['eatMaxTxnLim'] = 0.00;
        $dbData['eatDayTxnLim'] = 0.00;
        $dbData['eatNoOfTxn'] = 0;
        $dbData['eatEffectiveDate'] = "";

        $dbData['obtMinTxnLim'] = 0.00;
        $dbData['obtMaxTxnLim'] = 0.00;
        $dbData['obtDayTxnLim'] = 0.00;
        $dbData['obtNoOfTxn'] = 0;
        $dbData['obtEffectiveDate'] = "";

        if (!empty($ownAccount)) {
            $dbData['oatMinTxnLim'] = $ownAccount[0];
            $dbData['oatMaxTxnLim'] = $ownAccount[1];
            $dbData['oatDayTxnLim'] = $ownAccount[2];
            $dbData['oatNoOfTxn'] = $ownAccount[3];
            $dbData['oatEffectiveDate'] = date("y-m-d");
        }

        if (!empty($billsPay)) {
            $dbData['pbMinTxnLim'] = $billsPay[0];
            $dbData['pbMaxTxnLim'] = $billsPay[1];
            $dbData['pbDayTxnLim'] = $billsPay[2];
            $dbData['pbNoOfTxn'] = $billsPay[3];
            $dbData['pbEffectiveDate'] = date("y-m-d");
        }

        if (!empty($eblAccount)) {
            $dbData['eatMinTxnLim'] = $eblAccount[0];
            $dbData['eatMaxTxnLim'] = $eblAccount[1];
            $dbData['eatDayTxnLim'] = $eblAccount[2];
            $dbData['eatNoOfTxn'] = $eblAccount[3];
            $dbData['eatEffectiveDate'] = date("y-m-d");
        }

        if (!empty($otherBank)) {
            $dbData['obtMinTxnLim'] = $otherBank[0];
            $dbData['obtMaxTxnLim'] = $otherBank[1];
            $dbData['obtDayTxnLim'] = $otherBank[2];
            $dbData['obtNoOfTxn'] = $otherBank[3];
            $dbData['obtEffectiveDate'] = date("y-m-d");
        }


        // for maker checker
        $dbData['mcStatus'] = 0;
        $dbData['makerAction'] = $_POST['selected_action_name'];
        $dbData['makerActionCode'] = 'add';
        $dbData['makerActionDt'] = date("y-m-d");
        $dbData['makerActionTm'] = date("G:i:s");
        $dbData['makerActionBy'] = $this->session->userdata('adminUserId');
        $dbData['isPublished'] = 0;
        $dbData['isActive'] = 1;


        $groupNameCheck = $this->transaction_limit_setup_model_maker->getGroupByName($groupName); // To check if group exists
        if ($groupNameCheck) {
            echo 0;
        } else {
            $insertedId = $this->transaction_limit_setup_model_maker->insertUserGroupInfo($dbData);
            echo $insertedId;
        }
    }

    function editTransactionLimitPackage($data, $selectedActionName = NULL, $message = NULL) {
        $dbData = $this->transaction_limit_setup_model_maker->getGroupById($data);

        $var['packages'] = json_encode($dbData);


        $var['checkerActionComment'] = $dbData['checkerActionComment'];

        if ($var['checkerActionComment'] != NULL) {
            $var['reasonModeOfDisplay'] = "display: block;";
        } else {
            $var['reasonModeOfDisplay'] = "display: none;";
        }



        if ($message == NULL) {
            $var['message'] = "Group name can be updated here and 
                               <br> new package can be assigned or existing pacage can be removed";
        } else {
            $var['message'] = $message;
        }
        $var['appsGroupId'] = $data;
        $var['selectedActionName'] = $selectedActionName;
        $var['body_template'] = 'transaction_limit_package_maker/edit_package_view.php';
        $this->load->view('site_template.php', $var);
    }

    function editGroup() {
        $data['appsGroupId'] = $_POST['appsGroupId'];
        $data['selectedActionName'] = $_POST['selectedActionName'];
        if (!isset($_POST['packageName'])) {
            $data['message'] = "At least One Package Must Be Selected";
            return $this->editTransactionLimitPackage($data['appsGroupId'], $data['selectedActionName'], $data['message']);
        }

        $dbData = $this->transaction_limit_setup_model_maker->getGroupById($data['appsGroupId']);
        $data['group'] = json_encode($dbData);

        $data['userGroupName'] = $_POST['groupName'];
        $groupNameCheck = $this->transaction_limit_setup_model_maker->checkIfGroupExist($data); // To check if group exists
        if ($groupNameCheck > 0) {
            $data['message'] = 'The group "' . $data['userGroupName'] . '" already exists';
            $this->editTransactionLimitPackage($data['appsGroupId'], $data['selectedActionName'], $data['message']);
        }

        $var = $_POST['packageName'];
        foreach ($var as $index => $value) {
            if ($value == 1) {
                $array[] = array('packageId' => $value,
                    'packageName' => "Own Account Transfer");
            }
            if ($value == 2) {
                $array[] = array('packageId' => $value,
                    'packageName' => "EBL Account Transfer");
            }
            if ($value == 3) {
                $array[] = array('packageId' => $value,
                    'packageName' => "Other Bank Transfer");
            }
            if ($value == 4) {
                $array[] = array('packageId' => $value,
                    'packageName' => "Bills Pay");
            }
        }
        $data['packages'] = json_encode($array);
        $data['body_template'] = 'transaction_limit_package_maker/edit_limit_view.php';
        $this->load->view('site_template.php', $data);
    }

    public function updateGroup() {
        $groupId = $_POST['group_id'];
        $groupName = $_POST['group_name'];
        $ownAccount = isset($_POST['own_acc_transfer']) ? $_POST['own_acc_transfer'] : array();
        $eblAccount = isset($_POST['ebl_acc_transfer']) ? $_POST['ebl_acc_transfer'] : array();
        $otherBank = isset($_POST['other_bank_transfer']) ? $_POST['other_bank_transfer'] : array();
        $billsPay = isset($_POST['bills_pay']) ? $_POST['bills_pay'] : array();
        $ownAccount = array_slice($ownAccount, 2);
        $eblAccount = array_slice($eblAccount, 2);
        $otherBank = array_slice($otherBank, 2);
        $billsPay = array_slice($billsPay, 2);

        $currentDate = date("y-m-d");

        $dbData['userGroupName'] = $groupName;

        $dbData['oatMinTxnLim'] = 0.00;
        $dbData['oatMaxTxnLim'] = 0.00;
        $dbData['oatDayTxnLim'] = 0.00;
        $dbData['oatNoOfTxn'] = 0;
        $dbData['oatEffectiveDate'] = "";

        $dbData['pbMinTxnLim'] = 0.00;
        $dbData['pbMaxTxnLim'] = 0.00;
        $dbData['pbDayTxnLim'] = 0.00;
        $dbData['pbNoOfTxn'] = 0;
        $dbData['pbEffectiveDate'] = "";

        $dbData['eatMinTxnLim'] = 0.00;
        $dbData['eatMaxTxnLim'] = 0.00;
        $dbData['eatDayTxnLim'] = 0.00;
        $dbData['eatNoOfTxn'] = 0;
        $dbData['eatEffectiveDate'] = "";

        $dbData['obtMinTxnLim'] = 0.00;
        $dbData['obtMaxTxnLim'] = 0.00;
        $dbData['obtDayTxnLim'] = 0.00;
        $dbData['obtNoOfTxn'] = 0;
        $dbData['obtEffectiveDate'] = "";

        if (!empty($ownAccount)) {
            $dbData['oatMinTxnLim'] = $ownAccount[0];
            $dbData['oatMaxTxnLim'] = $ownAccount[1];
            $dbData['oatDayTxnLim'] = $ownAccount[2];
            $dbData['oatNoOfTxn'] = $ownAccount[3];
            $dbData['oatEffectiveDate'] = $currentDate;
        }

        if (!empty($billsPay)) {
            $dbData['pbMinTxnLim'] = $billsPay[0];
            $dbData['pbMaxTxnLim'] = $billsPay[1];
            $dbData['pbDayTxnLim'] = $billsPay[2];
            $dbData['pbNoOfTxn'] = $billsPay[3];
            $dbData['pbEffectiveDate'] = $currentDate;
        }

        if (!empty($eblAccount)) {
            $dbData['eatMinTxnLim'] = $eblAccount[0];
            $dbData['eatMaxTxnLim'] = $eblAccount[1];
            $dbData['eatDayTxnLim'] = $eblAccount[2];
            $dbData['eatNoOfTxn'] = $eblAccount[3];
            $dbData['eatEffectiveDate'] = $currentDate;
        }

        if (!empty($otherBank)) {
            $dbData['obtMinTxnLim'] = $otherBank[0];
            $dbData['obtMaxTxnLim'] = $otherBank[1];
            $dbData['obtDayTxnLim'] = $otherBank[2];
            $dbData['obtNoOfTxn'] = $otherBank[3];
            $dbData['obtEffectiveDate'] = $currentDate;
        }

        $dbData['mcStatus'] = 0;
        $dbData['makerAction'] = $_POST['selected_action_name'];
        $dbData['makerActionCode'] = 'edit';
        $dbData['makerActionDt'] = date("y-m-d");
        $dbData['makerActionTm'] = date("G:i:s");
        $dbData['makerActionBy'] = $this->my_session->userId;


        $this->transaction_limit_setup_model_maker->updateUserGroupInfo($dbData, $groupId);
        echo 1;
    }

    function packageActive() {
        $appsGroupId = explode("|", $_POST['appsGroupId']);
        $appsGroupIdString = $_POST['appsGroupId'];
        $checkData = $this->chkPermission($appsGroupId);

        if (strcmp($appsGroupIdString, $checkData) != 0) {
            $json = array(
                "msg" => "no matching group id found",
                "success" => false
            );
            my_json_output($json);
        }
        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($appsGroupId as $index => $value) {
            $updateData = array(
                "appsGroupId" => $value,
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
            $this->db->update_batch('apps_users_group_mc', $updateArray, 'appsGroupId');
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;

        $json = array(
            "msg" => "no package id provided",
            "success" => false
        );
        my_json_output($json);
    }

    function packageInactive() {
        $appsGroupId = explode("|", $_POST['appsGroupId']);
        $appsGroupIdString = $_POST['appsGroupId'];
        $checkData = $this->chkPermission($appsGroupId);

        if (strcmp($appsGroupIdString, $checkData) != 0) {
            $json = array(
                "msg" => "no matching group id found",
                "success" => false
            );
            my_json_output($json);
        }

        $selectedActionName = $_POST['selectedActionName'];
        $updateArray = array();
        foreach ($appsGroupId as $index => $value) {
            $updateData = array(
                "appsGroupId" => $value,
                "isActive" => 0,
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
            $this->db->update_batch('apps_users_group_mc', $updateArray, 'appsGroupId');
            $json = array(
                "success" => true
            );
            my_json_output($json);
        endif;

        $json = array(
            "msg" => "no package id provided",
            "success" => false
        );
        my_json_output($json);
    }

    public function chkPermission($data) { // function to check injection
        $id = $this->my_session->userId;

        $this->db->select('apps_users_group_mc.appsGroupId');
        $this->db->where_in('appsGroupId', $data);
        $this->db->where('(apps_users_group_mc.makerActionBy = ' . $id . ' OR mcStatus = 1)');
        $query = $this->db->get('apps_users_group_mc');
        $data = $this->multiDimensionArrayToString($query->result_array());
        return $data;
    }

    function multiDimensionArrayToString($array) {
        $out = implode("|", array_map(function($a) {
                    return implode("~", $a);
                }, $array));
        return $out;
    }

}
