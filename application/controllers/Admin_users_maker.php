<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_users_maker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->model('admin_users_model_maker');
        $this->load->library('BOcrypter');
    }

    function index() {
        $adminUserData = $this->admin_users_model_maker->getAllUsers();

        foreach ($adminUserData as $index => $value) {
            $adminUserData[$index]->email = $this->bocrypter->Decrypt($adminUserData[$index]->email);
        }

        $data['adminUsers'] = json_encode($adminUserData);
        $data['pageTitle'] = 'Admin User';
        $data["body_template"] = "admin_users_maker/all_admin_users_view.php";
        $this->load->view('site_template.php', $data);
    }

    function addNewUser($selectedActionName = NULL) {
        $data['userGroups'] = $this->admin_users_model_maker->getAllGroups();
        $data['selectedActionName'] = $selectedActionName;
        $data['message'] = "";

        $data["pageTitle"] = "Add Admin User";
        $data["body_template"] = "admin_users_maker/add_new_admin_users.php";
        $this->load->view('site_template.php', $data);
    }

    public function insertNewUser() {
        $password = $this->input->post('password1');
        $data['fullName'] = $this->input->post('fullName');
        $data['adminUserName'] = $this->input->post('userId');
        $data['adminUserGroup'] = $this->input->post('group');
        $data['passwordSetDtTm'] = 0;
        $data['passwordSalt'] = 12;
        $data['encryptedPassword'] = $this->bocrypter->Encrypt($password);
        $data['passwordChangeTms'] = 0;
        $data['passwordChangeDtTm'] = 0;
        $data['isReset'] = 0;
        $data['passwordResetTms'] = 0;
        $data['email'] = $this->bocrypter->Encrypt($this->input->post('email'));
        $data['dob'] = $this->input->post('dob');
        $data['mcStatus'] = 0;
        $data['makerAction'] = $this->input->post('selectedActionName');
        $data['makerActionCode'] = 'add';
        $data['makerActionDt'] = date("y-m-d");
        $data['makerActionTm'] = date("G:i:s");
        $data['makerActionBy'] = $this->my_session->adminUserId;
        $data['isLocked'] = 0;
        $data['isPublished'] = 0;
        $data['isActive'] = 1;
        $data['createdBy'] = $this->my_session->adminUserId;
        $data['createdDtTm'] = input_date();

        $userNameCheck = $this->admin_users_model_maker->getUserByName($data['adminUserName']); // To check if user exists

        if ($userNameCheck) {
            $data['message'] = 'The User "' . $data['adminUserName'] . '" already exists';
            $data['userGroups'] = $this->admin_users_model_maker->getAllGroups();

            $data["pageTitle"] = "Add Admin User";
            $data["body_template"] = "admin_users_maker/add_new_admin_users.php";
            $this->load->view('site_template.php', $data);
        } else {
            $this->admin_users_model_maker->insertAdminUserInfo($data);
            redirect('admin_users_maker');
        }
    }

    public function editUser($data, $selectedActionName = NULL, $message = NULL) {
        $tableData = $this->admin_users_model_maker->getAdminUserById($data);

        $tableData['email'] = $this->bocrypter->Decrypt($tableData['email']);


        $viewData['checkerActionComment'] = $tableData['checkerActionComment'];

        if ($viewData['checkerActionComment'] != NULL) {
            $viewData['reasonModeOfDisplay'] = "display: block;";
        } else {
            $viewData['reasonModeOfDisplay'] = "display: none;";
        }

        $viewData['adminUserData'] = $tableData;
        $viewData['userGroups'] = $this->admin_users_model_maker->getAllGroups();
        $viewData['selectedActionName'] = $selectedActionName;
        $viewData['message'] = $message;

        $viewData["pageTitle"] = "Edit Admin User";
        $viewData["body_template"] = "admin_users_maker/edit_admin_users.php";
        $this->load->view('site_template.php', $viewData);
    }

    public function updateAdminUser() {
        $adminUserId = $this->input->post('adminUserId');
        $email = $this->input->post('email');
        $data['adminUserName'] = $this->input->post('userId');
        $data['fullName'] = $this->input->post('fullName');
        $data['adminUserGroup'] = $this->input->post('group');
        $data['email'] = $this->bocrypter->Encrypt($email);
        $data['dob'] = $this->input->post('dob');
        $data['mcStatus'] = 0;
        $data['makerAction'] = $this->input->post('selectedActionName');
        $data['makerActionCode'] = 'edit';
        $data['makerActionDt'] = date("y-m-d");
        $data['makerActionTm'] = date("G:i:s");
        $data['makerActionBy'] = $this->my_session->adminUserId;



        $userNameCheck = $this->admin_users_model_maker->checkIfUserExist($adminUserId, $data); // To check if user exists

        if ($userNameCheck > 0) {
            $message = 'The user "' . $data['adminUserName'] . '" already exists';
            $this->editUser($adminUserId, $data['makerAction'], $message);
        } else {
            $this->admin_users_model_maker->updateAdminUserInfo($data, $adminUserId);
            redirect('admin_users_maker');
        }
    }

    public function adminUserActive() {
        $adminUserId = explode("|", $this->input->post('adminUserId'));
        $adminUserIdString = $this->input->post('adminUserId');
        $checkData = $this->chkPermission($adminUserId);

        if (strcmp($adminUserIdString, $checkData) == 0) {
            $selectedActionName = $this->input->post('selectedActionName');
            foreach ($adminUserId as $index => $value) {
                $updateData = array("adminUserId" => $value,
                    "isActive" => 1,
                    "mcStatus" => 0,
                    "makerAction" => $selectedActionName,
                    "makerActionCode" => 'active',
                    "makerActionDt" => date("y-m-d"),
                    "makerActionTm" => date("G:i:s"),
                    "makerActionBy" => $this->my_session->adminUserId
                );
                $updateArray[] = $updateData;
            }
            $this->db->update_batch('admin_users_mc', $updateArray, 'adminUserId');
            echo 1;
        } else {
            echo 2;
        }
    }

    public function adminUserInactive() {
        $adminUserId = explode("|", $this->input->post('adminUserId'));
        $adminUserIdString = $this->input->post('adminUserId');
        ;
        $checkData = $this->chkPermission($adminUserId);

        if (strcmp($adminUserIdString, $checkData) == 0) {
            $selectedActionName = $this->input->post('selectedActionName');
            foreach ($adminUserId as $index => $value) {
                $updateData = array("adminUserId" => $value,
                    "isActive" => 0,
                    "mcStatus" => 0,
                    "makerAction" => $selectedActionName,
                    "makerActionCode" => 'inactive',
                    "makerActionDt" => date("y-m-d"),
                    "makerActionTm" => date("G:i:s"),
                    "makerActionBy" => $this->my_session->adminUserId);
                $updateArray[] = $updateData;
            }
            $this->db->update_batch('admin_users_mc', $updateArray, 'adminUserId');
            echo 1;
        } else {
            echo 2;
        }
    }

    public function adminUserLock() {
        $adminUserId = explode("|", $this->input->post('adminUserId'));
        $adminUserIdString = $this->input->post('adminUserId');
        $checkData = $this->chkPermission($adminUserId);

        if (strcmp($adminUserIdString, $checkData) == 0) {
            $selectedActionName = $this->input->post('selectedActionName');
            foreach ($adminUserId as $index => $value) {
                $updateData = array("adminUserId" => $value,
                    "isLocked" => 1,
                    "mcStatus" => 0,
                    "makerAction" => $selectedActionName,
                    "makerActionCode" => 'lock',
                    "makerActionDt" => date("y-m-d"),
                    "makerActionTm" => date("G:i:s"),
                    "makerActionBy" => $this->my_session->adminUserId);
                $updateArray[] = $updateData;
            }
            $this->db->update_batch('admin_users_mc', $updateArray, 'adminUserId');
            echo 1;
        } else {
            echo 2;
        }
    }

    public function adminUserUnlock() {
        $adminUserId = explode("|", $this->input->post('adminUserId'));
        $adminUserIdString = $this->input->post('adminUserId');
        $checkData = $this->chkPermission($adminUserId);

        if (strcmp($adminUserIdString, $checkData) == 0) {
            $selectedActionName = $this->input->post('selectedActionName');
            foreach ($adminUserId as $index => $value) {
                $updateData = array("adminUserId" => $value,
                    "isLocked" => 0,
                    "mcStatus" => 0,
                    "makerAction" => $selectedActionName,
                    "makerActionCode" => 'unlock',
                    "makerActionDt" => date("y-m-d"),
                    "makerActionTm" => date("G:i:s"),
                    "makerActionBy" => $this->my_session->adminUserId);
                $updateArray[] = $updateData;
            }
            $this->db->update_batch('admin_users_mc', $updateArray, 'adminUserId');
            echo 1;
        } else {
            echo 2;
        }
    }

    public function chkPermission($data) { // function to check injection
        $id = $this->my_session->adminUserId;

        $this->db->select('admin_users_mc.adminUserId');
        $this->db->where_in('adminUserId', $data);
        $this->db->where('(admin_users_mc.makerActionBy = ' . $id . ' OR mcStatus = 1) AND (admin_users_mc.adminUserId != ' . $id . ')');
        $query = $this->db->get('admin_users_mc');
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
