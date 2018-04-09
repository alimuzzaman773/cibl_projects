<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generate_eblskyid_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->model('common_model');
    }

    // public function createEBLskyId($totalNumber, $selectedActionName) {
    //     $this->db->select_max('generateId');
    //     $query = $this->db->get('generate_eblskyid');
    //     $row = $query->row_array();
    //     $maxId = $row['generateId'];
    //     for($i=1; $i<=$totalNumber; $i++) {
    //         $insertData = array('eblSkyId' => 'ESB' . (1000 + $maxId + $i),
    //                             'pin' => $this->common_model->generateRandomString(),
    //                             'createdBy' => $this->session->userdata('adminUserId'),
    //                             'creationDtTm' => date("Y-m-d G:i:s"),
    //                             'isActive' => 1,
    //                             'mcStatus' => 1,
    //                             'makerAction' => $selectedActionName,
    //                             'makerActionCode' => 'create',
    //                             'isPublished' => 1,
    //                             'checkerAction' => 'Approved',
    //                             'checkerActionComment' => NULL);
    //         $insertBatch[] = $insertData;
    //     }
    //     $this->db->insert_batch('generate_eblskyid', $insertBatch);
    //     // create activity log array to json encode //
    //     $pinCreateActivity['totalIdGenerated'] = $totalNumber;
    //     $pinCreateActivity['createdIdRange'] = $insertBatch[0]['eblSkyId'] . ' to ' . $insertBatch[$totalNumber - 1]['eblSkyId'];
    //     $pinCreateActivity['createdBy'] = $this->session->userdata('adminUserId');
    //     $pinCreateActivity['creationDtTm'] = $insertBatch[$totalNumber - 1]['creationDtTm'];
    //     $pinCreateActivity['isPublished'] = 1;
    //     // prepare data for activity log //
    //     $activityLog = array('activityJson' => json_encode($pinCreateActivity),
    //                          'adminUserId' => $this->session->userdata('adminUserId'),
    //                          'adminUserName' => $this->session->userdata('username'),
    //                          'tableName' => 'generate_eblskyid',
    //                          'moduleName' => 'pin_module',
    //                          'moduleCode' => '03',
    //                          'actionCode' => $insertBatch[0]['makerActionCode'],
    //                          'actionName' => $insertBatch[0]['makerAction'],
    //                          'creationDtTm' => date("Y-m-d G:i:s"));
    //     $this->db->insert('bo_activity_log', $activityLog);
    // }




    public function getAllPin() {
        $query = $this->db->get('generate_eblskyid');
        return $query->result();
    }

    public function getPinToDestroy() {
        $query = $this->db->get_where('generate_eblskyid', array('isActive' => 1, 'isUsed' => 0));
        return $query->result();
    }

    public function getPinToPrint() {
        //$this->db->where('isPrinted', 0); previously commented
        // $this->db->where('isactive', 1);
        // $this->db->where('isUsed', 0);
        // $this->db->or_where('isReset', 1);


        $query = $this->db->query('SELECT * FROM `generate_eblskyid` WHERE (`isUsed` = 0 OR `isReset` = 1) AND `isPrinted` = 0');
        return $query->result();
    }

    public function getPinToReset() {
        // $query = $this->db->get_where('generate_eblskyid', array('isReset' => 0, 'isUsed' => 1));
        // $query = $this->db->get_where('generate_eblskyid', array('isPrinted !=' => 1));


        $query = $this->db->get('generate_eblskyid');

        return $query->result();
    }

    public function getAllPinRequests() {
        $this->db->select('pin_generation_request.*,
                           admin_users.fullName,
                           admin_users.adminUserName');
        $this->db->where('pin_generation_request.makerActionBy =', $this->session->userdata('adminUserId'));
        $this->db->from('pin_generation_request');
        $this->db->join('admin_users', 'pin_generation_request.makerActionBy = admin_users.adminUserId');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPinRequestById($id) {
        $this->db->where('requestId', $id);
        $query = $this->db->get('pin_generation_request');
        return $query->row_array();
    }

}
