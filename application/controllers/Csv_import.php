<?php

class Csv_import extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->model('csv_model');
        $this->load->helper('url');
        $this->load->library('grocery_CRUD');
        $this->load->library('session');
        $this->load->library('csvimport');


        $this->load->model('login_model');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    function index() {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        foreach ($moduleCodes as $index => $value) {
            if ($moduleCodes[$index] == routing_number_module) {
                $moduleWiseActionCodes = $actionCodes[$index];
                if (strpos($moduleWiseActionCodes, "importCsv") > -1) {
                    $this->output->set_template('theme2');
                    $this->load->view('routing_number/csv_import_view.php');
                }
            }
        }
    }

    function importcsv() {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);

        $admin_id = $this->session->userdata('adminUserId');
        $current_date = input_date();

        foreach ($moduleCodes as $index => $value) {
            if ($moduleCodes[$index] == routing_number_module) {
                $moduleWiseActionCodes = $actionCodes[$index];
                if (strpos($moduleWiseActionCodes, "importCsv") > -1) {
                    $data['error'] = '';    //initialize image upload error array to empty
                    $config['upload_path'] = './assets/uploads/routing_number_csv/';
                    $config['allowed_types'] = 'txt';
                    $config['max_size'] = '1000';

                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload()) {
                        $data['error'] = $this->upload->display_errors();
                        $this->load->view('routing_number/csv_import_view.php', $data);
                    } else {
                        $file_data = $this->upload->data();
                        $file_path = './assets/uploads/routing_number_csv/' . $file_data['file_name'];

                        if ($this->csvimport->get_array($file_path)) {


                            $res_arr_values = array();
                            $csv_array = $this->csvimport->get_array($file_path);

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
                            $this->db->truncate('routing_number');
                            $this->csv_model->insert_csv_batch($res_arr_values);



                            // create activity log array to json encode //
                            $csvImportActivity['totalRoutingNumber'] = sizeof($csv_array);
                            $csvImportActivity['importedBy'] = $admin_id;
                            $csvImportActivity['importTime'] = $current_date;
                            $csvImportActivity['fileName'] = $file_data['file_name'];



                            // prepare data for activity log //
                            $activityLog = array('activityJson' => json_encode($csvImportActivity),
                                'adminUserId' => $admin_id,
                                'adminUserName' => $this->session->userdata('username'),
                                'tableName' => 'routing_number',
                                'moduleName' => 'routing_number_module',
                                'moduleCode' => '07',
                                'actionCode' => 'importCsv',
                                'actionName' => 'Import CSV',
                                'creationDtTm' => $current_date);
                            $this->db->insert('bo_activity_log', $activityLog);

                            $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                            redirect(base_url() . 'csv_import');
                        } else {
                            $data['error'] = "Error occured";
                            $this->load->view('routing_number/csv_import_view.php', $data);
                        }
                    }
                }
            }
        }
    }

}
