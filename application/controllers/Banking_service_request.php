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
        $this->my_session->authorize("canViewBankingRequest");
        $moduleName = "Banking";
        $data['pServiceTypeCode'] = "0";
        $data["pageTitle"] = "Banking Service";
        $data['parentServiceList'] = $this->banking_service_request_model->getAllServiceTypeByModule($moduleName, $data['pServiceTypeCode']);        
        $data["body_template"] = "banking_service_request/show_banking_request.php";
        $this->load->view('site_template.php', $data);
    }

    function get_child_service() {
        $this->my_session->authorize("canViewBankingRequest");
        $typeId = $this->input->get("type_code", true);
        
        $result = $this->banking_service_request_model->getChildService($typeId);
        $data["child_list"] = array();
        if ($result) {
            $data["child_list"] = $result->result();
        }
        $data["success"] = true;
        $data['q'] = $this->db->last_query();
        my_json_output($data);
    }

    function get_requests_ajax() {
        $this->my_session->authorize("canViewBankingRequest");
        $params['limit'] = (int) $this->input->get("limit", true);
        $params['offset'] = (int) $this->input->get("offset", true);
        $params['get_count'] = (bool) $this->input->get("get_count", true);
        $params['type_code'] = $this->input->get("type_code", true);
        $params['serviceTypeCode'] = $this->input->get("serviceTypeCode", true);
                
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
        
        $data['q'] = log_last_query($data['q']);
        my_json_output($data);
    }

    public function processRequestById($id) {
        $this->my_session->authorize("canEmailBankingRequest");
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
        
        $strings['limitPackage'] = "userName,eblSkyId,userMobNo1,userEmail";

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
        } else if ($request['typeCode'] == "0805" 
                || in_array($request['typeCode'], array('PP01','PP02','PP03'))) { // Card or Pin Replacement
            foreach ($request as $key => $value) {
                if (strpos($strings['cardPinReplacement'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
        } else if ($request['typeCode'] == "0807"
                || in_array($request['typeCode'], array('PP01','PP02','PP03'))) {
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
        } else if ($request['typeCode'] == "0809"
                || in_array($request['typeCode'], array('PP01','PP02','PP03'))) {
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
        else if($request['typeCode'] == "lp"){
            foreach ($request as $key => $value) {
                if (strpos($strings['limitPackage'], $key) > - 1) {
                    $mailData['body'] .= "<p>" . $replaceArray[$key] . ':  ' . $value . "<p>";
                }
            }
            
            $this->db->reset_query();
            $aR = $this->db //->where("isActive", 1)
                           ->get('apps_users_group')->result();
            
            $ag = array();
            foreach($aR as $a):
                $ag[$a->appsGroupId] = $a;
            endforeach;
            
            $mailData['body'] .= '<p>Existing Limit Package: '.$ag[$request['userAppsGroupId']]->userGroupName.'</p>';
            $mailData['body'] .= '<p>New Limit Package: '.$ag[$request['appsGroupId']]->userGroupName.'</p>';            
        }
        else if($request['typeCode'] == "PP01" ||
                $request['typeCode'] == "PP02" ||
                $request['typeCode'] == "PP03"){
            
        }

        $mailData['serviceId'] = $id;
        $mailData["pageTitle"] = "Banking Service";
        $mailData["body_template"] = "banking_service_request/mail_form.php";
        $this->load->view('site_template.php', $mailData);
    }

    public function sendMail() {
        $this->my_session->authorize("canEmailBankingRequest");
        $serviceId = $this->input->post("serviceId");
        $maildata["to"] = $this->input->post("to");
        //----new-----//
        $maildata['cc'] = $this->input->post("cc");
        $maildata['bcc'] = $this->input->post("bcc");
        //-------------//
        $maildata['subject'] = $this->input->post("subject");

        $bodyInstruction = $this->input->post("bodyInstruction");
        $maildata['body'] = $this->input->post("body") . "<br></br>" . $bodyInstruction;

        $isSuccess = $this->common_model->send_service_mail($maildata);

        if ($isSuccess['success']) {
            $this->banking_service_request_model->statusChange($serviceId, $maildata, $bodyInstruction);
            redirect('banking_service_request/getRequests');
        }
        
        echo "Could not send email due to :: ".@$isSuccess['msg'];
        
    }
    
    function activate_limit_package()
    {
        $serviceId = (int)$this->input->post('service_id', true);
        
        $result = $this->banking_service_request_model->getSingleRequestById($serviceId);
        if(empty($result)):
            $json = array(
                'success' => false,
                'msg' => "No service request information found"
            );
            my_json_output($json);
        endif;
        
        $result = (object)$result;
        if((int)$result->status1 == 1):
            $json = array(
                'success' => false,
                'msg' => "Service request already processed"
            );
            my_json_output($json);
        endif;
        
        $response = $this->banking_service_request_model->setAppsUserGroup($result->skyId, $result->appsGroupId, $result->serviceId);
        if(!$response['success']){
            $json = array(
                'success' => false,
                'msg' => $response['msg'],
                'res' => $response
            );
            my_json_output($json);
        }
        
        $json = array(
            'success' => true
        );
        my_json_output($json);
    }

}
