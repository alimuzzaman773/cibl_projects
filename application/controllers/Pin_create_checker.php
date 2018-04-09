
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pin_create_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->helper('url');
        $this->load->model('generate_eblskyid_model_checker');
        $this->load->library('session');

        $this->load->model('login_model');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    public function index() {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, pin_create_authorization) > -1) {
            $data['unApprovedRequest'] = json_encode($this->generate_eblskyid_model_checker->getUnapprovedRequests());
            $this->load->view('pin_create_checker/requests.php', $data);
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function pinCreateApprove() {

        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, pin_create_authorization) > -1) {
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
                $data['checkerActionBy'] = $this->session->userdata('adminUserId');

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
                        'createdBy' => $this->session->userdata('adminUserId'),
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
                $pinCreateActivity['createdBy'] = $this->session->userdata('adminUserId');
                $pinCreateActivity['creationDtTm'] = $insertBatch[$totalNumber - 1]['creationDtTm'];
                $pinCreateActivity['isPublished'] = 1;

                // prepare data for activity log //
                $activityLog = array('activityJson' => json_encode($pinCreateActivity),
                    'adminUserId' => $this->session->userdata('adminUserId'),
                    'adminUserName' => $this->session->userdata('username'),
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
        } else {
            echo 2;
        }
    }

    public function getRejectReason() {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, pin_create_authorization) > -1) {
            $data['requestId'] = $this->input->get('requestId');
            $data['makerActionDtTm'] = $this->input->get('makerActionDtTm');
            $data['checkerActionDtTm'] = $this->input->get('checkerActionDtTm');
            $data['message'] = "";
            $this->load->view('pin_create_checker/reject_reason', $data);
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function checkerReject() {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, pin_create_authorization) > -1) {


            $id = $this->input->post('requestId');
            $makerActionDtTmPost = $this->input->post('makerActionDtTm');
            $checkerActionDtTmPost = $this->input->post('checkerActionDtTm');



            $tableData = $this->generate_eblskyid_model_checker->getRequestById($id);
            $makerActionDtTm = $tableData['makerActionDt'] . " " . $tableData['makerActionTm'];
            $checkerActionDtTm = $tableData['checkerActionDt'] . " " . $tableData['checkerActionTm'];

            if (strtotime($makerActionDtTmPost) == strtotime($makerActionDtTm) && strtotime($checkerActionDtTmPost) == strtotime($checkerActionDtTm)) {
                $data['checkerActionDt'] = date("Y-m-d");
                $data['checkerActionTm'] = date("G:i:s");
                $data['checkerActionBy'] = $this->session->userdata('adminUserId');
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
        } else {
            echo "Authorization Module Not Given";
        }
    }

}
