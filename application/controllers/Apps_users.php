<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apps_users extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('push_to_cbs_service_library');
        $this->load->model('apps_users_model');
        $this->load->model('login_model');
        $this->load->model('common_model');
        
        $this->load->library('my_session');
        $this->my_session->checkSession();
    }

    function addAppsUser($selectedActionName = NULL) 
    {        
        $data['message'] = "";
        $data['selectedActionName'] = $selectedActionName;
        $data['body_template'] = 'apps_users/add_new_user.php';
        $this->load->view('site_template.php', $data);
            
    }

    function pullFromCbs()
    {
        $data['esbId'] = strtoupper($_POST['esbId']);
        $data['cfId'] = $_POST['cfId'];
        $data['clientId'] = $_POST['clientId'];
        $data['selectedActionName'] = $_POST['selectedActionName'];
        $esbcheck = $this->checkESBID($data);

        if ($esbcheck == 1) {
            $cfIdCheck = $this->apps_users_model->cfIdCheck($data['cfId']);

            if (empty($cfIdCheck)) {
                $reqTypeCode = "02";
                $data['accountNumber'] = "";
                $data['customerId'] = $data['cfId'];
                $result = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $data);
                $result = str_replace(array("\n", "\r", "\t"), '', $result);
                $xml = simplexml_load_string($result);

                if ($xml->ISSUCCESS == "Y") {
                    $userInfo['dob'] = (string) $xml->CUSTPERSONAL->DOB;
                    $userInfo['sex'] = (string) $xml->CUSTPERSONAL->SEX;
                    $userInfo['fatherName'] = (string) $xml->CUSTPERSONAL->FATHER_NAME;
                    $userInfo['motherName'] = (string) $xml->CUSTPERSONAL->MOTHER_NAME;
                    $userInfo['userMobNo1'] = (string) $xml->CUSTPERSONAL->MOB1;
                    $userInfo['userMobNo2'] = (string) $xml->CUSTPERSONAL->MOB2;
                    $userInfo['userName'] = (string) $xml->CUSTACCTSUMMARY->CUSTSUMMARY->ACCTDESC;
                    $userInfo['userEmail'] = (string) $xml->CUSTPERSONAL->EMAIL;
                    $userInfo['currAddress'] = (string) $xml->CUSTPERSONAL->CURRADDR;
                    $userInfo['parmAddress'] = (string) $xml->CUSTPERSONAL->PERMADDR;
                    $userInfo['billingAddress'] = (string) $xml->CUSTPERSONAL->MAILADDR;

                    $userInfo['homeBranchCode'] = (string) $xml->CUSTPERSONAL->HOMEBRANCHCODE;
                    $userInfo['homeBranchName'] = getBranchName($userInfo['homeBranchCode']);


                    foreach ($xml->CUSTACCTSUMMARY->CUSTSUMMARY as $item) {
                        $xmlToArray[] = array('accountNo' => (string) $item->IDACCOUNT,
                            'accountType' => (string) $item->ACCTTYPE,
                            'productName' => (string) $item->PRDNAME,
                            'accountCurrency' => (string) $item->CODACCTCURR);
                    }

                    $userInfo['accountInfo'] = json_encode($xmlToArray);

                    $userInfo['esbId'] = $data['esbId'];
                    $userInfo['cfId'] = $data['cfId'];
                    $userInfo['clientId'] = $data['clientId'];
                    $userInfo['selectedActionName'] = $data['selectedActionName'];

                    if (!empty($data['clientId'])) {
                        $clientIdCheck = $this->apps_users_model->clientIdCheck($data['clientId']);

                        if (empty($clientIdCheck)) {
                            $reqTypeCode = "10";
                            $cardsData['methodName'] = "CARD_ACCOUNT_DETAILS";
                            $cardsData['clientId'] = $data['clientId'];
                            $cardsData['cardNumber'] = "NULL";
                            $cardsData['cardCurrency'] = "NULL";
                            $accDetail = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
                            $accDetail = str_replace(array("\n", "\r", "\t"), '', $accDetail);
                            $accDetail = simplexml_load_string($accDetail);

                            if (((string) $accDetail->ISSUCCESS == "Y") && (!empty($accDetail->EBLCRD_OUTPUT))) {
                                $reqTypeCode = "10";
                                $cardsData['methodName'] = "CARD_INFORMATION_DETAILS";
                                $cardsData['clientId'] = $data['clientId'];
                                $cardsData['cardNumber'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER;
                                $cardsData['cardCurrency'] = "NULL";
                                $cardInfoDetail = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
                                $cardInfoDetail = str_replace(array("\n", "\r", "\t"), '', $cardInfoDetail);
                                $cardInfoDetail = simplexml_load_string($cardInfoDetail);


                                if ((string) $cardInfoDetail->ISSUCCESS == "Y") {
                                    $userInfo['clientNumber'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
                                    //$userInfo['cardNumber'] = $this->common_model->numberMasking(MASK, (string)$accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
                                    //$userInfo['cardType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
                                    //$userInfo['cardProductType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
                                    //$userInfo['cardStatus'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
                                    $userInfo['issupplementary'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
                                    $userInfo['primaryCardNumber'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
                                    $userInfo['userNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
                                    $userInfo['expiryDate'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
                                    $userInfo['clientBillingAddress'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
                                    $userInfo['dobCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DOB;
                                    $userInfo['mothersNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;

                                    /* new add for multi card */
                                    $itemCount = count($accDetail->EBLCRD_OUTPUT->ITEM);
                                    for ($w = 0; $w < $itemCount; $w++) {
                                        $cardNo = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CARDNUMBER;
                                        $cardItem['cardNumber'] = $this->common_model->numberMasking(MASK, $cardNo);
                                        $cardItem['cardType'] = checkCardTypes($cardNo);
                                        $cardItem['cardStatus'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CARDSTATUS;
                                        $cardItem['cardCurrency'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CURRENCYCODE;
                                        $multiCardItem[] = $cardItem;
                                    }
                                    $userInfo['multiCard'] = $multiCardItem;
                                    /* new add for multi card */

                                    $userInfo['cardsModeOfDisplay'] = "display: block;";
                                    $this->load->view('apps_users/import_account_view.php', $userInfo);
                                } else {
                                    $data['message'] = (string) $cardInfoDetail->WARNING;
                                    $this->load->view('apps_users/add_new_user.php', $data);
                                }
                            } else if ((string) $accDetail->ISSUCCESS == "N") {
                                $data['message'] = (string) $accDetail->WARNING;
                                $this->load->view('apps_users/add_new_user.php', $data);
                            } else {
                                $data['message'] = "No result found against this client ID";
                                $this->load->view('apps_users/add_new_user.php', $data);
                            }
                        } else {
                            $data['message'] = 'The client ID "' . $data['clientId'] . '" is already registered
                                                <br> with ESB ID "' . $clientIdCheck['eblSkyId'] . '" ';
                            $this->load->view('apps_users/add_new_user.php', $data);
                        }
                    } else {
                        $userInfo['cardsModeOfDisplay'] = "display: none;";
                        $this->load->view('apps_users/import_account_view.php', $userInfo);
                    }
                } else {
                    $data['message'] = (string) $xml->WARNING;
                    $this->load->view('apps_users/add_new_user.php', $data);
                }
            } else {
                $data['message'] = 'The CFID "' . $data['cfId'] . '" is already registered
                                    <br> with ESB ID "' . $cfIdCheck['eblSkyId'] . '" ';
                $this->load->view('apps_users/add_new_user.php', $data);
            }
        }
            
    }

    public function insertUserInfo() {
        $userInfo['eblSkyId'] = $_POST['esbid'];
        $userInfo['cfId'] = $_POST['cfid'];

        // if(!empty($_POST['clientId'])){
        //     $userInfo['clientId'] = $_POST['clientId'];
        // }else {
        //     $userInfo['clientId'] = 0;
        // }

        $userInfo['clientId'] = $_POST['clientId'];

        $data['esbId'] = $userInfo['eblSkyId'];
        $data['selectedActionName'] = $_POST['selectedActionName'];

        $esbcheck = $this->checkESBID($data);

        if ($esbcheck == 1) {

            // get the pin number form the generate_eblSkyId table
            // one way encryption need to implement here. get the encrypted pin from pin table and after one way encryption insert into apps user table
            $pinNumber = $this->apps_users_model->getPin($userInfo['eblSkyId']);
            $pin = $this->login_model->decryptPin($pinNumber['pin']);
            $userInfo['passWord'] = md5($pin);

            // general info 
            $userInfo['gender'] = $_POST['sex'];
            $userInfo['dob'] = date('Y-m-d', strtotime($_POST['dob'])); // cbs data example: "01-JUL-75" converted to "Y-m-d"
            $userInfo['userName'] = $_POST['username'];
            $userInfo['userEmail'] = $_POST['email'];
            $userInfo['fatherName'] = $_POST['fatherName'];
            $userInfo['motherName'] = $_POST['motherName'];
            $userInfo['userMobNo1'] = $_POST['mob1'];
            $userInfo['userMobNo2'] = $_POST['mob2'];
            $userInfo['currAddress'] = $_POST['currAddress'];
            $userInfo['parmAddress'] = $_POST['parmAddress'];
            $userInfo['billingAddress'] = $_POST['billingAddress'];

            $userInfo['homeBranchCode'] = $_POST['homeBranchCode'];


            // account informations
            $userInfo['accountNo'] = $_POST['accNo'];
            $userInfo['accountType'] = $_POST['accType'];
            $userInfo['accountName'] = $_POST['productName'];
            $userInfo['accountCurrency'] = $_POST['accCurrency'];
            $accountTypes = explode("|", $userInfo['accountType']);
            array_shift($accountTypes);
            $accountIsLocked = "";
            foreach ($accountTypes as $item) {
                $accountIsLocked = $accountIsLocked . "|" . 1;
            }
            $userInfo['accountIsLocked'] = $accountIsLocked;

            // get the default group as default setting for first time
            $userInfo['appsGroupId'] = 1;

            // Maker checker portion ****
            $userInfo['mcStatus'] = 0;
            $userInfo['makerAction'] = $_POST['selectedActionName'];
            $userInfo['makerActionCode'] = 'add';
            $userInfo['makerActionDt'] = date("y-m-d");
            $userInfo['makerActionTm'] = date("G:i:s");
            $userInfo['makerActionBy'] = $this->session->userdata('adminUserId');
            $userInfo['isLocked'] = 0;
            $userInfo['isPublished'] = 0;
            $userInfo['isActive'] = 1;
            $userInfo['createdBy'] = $this->session->userdata('adminUserId');
            $userInfo['createdDtTm'] = input_date();

            $insertedId = $this->apps_users_model->insertUserInfo($userInfo); // user info + account info
            $this->apps_users_model->UpdateSkyId($userInfo['eblSkyId']); // make the ESB id used
            // update date time of generate_eblskyid table will be in the apps user table

            $this->db->where('eblSkyId', $userInfo['eblSkyId']);
            $this->db->update('apps_users_mc', array('pinExpiryReferenceTm' => date("Y-m-d G:i:s")));

            echo $insertedId;
        } else {
            echo 0;
        }
    }

    // user group selection
    public function userGroupSelection()
    {
        $data = $_GET['skyId'];
        $action = $_GET['action'];
        //$this->output->set_template('theme2');


        if ($action == "edit") {
            $viewData['message'] = "User Successfully Updated.
                                <br> Now Assign Group For User or Cancle To Keep The Current Setting.";
        } else {
            $viewData['message'] = "User Successfully Created with Default Group Setting.
                                <br> Now Assign Group For User or Cancle Keep Default Setting.";
        }


        $tableData = $this->apps_users_model->getUserById($data);
        $groupData = $this->apps_users_model->getGroupById($tableData['appsGroupId']);


        if ($groupData['oatMinTxnLim'] > 0) {
            $array[] = array('packageId' => 1,
                'packageName' => "Own Account Transfer");
        }
        if ($groupData['eatMinTxnLim'] > 0) {
            $array[] = array('packageId' => 2,
                'packageName' => "EBL Account Transfer");
        }
        if ($groupData['obtMinTxnLim'] > 0) {
            $array[] = array('packageId' => 3,
                'packageName' => "Other Bank Transfer");
        }
        if ($groupData['pbMinTxnLim'] > 0) {
            $array[] = array('packageId' => 4,
                'packageName' => "Bills Pay");
        }


        $viewData['skyId'] = $data;
        $viewData['userGroup'] = $this->apps_users_model->getAllGroups();
        $viewData['packages'] = json_encode($array);
        $viewData['group'] = json_encode($groupData);
        
        $viewData['body_template'] = 'apps_users/user_group.php';
        $this->load->view('site_template.php', $viewData);
    }

    // user group assign
    public function assignUserGroup() {
        $groupId = $_POST['group'];
        $skyId = $_POST['skyId'];
        $action = $_POST['action'];

        $groupData = $this->apps_users_model->getGroupById($groupId);

        if ($action == "b") {
            $dateTime = date("Y-m-d G:i:s");
            $userInfo['appsGroupId'] = $groupData['appsGroupId'];
            $this->db->where('skyId', $skyId);
            $this->db->update('apps_users_mc', $userInfo);
            redirect('client_registration');
        } else if ($action == "d") {
            $this->output->set_template('theme2');

            if ($groupData['oatMinTxnLim'] > 0) {
                $array[] = array('packageId' => 1,
                    'packageName' => "Own Account Transfer");
            }
            if ($groupData['eatMinTxnLim'] > 0) {
                $array[] = array('packageId' => 2,
                    'packageName' => "EBL Account Transfer");
            }
            if ($groupData['obtMinTxnLim'] > 0) {
                $array[] = array('packageId' => 3,
                    'packageName' => "Other Bank Transfer");
            }
            if ($groupData['pbMinTxnLim'] > 0) {
                $array[] = array('packageId' => 4,
                    'packageName' => "Bills Pay");
            }

            $viewData['userGroup'] = $this->apps_users_model->getAllGroups();
            $viewData['skyId'] = $skyId;

            $viewData['message'] = "User Successfully Created with Default Group Setting.
                                <br> Now Assign Group For User or Cancle Keep Default Setting.";
            $viewData['packages'] = json_encode($array);
            $viewData['group'] = json_encode($groupData);
            $this->load->view('apps_users/user_group.php', $viewData);
        }
    }

    public function editAppsUser() 
    {
        $data['eblSkyId'] = $_GET['eblSkyId'];
        $data['cfId'] = $_GET['cfId'];
        $data['clientId'] = $_GET['clientId'];
        $data['skyId'] = $_GET['skyId'];
        $data['selectedActionName'] = $_GET['selectedActionName'];
        $data['message'] = "";
        
        $data['pageTitle'] = "Edit Apps User";
        
        $data['body_template'] = 'apps_users/edit_user.php';
        $this->load->view('site_template.php', $data);
    }
    
    // User edit start
    public function pullFromCbsEdit() 
    {        
        
        $data['eblSkyId'] = $this->input->post('eblSkyId');
        $data['cfId'] = $this->input->post('cfId');
        $data['clientId'] = $this->input->post('clientId');
        $data['skyId'] = $this->input->post('skyId');
        $data['selectedActionName'] = $this->input->post('selectedActionName');

        
        //d($_POST);

        $reqTypeCode = "02";
        $data['accountNumber'] = "";
        $data['customerId'] = $data['cfId'];
        $result = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $data);
        $result = str_replace(array("\n", "\r", "\t"), '', $result);
        $xml = simplexml_load_string($result);

        if($xml->ISSUCCESS != "Y"){
            $data['message'] = (string) $xml->WARNING;
            $this->load->view('apps_users/edit_user.php', $data);
            die();
        }
                
        $userInfo['dob'] = (string) $xml->CUSTPERSONAL->DOB;
        $userInfo['sex'] = (string) $xml->CUSTPERSONAL->SEX;
        $userInfo['fatherName'] = (string) $xml->CUSTPERSONAL->FATHER_NAME;
        $userInfo['motherName'] = (string) $xml->CUSTPERSONAL->MOTHER_NAME;
        $userInfo['userMobNo1'] = (string) $xml->CUSTPERSONAL->MOB1;
        $userInfo['userMobNo2'] = (string) $xml->CUSTPERSONAL->MOB2;
        $userInfo['userName'] = (string) $xml->CUSTACCTSUMMARY->CUSTSUMMARY->ACCTDESC;
        $userInfo['userEmail'] = (string) $xml->CUSTPERSONAL->EMAIL;
        $userInfo['currAddress'] = (string) $xml->CUSTPERSONAL->CURRADDR;
        $userInfo['parmAddress'] = (string) $xml->CUSTPERSONAL->PERMADDR;
        $userInfo['billingAddress'] = (string) $xml->CUSTPERSONAL->MAILADDR;

        $userInfo['homeBranchCode'] = (string) $xml->CUSTPERSONAL->HOMEBRANCHCODE;
        $userInfo['homeBranchName'] = getBranchName($userInfo['homeBranchCode']);

        $id['eblSkyId'] = $data['eblSkyId'];
        $existingAccounts = $this->apps_users_model->getAppsUser($id);

        $userInfo['checkerActionComment'] = $existingAccounts['checkerActionComment'];

        if (!empty($userInfo['checkerActionComment'])) {
            $userInfo['reasonModeOfDisplay'] = "display: block;";
        } else {
            $userInfo['reasonModeOfDisplay'] = "display: none;";
        }

        $cbsAccounts = array();
        foreach ($xml->CUSTACCTSUMMARY->CUSTSUMMARY as $item) {
            $cbsAccountsItem = array(
                    'accNo' => (string) $item->IDACCOUNT,
                    'accType' => (string) $item->ACCTTYPE,
                    'accName' => (string) $item->PRDNAME,
                    'accCurrency' => (string) $item->CODACCTCURR
            );
            
            if (strpos($existingAccounts['accountNo'], (string) $item->IDACCOUNT) > -1) {
                $cbsAccountsItem['isExist'] = 1;
            } else {
                $cbsAccountsItem['isExist'] = 0;
            }
            
            $cbsAccounts[] = $cbsAccountsItem;
        }
        
        $userInfo['accountInfo'] = json_encode($cbsAccounts);
        $userInfo['esbId'] = $data['eblSkyId'];
        $userInfo['cfId'] = $data['cfId'];
        $userInfo['clientId'] = $data['clientId'];
        $userInfo['skyId'] = $data['skyId'];
        $userInfo['selectedActionName'] = $data['selectedActionName'];

        $userInfo['cardsModeOfDisplay'] = "display: block;";
        if(trim($data['clientId']) == ""){
            $userInfo['cardsModeOfDisplay'] = "display: none;";
            $userInfo['body_template'] = 'apps_users/edit_account_view.php';
            $this->load->view('site_template.php', $userInfo);
            die();
        }
                
        $clientIdCheckEdit = $this->apps_users_model->clientIdCheckEdit($data);
        if(!empty($clientIdCheckEdit)):
            $data['message'] = 'The client ID "' . $data['clientId'] . '" is already registered
                                    <br> with ESB ID "' . $clientIdCheckEdit[0]['eblSkyId'] . '" ';
            $data['body_template'] = 'apps_users/edit_user.php';
            $this->load->view('site_template.php', $data);
        endif;
        
        // pull card information //
        $reqTypeCode = "10";
        $cardsData['methodName'] = "CARD_ACCOUNT_DETAILS";
        $cardsData['clientId'] = $data['clientId'];
        $cardsData['cardNumber'] = "NULL";
        $cardsData['cardCurrency'] = "NULL";
        $accDetail = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
        $accDetail = str_replace(array("\n", "\r", "\t"), '', $accDetail);
        $accDetail = simplexml_load_string($accDetail);

        if ((string) $accDetail->ISSUCCESS == "N") 
        {
            $data['message'] = (string) $accDetail->WARNING;
            
            $data['body_template'] = 'apps_users/edit_user.php';
            $this->load->view('site_template.php', $data);
            die();
        }
        else if((string) $accDetail->ISSUCCESS != "Y" || empty($accDetail->EBLCRD_OUTPUT)) {
            $data['message'] = "No result found against this client ID";
            $data['body_template'] = 'apps_users/edit_user.php';
            $this->load->view('site_template.php', $data);
            die();
        }
        
        //if (((string) $accDetail->ISSUCCESS == "Y") && (!empty($accDetail->EBLCRD_OUTPUT)))
        
        $reqTypeCode = "10";
        $cardsData['methodName'] = "CARD_INFORMATION_DETAILS";
        $cardsData['clientId'] = $data['clientId'];
        $cardsData['cardNumber'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER;
        $cardsData['cardCurrency'] = "NULL";
        $cardInfoDetail = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
        $cardInfoDetail = str_replace(array("\n", "\r", "\t"), '', $cardInfoDetail);
        $cardInfoDetail = simplexml_load_string($cardInfoDetail);


        if ((string) $cardInfoDetail->ISSUCCESS != "Y") 
        {
            $data['message'] = (string) $cardInfoDetail->WARNING;
            $data['body_template'] = 'apps_users/edit_user.php';
            $this->load->view('site_template.php', $data);
            die();
        }  
        
        
        // cards data //
        $userInfo['clientNumber'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
        //$userInfo['cardNumber'] = $this->common_model->numberMasking(MASK, (string)$accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
        //$userInfo['cardType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
        //$userInfo['cardProductType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
        //$userInfo['cardStatus'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
        $userInfo['issupplementary'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
        $userInfo['primaryCardNumber'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
        $userInfo['userNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
        $userInfo['expiryDate'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
        $userInfo['clientBillingAddress'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
        $userInfo['dobCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DOB;
        $userInfo['mothersNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;


        $itemCount = count($accDetail->EBLCRD_OUTPUT->ITEM);
        for ($w = 0; $w < $itemCount; $w++) {
            $cardNo = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CARDNUMBER;
            $cardItem['cardNumber'] = $this->common_model->numberMasking(MASK, $cardNo);
            $cardItem['cardType'] = checkCardTypes($cardNo);
            $cardItem['cardStatus'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CARDSTATUS;
            $cardItem['cardCurrency'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CURRENCYCODE;
            $multiCardItem[] = $cardItem;
        }
        $userInfo['multiCard'] = $multiCardItem;
        $userInfo['cardsModeOfDisplay'] = "display: block;";
        $userInfo['body_template'] = 'apps_users/edit_account_view.php';
        $this->load->view('site_template.php', $userInfo);
        
                           
    }

    // apps user update
    public function updateAppsUser() {
        $userInfo['skyId'] = $_POST['skyId'];

        // $userInfo['cfId'] = $_POST['cfid'];
        // if(!empty($_POST['clientId'])){
        //     $userInfo['clientId'] = $_POST['clientId'];
        // }else {
        //     $userInfo['clientId'] = 0;
        // }

        $userInfo['clientId'] = $_POST['clientId'];


        // general info 
        $userInfo['gender'] = $_POST['sex'];
        $userInfo['dob'] = date('Y-m-d', strtotime($_POST['dob'])); // cbs data example: "01-JUL-75" converted to "Y-m-d"
        $userInfo['userName'] = $_POST['username'];
        $userInfo['userEmail'] = $_POST['email'];
        $userInfo['fatherName'] = $_POST['fatherName'];
        $userInfo['motherName'] = $_POST['motherName'];
        $userInfo['userMobNo1'] = $_POST['mob1'];
        $userInfo['userMobNo2'] = $_POST['mob2'];
        $userInfo['currAddress'] = $_POST['currAddress'];
        $userInfo['parmAddress'] = $_POST['parmAddress'];
        $userInfo['billingAddress'] = $_POST['billingAddress'];

        $userInfo['homeBranchCode'] = $_POST['homeBranchCode'];

        // account informations
        $userInfo['accountNo'] = $_POST['accNo'];
        $userInfo['accountType'] = $_POST['accType'];
        $userInfo['accountName'] = $_POST['productName'];
        $userInfo['accountCurrency'] = $_POST['accCurrency'];
        $accountTypes = explode("|", $userInfo['accountType']);
        array_shift($accountTypes);
        $accountIsLocked = "";
        foreach ($accountTypes as $item) {
            $accountIsLocked = $accountIsLocked . "|" . 1;
        }
        $userInfo['accountIsLocked'] = $accountIsLocked;


        // Maker checker portion ****

        $userInfo['mcStatus'] = 0;
        $userInfo['makerAction'] = $_POST['selectedActionName'];
        $userInfo['makerActionCode'] = 'edit';
        $userInfo['makerActionDt'] = date("y-m-d");
        $userInfo['makerActionTm'] = date("G:i:s");
        $userInfo['makerActionBy'] = $this->my_session->userId; //$this->session->userdata('adminUserId');

        $this->apps_users_model->updateUserInfo($userInfo); // user info + account info
        echo $userInfo['skyId'];
    }

    // function to check ESB ID
    public function checkESBID($data) {
        $this->db->from('generate_eblskyid');
        $this->db->where('eblSkyId', $data['esbId']);
        $query = $this->db->get();
        $row = $query->row_array();

        $message['selectedActionName'] = $data['selectedActionName'];

        if ($row) {
            if ($row['isActive'] == 0) {
                $message['message'] = 'The ESB ID "' . $data['esbId'] . '" is Destroyed';
                $this->load->view('apps_users/add_new_user.php', $message);
                return 0;
            } else if ($row['isUsed'] == 1) {
                $message['message'] = 'The ESB ID "' . $data['esbId'] . '" is Already Used';
                $this->load->view('apps_users/add_new_user.php', $message);
                return 0;
            } else if ($row['isPrinted'] == 0) {
                $message['message'] = 'The ESB ID "' . $data['esbId'] . '" is Not Printed Yet.';
                $this->load->view('apps_users/add_new_user.php', $message);
                return 0;
            } else {
                return 1;
            }
        } else {
            $message['message'] = 'The ESB ID "' . $data['esbId'] . '" is not Valid';
            $this->load->view('apps_users/add_new_user.php', $message);
            return 0;
        }
    }

}
