<?php

class Banking_service_request extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('banking_service_request_model', 'common_model', 'login_model'));
        $this->load->model('common_model'); // for sending mail
    }

    public function getRequests() {

        //$moduleCodes = $this->session->userdata('serviceRequestModules');
        //$moduleCodes = explode("|", $moduleCodes);
        //$index = array_search(banking_sr, $moduleCodes);
        //if ($index > -1) {


        $moduleName = "Banking";
        $data['pServiceTypeCode'] = "0";

        /*
          $data['cServiceTypeCode'] = "0";
          $data['parentServiceList'] = $this->banking_service_request_model->getAllServiceTypeByModule($moduleName, $data['pServiceTypeCode']);
          $data['BankingRequestList'] = json_encode($this->banking_service_request_model->getAllBankingRequest());

          if (isset($_POST['Prequest'])) {
          if ($_POST['Prequest'] != "0") {
          $data['cServiceTypeCode'] = $_POST['Prequest'];
          $data['childServiceList'] = $this->banking_service_request_model->getAllServiceTypeByModule($moduleName, $data['pServiceTypeCode'], $data['cServiceTypeCode']);
          }
          }
          if (isset($_POST['Crequest'])) {
          if ($_POST['Crequest'] != "0") {
          $data['pServiceTypeCode'] = $_POST['Crequest'];
          $data['BankingRequestList'] = json_encode($this->banking_service_request_model->getAllBankingRequestByTypeCode($_POST['Crequest']));
          }
          } */

        $data["pageTitle"] = "Banking Service";
        $data['parentServiceList'] = $this->banking_service_request_model->getAllServiceTypeByModule($moduleName, $data['pServiceTypeCode']);
        $data["body_template"] = "banking_service_request/show_banking_request.php";
        $this->load->view('site_template.php', $data);
        // } else {
        // echo "not allowed";
        //  die();
        //}
    }

    function get_child_service() {
        $typeId = (int) $this->input->get("type_code", true);
        $result = $this->banking_service_request_model->getChildService($typeId);
        $data["child_list"] = array();
        if ($result) {
            $data["child_list"] = $result->result();
        }
        $data["success"] = true;
        my_json_output($data);
    }

    function get_requests_ajax() {
        $params['limit'] = (int) $this->input->get("limit", true);
        $params['offset'] = (int) $this->input->get("offset", true);
        $params['get_count'] = (bool) $this->input->get("get_count", true);
        $params['type_code'] = $this->input->get("type_code", true);

        $data['total'] = array();
        $data['banking_list'] = array();

        if ((int) $params['get_count'] > 0) {
            $countParams = $params;
            unset($countParams['limit']);
            unset($countParams['offset']);
            $countParams['count'] = true;
            $countRes = $this->banking_service_request_model->getAllBankingRequest($countParams);
            if ($countRes):
                $data['total'] = $countRes->row()->total;
            endif;
        }
        $result = $this->banking_service_request_model->getAllBankingRequest($params);
        if ($result) {
            $data['banking_list'] = $result->result();
        }
        my_json_output($data);
    }

    public function processRequestById($id) {
        //$moduleCodes = $this->session->userdata('serviceRequestModules');
        //$moduleCodes = explode("|", $moduleCodes);
        //$index = array_search(banking_sr, $moduleCodes);
        // if ($index > -1) {



        $request = $this->banking_service_request_model->getSingleRequestById($id);

        $mailData['to'] = "";
        $mailData['body'] = "";
        $mailData['subject'] = $request['serviceName'];



        $strings['nomineeChange'] = "userName,accNo,currentNominee,newNominee,referenceNo,requestDtTm";
        $strings['newCheckBook'] = "userName,accNo,noOfLeaves,collectionMethod,referenceNo,requestDtTm";
        $strings['positivePay'] = "userName,accNo,payeeName,dtOfIssue,chequeNo,amount,referenceNo,requestDtTm";
        $strings['loan'] = "userName,accNo,referenceNo,requestDtTm,collectionMethod";
        $strings['statementCertificate'] = "userName,accNo,pFromDt,pToDt,collectionMethod,referenceNo,requestDtTm";


// cards related
        $strings['vcasEnrolment'] = "userName,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";
        $strings['productTypeChange'] = "userName,accCardNo,currentProductType,newProductType,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";
        $strings['standingInstruction'] = "userName,accCardNo,minFullDue,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";
        $strings['newCardCheque'] = "userName,accCardNo,noOfLeaves,collectionMethod,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";
        $strings['smsAlert'] = "userName,accCardNo,mobileNo,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";
        $strings['cardPinReplacement'] = "userName,accCardNo,replacement,reason,collectionMethod,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";
        $strings['FPenableDisable'] = "userName,accCardNo,foreignPart,pFromDt,pToDt,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";
        $strings['duplicateStatement'] = "userName,accCardNo,pFromDt,pToDt,collectionMethod,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";
        $strings['limitConversion'] = "userName,accCardNo,convertMyLimit,convAmnt,referenceNo,requestDtTm,eblSkyId,clientId,userMobNo1,userEmail";



        $replaceArray = array('skyId' => 'Sky ID',
            'userName' => 'User Name',
            'eblSkyId' => 'Apps User ID',
            'clientId' => 'Client ID',
            'userMobNo1' => 'KYC Mobile Number',
            'userEmail' => 'KYC Email Address',
            'referenceNo' => 'Reference Number',
            'customerId' => 'Customer ID',
            'accTitle' => 'Account Title',
            'accCardNo' => 'Card Number',
            'requestDtTm' => 'Request Date',
            'remarks' => 'Remarks',
            'currentNominee' => 'Current Nominee',
            'newNominee' => 'New Nominee',
            'noOfLeaves' => 'Number Of Leaves',
            'collectionMethod' => 'Collection Method',
            'payeeName' => 'Payee Name',
            'dtOfIssue' => 'Date Of Issue',
            'chequeNo' => 'Cheque No',
            'amount' => 'Amount',
            'currentProductType' => 'Current Product Type',
            'newProductType' => 'New Product Type',
            'minFullDue' => 'Minimum Full Due',
            'isMin' => 'Is Minimum',
            'accNo' => 'Account Number',
            'mobileNo' => 'Mobile Number',
            'replacement' => 'Replacement',
            'reason' => 'Reason',
            'pFromDt' => 'Period(From)',
            'pToDt' => 'Period(To)',
            'convertMyLimit' => 'Convert My Limit',
            'convAmnt' => 'Amount To Convert',
            'foreignPart' => 'Foreign Part',
            'serviceName' => 'Service Name');



        if ($request['typeCode'] == "0501") { // for nominee change under account category
            foreach ($request as $key => $value) {
                if (strpos($strings['nomineeChange'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        }


        if ($request['typeCode'] == "0601") { // for new cheque book under cheque category
            foreach ($request as $key => $value) {
                if (strpos($strings['newCheckBook'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0605") { // for positive pay under cheque category
            foreach ($request as $key => $value) {
                if (strpos($strings['positivePay'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0701" ||
                $request['typeCode'] == "0702" ||
                $request['typeCode'] == "0703" ||
                $request['typeCode'] == "0704" ||
                $request['typeCode'] == "0705") {   // for loan related banking request
            foreach ($request as $key => $value) {
                if (strpos($strings['loan'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        }

        // cards related 
        else if ($request['typeCode'] == "0806") { // vcas enrolement 
            foreach ($request as $key => $value) {
                if (strpos($strings['vcasEnrolment'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0801") { // for standing instruction and product type change of credit card
            foreach ($request as $key => $value) {
                if (strpos($strings['productTypeChange'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0802") { // new card cheque
            foreach ($request as $key => $value) {
                if (strpos($strings['newCardCheque'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0803") { // new card cheque
            foreach ($request as $key => $value) {
                if (strpos($strings['standingInstruction'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0804") { // Enrolement of SMS alert
            foreach ($request as $key => $value) {
                if (strpos($strings['smsAlert'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0805") { // Card or Pin Replacement
            foreach ($request as $key => $value) {
                if (strpos($strings['cardPinReplacement'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0807") {
            foreach ($request as $key => $value) {
                if (strpos($strings['FPenableDisable'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0808") {
            foreach ($request as $key => $value) {
                if (strpos($strings['duplicateStatement'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0809") {
            foreach ($request as $key => $value) {
                if (strpos($strings['limitConversion'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0901" ||
                $request['typeCode'] == "0902" ||
                $request['typeCode'] == "0903" ||
                $request['typeCode'] == "0904") {  // for statement and certificate (main category)
            foreach ($request as $key => $value) {
                if (strpos($strings['statementCertificate'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        }

        $mailData['serviceId'] = $id;

        $mailData["pageTitle"] = "Banking Service";
        $mailData["body_template"] = "banking_service_request/mail_form.php";
        $this->load->view('site_template.php', $mailData);

        //} else {
        // echo "not allowed";
        // die();
        // }
    }

    public function sendMail() {

        // $moduleCodes = $this->session->userdata('serviceRequestModules');
        // $moduleCodes = explode("|", $moduleCodes);
        //  $index = array_search(banking_sr, $moduleCodes);
        //  if ($index > -1) {

        $serviceId = $this->input->post("serviceId");
        $maildata["to"] = $this->input->post("to");
        //----new-----//

        $maildata['cc'] = $this->input->post("cc");
        $maildata['bcc'] = $this->input->post("bcc");
        //-------------//
        $maildata['subject'] = $this->input->post("subject");


        $bodyInstruction = $this->input->post("bodyInstruction");
        $maildata['body'] = $this->input->post("body") . "<br></br>" . $bodyInstruction;

        //$maildata['body'] = $_POST['body'];
        $isSuccess = $this->common_model->send_service_mail($maildata);

        if ($isSuccess) {
            $this->banking_service_request_model->statusChange($serviceId, $maildata, $bodyInstruction);
            redirect('banking_service_request/getRequests');
        }
        // } else {
        //    echo "not allowed";
        die();
        // }
    }

}
