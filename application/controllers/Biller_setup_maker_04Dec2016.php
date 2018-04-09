<?php


class Biller_setup_maker extends CI_Controller {



    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        
        $this->load->helper('url');
        $this->load->model('biller_setup_model_maker');
        $this->load->library('session');
  
        $this->load->model('login_model');
        if($this->login_model->check_session()){
            redirect('/admin_login/index');
        }
    }


    public function index()
    {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $actionNames = $this->session->userdata('actionNames');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $actionNames = explode("#", $actionNames);
        $index = array_search(biller_setup_module, $moduleCodes);
        if($index > -1){
            $actionCodes = json_encode(explode(",", $actionCodes[$index]));
            $actionNames = json_encode(explode(",", $actionNames[$index]));
            $billerData = $this->biller_setup_model_maker->getAllBillers();

            $data['billers'] = json_encode($billerData);
            $data['actionCodes'] = $actionCodes;
            $data['actionNames'] = $actionNames;
            $this->load->view('biller_setup_maker/overall_view.php', $data);
        }
        else{
            echo "not allowed";
        }
    }




    public function addNewBiller($selectedActionName = NULL)
    {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(biller_setup_module, $moduleCodes);
        if($index > -1){
            $moduleWiseActionCodes = $actionCodes[$index];
            if(strpos($moduleWiseActionCodes, "add") > -1){
                $data['billTypes'] = $this->biller_setup_model_maker->getAllBillTypes();
                $data['selectedActionName'] = $selectedActionName;
                $data['message'] = "";
                $this->load->view('biller_setup_maker/add_biller.php', $data);
            }
        }
        else{
            echo "not allowed";
        }
    }


    public function insertNewBiller()
    {
    	$data['billerName'] = $this->input->post('billerName');
    	$data['cfId'] = $this->input->post('cfId');
    	$data['billerCode'] = $this->input->post('billerCode');
    	$data['billerOrder'] = $this->input->post('billerOrder');


    	$data['suggestion'] = $this->input->post('suggestion');
        $data['referenceRegex'] = $this->input->post('referenceRegex');
        $data['referenceMatch'] = $this->input->post('referenceMatch');
        $data['amountMessage'] = $this->input->post('amountMessage');
        $data['amountRegex'] = $this->input->post('amountRegex');
        $data['amountMatch'] = $this->input->post('amountMatch');
        $data['referenceMessage'] = $this->input->post('referenceMessage');
        


    	$data['billTypeCode'] = $this->input->post('billTypeCode');
        $data['mcStatus'] = 0;
        $data['makerAction'] = $this->input->post('selectedActionName');
        $data['makerActionCode'] = 'add';
        $data['makerActionDt'] = date("y-m-d");
        $data['makerActionTm'] = date("G:i:s");
        $data['makerActionBy'] = $this->session->userdata('adminUserId');
        $data['isLocked'] = 0;
        $data['isPublished'] = 0;
        $data['isActive'] = 1;


        $billerNameCheck = $this->biller_setup_model_maker->getBillerByCode($data['billerCode']);

        if(!empty($billerNameCheck))
        {
			$this->output->set_template('theme2');
            $data['message'] = 'The biller with code "'.$data['billerCode'].'" already exists';
            $data['billTypes'] = $this->biller_setup_model_maker->getAllBillTypes();
            $data['selectedActionName'] = $data['makerAction'];
            $this->load->view('biller_setup_maker/add_biller.php', $data);
        }

        else
        {
        	$this->biller_setup_model_maker->insertBillerInfo($data);
        	redirect('Biller_setup_maker');
        }
    }




    public function editBiller($data, $selectedActionName = NULL, $message = NULL)
    {
        $this->output->set_template('theme2');
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(biller_setup_module, $moduleCodes);
        if($index > -1){
            $moduleWiseActionCodes = $actionCodes[$index];
            if(strpos($moduleWiseActionCodes, "edit") > -1){

                $tableData = $this->biller_setup_model_maker->getBillerById($data);

                // echo "<pre>";
                // print_r($tableData); die();

                $viewData['checkerActionComment'] = $tableData['checkerActionComment'];
                if($viewData['checkerActionComment'] != NULL){
                    $viewData['reasonModeOfDisplay'] = "display: block; color: red";
                }else{
                    $viewData['reasonModeOfDisplay'] = "display: none;";
                }
                $viewData['billerData'] = $tableData;
                $viewData['billTypes'] = $this->biller_setup_model_maker->getAllBillTypes();
                $viewData['selectedActionName'] = $selectedActionName;
                $viewData['message'] = $message;
                $this->load->view('biller_setup_maker/edit_biller.php', $viewData);
            }
        }
        else{
            echo "not allowed";
        }
    }


    public function updateBiller()
    {
    	$billerId = $this->input->post('billerId');
    	$data['billerName'] = $this->input->post('billerName');
    	$data['cfId'] = $this->input->post('cfId');
    	$data['billerCode'] = $this->input->post('billerCode');
    	$data['billerOrder'] = $this->input->post('billerOrder');


        $data['suggestion'] = $this->input->post('suggestion');
        $data['referenceRegex'] = $this->input->post('referenceRegex');
        $data['referenceMatch'] = $this->input->post('referenceMatch');
        $data['amountMessage'] = $this->input->post('amountMessage');
        $data['amountRegex'] = $this->input->post('amountRegex');
        $data['amountMatch'] = $this->input->post('amountMatch');
        $data['referenceMessage'] = $this->input->post('suggestion');



    	$data['billTypeCode'] = $this->input->post('billTypeCode');
        $data['mcStatus'] = 0;
        $data['makerAction'] = $this->input->post('selectedActionName');
        $data['makerActionCode'] = 'edit';
        $data['makerActionDt'] = date("y-m-d");
        $data['makerActionTm'] = date("G:i:s");
        $data['makerActionBy'] = $this->session->userdata('adminUserId');


        // echo "<pre>";
        // print_r($data); die();



        $billerNameCheck = $this->biller_setup_model_maker->checkBiller($billerId, $data);



        if($billerNameCheck > 0)
        {
        	$message = 'The biller with code "'.$data['billerCode'].'" already exists';
        	$this->editBiller($billerId, $data['makerAction'], $message);
        }

        else
        {
        	$this->biller_setup_model_maker->updateBillerInfo($data, $billerId);
        	redirect('biller_setup_maker');
        }
    }


    public function billerActive()
    {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(biller_setup_module, $moduleCodes);
        if($index > -1){
            $moduleWiseActionCodes = $actionCodes[$index];
            if(strpos($moduleWiseActionCodes, "active") > -1){

                $billerId = explode("|", $_POST['billerId']);
                $billerIdString = $_POST['billerId'];
                $checkData = $this->chkPermission($billerId);

                if(strcmp($billerIdString, $checkData) == 0)
                {
                    $selectedActionName = $_POST['selectedActionName'];
                    foreach($billerId as $index => $value){
                        $updateData = array("billerId" => $value,
                                            "isActive" => 1,
                                            "mcStatus" => 0,
                                            "makerAction" => $selectedActionName,
                                            "makerActionCode" => 'active',
                                            "makerActionDt" => date("y-m-d"),
                                            "makerActionTm" => date("G:i:s"),
                                            "makerActionBy" => $this->session->userdata('adminUserId'));
                        $updateArray[] = $updateData;
                    }
                    $this->db->update_batch('biller_setup_mc', $updateArray, 'billerId');
                    echo 1;
                }
                else{
                    echo 2;
                }
            }
        }
        else{
            echo "not allowed";
        }
    }




    public function billerInactive()
    {
        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(biller_setup_module, $moduleCodes);
        if($index > -1){
            $moduleWiseActionCodes = $actionCodes[$index];
            if(strpos($moduleWiseActionCodes, "inactive") > -1){

                $billerId = explode("|", $_POST['billerId']);
                $billerIdString = $_POST['billerId'];
                $checkData = $this->chkPermission($billerId);

                if(strcmp($billerIdString, $checkData) == 0)
                {
                    $selectedActionName = $_POST['selectedActionName'];
                    foreach($billerId as $index => $value){
                        $updateData = array("billerId" => $value,
                                            "isActive" => 0,
                                            "mcStatus" => 0,
                                            "makerAction" => $selectedActionName,
                                            "makerActionCode" => 'active',
                                            "makerActionDt" => date("y-m-d"),
                                            "makerActionTm" => date("G:i:s"),
                                            "makerActionBy" => $this->session->userdata('adminUserId'));
                        $updateArray[] = $updateData;
                    }
                    $this->db->update_batch('biller_setup_mc', $updateArray, 'billerId');
                    echo 1;
                }
                else{
                    echo 2;
                }
            }
        }
        else{
            echo "not allowed";
        }
    }



    public function chkPermission($data) // function to check injection
    {
        $id = $this->session->userdata('adminUserId');
        $this->db->select('biller_setup_mc.billerId');
        $this->db->where_in('billerId', $data);
        $this->db->where('(biller_setup_mc.makerActionBy = ' . $id . ' OR mcStatus = 1)');
        $query = $this->db->get('biller_setup_mc');
        $data = $this->multiDimensionArrayToString($query->result_array());
        return $data;
    }


    function multiDimensionArrayToString($array)
    {
      $out = implode("|",array_map(function($a) {return implode("~",$a);},$array));
      return $out;
    }



}