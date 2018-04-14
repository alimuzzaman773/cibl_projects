<?php

class Login_model extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->load->library('my_session');
        //$this->load->library('Crypter');
        $this->load->library('BOcrypter');
    }

    public function check_validity($data) { // check if user input is valid, if valid then one row will be returned
        $data = array('apps_users.eblSkyId' => $data['eblSkyId'],
            'apps_users.skyId' => $data['skyId'],
            'apps_users.passWord' => $data['passWord'],
            'device_info.imeiNo' => $data['imeiNo']);

        $this->db->select('apps_users.skyId,
                           apps_users.isActive as isActiveAppsuser,
                           apps_users.isLocked as isLockedAppsuser,

                           device_info.deviceToken,
                           device_info.deviceId,
                           device_info.isVaryfied,
                           device_info.isActive as isActiveDevice');

        $this->db->from('apps_users');
        $this->db->join('device_info', 'device_info.skyId = apps_users.skyId');
        $this->db->where($data);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function checkLoginTable($data) { // check if the user is already logged in
        $this->db->where($data);
        $query = $this->db->get('login_appsuser');
        return $query->row_array();
    }

    public function PickTokenFromDevice($data) {

        $query = $this->db->get_where('device_info', array('imeiNo' => $data));
        return $query->row_array();
    }

    public function getTokenForLogout($data) {

        $query = $this->db->get_where('login_appsuser', array('skyId' => $data));
        return $query->row_array();
    }

    public function insertLogintable($data) {
        $this->db->insert('login_appsuser', $data);
    }

    public function updateLogintable($data) { // make the user log in 
        $wheredata['skyId'] = $data['skyId'];
        unset($data['skyId']);
        $this->db->where($wheredata);
        $this->db->update('login_appsuser', $data);
    }

    public function userLogOut($data) { // make the user log out
        $wheredata['skyId'] = $data['skyId'];
        unset($data['skyId']);
        $this->db->where($wheredata);
        $this->db->update('login_appsuser', $data);
    }

    public function get_apps_users_info($data) { // supply data after login 
        unset($data['imeiNo']);
        $this->db->select('skyId, eblSkyId, cfId, userName, userEmail, userMobNo1, userMobNo2, passwordChangeTms, isReset, accountNo, accountType, accountName, accountCurrency');
        $query = $this->db->get_where('apps_users', $data);
        $userData = $query->row_array();

        $accNo = explode("|", $userData['accountNo']);
        $accType = explode("|", $userData['accountType']);
        $accName = explode("|", $userData['accountName']);
        $accCurrency = explode("|", $userData['accountCurrency']);

        array_shift($accNo);
        array_shift($accType);
        array_shift($accName);
        array_shift($accCurrency);

        foreach ($accNo as $index => $value) {
            $arrayData = array(
                'accNo' => $accNo[$index],
                'accTypeCode' => $accType[$index],
                'accName' => $accName[$index],
                'accCurrency' => $accCurrency[$index]);

            $accountInfo[] = $arrayData;
        }

        unset($userData['accountNo']);
        unset($userData['accountType']);
        unset($userData['accountName']);
        unset($userData['accountCurrency']);

        $data['generalInfo'] = $userData;
        $data['accountInfo'] = $accountInfo;
        return $data;
    }

    public function get_apps_users_ebl_acc_info($data) { // supply data after login 
        $query = $this->db->get_where('account_info', $data);
        return $query->result();
    }

    public function get_apps_users_ebl_card_info($data) {
        $query = $this->db->get_where('token', $data);
        return $query->result();
    }

    public function checkPassword($data) {

        $data = array('apps_users.eblSkyId' => $data['eblSkyId'],
            'apps_users.passWord' => $data['passWord']);

        $this->db->select('apps_users.passWord, login_appsuser.deviceToken');
        $this->db->from('apps_users');
        $this->db->join('login_appsuser', 'login_appsuser.skyId = apps_users.skyId');
        $this->db->where($data);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function changePassword($data) {

        $changePassword['passWord'] = $data['passWord'];
        $changePassword['isReset'] = 0;
        $changePassword['passChangeDtTm'] = date("y-m-d G:i:s");
        $this->db->set('passwordChangeTms', 'passwordChangeTms +' . 1, FALSE);
        $this->db->where('eblSkyId', $data['eblSkyId']);
        $this->db->update('apps_users', $changePassword);


        $this->db->set('passwordChangeTms', 'passwordChangeTms +' . 1, FALSE);
        $this->db->where('eblSkyId', $data['eblSkyId']);
        $this->db->update('apps_users_mc', $changePassword);


        $generateTable['isReset'] = 0;
        $this->db->where('eblSkyId', $data['eblSkyId']);
        $this->db->update('generate_eblskyid', $generateTable);
    }

    public function activateDevice($data) { // check if user input is valid
        $data = array('apps_users.eblSkyId' => $data['eblSkyId'],
            'apps_users.passWord' => $data['passWord'],
            'device_info.imeiNo' => $data['imeiNo']);

        $this->db->select('apps_users.skyId,
                           apps_users.isActive as appsUserIsActive,
                           device_info.isActive as deviceIsActive,
                           device_info.deviceId,
                           device_info.imeiNo,
                           device_info.isVaryfied');

        $this->db->from('apps_users');
        $this->db->join('device_info', 'device_info.skyId = apps_users.skyId');
        $this->db->where($data);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updateDeviceInfo($data) { // verify device
        $id = $data['deviceId'];
        unset($data['deviceId']);

        $this->db->where('deviceId', $id);
        $this->db->update('device_info_mc', $data);

        $this->db->where('deviceId', $id);
        $this->db->update('device_info', $data);
    }

    public function updateGcmUsers($data) { // insert/update gcm registration ID
        $wheredata['skyId'] = $data['skyId'];
        $wheredata['deviceId'] = $data['deviceId'];
        $query = $this->db->get_where('gcm_users', $wheredata);
        $rowdata = $query->row_array();

        if ($rowdata) {

            $gcm['gcmRegId'] = $data['gcmRegId'];
            $gcm['updateDtTm'] = date("Y-m-d G:i:s");
            $this->db->where($wheredata);
            $this->db->update('gcm_users', $gcm);
        } else {

            $this->db->insert('gcm_users', $data);
        }
    }

// **** To return account information of specific users according to requirement *****

    public function checkAccount($userData, $account = NULL) {

        $accNo = explode("|", $userData['accountNo']);
        $accType = explode("|", $userData['accountType']);
        $accName = explode("|", $userData['accountName']);
        $accCurrency = explode("|", $userData['accountCurrency']);
        $accIsLocked = explode("|", $userData['accountIsLocked']);
        array_shift($accNo);
        array_shift($accType);
        array_shift($accName);
        array_shift($accCurrency);
        array_shift($accIsLocked);

        if ($account != NULL) {
            foreach ($accNo as $index => $value) {
                if (($accNo[$index] == $account) && ($accType[$index] == "C")) {
                    $userData['accountNo'] = $accNo[$index];
                    $userData['accountType'] = $accType[$index];
                    $userData['accountName'] = $accName[$index];
                    $userData['accountCurrency'] = $accCurrency[$index];
                    $userData['accountIsLocked'] = $accIsLocked[$index];
                    return $userData;
                }
            }
            return FALSE;
        } else {
            foreach ($accNo as $index => $value) {
                $array = array(
                    'accNo' => $accNo[$index],
                    'accType' => $accType[$index],
                    'accName' => $accName[$index],
                    'accCurrency' => $accCurrency[$index],
                    'accIsLocked' => $accIsLocked[$index]);
                $accountInfo[] = $array;
            }
            return $accountInfo;
        }
    }

    public function check_session() {
        if ($this->session->userdata('logged_in') == TRUE) {
            return FALSE;
        }
        return TRUE;
    }

    public function delete_session() {
        $this->session->sess_destroy();
    }

    public function checkSessionTimeOut($data) { // check the idle time of the apps user
        $query = $this->db->get_where('login_appsuser', array('skyId' => $data['skyId']));
        $loginData = $query->row_array();
        $time = date("Y-m-d G:i:s");
        $nowTime = strtotime($time);
        $lastCommTime = strtotime($loginData['lastCommDtTm']);

        if ($loginData['isLogin'] == 1) { // check if user is logged in 
            if (($nowTime - $lastCommTime) >= IDLE_TIME) { // check idle time
                // make the user log out and return message
                $wheredata['skyId'] = $data['skyId'];
                $logOutData['isLogin'] = 0;
                $logOutData['lastCommDtTm'] = $time;
                $logOutData['lastAction'] = "Logout";
                $this->db->where($wheredata);
                $this->db->update('login_appsuser', $logOutData);

                $message['isSuccess'] = "N";
                $message['reason'] = "C0001";
                $message['warning'] = "Session Out";

                return $message;
            } else {
                // return message and update login table data

                $wheredata['skyId'] = $data['skyId'];
                $logOutData['lastCommDtTm'] = $time;
                $logOutData['lastAction'] = $data['action'];
                $this->db->where($wheredata);
                $this->db->update('login_appsuser', $logOutData);

                $message['isSuccess'] = "Y";

                return $message;
            }
        } else {
            // for later use
            $message['isSuccess'] = "N";
            $message['reason'] = "C0002";
            $message['warning'] = "Contact Bank";
            return $message;
        }
    }

    // **** function to format the front end encrypted data*****
    // public function frontEndDataFormating($frontEndData)
    // {
    //     $decryptedData = substr($this->crypter->Decrypt($frontEndData), 1, -1);
    //     $array = explode(",", $decryptedData);
    //     foreach($array as $value)
    //     {
    //         //**** previous working code *****//
    //         // $innserString = explode("=", $value);
    //         // $valueIndex = preg_replace('/\s+/', '', $innserString[0]);
    //         // $data[$valueIndex] = $innserString[1];
    //         // *** currently working code **** //
    //         $innserString = explode("=", $value);
    //         $valueIndex = preg_replace('/\s+/', '', $innserString[0]);
    //         $valueIndex = str_replace('"', '', $valueIndex);
    //         $data[$valueIndex] = str_replace('"', '', $innserString[1]);
    //     }
    //     return $data;
    // }
    // public function sypplyDataToFrontEnd($data)
    // {
    //     $encryptedData = $this->crypter->Encrypt($data);
    //     return $encryptedData;
    // }


    public function decryptPin($data) {
        $decryptedData = $this->bocrypter->Decrypt($data);
        return $decryptedData;
    }

    // **** for generating random string as token ***
    public function generateRandomString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}
