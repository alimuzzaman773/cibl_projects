<?php

class Priority_request_process extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('priority_request_process_model', 'common_model', 'login_model'));
    }

    public function getRequests() {
        $this->my_session->authorize("canViewPriorityRequest");
        $data['service_list'] = $this->priority_request_process_model->getAllServiceTypeByModule("Priority");
        $data["pageTitle"] = "Priority Request";
        $data["body_template"] = "priority_request_process/show_priority_request.php";
        $this->load->view('site_template.php', $data);
    }

    function get_requests_ajax() {
        $this->my_session->authorize("canViewPriorityRequest");
        $params['limit'] = (int) $this->input->get("limit", true);
        $params['offset'] = (int) $this->input->get("offset", true);
        $params['get_count'] = (bool) $this->input->get("get_count", true);
        $params['type_code'] = $this->input->get("typeCode", true);
        $params['apps_id'] = $this->input->get("appsId", true);
        $params['reference_no'] = $this->input->get("referenceNo", true);
        $params['customer_name'] = $this->input->get("customerName", true);
        $params['mobile_no'] = $this->input->get("mobileNo", true);

        $data['total'] = array();
        $data['priority_list'] = array();

        if ((int) $params['get_count'] > 0) {
            $countParams = $params;
            unset($countParams['limit']);
            unset($countParams['offset']);
            $countParams['count'] = true;
            $countRes = $this->priority_request_process_model->getAllRequestByTypeCode($countParams);
            if ($countRes):
                $data['total'] = $countRes->row()->total;
            endif;
        }
        $result = $this->priority_request_process_model->getAllRequestByTypeCode($params);
        if ($result) {
            $data['priority_list'] = $result->result();
        }
        my_json_output($data);
    }

    public function processRequestById($id) {
        $this->my_session->authorize("canEmailPriorityRequest");
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
        $mailData["pageTitle"] = "Priority Request";
        $mailData["body_template"] = "priority_request_process/mail_form.php";
        $this->load->view('site_template.php', $mailData);
    }

    public function sendMail() {
        $this->my_session->authorize("canEmailPriorityRequest");
        $serviceRequestID = $this->input->post("serviceRequestID");
        $maildata['to'] = $this->input->post("to");
        $maildata['cc'] = $this->input->post("cc");
        $maildata['bcc'] = $this->input->post("bcc");
        $maildata['subject'] = $this->input->post("subject");
        $bodyInstruction = $this->input->post("bodyInstruction");
        $maildata['body'] = $this->input->post("body") . "<br></br>" . $bodyInstruction;
        $isSuccess = $this->common_model->send_service_mail($maildata);
        if ($isSuccess) {
            $this->priority_request_process_model->statusChange($serviceRequestID, $maildata, $bodyInstruction);
            redirect('priority_request_process/getRequests');
        }
    }

}
