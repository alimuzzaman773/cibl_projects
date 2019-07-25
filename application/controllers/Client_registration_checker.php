<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client_registration_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('client_registration_model_checker', 'common_model', 'login_model'));
        $this->load->library('push_to_cbs_service_library');
    }

    public function index() {
        $this->my_session->authorize("canViewAppsUserAuthorization");
        $data['appsUsers'] = json_encode($this->client_registration_model_checker->getUnapprovedAppsUsers());
        $data["pageTitle"] = "Device Iinformation Checker";
        $data["body_template"] = "client_registration_checker/unapproved_apps_users_view.php";
        $this->load->view('site_template.php', $data);
    }

    public function get_unapproved_users_list() {
        $this->my_session->authorize("canViewAppsUserAuthorization");
        $params['limit'] = (int) $this->input->get("limit", true);
        $params['offset'] = (int) $this->input->get("offset", true);
        $params['get_count'] = (bool) $this->input->get("get_count", true);
        $params['search'] = $this->input->get("search", true);
        $params['isLocked'] = $this->input->get("isLocked", true);
        $params['isActive'] = $this->input->get("isActive", true);

        $data['total'] = array();
        $data['list'] = array();

        if ((int) $params['get_count'] > 0) {
            $countParams = $params;
            unset($countParams['limit']);
            unset($countParams['offset']);
            $countParams['count'] = true;
            $countRes = $this->client_registration_model_checker->getUnapprovedAppsUsers($countParams);
            if ($countRes):
                $data['total'] = $countRes->num_rows();
            endif;
        }
        $result = $this->client_registration_model_checker->getUnapprovedAppsUsers($params);
        if ($result) {
            $data['list'] = $result->result();
        }

        $data['q'] = log_last_query($data['q']);
        my_json_output($data);
    }

    public function getAppsUserForApproval($id) {
        $this->my_session->authorize("canApproveAppsUser");
        $data['multiCard'] = array();
        $data['multiCard_c'] = array();

        $data['clientNumber_c'] = "";
        $data['cardNumber_c'] = "";
        $data['cardType_c'] = "";
        $data['cardProductType_c'] = "";
        $data['cardStatus_c'] = "";
        $data['issupplementary_c'] = "";
        $data['primaryCardNumber_c'] = "";
        $data['userNameCard_c'] = "";
        $data['expiryDate_c'] = "";
        $data['clientBillingAddress_c'] = "";
        $data['dobCard_c'] = "";
        $data['mothersNameCard_c'] = "";

        $data['clientNumber'] = "";
        $data['cardNumber'] = "";
        $data['cardType'] = "";
        $data['cardProductType'] = "";
        $data['cardStatus'] = "";
        $data['issupplementary'] = "";
        $data['primaryCardNumber'] = "";
        $data['userNameCard'] = "";
        $data['expiryDate'] = "";
        $data['clientBillingAddress'] = "";
        $data['dobCard'] = "";
        $data['mothersNameCard'] = "";

        $dbData = $this->client_registration_model_checker->getAppsUserById($id);

        $data['makerActionDtTm'] = $dbData['makerActionDt'] . " " . $dbData['makerActionTm'];
        $data['checkerActionDtTm'] = $dbData['checkerActionDt'] . " " . $dbData['checkerActionTm'];
        if ($data['checkerActionDtTm'] == " ") {
            $data['checkerActionDtTm'] = "";
        }

        $data['makerActionDtTm_c'] = $dbData['makerActionDt_c'] . " " . $dbData['makerActionTm_c'];
        $data['checkerActionDtTm_c'] = $dbData['checkerActionDt_c'] . " " . $dbData['checkerActionTm_c'];
        if ($data['checkerActionDtTm_c'] == " ") {
            $data['checkerActionDtTm_c'] = "";
        }

        // reason for changed data
        $data['checkerActionComment'] = $dbData['checkerActionComment'];
        if ($data['checkerActionComment'] != NULL) {
            $data['reasonModeOfDisplay'] = "display: block;";
        } else {
            $data['reasonModeOfDisplay'] = "display: none;";
        }

        // reason for published data
        $data['checkerActionComment_c'] = $dbData['checkerActionComment_c'];
        if ($data['checkerActionComment_c'] != NULL) {
            $data['reasonModeOfDisplay_c'] = "display: block;";
        } else {
            $data['reasonModeOfDisplay_c'] = "display: none;";
        }

        if ($dbData['skyId_c'] != NULL) {
            $data['publishDataModeOfDisplay_c'] = "display: block;";
        } else {
            $data['publishDataModeOfDisplay_c'] = "display: none;";
        }

        $data['homeBranchName'] = getBranchName($dbData['homeBranchCode']);
        $data['homeBranchName_c'] = getBranchName($dbData['homeBranchCode_c']);

        $data['isActive'] = "";
        $data['isActive_c'] = "";

        $data['isLocked'] = "";
        $data['isLocked_c'] = "";

        // active & inactive
        if ($dbData['isActive'] == "1") {
            $data['isActive'] = "Active";
        } else if ($dbData['isActive'] == "0") {
            $data['isActive'] = "Inactive";
        }

        if ($dbData['isActive_c'] == "1") {
            $data['isActive_c'] = "Active";
        } else if ($dbData['isActive_c'] == "0") {
            $data['isActive_c'] = "Inactive";
        } else {
            $data['isActive_c'] = "";
        }

        // lock & unlock
        if ($dbData['isLocked'] == "1") {
            $data['isLocked'] = "Locked";
        } else if ($dbData['isLocked'] == "0") {
            $data['isLocked'] = "Unlocked";
        }

        if ($dbData['isLocked_c'] == "1") {
            $data['isLocked_c'] = "Locked";
        } else if ($dbData['isLocked_c'] == "0") {
            $data['isLocked_c'] = "Unlocked";
        } else {
            $data['isLocked_c'] = "";
        }

        $data['appsUser'] = $dbData;

        // For delete account
        $dataDelete = json_decode($dbData['dataDelete'], true);
        $dbData['account_delete'] = (!empty($dataDelete['account_delete']) ? $dataDelete['account_delete'] : null);

        //d($data['account_delete']);
        $data['accountInfo'] = json_encode($this->login_model->checkAccount($dbData));

        if ($dbData['skyId_c'] != NULL) {
            //$approvedAccounts = $this->client_registration_model_checker->getApprovedAccountsById($id);
            //$data['accountInfo_c'] = json_encode($this->login_model->checkAccount($approvedAccounts));
            $data['accountInfo_c'] = json_encode($this->login_model->checkAccount(array('skyId' => $id)));
        } else {
            $data['accountInfo_c'] = json_encode((object) null);
        }
        
        //d($dbData);

//        $reqTypeCode = "10";
//
//        if (!empty($dbData['clientId'])) {
//            $cardsData['methodName'] = "CARD_ACCOUNT_DETAILS";
//            $cardsData['clientId'] = $dbData['clientId'];
//            $cardsData['cardNumber'] = "NULL";
//            $cardsData['cardCurrency'] = "NULL";
//            $accDetail = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
//            $accDetail = str_replace(array("\n", "\r", "\t"), '', $accDetail);
//            $accDetail = simplexml_load_string($accDetail);
//
//            if ((string) $accDetail->ISSUCCESS == "Y") {
//                $cardsData['methodName'] = "CARD_INFORMATION_DETAILS";
//                $cardsData['clientId'] = $dbData['clientId'];
//                $cardsData['cardNumber'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER;
//                $cardsData['cardCurrency'] = "NULL";
//                $cardInfoDetail = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
//                $cardInfoDetail = str_replace(array("\n", "\r", "\t"), '', $cardInfoDetail);
//                $cardInfoDetail = simplexml_load_string($cardInfoDetail);
//            }
//        }
//
//
//
//        if (!empty($dbData['clientId_c'])) {
//            $cardsData['methodName'] = "CARD_ACCOUNT_DETAILS";
//            $cardsData['clientId'] = $dbData['clientId_c'];
//            $cardsData['cardNumber'] = "NULL";
//            $cardsData['cardCurrency'] = "NULL";
//            $accDetail_c = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
//            $accDetail_c = str_replace(array("\n", "\r", "\t"), '', $accDetail_c);
//            $accDetail_c = simplexml_load_string($accDetail_c);
//
//            if ((string) $accDetail_c->ISSUCCESS == "Y") {
//                $cardsData['methodName'] = "CARD_INFORMATION_DETAILS";
//                $cardsData['clientId'] = $dbData['clientId_c'];
//                $cardsData['cardNumber'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER;
//                $cardsData['cardCurrency'] = "NULL";
//                $cardInfoDetail_c = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
//                $cardInfoDetail_c = str_replace(array("\n", "\r", "\t"), '', $cardInfoDetail_c);
//                $cardInfoDetail_c = simplexml_load_string($cardInfoDetail_c);
//            }
//        }
//
//
//        if (empty($dbData['clientId']) && !empty($dbData['clientId_c'])) {
//            $data['message'] = "Client ID is empty";
//            if ((string) $accDetail_c->ISSUCCESS == "Y") {
//                if ((string) $cardInfoDetail_c->ISSUCCESS == "Y") {
//                    //$data['clientNumber_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
//                    //$data['cardNumber_c'] = $this->common_model->numberMasking(MASK, (string)$accDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
//                    //$data['cardType_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
//                    //$data['cardProductType_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
//                    //$data['cardStatus_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
//                    $data['issupplementary_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
//                    $data['primaryCardNumber_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
//                    $data['userNameCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
//                    $data['expiryDate_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
//                    $data['clientBillingAddress_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
//                    $data['dobCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->DOB;
//                    $data['mothersNameCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;
//
//                    /* new add for multi card */
//                    $itemCount_c = count($accDetail_c->EBLCRD_OUTPUT->ITEM);
//                    for ($w = 0; $w < $itemCount_c; $w++) {
//                        $cardNo = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CARDNUMBER;
//                        $cardItem_c['cardNumber'] = $this->common_model->numberMasking(MASK, $cardNo);
//                        $cardItem_c['cardType'] = checkCardTypes($cardNo);
//                        $cardItem_c['cardStatus'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CARDSTATUS;
//                        $cardItem_c['cardCurrency'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CURRENCYCODE;
//                        $multiCardItem_c[] = $cardItem_c;
//                    }
//                    $data['multiCard_c'] = $multiCardItem_c;
//                    /* new add for multi card */
//
//                    $data['cardsModeOfDisplay'] = "display: none;";
//                    $data['cardsModeOfDisplay_c'] = "display: block;";
//                    $data['message_c'] = "";
//                } else {
//                    $data['cardsModeOfDisplay'] = "display: none;";
//                    $data['cardsModeOfDisplay_c'] = "display: none;";
//                    $data['message_c'] = (string) $cardInfoDetail_c->WARNING;
//                }
//            } else {
//                $data['cardsModeOfDisplay'] = "display: none;";
//                $data['cardsModeOfDisplay_c'] = "display: none;";
//                $data['message_c'] = (string) $accDetail_c->WARNING;
//            }
//        }
//
//
//
//        if (!empty($dbData['clientId']) && empty($dbData['clientId_c'])) {
//            $data['message_c'] = "Client ID is empty";
//            if ((string) $accDetail->ISSUCCESS == "Y") {
//                if ((string) $cardInfoDetail->ISSUCCESS == "Y") {
//                    //$data['clientNumber'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
//                    //$data['cardNumber'] = $this->common_model->numberMasking(MASK, (string)$accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
//                    //$data['cardType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
//                    //$data['cardProductType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
//                    //$data['cardStatus'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
//                    $data['issupplementary'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
//                    $data['primaryCardNumber'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
//                    $data['userNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
//                    $data['expiryDate'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
//                    $data['clientBillingAddress'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
//                    $data['dobCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DOB;
//                    $data['mothersNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;
//
//
//                    /* new add for multi card */
//                    $itemCount = count($accDetail->EBLCRD_OUTPUT->ITEM);
//                    for ($w = 0; $w < $itemCount; $w++) {
//                        $cardNo = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CARDNUMBER;
//                        $cardItem['cardNumber'] = $this->common_model->numberMasking(MASK, $cardNo);
//                        $cardItem['cardType'] = checkCardTypes($cardNo);
//                        $cardItem['cardStatus'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CARDSTATUS;
//                        $cardItem['cardCurrency'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CURRENCYCODE;
//                        $multiCardItem[] = $cardItem;
//                    }
//                    $data['multiCard'] = $multiCardItem;
//                    /* new add for multi card */
//
//                    $data['cardsModeOfDisplay'] = "display: block;";
//                    $data['cardsModeOfDisplay_c'] = "display: none;";
//                    $data['message'] = "";
//                } else {
//                    $data['cardsModeOfDisplay'] = "display: none;";
//                    $data['cardsModeOfDisplay_c'] = "display: none;";
//                    $data['message'] = (string) $cardInfoDetail->WARNING;
//                }
//            } else {
//                $data['cardsModeOfDisplay'] = "display: none;";
//                $data['cardsModeOfDisplay_c'] = "display: none;";
//                $data['message'] = (string) $accDetail->WARNING;
//            }
//        }
//
//
//        if (!empty($dbData['clientId']) && !empty($dbData['clientId_c'])) {
//            if ((string) $accDetail->ISSUCCESS == "Y") {
//                if ((string) $cardInfoDetail->ISSUCCESS == "Y") {
//                    //$data['clientNumber'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
//                    //$data['cardNumber'] = $this->common_model->numberMasking(MASK, (string)$accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
//                    //$data['cardType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
//                    //$data['cardProductType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
//                    //$data['cardStatus'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
//                    $data['issupplementary'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
//                    $data['primaryCardNumber'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
//                    $data['userNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
//                    $data['expiryDate'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
//                    $data['clientBillingAddress'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
//                    $data['dobCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DOB;
//                    $data['mothersNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;
//
//                    /* new add for multi card */
//                    $itemCount = count($accDetail->EBLCRD_OUTPUT->ITEM);
//                    for ($w = 0; $w < $itemCount; $w++) {
//                        $cardNo = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CARDNUMBER;
//                        $cardItem['cardNumber'] = $this->common_model->numberMasking(MASK, $cardNo);
//                        $cardItem['cardType'] = checkCardTypes($cardNo);
//                        $cardItem['cardStatus'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CARDSTATUS;
//                        $cardItem['cardCurrency'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[$w]->CURRENCYCODE;
//                        $multiCardItem[] = $cardItem;
//                    }
//
//                    $data['multiCard'] = $multiCardItem;
//                    /* new add for multi card */
//
//
//                    $data['cardsModeOfDisplay'] = "display: block;";
//                    $data['cardsModeOfDisplay_c'] = "display: block;";
//                    $data['message'] = "";
//                } else {
//                    $data['cardsModeOfDisplay'] = "display: none;";
//                    $data['cardsModeOfDisplay_c'] = "display: none;";
//                    $data['message'] = (string) $cardInfoDetail->WARNING;
//                }
//            } else {
//                $data['cardsModeOfDisplay'] = "display: none;";
//                $data['cardsModeOfDisplay_c'] = "display: none;";
//                $data['message'] = (string) $accDetail->WARNING;
//            }
//
//
//            if ((string) $accDetail_c->ISSUCCESS == "Y") {
//                if ((string) $cardInfoDetail_c->ISSUCCESS == "Y") {
//                    //$data['clientNumber_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
//                    //$data['cardNumber_c'] = $this->common_model->numberMasking(MASK, (string)$accDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
//                    //$data['cardType_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
//                    //$data['cardProductType_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
//                    //$data['cardStatus_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
//                    $data['issupplementary_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
//                    $data['primaryCardNumber_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
//                    $data['userNameCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
//                    $data['expiryDate_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
//                    $data['clientBillingAddress_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
//                    $data['dobCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->DOB;
//                    $data['mothersNameCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;
//
//                    /* new add for multi card */
//                    $itemCount_c = count($accDetail_c->EBLCRD_OUTPUT->ITEM);
//                    for ($w = 0; $w < $itemCount_c; $w++) {
//                        $cardNo = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CARDNUMBER;
//                        $cardItem_c['cardNumber'] = $this->common_model->numberMasking(MASK, $cardNo);
//                        $cardItem_c['cardType'] = checkCardTypes($cardNo);
//                        $cardItem_c['cardStatus'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CARDSTATUS;
//                        $cardItem_c['cardCurrency'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CURRENCYCODE;
//                        $multiCardItem_c[] = $cardItem_c;
//                    }
//                    $data['multiCard_c'] = $multiCardItem_c;
//                    /* new add for multi card */
//
//                    $data['cardsModeOfDisplay'] = "display: block;";
//                    $data['cardsModeOfDisplay_c'] = "display: block;";
//                    $data['message_c'] = "";
//                } else {
//                    $data['cardsModeOfDisplay'] = "display: none;";
//                    $data['cardsModeOfDisplay_c'] = "display: none;";
//                    $data['message_c'] = (string) $cardInfoDetail_c->WARNING;
//                }
//            } else {
//                $data['cardsModeOfDisplay'] = "display: none;";
//                $data['cardsModeOfDisplay_c'] = "display: none;";
//                $data['message_c'] = (string) $accDetail_c->WARNING;
//            }
//        }
//
//        if (empty($dbData['clientId']) && empty($dbData['clientId_c'])) {
//            $data['cardsModeOfDisplay'] = "display: none;";
//            $data['message'] = "Client ID is empty";
//            $data['cardsModeOfDisplay_c'] = "display: none;";
//            $data['message_c'] = "Client ID is empty";
//        }

        $data["pageTitle"] = "Apps User Checker";
        $data["body_template"] = "client_registration_checker/apps_user_approve_form.php";
        $this->load->view('site_template.php', $data);
    }

    public function getReason() {
        $data['checkerAction'] = $this->input->post("checkerAction");
        $id = $this->input->post("skyId");
        $makerActionDtTm = $this->input->post("makerActionDtTm");
        $checkerActionDtTm = $this->input->post("checkerActionDtTm");
        $dbData = $this->client_registration_model_checker->getAppsUserById($id);

        /* if ($dbData['makerActionBy'] == $this->my_session->userId) {
          echo "You can not authorize your own maker action";
          } */

        if ($data['checkerAction'] == "approve") {
            $chkdata['checkerActionDt'] = date("Y-m-d");
            $chkdata['checkerActionTm'] = date("G:i:s");
            $chkdata['isPublished'] = 1;
            $chkdata['checkerActionBy'] = $this->my_session->userId;
            $chkdata['checkerAction'] = "Approved";
            $chkdata['checkerActionComment'] = NULL;
            $chkdata['mcStatus'] = 1;

            $dataDelete = json_decode($dbData['dataDelete'], true);
            $p['account_delete'] = (!empty($dataDelete['account_delete']) ? $dataDelete['account_delete'] : null);

            $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

            if ($res == 0) {
                if ($dbData['isPublished'] == 0) {
                    // update and insert
                    $this->client_registration_model_checker->UpdateInsertCheckerApprove($id, $chkdata, $p);
                } else if ($dbData['isPublished'] == 1) {

                    if ($dbData['appsGroupId'] == $dbData['appsGroupId_c']) {
                        $descision = 1;
                        $this->client_registration_model_checker->UpdateUpdateCheckerApprove($id, $chkdata, $descision, $p);
                    } else {
                        $descision = 0;
                        $this->client_registration_model_checker->UpdateUpdateCheckerApprove($id, $chkdata, $descision, $p);
                    }
                }
                // activity log goes here
                redirect('client_registration_checker');
            } else {
                // redirect
                echo "interaction";
            }
        } else if ($data['checkerAction'] == 'reject') {
            $data['checkerActionDt'] = date("Y-m-d");
            $data['checkerActionTm'] = date("G:i:s");
            $data['checkerActionBy'] = $this->my_session->userId;
            $data['checkerAction'] = "Rejected";
            $data['checkerActionComment'] = $this->input->post("newReason");
            $data['mcStatus'] = 2;

            $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);
            if ($res == 0) {
                $this->client_registration_model_checker->checkerReject($id, $data);
                redirect('client_registration_checker');
            } else {
                echo "interaction";
            }
        }
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
        $this->my_session->authorize("canApproveAppsUser");
        $checkUserInteraction = 1;
        $actualdata = $this->client_registration_model_checker->getAppsUserById($id);
        $makerActionDtTm = $actualdata['makerActionDt'] . " " . $actualdata['makerActionTm'];
        $checkerActionDtTm = $actualdata['checkerActionDt'] . " " . $actualdata['checkerActionTm'];
        if ($checkerActionDtTm == " ") {
            $checkerActionDtTm = "";
        }
        if ($makerActionDtTmPost == $makerActionDtTm && $checkerActionDtTmPost == $checkerActionDtTm) {
            $checkUserInteraction = 0;
        }
        return $checkUserInteraction;
    }

}
