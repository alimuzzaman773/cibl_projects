<?php

class Priority_request_process extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->helper('url');
        $this->load->model('priority_request_process_model');
        $this->load->model('common_model');

        $this->load->model('login_model');
        $this->load->library('session');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    public function getRequests() {
        $moduleCodes = $this->session->userdata('serviceRequestModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(priority_sr, $moduleCodes);
        if ($index > -1) {


            $moduleName = "Priority";
            $data['TypeCode'] = isset($_POST['request']) ? $_POST['request'] : "0";
            $data['service_list'] = $this->priority_request_process_model->getAllServiceTypeByModule($moduleName);

            if ($data['TypeCode'] == "0") {
                $data['requestList'] = json_encode($this->priority_request_process_model->getAllRequest());
            } else {
                $data['requestList'] = json_encode($this->priority_request_process_model->getAllRequestByTypeCode($data['TypeCode']));
            }
            $this->output->set_template('theme2');
            $this->load->view('priority_request_process/show_priority_request', $data);
        } else {
            echo "not allowed";
            die();
        }
    }

    public function processRequestById($id) {


        $moduleCodes = $this->session->userdata('serviceRequestModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(priority_sr, $moduleCodes);
        if ($index > -1) {




            $request = $this->priority_request_process_model->getSingleRequestById($id);


            $mailData['to'] = "";
            $mailData['body'] = "";
            $mailData['subject'] = "";

            $strings['airPortPickDrop'] = "referenceNo,customerID,name,cardNo,requestDtTm,noOfPeople,altContact,remarks,jAirLineName,jFlightNo,jReportingDtTm,jPickUpAddress,jDropOffAddress,rAirLineName,rFlightNo,rReportingDtTm,rPickUpAddress,rDropOffAddress,prefferedVehicle";
            $strings['aiPortMeetGreet'] = "referenceNo,customerID,name,cardNo,requestDtTm,noOfPeople,altContact,remarks,jAirLineName,jFlightNo,jReportingDtTm,rAirLineName,rFlightNo,rReportingDtTm";
            $strings['skyLounge'] = "referenceNo,customerID,name,cardNo,requestDtTm,noOfPeople,altContact,remarks,jAirLineName,jFlightNo,jReportingDtTm";
            $strings['rmCallback'] = "referenceNo,name,contactNo,email,myLocation,preferredCallDtTm,callBackFor";

            $replacearray = array('typeCode' => 'Type Code',
                'referenceNo' => 'Reference No',
                'customerID' => 'Customer ID',
                'name' => 'Name',
                'contactNo' => 'Contact No.',
                'email' => 'Email',
                'cardNo' => 'Card No',
                'requestDtTm' => 'Request Date',
                'noOfPeople' => 'No. of People',
                'altContact' => 'Contact No.',
                'remarks' => 'Remarks',
                'jAirLineName' => 'Journey Air Line Name',
                'jFlightNo' => 'Journey Flight Number',
                'jReportingDtTm' => 'Journey Reporting Date',
                'jPickUpAddress' => 'Journey Pickup Address',
                'jDropOffAddress' => 'Journey Drop off Address',
                'rAirLineName' => 'Return Air Line Name',
                'rFlightNo' => 'Return Flight Number',
                'rReportingDtTm' => 'Return Reporting Date',
                'rPickUpAddress' => 'Return Pickup Address',
                'rDropOffAddress' => 'Return Drop off Address',
                'myLocation' => 'My Location',
                'preferredCallDtTm' => 'Preferred Call Date',
                'prefferedVehicle' => 'Preferred Vehicle Type',
                'callBackFor' => 'Call Back For');



            if ($request['typeCode'] == "01") {
                $mailData['subject'] = "Airport Pick & Drop";
                foreach ($request as $key => $value) {
                    if (strpos($strings['airPortPickDrop'], $key) > - 1) {
                        $mailData['body'] .= "<p>" . $replacearray[$key] . ':  ' . $value . "<p>";
                    }
                }
            } else if ($request['typeCode'] == "02") {
                $mailData['subject'] = "Airport Meet & Greet";
                foreach ($request as $key => $value) {
                    if (strpos($strings['aiPortMeetGreet'], $key) > - 1) {
                        $mailData['body'] .= "<p>" . $replacearray[$key] . ':  ' . $value . "<p>";
                    }
                }
            } else if ($request['typeCode'] == "03") {
                $mailData['subject'] = "Sky Lounge";
                foreach ($request as $key => $value) {
                    if (strpos($strings['skyLounge'], $key) > - 1) {
                        $mailData['body'] .= "<p>" . $replacearray[$key] . ':  ' . $value . "<p>";
                    }
                }
            } else if ($request['typeCode'] == "04") {
                $mailData['subject'] = "RM Call Back";
                foreach ($request as $key => $value) {
                    if (strpos($strings['rmCallback'], $key) > - 1) {
                        $mailData['body'] .= "<p>" . $replacearray[$key] . ':  ' . $value . "<p>";
                    }
                }
            }
            $mailData['serviceRequestID'] = $id;
            $this->output->set_template('theme2');
            $this->load->view('priority_request_process/mail_form', $mailData);
        } else {
            echo "not allowed";
            die();
        }
    }

    public function sendMail() {


        $moduleCodes = $this->session->userdata('serviceRequestModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(priority_sr, $moduleCodes);
        if ($index > -1) {
            $serviceRequestID = $_POST['serviceRequestID'];
            $maildata['to'] = $_POST['to'];
            //----new-----//
            $ccAddress = "";
            $ccAddress = $_POST['cc'];
            $maildata['cc'] = $ccAddress;
            $bccAddress = "";
            $bccAddress = $_POST['bcc'];
            $maildata['bcc'] = $bccAddress;
            //--------------//
            $maildata['subject'] = $_POST['subject'];
            $bodyInstruction = "";
            $bodyInstruction = $_POST['bodyInstruction'];
            $maildata['body'] = $_POST['body'] . "<br></br>" . $bodyInstruction;
            $isSuccess = $this->common_model->send_service_mail($maildata);

            if ($isSuccess) {
                //----new----//
                $this->priority_request_process_model->statusChange($serviceRequestID, $maildata, $bodyInstruction);
                //-----------//
                redirect('priority_request_process/getRequests');
            }
        } else {
            echo "not allowed";
            die();
        }
    }

}
