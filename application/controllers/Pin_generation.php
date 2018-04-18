<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pin_generation extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();

        $this->load->library('BOcrypter');
        $this->load->model('generate_eblskyid_model');
    }

    public function index() {
        $pinRequestData = $this->generate_eblskyid_model->getAllPinRequests();
        $data['pinRequest'] = json_encode($pinRequestData);

        $data['pageTitle'] = 'Pin Request';
        $data["body_template"] = "generate_pin/generate_pin.php";
        $this->load->view('site_template.php', $data);
    }

    public function newRequest($selectedActionName) {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(pin_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "create") > -1) {
                $data['selectedActionName'] = $selectedActionName;
                $data['message'] = "";
                $this->load->view('generate_pin/new_request.php', $data);
            }
        } else {
            echo "not allowed";
        }
    }

    public function insertNewRequest() {
        $data['totalPin'] = $this->input->post('totalPin');
        $data['mcStatus'] = 0;
        $data['makerAction'] = $this->input->post('selectedActionName');
        $data['makerActionComment'] = $this->input->post('makerActionComment');
        $data['makerActionDt'] = date("Y-m-d");
        $data['makerActionTm'] = date("G:i:s");
        $data['makerActionBy'] = $this->session->userdata('adminUserId');

        $this->db->insert('pin_generation_request', $data);
        redirect('pin_generation');
    }

    public function editRequest($id, $selectedActionName, $message = NULL) {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(pin_module, $moduleCodes);
        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];
            if (strpos($moduleWiseActionCodes, "create") > -1) {
                $tableData = $this->generate_eblskyid_model->getPinRequestById($id);
                $viewData['checkerActionComment'] = $tableData['checkerActionComment'];
                if ($viewData['checkerActionComment'] != NULL) {
                    $viewData['reasonModeOfDisplay'] = "display: block; color: red";
                } else {
                    $viewData['reasonModeOfDisplay'] = "display: none;";
                }
                $viewData['pinRequestData'] = $tableData;
                $viewData['selectedActionName'] = $selectedActionName;
                $viewData['message'] = $message;
                $this->load->view('generate_pin/edit_request.php', $viewData);
            }
        } else {
            echo "not allowed";
        }
    }

    public function updatePinRequest() {
        $id = $this->input->post('requestId');
        $selectedActionName = $this->input->post('selectedActionName');
        $tableData = $this->generate_eblskyid_model->getPinRequestById($id);

        $data['totalPin'] = $this->input->post('totalPin');
        $data['mcStatus'] = 0;
        $data['makerAction'] = 'Edit';
        $data['makerActionComment'] = $this->input->post('makerActionComment');
        $data['makerActionDt'] = date("Y-m-d");
        $data['makerActionTm'] = date("G:i:s");
        $data['makerActionBy'] = $this->session->userdata('adminUserId');



        if ($tableData['makerActionBy'] == $this->session->userdata('adminUserId')) {
            $this->db->where('requestId', $id);
            $this->db->update('pin_generation_request', $data);
            redirect('pin_generation');
        } else {
            $message = "User Can only Update his own request";
            $this->editRequest($id, $selectedActionName, $message);
        }
    }

    public function generatePin() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(pin_module, $moduleCodes);

        if ($index > -1) {
            $actionCodes = $actionCodes[$index];
            if (strpos($actionCodes, "create") > -1) {
                if (isset($_POST['totalId'])) {
                    $totalnumber = $_POST['totalId'];
                    $selectedActionName = $_POST['selectedActionName'];
                    $data['message'] = 'Total "' . $totalnumber . '" ESB ID Generated';
                    $data['selectedActionName'] = $selectedActionName;
                    $this->load->view('generate_pin/generate_pin.php', $data);
                } else {
                    $data['message'] = 'The number of id to be generated not given';
                    $data['selectedActionName'] = $selectedActionName;
                    $this->load->view('generate_pin/generate_pin.php', $data);
                }
            }
        } else {
            echo "not allowed";
        }
    }

    public function viewPinByAction() {
        $action = $this->input->post('action');
        
        $data = isset($action) ? $action : "all";

        if ($data == "all" || $data == "create") {
            $viewData['pinNumbers'] = json_encode($this->generate_eblskyid_model->getAllPin());
        }
        if ($data == "destroy") {
            $viewData['pinNumbers'] = json_encode($this->generate_eblskyid_model->getPinToDestroy());
        }
        if ($data == "print") {
            $viewData['pinNumbers'] = json_encode($this->generate_eblskyid_model->getPinToPrint());
        }
        if ($data == "reset") {
            $viewData['pinNumbers'] = json_encode($this->generate_eblskyid_model->getPinToReset());
        }

        $viewData['selectedValue'] = $data;
        $viewData['pageTitle'] = 'Pin Numbers';
        $viewData["body_template"] = "generate_pin/view_pin.php";
        $this->load->view('site_template.php', $viewData);
    }

    public function pinDestroy() {
        // $moduleCodes = $this->session->userdata('moduleCodes');
        // $actionCodes = $this->session->userdata('actionCodes');
        // $moduleCodes = explode("|", $moduleCodes);
        // $actionCodes = explode("#", $actionCodes);
        // foreach($moduleCodes as $index => $value){
        //     if($moduleCodes[$index] == pin_module){
        //         $actionCodes = $actionCodes[$index];
        //         if(strpos($actionCodes, "destroy") > -1){
        //             $data = $_POST['esbid'];
        //             $selectedActionName = $_POST['selectedActionName'];
        //             $esbid = $this->explodFunction($data);
        //             foreach($esbid as $index => $value){
        //                 $updateData = array("eblSkyId" => $value,
        //                                     "isActive" => 0,
        //                                     "mcStatus" => 1,
        //                                     "makerAction" => $selectedActionName,
        //                                     "makerActionCode" => 'destroy',
        //                                     "updatedBy" => $this->session->userdata('adminUserId'),
        //                                     "updateDtTm" => date("Y-m-d G:i:s"),
        //                                     "isPublished" => 1);
        //                 $updateArray[] = $updateData;
        //             }
        //             $this->db->update_batch('generate_eblskyid', $updateArray, 'eblSkyId');
        //             echo 1;
        //         }
        //     }
        // }
    }

    public function pinReset() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        foreach ($moduleCodes as $index => $value) {
            if ($moduleCodes[$index] == pin_module) {
                $actionCodes = $actionCodes[$index];
                if (strpos($actionCodes, "reset") > -1) {
                    $data = $_POST['esbid'];
                    $selectedActionName = $_POST['selectedActionName'];
                    $esbid = $this->explodFunction($data);
                    foreach ($esbid as $index => $value) {
                        $pinData = array('eblSkyId' => $value,
                            'isReset' => 1,
                            'isPrinted' => 0,
                            'mcStatus' => 0,
                            'makerAction' => $selectedActionName,
                            "makerActionCode" => 'reset',
                            'makerActionDt' => date("y-m-d"),
                            'makerActionTm' => date("G:i:s"),
                            'makerActionBy' => $this->session->userdata('adminUserId')); // status2 used for handeling the increment process of reset times

                        $updateArrayPin[] = $pinData;
                    }
                    $this->db->update_batch('generate_eblskyid', $updateArrayPin, 'eblSkyId');
                    echo 1;
                }
            }
        }
    }

    public function pinPrint() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        foreach ($moduleCodes as $index => $value) {
            if ($moduleCodes[$index] == pin_module) {
                $actionCodes = $actionCodes[$index];
                if (strpos($actionCodes, "print") > -1) {
                    $data = $_POST['esbid'];
                    $selectedActionName = $_POST['selectedActionName'];
                    $esbid = $this->explodFunction($data);

                    // print job start here //
                    $this->db->select('eblSkyId, pin');
                    $this->db->where_in('eblSkyId', $esbid);
                    $query = $this->db->get('generate_eblskyid');
                    $printData = $query->result_array();

                    foreach ($printData as $index => $value) {
                        $printData[$index]['pin'] = $this->bocrypter->Decrypt($printData[$index]['pin']);
                    }

                    foreach ($esbid as $index => $value) {
                        $pinData = array('eblSkyId' => $value,
                            'isReset' => 0,
                            'isPrinted' => 1,
                            'mcStatus' => 1,
                            'makerAction' => $selectedActionName,
                            'makerActionCode' => 'print',
                            'makerActionDt' => date("y-m-d"),
                            'makerActionTm' => date("G:i:s"),
                            'makerActionBy' => $this->session->userdata('adminUserId'));
                        $updateArrayPin[] = $pinData;
                    }


                    $this->db->update_batch('generate_eblskyid', $updateArrayPin, 'eblSkyId');

                    // create activity log array to json encode //
                    $pinPrintActivity['totalPinPrinted'] = sizeof($esbid);
                    $pinPrintActivity['printedPinRange'] = $updateArrayPin[0]['eblSkyId'] . ' to ' . $updateArrayPin[sizeof($esbid) - 1]['eblSkyId'];
                    $pinPrintActivity['makerActionBy'] = $this->session->userdata('adminUserId');
                    $pinPrintActivity['makerActionDt'] = $updateArrayPin[sizeof($esbid) - 1]['makerActionDt'];
                    $pinPrintActivity['makerActionTm'] = $updateArrayPin[sizeof($esbid) - 1]['makerActionTm'];
                    ;

                    // prepare data for activity log //
                    $activityLog = array('activityJson' => json_encode($pinPrintActivity),
                        'adminUserId' => $this->session->userdata('adminUserId'),
                        'adminUserName' => $this->session->userdata('username'),
                        'tableName' => 'generate_eblskyid',
                        'moduleName' => 'pin_module',
                        'moduleCode' => '03',
                        'actionCode' => $updateArrayPin[0]['makerActionCode'],
                        'actionName' => $updateArrayPin[0]['makerAction'],
                        'creationDtTm' => date("Y-m-d G:i:s"));
                    $this->db->insert('bo_activity_log', $activityLog);

                    $response['responseCode'] = 1;
                    $response['message'] = "Successfully printed from " . $pinPrintActivity['printedPinRange'];
                    $response['printData'] = $printData;
                    echo json_encode($response);
                }
            }
        }
    }

    public function explodFunction($data) {
        $data = explode("|", $data);
        array_shift($data);
        return $data;
    }

}
