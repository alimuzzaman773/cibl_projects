<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_user_group_maker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->model('admin_user_group_model_maker');
    }

    function index() {
        $this->my_session->authorize("canViewAdminUserGroup");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Admin User Group');
            $crud->set_table(TBL_ADMIN_USERS_GROUP_MC);
            $crud->where('mcStatus', 1);
            $crud->order_by('userGroupId', 'desc');

            $crud->required_fields('userGroupName');
            $crud->columns('userGroupName', 'isLocked', 'isActive', 'mcStatus');

            if ((int) $this->uri->segment(4) > 0):
                $crud->set_rules("userGroupName", "User Group Name", "trim|required");
            else:
                $crud->set_rules("userGroupName", "User Group Name", "trim|required|is_unique[" . TBL_ADMIN_USERS_GROUP_MC . ".userGroupName]");
            endif;

            $time = date("Y-m-d H:i:s");
            $status = 0;

            $crud->add_fields('userGroupName', 'machine_name', 'isLocked', 'isActive', 'mcStatus', 'makerAction', 'makerActionCode', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('userGroupName', 'machine_name', 'isLocked', 'isActive', 'mcStatus', 'makerAction', 'makerActionCode', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('mcStatus', 'hidden', $status);
            $crud->change_field_type('makerAction', 'hidden', 'add');
            $crud->change_field_type('makerActionCode', 'hidden', '01');

//            $crud->callback_before_insert(array($this, function($post_array) {
//                    $post_array['makerAction'] = "add";
//                    $post_array['makerActionCode'] = "01";
//                    return $post_array;
//                }));
//            
//            $crud->callback_before_update(array($this, function($post_array) {
//                    $post_array['makerAction'] = "add";
//                    $post_array['makerActionCode'] = "01";
//                    return $post_array;
//                }));

            if (ci_check_permission("canSetuPermissionAdminUserGroup")) {
                $icon = base_url() . "/assets/images/permission.png";
                $crud->add_action("Set Permission", $icon, base_url() . 'admin_user_group_maker/set_permission/');
            }

            if (!ci_check_permission("canEditAdminUserGroup")):
                $crud->unset_edit();
            endif;

            if (!ci_check_permission("canAddAdminUserGroup")):
                $crud->unset_add();
            endif;

            if (!ci_check_permission("canDeleteAdminUserGroup")):
                $crud->unset_delete();
            endif;

            $crud->unset_list();

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Admin User Group";
            $output->base_url = base_url();

            $output->body_template = "admin_user_group_maker/user_group_index.php";
            $output->crudState = $crud->getState();
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            if ($e->getCode() == "14") {
                return $this->group_old();
            } else {
                show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
            }
        }
    }

    // Will be removed
    public function group_old() {
        $this->my_session->authorize("canViewAdminUserGroup");
        $data['pageTitle'] = "Admin User Group";
        $data['adminGroups'] = json_encode($this->admin_user_group_model_maker->getAllGroups());
        $data["body_template"] = "admin_user_group_maker/overall_view.php";
        $this->load->view('site_template.php', $data);
    }

    public function selectModule($selectedActionName = NULL, $message = NULL) {
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

        $data["pageTitle"] = "Add Admin User Group";
        $data["body_template"] = "admin_user_group_maker/select_module_view.php";
        $this->load->view('site_template.php', $data);
    }

    function selectAction() {
        $moduleIds = $this->input->post('moduleIds');
        $groupName = $this->input->post('groupName');
        if ($moduleIds != "" && $groupName != "") {
            $data['moduleIds'] = $this->input->post('moduleIds');
            $data['groupName'] = $this->input->post('groupName');

            if (!empty($this->input->post('authorizationModuleCodes'))) {
                $data['authorizationModuleCodes'] = implode("|", $this->input->post('authorizationModuleCodes'));
            } else {
                $data['authorizationModuleCodes'] = "";
            }

            if (!empty($this->input->post('contentSetupModuleCodes'))) {
                $data['contentSetupModuleCodes'] = implode("|", $this->input->post('contentSetupModuleCodes'));
            } else {
                $data['contentSetupModuleCodes'] = "";
            }

            if (!empty($this->input->post('serviceRequestModuleCodes'))) {
                $data['serviceRequestModuleCodes'] = implode("|", $this->input->post('serviceRequestModuleCodes'));
            } else {
                $data['serviceRequestModuleCodes'] = "";
            }

            if (!empty($this->input->post('reportTypeModuleCodes'))) {
                $data['reportTypeModuleCodes'] = implode("|", $this->input->post('reportTypeModuleCodes'));
            } else {
                $data['reportTypeModuleCodes'] = "";
            }

            $data['selectedActionName'] = $this->input->post('selectedActionName');
            $data['modulesActions'] = $this->admin_user_group_model_maker->getModuleWiseAction($data['moduleIds']);
            $groupNameCheck = $this->admin_user_group_model_maker->getUserGroupByName($data['groupName']);
            if ($groupNameCheck) {
                $message = 'The group "' . $groupNameCheck['userGroupName'] . '" already exists';
                $selectedActionName = NULL;
                $this->selectModule($selectedActionName, $message);
            } else {
                $data["pageTitle"] = "Action";
                $data["body_template"] = "admin_user_group_maker/select_action_view.php";
                $this->load->view('site_template.php', $data);
            }
        } else {
            $message = "At least one module must be selected";
            $selectedActionName = NULL;
            $this->selectModule($selectedActionName, $message);
        }
    }

    public function createAdminUserGroup() {
        $loginId = $this->my_session->userId;

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

    function set_permission($id = null) {

        $result = $this->admin_user_group_model_maker->getUGinfoMC($id);

        if (!$result):
            return FALSE;
        endif;

        $data['uginfo'] = $result->row();
        //d($result->row()->permissions);
        $data['existingPermission'] = array();
        $p = explode(",", $data['uginfo']->permissions);
        foreach ($p as $k => $v):
            $data['existingPermission'][$v] = $v;
        endforeach;
        //d($data['existingPermission']);
        $pres = $this->db->get(TBL_PERMISSIONS)->result();

        $data['permissions'] = array();

        foreach ($pres as $p):
            if (!isset($data['permissions']['category'][$p->category])) {
                $data['permissions']['category'][$p->category] = array();
            }
            $data['permissions']['category'][$p->category][] = $p;

        endforeach;
        //d($data);
        $data['pageTitle'] = "User Group Permissions";
        $data['base_url'] = base_url();
        $data['body_template'] = "admin_user_group_maker/ug_permission_view.php";
        $this->load->view("site_template.php", $data);
    }

    function update_permission() {
        $action = $this->input->post("action", true);
        if ($action == "edit_permissions"):
            $ugid = $this->input->post("userGroupId", true);
            $permission = $this->input->post("permissions", true);

            $permission_string = implode(",", $permission);

            $result = $this->admin_user_group_model_maker->updatePermission($ugid, $permission_string);

            if (!$result):
                return FALSE;
            endif;

            redirect(base_url() . "admin_user_group_maker");
        endif;
    }

    function convert_permission_to_array($permissionString) {
        $pstring = explode(",", $permissionString);
        $parray = array();
        foreach ($pstring as $key => $val) {
            $parray[$val] = $val;
        }
        array_pop($parray);
        return $parray;
    }

    function convert_permission_to_string($permission) {
        $pstring = "";
        if (is_array($permission)):
            foreach ($permission as $key => $val) {
                $pstring .= $val . ";";
            }
        endif;

        return $pstring;
    }

    public function editModule($id, $selectedActionName = NULL, $message = NULL) {
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
        $var['pageTitle'] = "Edit Module";
        $var["body_template"] = "admin_user_group_maker/edit_module_view.php";
        $this->load->view('site_template.php', $var);
    }

    function editAction() {
        $data['userGroupId'] = $userGroupId = $this->input->post('userGroupId');
        $moduleIds = $this->input->post('moduleIds');
        $groupName = $this->input->post('groupName');
        $authorizationModuleCodes = $this->input->post('authorizationModuleCodes');
        $contentSetupModuleCodes = $this->input->post('contentSetupModuleCodes');
        $serviceRequestModuleCodes = $this->input->post('serviceRequestModuleCodes');
        $reportTypeModuleCodes = $this->input->post('reportTypeModuleCodes');

        if ($moduleIds != "" && $groupName != "" && $userGroupId) {

            $data['moduleIds'] = $this->input->post('moduleIds');
            $data['groupName'] = $this->input->post('groupName');
            $data['userGroupId'] = $this->input->post('userGroupId');
            $data['selectedActionName'] = $this->input->post('selectedActionName');
            $data['modulesActions'] = $this->admin_user_group_model_maker->getModuleWiseAction($data['moduleIds']);

            if ($authorizationModuleCodes != "") {
                $data['authorizationModuleCodes'] = implode("|", $this->input->post('authorizationModuleCodes'));
            } else {
                $data['authorizationModuleCodes'] = "";
            }

            if ($contentSetupModuleCodes != "") {
                $data['contentSetupModuleCodes'] = implode("|", $this->input->post('contentSetupModuleCodes'));
            } else {
                $data['contentSetupModuleCodes'] = "";
            }

            if ($serviceRequestModuleCodes != "") {
                $data['serviceRequestModuleCodes'] = implode("|", $this->input->post('serviceRequestModuleCodes'));
            } else {
                $data['serviceRequestModuleCodes'] = "";
            }

            if ($reportTypeModuleCodes != "") {
                $data['reportTypeModuleCodes'] = implode("|", $this->input->post('reportTypeModuleCodes'));
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
                $data['pageTitle'] = "Edit Module";
                $data["body_template"] = "admin_user_group_maker/edit_action_view.php";
                $this->load->view('site_template.php', $data);
            }
        } else {
            $message = "At least one module must be selected";
            $selectedActionName = NULL;
            $this->editModule($data['userGroupId'], $selectedActionName, $message);
        }
    }

    public function editAdminUserGroup() {
        $loginId = $this->my_session->userId;

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
        $userGroupId = explode("|", $this->input->post('userGroupId'));
        $selectedActionName = $this->input->post('selectedActionName');
        foreach ($userGroupId as $index => $value) {
            $updateData = array("userGroupId" => $userGroupId[$index],
                "isActive" => 1,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'active',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->adminUserId,
                "updateDtTm" => date('Y-m-d G:i:s'),
                "updatedBy" => $this->my_session->adminUserId);
            $updateArray[] = $updateData;
        }
        $this->db->update_batch('admin_users_group_mc', $updateArray, 'userGroupId');
        echo 1;
    }

    public function groupInactive() {
        $userGroupId = explode("|", $this->input->post('userGroupId'));
        $selectedActionName = $this->input->post('selectedActionName');
        foreach ($userGroupId as $index => $value) {
            $updateData = array("userGroupId" => $userGroupId[$index],
                "isActive" => 0,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'inactive',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->adminUserId,
                "updateDtTm" => date('Y-m-d G:i:s'),
                "updatedBy" => $this->my_session->adminUserId);
            $updateArray[] = $updateData;
        }
        $this->db->update_batch('admin_users_group_mc', $updateArray, 'userGroupId');
        echo 1;
    }

    public function groupLock() {
        $userGroupId = explode("|", $this->input->post('userGroupId'));
        $selectedActionName = $this->input->post('selectedActionName');
        foreach ($userGroupId as $index => $value) {
            $updateData = array("userGroupId" => $userGroupId[$index],
                "isLocked" => 1,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'lock',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->adminUserId,
                "updateDtTm" => date('Y-m-d G:i:s'),
                "updatedBy" => $this->my_session->adminUserId);
            $updateArray[] = $updateData;
        }
        $this->db->update_batch('admin_users_group_mc', $updateArray, 'userGroupId');
        echo 1;
    }

    public function groupUnlock() {
        $userGroupId = explode("|", $this->input->post('userGroupId'));
        $selectedActionName = $this->input->post('selectedActionName');
        foreach ($userGroupId as $index => $value) {
            $updateData = array("userGroupId" => $userGroupId[$index],
                "isLocked" => 0,
                "mcStatus" => 0,
                "makerAction" => $selectedActionName,
                "makerActionCode" => 'unlock',
                "makerActionDt" => date("y-m-d"),
                "makerActionTm" => date("G:i:s"),
                "makerActionBy" => $this->my_session->adminUserId,
                "updateDtTm" => date('Y-m-d G:i:s'),
                "updatedBy" => $this->my_session->adminUserId);
            $updateArray[] = $updateData;
        }
        $this->db->update_batch('admin_users_group_mc', $updateArray, 'userGroupId');
        echo 1;
    }

}
