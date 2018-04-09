<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apps_user_delete_checker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('apps_user_delete_checker_model');
        $this->load->model('client_registration_model_checker');
        $this->load->library('push_to_cbs_service_library');
        $this->load->model('common_model');
        $this->load->library('session');
        $this->load->library('BOcrypter');
        $this->load->model('login_model');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }
    }

    public function index() {
        $this->output->set_template('theme2');
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, apps_user_delete_authorization) > -1) {
            $data['deleteUser'] = json_encode($this->apps_user_delete_checker_model->getUnapprovedDeleteUser());
            $this->load->view('apps_user_delete_checker/unapproved_delete_user', $data);
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function getAppsUserForApproval($id) {
        $data['multiCard'] = array();
        $data['multiCard_c'] = array();


        $this->output->set_template('theme2');

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


        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, apps_users_authorization) > -1) {

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
            }

            $data['appsUser'] = $dbData;
            $data['accountInfo'] = json_encode($this->login_model->checkAccount($dbData));

            if ($dbData['skyId_c'] != NULL) {
                $approvedAccounts = $this->client_registration_model_checker->getApprovedAccountsById($id);
                $data['accountInfo_c'] = json_encode($this->login_model->checkAccount($approvedAccounts));
            } else {
                $data['accountInfo_c'] = json_encode((object) null);
            }


            $reqTypeCode = "10";

            if (!empty($dbData['clientId'])) {
                $cardsData['methodName'] = "CARD_ACCOUNT_DETAILS";
                $cardsData['clientId'] = $dbData['clientId'];
                $cardsData['cardNumber'] = "NULL";
                $cardsData['cardCurrency'] = "NULL";
                $accDetail = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
                $accDetail = str_replace(array("\n", "\r", "\t"), '', $accDetail);
                $accDetail = simplexml_load_string($accDetail);

                if ((string) $accDetail->ISSUCCESS == "Y") {
                    $cardsData['methodName'] = "CARD_INFORMATION_DETAILS";
                    $cardsData['clientId'] = $dbData['clientId'];
                    $cardsData['cardNumber'] = (string) $accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER;
                    $cardsData['cardCurrency'] = "NULL";
                    $cardInfoDetail = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
                    $cardInfoDetail = str_replace(array("\n", "\r", "\t"), '', $cardInfoDetail);
                    $cardInfoDetail = simplexml_load_string($cardInfoDetail);
                }
            }


            if (!empty($dbData['clientId_c'])) {
                $cardsData['methodName'] = "CARD_ACCOUNT_DETAILS";
                $cardsData['clientId'] = $dbData['clientId_c'];
                $cardsData['cardNumber'] = "NULL";
                $cardsData['cardCurrency'] = "NULL";
                $accDetail_c = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
                $accDetail_c = str_replace(array("\n", "\r", "\t"), '', $accDetail_c);
                $accDetail_c = simplexml_load_string($accDetail_c);

                if ((string) $accDetail_c->ISSUCCESS == "Y") {
                    $cardsData['methodName'] = "CARD_INFORMATION_DETAILS";
                    $cardsData['clientId'] = $dbData['clientId_c'];
                    $cardsData['cardNumber'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER;
                    $cardsData['cardCurrency'] = "NULL";
                    $cardInfoDetail_c = $this->push_to_cbs_service_library->pushToCbsService($reqTypeCode, $cardsData);
                    $cardInfoDetail_c = str_replace(array("\n", "\r", "\t"), '', $cardInfoDetail_c);
                    $cardInfoDetail_c = simplexml_load_string($cardInfoDetail_c);
                }
            }


            if (empty($dbData['clientId']) && !empty($dbData['clientId_c'])) {
                $data['message'] = "Client ID is empty";
                if ((string) $accDetail_c->ISSUCCESS == "Y") {
                    if ((string) $cardInfoDetail_c->ISSUCCESS == "Y") {
                        //$data['clientNumber_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
                        //$data['cardNumber_c'] = $this->common_model->numberMasking(MASK, (string)$accDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
                        //$data['cardType_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
                        //$data['cardProductType_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
                        //$data['cardStatus_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
                        $data['issupplementary_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
                        $data['primaryCardNumber_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
                        $data['userNameCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
                        $data['expiryDate_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
                        $data['clientBillingAddress_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
                        $data['dobCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->DOB;
                        $data['mothersNameCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;

                        /* new add for multi card */
                        $itemCount_c = count($accDetail_c->EBLCRD_OUTPUT->ITEM);
                        for ($w = 0; $w < $itemCount_c; $w++) {
                            $cardNo = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CARDNUMBER;
                            $cardItem_c['cardNumber'] = $this->common_model->numberMasking(MASK, $cardNo);
                            $cardItem_c['cardType'] = checkCardTypes($cardNo);
                            $cardItem_c['cardStatus'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CARDSTATUS;
                            $cardItem_c['cardCurrency'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CURRENCYCODE;
                            $multiCardItem_c[] = $cardItem_c;
                        }
                        $data['multiCard_c'] = $multiCardItem_c;
                        /* new add for multi card */

                        $data['cardsModeOfDisplay'] = "display: none;";
                        $data['cardsModeOfDisplay_c'] = "display: block;";
                        $data['message_c'] = "";
                    } else {
                        $data['cardsModeOfDisplay'] = "display: none;";
                        $data['cardsModeOfDisplay_c'] = "display: none;";
                        $data['message_c'] = (string) $cardInfoDetail_c->WARNING;
                    }
                } else {
                    $data['cardsModeOfDisplay'] = "display: none;";
                    $data['cardsModeOfDisplay_c'] = "display: none;";
                    $data['message_c'] = (string) $accDetail_c->WARNING;
                }
            }


            if (!empty($dbData['clientId']) && empty($dbData['clientId_c'])) {
                $data['message_c'] = "Client ID is empty";
                if ((string) $accDetail->ISSUCCESS == "Y") {
                    if ((string) $cardInfoDetail->ISSUCCESS == "Y") {
                        //$data['clientNumber'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
                        //$data['cardNumber'] = $this->common_model->numberMasking(MASK, (string)$accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
                        //$data['cardType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
                        //$data['cardProductType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
                        //$data['cardStatus'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
                        $data['issupplementary'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
                        $data['primaryCardNumber'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
                        $data['userNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
                        $data['expiryDate'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
                        $data['clientBillingAddress'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
                        $data['dobCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DOB;
                        $data['mothersNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;


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
                        $data['multiCard'] = $multiCardItem;
                        /* new add for multi card */

                        $data['cardsModeOfDisplay'] = "display: block;";
                        $data['cardsModeOfDisplay_c'] = "display: none;";
                        $data['message'] = "";
                    } else {
                        $data['cardsModeOfDisplay'] = "display: none;";
                        $data['cardsModeOfDisplay_c'] = "display: none;";
                        $data['message'] = (string) $cardInfoDetail->WARNING;
                    }
                } else {
                    $data['cardsModeOfDisplay'] = "display: none;";
                    $data['cardsModeOfDisplay_c'] = "display: none;";
                    $data['message'] = (string) $accDetail->WARNING;
                }
            }


            if (!empty($dbData['clientId']) && !empty($dbData['clientId_c'])) {
                if ((string) $accDetail->ISSUCCESS == "Y") {
                    if ((string) $cardInfoDetail->ISSUCCESS == "Y") {
                        //$data['clientNumber'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
                        //$data['cardNumber'] = $this->common_model->numberMasking(MASK, (string)$accDetail->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
                        //$data['cardType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
                        //$data['cardProductType'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
                        //$data['cardStatus'] = (string)$cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
                        $data['issupplementary'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
                        $data['primaryCardNumber'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
                        $data['userNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
                        $data['expiryDate'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
                        $data['clientBillingAddress'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
                        $data['dobCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->DOB;
                        $data['mothersNameCard'] = (string) $cardInfoDetail->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;

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

                        $data['multiCard'] = $multiCardItem;
                        /* new add for multi card */


                        $data['cardsModeOfDisplay'] = "display: block;";
                        $data['cardsModeOfDisplay_c'] = "display: block;";
                        $data['message'] = "";
                    } else {
                        $data['cardsModeOfDisplay'] = "display: none;";
                        $data['cardsModeOfDisplay_c'] = "display: none;";
                        $data['message'] = (string) $cardInfoDetail->WARNING;
                    }
                } else {
                    $data['cardsModeOfDisplay'] = "display: none;";
                    $data['cardsModeOfDisplay_c'] = "display: none;";
                    $data['message'] = (string) $accDetail->WARNING;
                }


                if ((string) $accDetail_c->ISSUCCESS == "Y") {
                    if ((string) $cardInfoDetail_c->ISSUCCESS == "Y") {
                        //$data['clientNumber_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CLIENTNUMBER;
                        //$data['cardNumber_c'] = $this->common_model->numberMasking(MASK, (string)$accDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDNUMBER);
                        //$data['cardType_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->DEBITORCREDITCARD;
                        //$data['cardProductType_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDTYPE;
                        //$data['cardStatus_c'] = (string)$cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CARDSTATUS;
                        $data['issupplementary_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->ISSUPPLEMENTARY;
                        $data['primaryCardNumber_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->PRIMARYCARDNUMBER;
                        $data['userNameCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->EMBOSSEDNAME;
                        $data['expiryDate_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->EXPIRYDATE;
                        $data['clientBillingAddress_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->CLIENTBILLINGADDRESS;
                        $data['dobCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->DOB;
                        $data['mothersNameCard_c'] = (string) $cardInfoDetail_c->EBLCRD_OUTPUT->ITEM[0]->MOTHERSNAME;

                        /* new add for multi card */
                        $itemCount_c = count($accDetail_c->EBLCRD_OUTPUT->ITEM);
                        for ($w = 0; $w < $itemCount_c; $w++) {
                            $cardNo = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CARDNUMBER;
                            $cardItem_c['cardNumber'] = $this->common_model->numberMasking(MASK, $cardNo);
                            $cardItem_c['cardType'] = checkCardTypes($cardNo);
                            $cardItem_c['cardStatus'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CARDSTATUS;
                            $cardItem_c['cardCurrency'] = (string) $accDetail_c->EBLCRD_OUTPUT->ITEM[$w]->CURRENCYCODE;
                            $multiCardItem_c[] = $cardItem_c;
                        }
                        $data['multiCard_c'] = $multiCardItem_c;
                        /* new add for multi card */


                        $data['cardsModeOfDisplay'] = "display: block;";
                        $data['cardsModeOfDisplay_c'] = "display: block;";
                        $data['message_c'] = "";
                    } else {
                        $data['cardsModeOfDisplay'] = "display: none;";
                        $data['cardsModeOfDisplay_c'] = "display: none;";
                        $data['message_c'] = (string) $cardInfoDetail_c->WARNING;
                    }
                } else {
                    $data['cardsModeOfDisplay'] = "display: none;";
                    $data['cardsModeOfDisplay_c'] = "display: none;";
                    $data['message_c'] = (string) $accDetail_c->WARNING;
                }
            }


            if (empty($dbData['clientId']) && empty($dbData['clientId_c'])) {
                $data['cardsModeOfDisplay'] = "display: none;";
                $data['message'] = "Client ID is empty";
                $data['cardsModeOfDisplay_c'] = "display: none;";
                $data['message_c'] = "Client ID is empty";
            }

            $this->load->view('apps_user_delete_checker/apps_user_delete_approve_form', $data);
        } else {
            echo "Authorization Module Not Given";
        }
    }

    public function getReason() {
        $authorizationModules = $this->session->userdata('authorizationModules');
        if (strpos($authorizationModules, apps_users_authorization) > -1) {
            $data['checkerAction'] = $_POST['checkerAction'];
            $id = $_POST['skyId'];
            $makerActionDtTm = $_POST['makerActionDtTm'];
            $checkerActionDtTm = $_POST['checkerActionDtTm'];
            $dbData = $this->client_registration_model_checker->getAppsUserById($id);

            if ($dbData['makerActionBy'] == $this->session->userdata('adminUserId')) {
                echo "You can not authorize your own maker action";
            } else {
                if ($data['checkerAction'] == "approve") {
                    // $chkdata['salt2'] = "";

                    $chkdata['checkerActionDt'] = date("Y-m-d");
                    $chkdata['checkerActionTm'] = date("G:i:s");
                    $chkdata['isPublished'] = 1;
                    $chkdata['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $chkdata['checkerAction'] = "Approved";
                    $chkdata['checkerActionComment'] = NULL;
                    $chkdata['mcStatus'] = 1;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        $this->apps_user_delete_checker_model->deleteCheckerApproval($id, $dbData["eblSkyId"]);
                        // activity log goes here
                        redirect('apps_user_delete_checker');
                    } else {
                        echo "interaction";
                    }
                } else if ($data['checkerAction'] == 'reject') {
                    $data['salt2'] = Null;
                    $data['checkerActionDt'] = date("Y-m-d");
                    $data['checkerActionTm'] = date("G:i:s");
                    $data['checkerActionBy'] = $this->session->userdata('adminUserId');
                    $data['checkerAction'] = "Rejected";
                    $data['checkerActionComment'] = $_POST['newReason'];
                    $data['mcStatus'] = 2;

                    $res = $this->checkUserInteraction($id, $makerActionDtTm, $checkerActionDtTm);

                    if ($res == 0) {
                        // update
                        $this->apps_user_delete_checker_model->checkerReject($id, $data);
                        redirect('apps_user_delete_checker');
                    } else {
                        // redirect
                        echo "interaction";
                    }
                }
            }
        } else {
            echo "Authorization module not given";
        }
    }

    public function checkUserInteraction($id, $makerActionDtTmPost, $checkerActionDtTmPost) {
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
