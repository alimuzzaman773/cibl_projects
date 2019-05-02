<?php

class Csv_import extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->model('csv_model');
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('my_session');
        $this->load->library('csvimport');


        $this->load->model('login_model');

        $this->my_session->checkSession();
    }

    function index() {
        #$this->my_session->authorize('canImportRoutingNumber');

        $this->my_session->authorize("canViewRoutingNumberMenu");

        $data['pageTitle'] = 'Import Routing Number';
        $data['body_template'] = 'routing_number/csv_import_view.php';
        $this->load->view('site_template', $data);
    }

    function importcsv() {
        $admin_id = $this->my_session->userId;
        $current_date = date("Y-m-d H:i:s");

        $data['error'] = '';    //initialize image upload error array to empty
        $config['upload_path'] = APPPATH . '../assets/uploads/files/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $data['success'] = false;
            $data['msg'] = $this->upload->display_errors();
            my_json_output($data);
        }

        $file_data = $this->upload->data();
        $file_path = APPPATH . '../assets/uploads/files/' . $file_data['file_name'];

        if ($this->csvimport->get_array($file_path)) {
            $res_arr_values = array();
            $csv_array = $this->csvimport->get_array($file_path);

            $columnError = $this->checkColumnName($csv_array);
            if (!$columnError["success"]) {
                my_json_output($columnError);
            }

            foreach ($csv_array as $row) {
                $insert_data = array(
                    'bankName' => $row['bankName'],
                    'districtName' => $row['districtName'],
                    'branchName' => $row['branchName'],
                    'routingNumber' => $row['routingNumber'],
                    'creationDtTm' => $current_date,
                    'updateDtTm' => $current_date,
                    'createdBy' => $admin_id,
                    'updatedBy' => $admin_id,
                    'isActive' => 1);

                $res_arr_values[] = $insert_data;
            }

            try {
                if (count($csv_array) <= 0):
                    throw new Exception("Zero number of items found");
                endif;

                $this->db->trans_begin();

                $this->db->reset_query();
                $this->db->truncate('routing_number');

                $this->db->reset_query();
                $this->csv_model->insert_csv_batch($res_arr_values);

                // create activity log array to json encode //
                $csvImportActivity['totalRoutingNumber'] = sizeof($csv_array);
                $csvImportActivity['importedBy'] = $admin_id;
                $csvImportActivity['importTime'] = $current_date;
                $csvImportActivity['fileName'] = $file_data['file_name'];

                $this->db->reset_query();
                // prepare data for activity log //
                $activityLog = array(
                    'activityJson' => json_encode($csvImportActivity),
                    'adminUserId' => $admin_id,
                    'adminUserName' => $this->my_session->userName,
                    'tableName' => 'routing_number',
                    'moduleName' => 'routing_number_module',
                    'moduleCode' => '07',
                    'actionCode' => 'importCsv',
                    'actionName' => 'Import CSV',
                    'creationDtTm' => $current_date
                );
                $this->db->insert('bo_activity_log', $activityLog);

                if ($this->db->trans_status() == false):
                    throw new Exception("Failure in transaction in " . __CLASS__ . "::" . __FILE__ . "::" . __LINE__);
                endif;

                $this->db->trans_commit();

                $json = array(
                    "success" => true,
                    'msg' => 'Csv Data Imported Succesfully'
                );
                my_json_output($json);
            } catch (Exception $ex) {
                $this->db->trans_rollback();
                $json = array(
                    'success' => false,
                    'msg' => $ex->getMessage()
                );
                my_json_output($json);
            }
        }

        $json = array(
            "success" => false,
            'msg' => 'No csv data imported'
        );
        my_json_output($json);
    }

    function checkColumnName($csv_array) {
        if (!isset($csv_array[0]['bankName'])) {
            return array(
                'success' => false,
                'msg' => "Invalid Bank Name Column"
            );
        }

        if (!isset($csv_array[0]['districtName'])) {
            return array(
                'success' => false,
                'msg' => "Invalid District Name Column"
            );
        }

        if (!isset($csv_array[0]['branchName'])) {
            return array(
                'success' => false,
                'msg' => "Invalid Branch Name Column"
            );
        }
        if (!isset($csv_array[0]['routingNumber'])) {
            return array(
                'success' => false,
                'msg' => "Invalid Routing Number Column"
            );
        }

        return array(
            'success' => true
        );
    }

}
