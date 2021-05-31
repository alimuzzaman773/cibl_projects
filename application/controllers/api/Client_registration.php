<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client_registration extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('my_session');
        $this->my_session->checkSession();
    }

    function update_user() {
        $params['skyId'] = $skyId = (int) $this->input->post('skyId', TRUE);
        $params['cfId'] = $this->input->post('cfId', TRUE);
        $params['clientId'] = $this->input->post('clientId', TRUE);
        $params['prepaidId'] = $this->input->post('prepaidId', TRUE);
        $params['userName'] = $this->input->post('userName', TRUE);
        $params['userEmail'] = $this->input->post('userEmail', TRUE);
        $params['userMobNo1'] = $this->input->post('userMobNo1', TRUE);
        $params['userMobNo2'] = $this->input->post('userMobNo2', TRUE);
        $params['currAddress'] = $this->input->post('currAddress', TRUE);
        $params['parmAddress'] = $this->input->post('parmAddress', TRUE);
        $params['billingAddress'] = $this->input->post('billingAddress', TRUE);
        $params['isOwnAccTransfer'] = $this->input->post('isOwnAccTransfer', true);
        $params['isInterAccTransfer'] = $this->input->post('isInterAccTransfer', true);
        $params['isOtherAccTransfer'] = $this->input->post('isOtherAccTransfer', true);
        $params['isAccToCardTransfer'] = $this->input->post('isAccToCardTransfer', true);
        $params['isCardToAccTransfer'] = $this->input->post('isCardToAccTransfer', true);
        $params['isUtilityTransfer'] = $this->input->post('isUtilityTransfer', true);
        $params['isQrPayment'] = $this->input->post('isQrPayment', true);
        $dataDelete = $this->input->post('dataDelete', TRUE);
        $params['dataDelete'] = json_encode(array('account_delete' => $dataDelete));

        //d($params);

        $this->load->model('client_registration_model');
        $this->load->library('form_validation');
        $this->form_validation->set_data($params);

        $this->form_validation->set_rules('skyId', 'Category Name', 'xss_clean|integer|required');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );

            echo json_encode($json);
            die();
        endif;

        //check cfid
        if (trim($params['cfId']) != ''):
            $p = array("cfId" => $params['cfId'], 'skyIdNotIn' => array($params['skyId']));
            $cfid1 = $this->client_registration_model->checkAppsUserData("apps_users", $p);
            if ($cfid1):
                $json = array(
                    "success" => false,
                    "msg" => "CFID is already in use with another user"
                );

                my_json_output($json);
            endif;

            $p = array("cfId" => $params['cfId'], 'skyIdNotIn' => array($params['skyId']));
            $cfid1 = $this->client_registration_model->checkAppsUserData("apps_users_mc", $p);
            if ($cfid1):
                $json = array(
                    "success" => false,
                    "msg" => "CFID is already in use with another user"
                );

                my_json_output($json);
            endif;
        endif;

        //check client id
        if (trim($params['clientId']) != ''):
            $p = array("clientId" => $params['clientId'], 'skyIdNotIn' => array($params['skyId']));
            $cfid1 = $this->client_registration_model->checkAppsUserData("apps_users", $p);
            if ($cfid1):
                $json = array(
                    "success" => false,
                    "msg" => "ClientID is already in use with another user"
                );

                my_json_output($json);
            endif;

            $p = array("clientId" => $params['clientId'], 'skyIdNotIn' => array($params['skyId']));
            $cfid1 = $this->client_registration_model->checkAppsUserData("apps_users_mc", $p);
            if ($cfid1):
                $json = array(
                    "success" => false,
                    "msg" => "ClientID is already in use with another user"
                );

                my_json_output($json);
            endif;
        endif;


        //$date = date("Y-m-d H:i:s");
        $params['makerActionDt'] = date("Y-m-d");
        $params['makerActionTm'] = date("H:i:s");
        $params['makerActionBy'] = $this->my_session->userId;
        $params['mcStatus'] = 0;
        $params['makerAction'] = "edit";

        //d($params);

        $result = $this->client_registration_model->updateUser($params);

        my_json_output($result);
    }

    // Get App User 
    function get_user($skyId = NULL) {
        if (empty($skyId)):
            $json["success"] = false;
            $json["msg"] = "Pleae check your URL";
            echo json_encode($json);
            die();
        endif;

        $params = array(
            "skyId" => $skyId
        );

        $this->load->model(array('client_registration_model', 'login_model', 'common_model'));
        $result = $this->client_registration_model->getAppUsers($params);

        if (!$result):
            $json = array(
                "success" => false,
                "msg" => "Info Not Found"
            );
            echo json_encode($json);
            die();
        endif;

        $data = $result->row();
        // Get Accounts
        $accounts = array();
        $rAcc = $this->login_model->getUserAccounts($params);
        if ($rAcc):
            foreach ($rAcc->result() as $r) {
                if (strtolower($r->type) == "prepaid_card" || strtolower($r->type) == "credit_card"):
                    $r->accNo = $this->common_model->numberMasking(MASK, $r->accNo);
                endif;

                $accounts[] = $r;
            }
        endif;
        
        $json = array(
            "success" => true,
            "data" => $data,
            "accounts" => $accounts
        );

        my_json_output($json);
    }

    function remove_user() {
        if (empty($this->my_session->userId) && $this->my_session->userId <= 0):
            $json = array(
                'success' => false,
                'msg' => 'You are not logged in'
            );
            my_json_output($json);
        endif;

        $params['skyId'] = (int) $this->input->post('skyId', TRUE);
        $params['reason'] = $this->input->post('reason', TRUE);
        $params['eblSkyId'] = $this->input->post('eblSkyId', TRUE);

        $this->load->model('apps_user_delete_checker_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('skyId', 'skyId', 'xss_clean|integer|required');
        $this->form_validation->set_rules('reason', 'reason', 'xss_clean|required');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );

            echo json_encode($json);
            die();
        endif;

        $result = $this->apps_user_delete_checker_model->deleteCheckerApproval($params['skyId'], $params);

        my_json_output($result);
    }

    function get_ad_user()
    {
        $params = $this->input->post(NULL, true);
        
        // Construct new Adldap instance.
        $ad = new \Adldap\Adldap();
        // Create a configuration array.
        $config = [  
          'port' => ad_port,
          // An array of your LDAP hosts. You can use either
          // the host name or the IP address of your host.
          'hosts'    => [ad_host],

          // The base distinguished name of your domain to perform searches upon.
          'base_dn'  => ad_base_dn,

          // The account to use for querying / modifying LDAP records. This
          // does not need to be an admin account. This can also
          // be a full distinguished name of the user account.
          'username' => ad_username,
          'password' => ad_password,
        ];
        
        // Add a connection provider to Adldap.
        $ad->addProvider($config);
        
        try {
            $data = [
                'success' => false
            ];
            // If a successful connection is made to your server, the provider will be returned.
            $provider = $ad->connect();
            $search = $provider->search();
            
            // Performing a query.
            //$results = $provider->search()->find($params['user']);
            $results = $provider->search()->where('samaccountname', '=', $params['user'])->get();
            if($results):
                $person = $results->getAttributes();
                $data['success'] = true;
                $data['email'] = $results->getAttribute('mail')[0];//$results['mail'][0];
                $data['name'] = $results['cn'][0];
                $data['surname'] = $results['sn'][0];
                $data['givenname'] = $results['givenname'][0];
            else:
                $data['msg'] = 'No user found';
            endif;
            
            my_json_output($data);

        } catch (\Adldap\Auth\BindException $e) {

            // There was an issue binding / connecting to the server.
            my_json_output(['success' => false, "msg" => $e->getMessage(), "ex" => true]);
        }
    }
    
}
