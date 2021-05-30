<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of session
 * Manages User Session Data
 * @author Arif sTalKer Majid
 */
Class My_session {

    const sessionTimeOut = 7200; // => seconds of inactivity will lead to logout.

    var $logged_in;
    var $userName;
    var $sessionId;
    var $cookieId;
    var $sessionToken;
    var $lastActivity;
    var $time;
    var $userInfo = array();
    var $permissions = array();
    var $userId = null;
    var $userGroupId = null;
    var $branchId = null;
    var $userLevel = null;
    var $language = array();
    var $companyId = null;
    var $adminUserId = null;
    var $group = null;

    function __construct() {
        session_save_path(ABS_SERVER_PATH . SITE_FOLDER . "sessions_files");
        ini_set('session.gc_probability', 1);

        $this->time = time();
        @session_start();

        /** get CI instances for ci library usage * */
        $CI = & get_instance();

        $checklogin = $this->check_login();
    }

    private function check_login() {

        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['session_userName']) && $_SESSION['session_userName'] != "Guest") {
            $this->setSessionVars();
            if (!$this->checkSessionValidity($_SESSION['session_lastActivity'], $this->time)) {
                // $this->log_out_from_counter();
                $this->unsetSessionVars();
                return false;
            }

            return true;
        } else {
            $this->unsetSessionVars();
            return false;
        }

        return false;
    }

    private function checkSessionValidity($lastactivity, $timeNow) {
        $sessiontime = $timeNow - $lastactivity;
        //var_dump($sessiontime);die();
        if ($sessiontime > My_session::sessionTimeOut):
            return false;
        endif;

        return true;
    }

    private function setSessionVars($userinfo = null) {

        if ($userinfo != null):
            $this->permissions = $_SESSION['permissions'] = $this->createPermissionArray($userinfo['permissions']);
            $this->group = $_SESSION['group'] = $_SESSION['userGroupName'] = $userinfo['userGroupName'];
            $this->userName = $_SESSION['username'] = $_SESSION['session_userName'] = $userinfo['adminUserName'];
            $this->adminUserId = $_SESSION['adminUserId'] = $userinfo['adminUserId'];
            $this->userId = $_SESSION['session_userId'] = $userinfo['adminUserId'];
            $this->userGroupId = $_SESSION['session_userGroupId'] = $userinfo['adminUserGroup'];
            $this->userLevel = $_SESSION['session_userLevel'] = $userinfo['user_level'];
            $this->userInfo = $_SESSION['session_userInfo'] = array('userId' => $userinfo['adminUserId'], "companyId" => $userinfo['company_id']);
            $this->companyId = $this->userInfo['companyId'];
            $this->language = $_SESSION['session_language'] = array("id" => 0, "code" => "en");
        else:
            $this->permissions = $_SESSION['permissions']; // = (isset($_SESSION['permisssions']))? $_SESSION['permissions']:""; 
            $this->group = $_SESSION['group'] = $_SESSION['userGroupName'];
            $this->userName = $_SESSION['username'] = $_SESSION['session_userName'];
            $this->adminUserId = $_SESSION['adminUserId'] = $_SESSION['adminUserId'];
            $this->userId = $_SESSION['session_userId'];
            $this->userGroupId = $_SESSION['session_userGroupId'];
            $this->userLevel = $_SESSION['session_userLevel'];
            $this->userInfo = $_SESSION['session_userInfo'];
            $this->companyId = $this->userInfo['companyId'];
            $this->language = $_SESSION['session_language'];
        endif;

        $this->logged_in = $_SESSION['logged_in'] = TRUE;
        //$this->sessionToken = $_SESSION['session_Token'] = md5(uniqid());
        if (isset($_SESSION['session_time'])):
            $this->lastActivity = $_SESSION['session_lastActivity'] = $_SESSION['session_time'];
        else:
            $this->lastActivity = $_SESSION['session_lastActivity'] = $this->time;
        endif;

        $_SESSION['session_time'] = $this->time;
    }

    private function unsetSessionVars() {
        $this->logged_in = $_SESSION['logged_in'] = FALSE;
        $this->userName = $_SESSION['session_userName'] = "Guest";
        $this->permissions = null;
        $this->userId = null;
        $this->userGroupId = null;
        //$this->sessionToken = null;
        $this->lastActivity = null;
        $this->userLevel = null;
        $this->userInfo = array();
        $this->branchId = null;
        $this->language = array();

        unset($_SESSION['session_userGroupId']);
        unset($_SESSION['session_userId']);
        unset($_SESSION['session_userName']);
        unset($_SESSION['userInfo']);
        unset($_SESSION['permissions']);
        unset($_SESSION['session_userInfo']);
        unset($_SESSION['session_lastActivity']);
        unset($_SESSION['session_userLevel']);
        unset($_SESSION['session_language']);
        unset($_SESSION['session_time']);
    }

    private function createPermissionArray($parray) {
        $pstring = explode(",", $parray);
        if (count($pstring) <= 0):
            $_SESSION['permissions'] = array();
            return $_SESSION['permissions'];
        endif;

        $ci = & get_instance();
        foreach ($pstring as $ps):
            $ci->db->or_where("permissionId", $ps);
        endforeach;

        $result = $ci->db->from(TBL_PERMISSIONS)->get();
        //d($ci->db->last_query());
        $permissions = array();
        foreach ($result->result() as $r):
            $permissions[$r->name] = array(
                'permissionId' => $r->permissionId,
                'name' => $r->name
            );
        endforeach;
        //d($permissions);
        $_SESSION['permissions'] = $permissions;
        return $_SESSION['permissions'];

        /* foreach ($pstring as $key => $val) {
          $_SESSION['permissions'][$val] = $val;
          }
          array_pop($_SESSION['permissions']);
          return $_SESSION['permissions']; */
    }

    public function log_in($user_name, $password, $authType = 'appAuth') {
        $CI = & get_instance();
        $CI->load->model("admin_users_model_maker");
        $CI->load->library('BOcrypter');
        
        if(trim($user_name) == ''):
            $this->setLoginError("Username was not provided");
            return false;
        endif;
        
        if(trim($password) == ''):
            $this->setLoginError("Password was not provided");
            return false;
        endif;

        //$result = $CI->users->checkUser($user_name, $password);
        $uParams = [];
        if($authType == 'appAuth'):
            $uParams['adminUserName'] = $user_name;
        endif;
        
        if($authType == 'adAuth'):
            $uParams['adUserName'] = $user_name;
        endif;
        
        $result = $CI->admin_users_model_maker->checkUserInformation($user_name);
        if (!$result) {
            $this->setLoginError("No user found");
            return false;
        }

        
        if($authType == 'appAuth'):
            $encryptedpassword = $CI->bocrypter->Encrypt($password);

            $this->unSetLoginError();
            $row = $result->row_array();

            $dbPass = $CI->bocrypter->Decrypt($row['encryptedPassword']);
            if($dbPass == false || trim($dbPass) == ''):
                $this->setLoginError("User password is not set in the system");
                return false;
            endif;


            if($dbPass !== $password):
                $this->setLoginError("Password did not match");
                return false;
            endif;

        elseif($authType == 'adAuth'):
            $adAuthResponse = $this->checkAdAuth($user_name, $password);
            if(!$adAuthResponse['success']):
                $this->setLoginError("AD authentication failed. ".$adAuthResponse['msg']);
                return false;
            endif;
        endif;

        $this->setSessionVars($row);
        return $return = array(
            "success" => true,
            "forgotPassword" => (int) $row['forgotPassword']
        );
    }

    public function log_out() {
        $this->unsetSessionVars();

        session_unset();
        session_destroy();
    }

    public function checkSession($page = null) {
        $CI = & get_instance();
        if ($page == null) {
            $page = $CI->config->item('base_url');
        }
        if (!$this->logged_in) {
            redirect($page);
            die();
        }
        return $this;
    }

    public function setLoginError($msg = "Incorrect Login Details") {
        $_SESSION['login_error'] = $msg;
    }

    public function getLoginError() {
        if (array_key_exists("login_error", $_SESSION)) {
            return $_SESSION['login_error'];
        }
        return "";
    }

    public function unSetLoginError() {
        if (array_key_exists("login_error", $_SESSION)) {
            unset($_SESSION['login_error']);
        }
    }

    public function hasModuleAccess($userLevel = array()) {
        if (!empty($userLevel)):
            foreach ($userLevel as $val):
                if ($this->userLevel == $val):
                    return $this;
                endif;
            endforeach;

            die("Not Allowed To Access This Module");
        endif;
        die("Not Allowed To Access This Module");
    }

    public function authorize($permissionString, $userLevel = array()) {
        $ci = & get_instance();
        if (isset($this->permissions[$permissionString])) {
            if (!empty($userLevel)):
                foreach ($userLevel as $val):
                    if ($this->userLevel == $val):
                        return true;
                    endif;
                endforeach;
                if ($ci->input->is_ajax_request()):
                    $json = array(
                        "success" => false,
                        "msg" => "Not allowed. You need the permission :: {$permissionString} to perform this action"
                    );
                    my_json_output($json);
                endif;
                die("Not Allowed");
            endif;

            return true;
        }


        if ($ci->input->is_ajax_request()):
            $json = array(
                "success" => false,
                "msg" => "Not allowed. You need the permission :: {$permissionString} to perform this action"
            );
            my_json_output($json);
        endif;

        die("Not Allowed");
    }

    public function checkPermission($permissionString, $userLevel = array()) {
        if (isset($this->permissions[$permissionString])) {
            if (!empty($userLevel)):
                foreach ($userLevel as $val):
                    if ($this->userLevel == $val):
                        return true;
                    endif;

                endforeach;

                return false;
            endif;

            return true;
        }
        return false;
    }

    /**
     * Get Branch Id will return the branch id from session if exists for everyone except super admin
     * If super admin then it will check the get post array using ci input helper function  
     * @return int 
     */
    public function getCompanyId($paramName = 'company_id') {
        $CI = & get_instance();
        if ($this->companyId == null || $this->companyId <= 0) {
            $companyId = (int) $CI->input->get_post($paramName, true);
            return $companyId;
        }
        return $this->companyId;
    }

    public function setLanguage($languageId, $languageCode) {
        if ((int) $languageId <= 0):
            $languageCode = "en";
            $languageId = 0;
        endif;

        $this->language['id'] = $languageId;
        $this->language['code'] = $languageCode;
        $_SESSION['session_language'] = $this->language;
    }
    
    function checkAdAuth($user_name, $password)
    {
        try 
        {
            $LDAPUserDomain = "@pbl";
            $LDAPHost = "192.168.1.2";              //Your LDAP server DNS Name or IP Address
            $dn = "DC=pbl,DC=COM";          //Put your Base DN here
            $LDAPUser = $user_name;            //A valid Active Directory login
            $LDAPUserPassword = $password;
            $LDAPFieldsToFind = array("*");         //Search Felids, Wildcard Supported for returning all values

            $cnx = ldap_connect($LDAPHost) or die("Could not connect to LDAP");
            ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);    //Set the LDAP Protocol used by your AD service
            ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);           //This was necessary for my AD to do anything
            $l = @ldap_bind($cnx,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword) or die("Could not bind to LDAP");
            if($l == true):
                return array(
                    'success' => true
                );
            endif;

            return array(
                'success' => false,
                'msg' => ldap_error($cnx)
            );
        }
        catch(Exception $e)
        {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
        
            
    }

}

?>