<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_user_group_maker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('admin_user_group_model_maker', 'login_model'));
    }

    public function index() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $actionNames = $this->session->userdata('actionNames');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $actionNames = explode("#", $actionNames);
        $index = array_search(admin_user_group_module, $moduleCodes);
        if ($index > -1) {
            $actionCodes = json_encode(explode(",", $actionCodes[$index]));
            $actionNames = json_encode(explode(",", $actionNames[$index]));
            $data['groups'] = json_encode($this->admin_user_group_model_maker->getAllGroups());
            $data['actionCodes'] = $actionCodes;
            $data['actionNames'] = $actionNames;
            $this->load->view('admin_user_group_maker/overall_view.php', $data);
        } else {
            echo "not allowed";
        }
    }

    public function selectModule($selectedActionName = NULL, $message = NULL) {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(admin_user_group_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "add") > -1) {
                $data['modules'] = $this->admin_user_group_model_maker->getAllModules();
                if ($message != NULL) {
                    $data['message'] = $message;
                } else {
                    $data['message'] = "";
                }

                $data['authorizationModules'] = array('01' => 'Apps Users Authorization',
                    '02' => 'Device Authorization',
                    '03' => 'Pin Reset Authorization',
                    '04' => 'Admin User Authorization',
                    '05' => 'Admin User Group Authorization',
                    '06' => 'Limit Package Authorization',
                    '07' => 'Biller Setup Authorization',
                    '08' => 'Pin Create Authorization',
                    '09' => 'Password Policy Authorization',
                    '10' => 'Apps User Delete Authorization');


                $data['contentSetupModules'] = array('01' => 'Product Setup',
                    '02' => 'Location Setup',
                    '03' => 'Zip Partners',
                    '04' => 'Priority Setup',
                    '05' => 'Benifit Setup',
                    '06' => 'News And Events',
                    '07' => 'Notification',
                    '08' => 'Advertisement',
                    '09' => 'Help Setup');


                $data['serviceRequestModules'] = array('01' => 'Priority',
                    '02' => 'Product',
                    '03' => 'Banking');

                $data['reportTypeModules'] = array('01' => 'Apps Users' . "'" . ' Status',
                    '02' => 'Customer Information',
                    '03' => 'User Login Information',
                    '04' => 'Fund Transfer',
                    '05' => 'Other Fund Transfer',
                    '06' => 'User ID Modification',
                    '07' => 'Billing Information',
                    '08' => 'Priority Request',
                    '09' => 'Product Request',
                    '10' => 'Banking Request');


                $data['selectedActionName'] = $selectedActionName;
                $this->load->view('admin_user_group_maker/select_module_view.php', $data);
            }
        } else {
            echo "not allowed";
        }
    }

    public function selectAction() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(admin_user_group_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "add") > -1) {
                if (isset($_POST['moduleIds']) && isset($_POST['groupName'])) {
                    $data['moduleIds'] = $_POST['moduleIds'];
                    $data['groupName'] = $_POST['groupName'];

                    if (!empty($_POST['authorizationModuleCodes'])) {
                        $data['authorizationModuleCodes'] = implode("|", $_POST['authorizationModuleCodes']);
                    } else {
                        $data['authorizationModuleCodes'] = "";
                    }

                    if (!empty($_POST['contentSetupModuleCodes'])) {
                        $data['contentSetupModuleCodes'] = implode("|", $_POST['contentSetupModuleCodes']);
                    } else {
                        $data['contentSetupModuleCodes'] = "";
                    }

                    if (!empty($_POST['serviceRequestModuleCodes'])) {
                        $data['serviceRequestModuleCodes'] = implode("|", $_POST['serviceRequestModuleCodes']);
                    } else {
                        $data['serviceRequestModuleCodes'] = "";
                    }

                    if (!empty($_POST['reportTypeModuleCodes'])) {
                        $data['reportTypeModuleCodes'] = implode("|", $_POST['reportTypeModuleCodes']);
                    } else {
                        $data['reportTypeModuleCodes'] = "";
                    }

                    $data['selectedActionName'] = $_POST['selectedActionName'];
                    $data['modulesActions'] = $this->admin_user_group_model_maker->getModuleWiseAction($data['moduleIds']);
                    $groupNameCheck = $this->admin_user_group_model_maker->getUserGroupByName($data['groupName']);
                    if ($groupNameCheck) {
                        $message = 'The group "' . $groupNameCheck['userGroupName'] . '" already exists';
                        $selectedActionName = NULL;
                        $this->selectModule($selectedActionName, $message);
                    } else {

                        $this->load->view('admin_user_group_maker/select_action_view.php', $data);
                    }
                } else {
                    $message = "At least one module must be selected";
                    $selectedActionName = NULL;
                    $this->selectModule($selectedActionName, $message);
                }
            }
        } else {
            echo "not allowed";
        }
    }

    public function createAdminUserGroup() {
        $loginId = $this->session->userdata('adminUserId');

        if (isset($_POST['groupName']) && isset($_POST['moduleCodes']) && isset($_POST['actionCodes']) && isset($_POST['moduleActionId'])) {

            $data['userGroupName'] = $_POST['groupName'];
            $data['moduleActionId'] = $_POST['moduleActionId'];
            $data['moduleCodes'] = $_POST['moduleCodes'];
            $data['actionCodes'] = $_POST['actionCodes'];
            $data['authorizationModules'] = $_POST['authorizationModuleCodes'];
            $data['contentSetupModules'] = $_POST['contentSetupModuleCodes'];
            $data['serviceRequestModules'] = $_POST['serviceRequestModuleCodes'];
            $data['reportTypeModules'] = $_POST['reportTypeModuleCodes'];
            $data['mcStatus'] = 0;
            $data['makerAction'] = $_POST['selectedActionName'];
            $data['makerActionCode'] = 'add';
            $data['makerActionDt'] = date("y-m-d");
            $data['makerActionTm'] = date("G:i:s");
            $data['makerActionBy'] = $loginId;
            $data['isLocked'] = 0;
            $data['isPublished'] = 0;
            $data['creationDtTm'] = date('Y-m-d G:i:s');
            $data['updateDtTm'] = date('Y-m-d G:i:s');
            $data['createdBy'] = $loginId;
            $data['updatedBy'] = $loginId;
            $data['isActive'] = 1;

            $this->admin_user_group_model_maker->insertAdminUserGroup($data);
            redirect('admin_user_group_maker');
        }
    }

    public function editModule($id, $selectedActionName = NULL, $message = NULL) {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(admin_user_group_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "edit") > -1) {
                $tableData = $this->admin_user_group_model_maker->getUserGroupById($id);



                $var['userGroupId'] = $id;
                $var['adminGroup'] = $tableData;
                $var['modules'] = $this->admin_user_group_model_maker->getAllModules();


                $var['checkerActionComment'] = $tableData['checkerActionComment'];

                if ($var['checkerActionComment'] != NULL) {
                    $var['reasonModeOfDisplay'] = "display: block;";
                } else {
                    $var['reasonModeOfDisplay'] = "display: none;";
                }


                $var['authorizationModuleCodes'] = json_encode(explode("|", $tableData['authorizationModules']));
                $var['contentSetupModulesCodes'] = json_encode(explode("|", $tableData['contentSetupModules']));
                $var['serviceRequestModuleCodes'] = json_encode(explode("|", $tableData['serviceRequestModules']));
                $var['reportTypeModuleCodes'] = json_encode(explode("|", $tableData['reportTypeModules']));

                $var['moduleCodes'] = explode("|", $tableData['moduleCodes']);
                $var['moduleIds'] = json_encode($this->admin_user_group_model_maker->getModuleIdsByModuleCode($var['moduleCodes']));
                if ($message != NULL) {
                    $var['message'] = $message;
                } else {
                    $var['message'] = "";
                }

                $var['authorizationModules'] = array('01' => 'Apps Users Authorization',
                    '02' => 'Device Authorization',
                    '03' => 'Pin Reset Authorization',
                    '04' => 'Admin User Authorization',
                    '05' => 'Admin User Group Authorization',
                    '06' => 'Limit Package Authorization',
                    '07' => 'Biller Setup Authorization',
                    '08' => 'Pin Create Authorization',
                    '09' => 'Password Policy Authorization',
                    '10' => 'Apps User Delete Authorization');

                $var['contentSetupModules'] = array('01' => 'Product Setup',
                    '02' => 'Location Setup',
                    '03' => 'Zip Partners',
                    '04' => 'Priority Setup',
                    '05' => 'Benifit Setup',
                    '06' => 'News And Events',
                    '07' => 'Notification',
                    '08' => 'Advertisement',
                    '09' => 'Help Setup');

                $var['serviceRequestModules'] = array('01' => 'Priority',
                    '02' => 'Product',
                    '03' => 'Banking');


                $var['reportTypeModules'] = array('01' => 'Apps Users' . "'" . ' Status',
                    '02' => 'Customer Information',
                    '03' => 'User Login Information',
                    '04' => 'Fund Transfer',
                    '05' => 'Other Fund Transfer',
                    '06' => 'User ID Modification',
                    '07' => 'Billing Information',
                    '08' => 'Priority Request',
                    '09' => 'Product Request',
                    '10' => 'Banking Request');



                $var['selectedActionName'] = $selectedActionName;
                $this->load->view('admin_user_group_maker/edit_module_view.php', $var);
            }
        } else {
            echo "not allowed";
        }
    }

    public function editAction() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);

        $index = array_search(admin_user_group_module, $moduleCodes);

        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "edit") > -1) {
                $data['userGroupId'] = $_POST['userGroupId'];
                if (isset($_POST['moduleIds']) && isset($_POST['groupName']) && isset($_POST['userGroupId'])) {

                    $data['moduleIds'] = $_POST['moduleIds'];
                    $data['groupName'] = $_POST['groupName'];
                    $data['userGroupId'] = $_POST['userGroupId'];
                    $data['selectedActionName'] = $_POST['selectedActionName'];
                    $data['modulesActions'] = $this->admin_user_group_model_maker->getModuleWiseAction($data['moduleIds']);

                    if (isset($_POST['authorizationModuleCodes'])) {
                        $data['authorizationModuleCodes'] = implode("|", $_POST['authorizationModuleCodes']);
                    } else {
                        $data['authorizationModuleCodes'] = "";
                    }


                    if (isset($_POST['contentSetupModuleCodes'])) {
                        $data['contentSetupModuleCodes'] = implode("|", $_POST['contentSetupModuleCodes']);
                    } else {
                        $data['contentSetupModuleCodes'] = "";
                    }


                    if (isset($_POST['serviceRequestModuleCodes'])) {
                        $data['serviceRequestModuleCodes'] = implode("|", $_POST['serviceRequestModuleCodes']);
                    } else {
                        $data['serviceRequestModuleCodes'] = "";
                    }


                    if (isset($_POST['reportTypeModuleCodes'])) {
                        $data['reportTypeModuleCodes'] = implode("|", $_POST['reportTypeModuleCodes']);
                    } else {
                        $data['reportTypeModuleCodes'] = "";
                    }



                    $tableData = $this->admin_user_group_model_maker->getUserGroupById($data['userGroupId']);

                    $data['moduleActionIds'] = json_encode(explode(",", $tableData['moduleActionId']));

                    $groupNameCheck = $this->admin_user_group_model_maker->checkIfGroupExist($data); // To check if group exists
                    if ($groupNameCheck > 0) {
                        $message = 'The group "' . $data['groupName'] . '" already exists';
                        $selectedActionName = NULL;
                        $this->editModule($data['userGroupId'], $selectedActionName, $message);
                    } else {
                        $this->load->view('admin_user_group_maker/edit_action_view.php', $data);
                    }
                } else {
                    $message = "At least one module must be selected";
                    $selectedActionName = NULL;
                    $this->editModule($data['userGroupId'], $selectedActionName, $message);
                }
            }
        } else {
            echo "not allowed";
        }
    }

    public function editAdminUserGroup() {
        $loginId = $this->session->userdata('adminUserId');

        if (isset($_POST['groupName']) && isset($_POST['moduleCodes']) && isset($_POST['actionCodes']) && isset($_POST['moduleActionId'])) {

            $userGroupId = $_POST['userGroupId'];

            $data['userGroupName'] = $_POST['groupName'];
            $data['moduleActionId'] = $_POST['moduleActionId'];
            $data['moduleCodes'] = $_POST['moduleCodes'];
            $data['actionCodes'] = $_POST['actionCodes'];
            $data['authorizationModules'] = $_POST['authorizationModuleCodes'];
            $data['contentSetupModules'] = $_POST['contentSetupModuleCodes'];
            $data['serviceRequestModules'] = $_POST['serviceRequestModuleCodes'];
            $data['reportTypeModules'] = $_POST['reportTypeModuleCodes'];
            $data['mcStatus'] = 0;
            $data['makerAction'] = $_POST['selectedActionName'];
            $data['makerActionCode'] = 'edit';
            $data['makerActionDt'] = date("y-m-d");
            $data['makerActionTm'] = date("G:i:s");
            $data['makerActionBy'] = $loginId;
            $data['updateDtTm'] = date('Y-m-d G:i:s');
            $data['updatedBy'] = $loginId;

            $this->admin_user_group_model_maker->updateAdminUserGroup($data, $userGroupId);
            redirect('admin_user_group_maker');
        }
    }

    public function groupActive() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(admin_user_group_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "active") > -1) {
                $userGroupId = explode("|", $_POST['userGroupId']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($userGroupId as $index => $value) {
                    $updateData = array("userGroupId" => $userGroupId[$index],
                        "isActive" => 1,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'active',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'),
                        "updateDtTm" => date('Y-m-d G:i:s'),
                        "updatedBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('admin_users_group_mc', $updateArray, 'userGroupId');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function groupInactive() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(admin_user_group_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "inactive") > -1) {
                $userGroupId = explode("|", $_POST['userGroupId']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($userGroupId as $index => $value) {
                    $updateData = array("userGroupId" => $userGroupId[$index],
                        "isActive" => 0,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'inactive',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'),
                        "updateDtTm" => date('Y-m-d G:i:s'),
                        "updatedBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('admin_users_group_mc', $updateArray, 'userGroupId');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function groupLock() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(admin_user_group_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "lock") > -1) {
                $userGroupId = explode("|", $_POST['userGroupId']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($userGroupId as $index => $value) {
                    $updateData = array("userGroupId" => $userGroupId[$index],
                        "isLocked" => 1,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'lock',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'),
                        "updateDtTm" => date('Y-m-d G:i:s'),
                        "updatedBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('admin_users_group_mc', $updateArray, 'userGroupId');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function groupUnlock() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(admin_user_group_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "unlock") > -1) {
                $userGroupId = explode("|", $_POST['userGroupId']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($userGroupId as $index => $value) {
                    $updateData = array("userGroupId" => $userGroupId[$index],
                        "isLocked" => 0,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'unlock',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'),
                        "updateDtTm" => date('Y-m-d G:i:s'),
                        "updatedBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('admin_users_group_mc', $updateArray, 'userGroupId');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

}
