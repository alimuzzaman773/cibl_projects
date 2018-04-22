
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pin_create_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model('generate_eblskyid_model_checker');
    }

    public function index() {
        $data['unApprovedRequest'] = json_encode($this->generate_eblskyid_model_checker->getUnapprovedRequests());
        $data["pageTitle"] = "PIN Create Checker";
        $data["body_template"] = "pin_create_checker/requests.php";
        $this->load->view('site_template.php', $data);
    }

    public function pinCreateApprove() {

        //$authorizationModules = $this->session->userdata('authorizationModules');
        //if (strpos($authorizationModules, pin_create_authorization) > -1) {
            $id = $this->input->post('requestId');
            $totalNumber = $this->input->post('totalPin');
            $makerActionDtTmPost = $this->input->post('makerActionDtTm');
            $checkerActionDtTmPost = $this->input->post('checkerActionDtTm');

            $tableData = $this->generate_eblskyid_model_checker->getRequestById($id);
            $makerActionDtTm = $tableData['makerActionDt'] . " " . $tableData['makerActionTm'];
            $checkerActionDtTm = $tableData['checkerActionDt'] . " " . $tableData['checkerActionTm'];

            if (strtotime($makerActionDtTmPost) == strtotime($makerActionDtTm) && strtotime($checkerActionDtTmPost) == strtotime($checkerActionDtTm)) {
                $data['mcStatus'] = 1;
                $data['checkerAction'] = "Approve";
                $data['checkerActionComment'] = NULL;
                $data['checkerActionDt'] = date("Y-m-d");
                $data['checkerActionTm'] = date('G:i:s');
                $data['checkerActionBy'] = $this->my_session->userId;

                $this->db->where('requestId', $id);
                $this->db->update('pin_generation_request', $data);
                $selectedActionName = "Create";

                $this->db->select_max('generateId');
                $query = $this->db->get('generate_eblskyid');
                $row = $query->row_array();
                $maxId = $row['generateId'];

                for ($i = 1; $i <= $totalNumber; $i++) {

                    $insertData = array('eblSkyId' => 'ESB' . (1000 + $maxId + $i),
                        'pin' => $this->common_model->generateRandomString(),
                        'createdBy' => $this->my_session->userId,
                        'creationDtTm' => date("Y-m-d G:i:s"),
                        'isActive' => 1,
                        'mcStatus' => 1,
                        'makerAction' => $selectedActionName,
                        'makerActionCode' => 'create',
                        'isPublished' => 1,
                        'checkerAction' => 'Approved',
                        'checkerActionComment' => NULL);

                    $insertBatch[] = $insertData;
                }

                $this->db->insert_batch('generate_eblskyid', $insertBatch);

                // create activity log array to json encode //
                $pinCreateActivity['totalIdGenerated'] = $totalNumber;
                $pinCreateActivity['createdIdRange'] = $insertBatch[0]['eblSkyId'] . ' to ' . $insertBatch[$totalNumber - 1]['eblSkyId'];
                $pinCreateActivity['createdBy'] = $this->my_session->userId;
                $pinCreateActivity['creationDtTm'] = $insertBatch[$totalNumber - 1]['creationDtTm'];
                $pinCreateActivity['isPublished'] = 1;

                // prepare data for activity log //
                $activityLog = array('activityJson' => json_encode($pinCreateActivity),
                    'adminUserId' => $this->my_session->userId,
                    'adminUserName' =>$this->my_session->userName,
                    'tableName' => 'generate_eblskyid',
                    'moduleName' => 'pin_module',
                    'moduleCode' => '03',
                    'actionCode' => $insertBatch[0]['makerActionCode'],
                    'actionName' => $insertBatch[0]['makerAction'],
                    'creationDtTm' => date("Y-m-d G:i:s"));
                $this->db->insert('bo_activity_log', $activityLog);

                echo 1;
            } else {
                echo 3;
            }
      //  } else {
          //  echo 2;
      //  }
    }

    public function getRejectReason() {
        $data['requestId'] = $this->input->get('requestId');
        $data['makerActionDtTm'] = $this->input->get('makerActionDtTm');
        $data['checkerActionDtTm'] = $this->input->get('checkerActionDtTm');
        $data['message'] = "";
        $data["pageTitle"] = "PIN Create Reject";
        $data["body_template"] = "pin_create_checker/reject_reason.php";
        $this->load->view('site_template.php', $data);
    }

    public function checkerReject() {

        $id = $this->input->post('requestId');
        $makerActionDtTmPost = $this->input->post('makerActionDtTm');
        $checkerActionDtTmPost = $this->input->post('checkerActionDtTm');

        $tableData = $this->generate_eblskyid_model_checker->getRequestById($id);
        $makerActionDtTm = $tableData['makerActionDt'] . " " . $tableData['makerActionTm'];
        $checkerActionDtTm = $tableData['checkerActionDt'] . " " . $tableData['checkerActionTm'];

        if (strtotime($makerActionDtTmPost) == strtotime($makerActionDtTm) && strtotime($checkerActionDtTmPost) == strtotime($checkerActionDtTm)) {
            $data['checkerActionDt'] = date("Y-m-d");
            $data['checkerActionTm'] = date("G:i:s");
            $data['checkerActionBy'] = $this->my_session->userId;
            $data['checkerAction'] = "Rejected";
            $data['checkerActionComment'] = $this->input->post('checkerActionComment');
            $data['mcStatus'] = 2;

            $this->db->where('requestId', $id);
            $this->db->update('pin_generation_request', $data);

            redirect('pin_create_checker');
        } else {
            $data['requestId'] = $id;
            $data['makerActionDtTm'] = $makerActionDtTm;
            $data['checkerActionDtTm'] = $checkerActionDtTm;
            $data['message'] = "Data changed. Please Cancel and try again";
            $this->load->view('pin_create_checker/reject_reason', $data);
        }
    }

}
