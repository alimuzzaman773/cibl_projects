<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client_registration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('client_registration_model');
        $this->load->model('common_model');
        $this->load->model('login_model'); // for formating user information with library function
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    public function index() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $actionNames = $this->session->userdata('actionNames');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $actionNames = explode("#", $actionNames);
        $index = array_search(apps_user_module, $moduleCodes);
        if ($index > -1) {
            $actionCodes = json_encode(explode(",", $actionCodes[$index]));
            $actionNames = json_encode(explode(",", $actionNames[$index]));
            $data['appsUsers'] = json_encode($this->client_registration_model->getAllAppsUsers());
            //$data['verifiedDevice'] = json_encode($this->client_registration_model->countVerifiedDevice());
            //$data['nonVerifiedDevice'] = json_encode($this->client_registration_model->countNonVerifiedDevice());
            //$data['totalDevice'] = json_encode($this->client_registration_model->countTotalDevice());
            $data['actionCodes'] = $actionCodes;
            $data['actionNames'] = $actionNames;
            $this->load->view('client_registration/client_registration_view.php', $data);
        } else {
            echo "not allowed";
        }
    }

    public function viewUser() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(apps_user_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "detailView") > -1) {
                if (isset($_GET['skyId'])) {
                    $skyId = $_GET['skyId'];
                    $data['userInfo'] = $this->client_registration_model->getAppsUsersById($skyId); // decision needed whether to show from main table or shadow
                    $data['deviceInfo'] = json_encode($this->client_registration_model->getDeviceBySkyid($skyId));
                    $data['accountInfo'] = json_encode($this->login_model->checkAccount($data['userInfo']));
                    $this->load->view('client_registration/apps_user_detail_view.php', $data);
                }
            }
        } else {
            echo "not allowed";
        }
    }

    public function deviceInfo() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $actionNames = $this->session->userdata('actionNames');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $actionNames = explode("#", $actionNames);
        $index = array_search(device_module, $moduleCodes);
        if ($index > -1) {
            $actionCodes = json_encode(explode(",", $actionCodes[$index]));
            $actionNames = json_encode(explode(",", $actionNames[$index]));
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
            $data['actionCodes'] = $actionCodes;
            $data['actionNames'] = $actionNames;
            $this->load->view('client_registration/device_info_view.php', $data);
        } else {
            echo "not allowed";
        }
    }

    public function addDeviceInfo() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(device_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "add") > -1) {
                if (isset($_GET['skyId']) && isset($_GET['eblSkyId'])) {
                    $data['skyId'] = $_GET['skyId'];
                    $data['eblSkyId'] = $_GET['eblSkyId'];
                    $data['selectedActionName'] = $_GET['selectedActionName'];

                    $data['message'] = "";
                    $this->load->view('client_registration/enter_imei_view.php', $data);
                }
            }
        } else {
            echo "not allowed";
        }
    }

    public function insertDevice() {
        if (isset($_POST['skyId']) && isset($_POST['eblSkyId']) && isset($_POST['imeiNo'])) {
            $this->output->set_template('theme2');

            $loginId = $this->session->userdata('adminUserId');

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
                $this->load->view('client_registration/enter_imei_view.php', $data);
            } else {
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
                $this->client_registration_model->insertImeiNo($data);
                $data['message'] = "IMEI Successfully added and more can be added";
                $this->load->view('client_registration/enter_imei_view.php', $data);
            }
        }
    }

    public function editDevice() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(device_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "edit") > -1) {
                if (isset($_GET['skyId']) && isset($_GET['eblSkyId']) && isset($_GET['imeiNo'])) {
                    $data['deviceId'] = $_GET['deviceId'];
                    $data['skyId'] = $_GET['skyId'];
                    $data['eblSkyId'] = $_GET['eblSkyId'];
                    $data['imeiNo'] = $_GET['imeiNo'];
                    $data['message'] = "";
                    $data['selectedActionName'] = $_GET['selectedActionName'];


                    $dbData = $this->client_registration_model->getImeiByNumber($data['imeiNo']);


                    $data['checkerActionComment'] = $dbData['checkerActionComment'];

                    if ($data['checkerActionComment'] != NULL) {
                        $data['reasonModeOfDisplay'] = "display: block;";
                    } else {
                        $data['reasonModeOfDisplay'] = "display: none;";
                    }


                    $this->load->view('client_registration/edit_imei_view.php', $data);
                }
            }
        } else {
            echo "not allowed";
        }
    }

    public function updateDevice() {
        $this->output->set_template('theme2');
        $loginId = $this->session->userdata('adminUserId');

        if (isset($_POST['skyId']) && isset($_POST['eblSkyId']) && isset($_POST['imeiNo']) && isset($_POST['deviceId'])) {
            $data['deviceId'] = $_POST['deviceId'];
            $data['skyId'] = $_POST['skyId'];
            $data['eblSkyId'] = $_POST['eblSkyId'];
            $data['imeiNo'] = $_POST['imeiNo'];
            $data['selectedActionName'] = $_POST['selectedActionName'];

            $count = $this->client_registration_model->checkDuplicateImei($data);

            if ($count > 0) {
                $checkImei = $this->client_registration_model->getImeiByNumber($data['imeiNo']);
                $data['message'] = 'The IMEI No. "' . $data['imeiNo'] . '" is already assigned to ESB ID "' . $checkImei['eblSkyId'] . '"';
                $this->load->view('client_registration/edit_imei_view.php', $data);
            } else {
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
                $data['message'] = "IMEI Successfully Updated";
                $data['reasonModeOfDisplay'] = "display: none;";
                $this->load->view('client_registration/edit_imei_view.php', $data);
            }
        }
    }

    public function userActive() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(apps_user_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "active") > -1) {
                $eblSkyId = explode("|", $_POST['eblSkyId']);
                $skyId = explode("|", $_POST['skyId']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($skyId as $index => $value) {
                    $updateData = array("skyId" => $skyId[$index],
                        "isActive" => 1,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'active',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function userInactive() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(apps_user_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "inactive") > -1) {
                $eblSkyId = explode("|", $_POST['eblSkyId']);
                $skyId = explode("|", $_POST['skyId']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($skyId as $index => $value) {
                    $updateData = array("skyId" => $skyId[$index],
                        "isActive" => 0,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'inactive',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function userLock() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(apps_user_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "lock") > -1) {
                $eblSkyId = explode("|", $_POST['eblSkyId']);
                $skyId = explode("|", $_POST['skyId']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($skyId as $index => $value) {
                    $updateData = array("skyId" => $skyId[$index],
                        "isLocked" => 1,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'lock',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function userUnlock() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(apps_user_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "unlock") > -1) {
                $eblSkyId = explode("|", $_POST['eblSkyId']);
                $skyId = explode("|", $_POST['skyId']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($skyId as $index => $value) {
                    $updateData = array("skyId" => $skyId[$index],
                        "isLocked" => 0,
                        "wrongAttempts" => 0,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'unlock',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('apps_users_mc', $updateArray, 'skyId');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function deviceActive() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(device_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "active") > -1) {
                $imeiNo = explode("|", $_POST['imeiNo']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($imeiNo as $index => $value) {
                    $updateData = array("imeiNo" => $imeiNo[$index],
                        "isActive" => 1,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'active',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('device_info_mc', $updateArray, 'imeiNo');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function deviceInactive() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(device_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "inactive") > -1) {
                $imeiNo = explode("|", $_POST['imeiNo']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($imeiNo as $index => $value) {
                    $updateData = array("imeiNo" => $imeiNo[$index],
                        "isActive" => 0,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'inactive',
                        "makerActionDt" => date("y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('device_info_mc', $updateArray, 'imeiNo');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function deviceUnlock() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(device_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "unlock") > -1) {
                $imeiNo = explode("|", $_POST['imeiNo']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($imeiNo as $index => $value) {
                    $updateData = array("imeiNo" => $imeiNo[$index],
                        "isLocked" => 0,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'unlock',
                        "makerActionDt" => date("Y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('device_info_mc', $updateArray, 'imeiNo');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function deviceLock() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(device_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "lock") > -1) {
                $imeiNo = explode("|", $_POST['imeiNo']);
                $selectedActionName = $_POST['selectedActionName'];
                foreach ($imeiNo as $index => $value) {
                    $updateData = array("imeiNo" => $imeiNo[$index],
                        "isLocked" => 1,
                        "mcStatus" => 0,
                        "makerAction" => $selectedActionName,
                        "makerActionCode" => 'lock',
                        "makerActionDt" => date("Y-m-d"),
                        "makerActionTm" => date("G:i:s"),
                        "makerActionBy" => $this->session->userdata('adminUserId'));
                    $updateArray[] = $updateData;
                }
                $this->db->update_batch('device_info_mc', $updateArray, 'imeiNo');
                echo 1;
            }
        } else {
            echo "not allowed";
        }
    }

    public function userDelete() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(apps_user_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "delete") > -1) {
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
        } else {
            echo "not allowed";
        }
    }

}
