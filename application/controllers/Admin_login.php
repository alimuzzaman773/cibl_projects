<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_login extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('BOcrypter');
        $this->load->model('login_model');
        $this->load->model('admin_users_model_maker');
        $this->load->model('common_model');
    }

    public function index() {
        $data['message'] = "";
        $this->load->view('admin_login/login_view.php', $data);
    }

    public function checkUserDetails() {

        /* Post Method update for Security 28 aug 16 */
        $input_user = $this->input->post('username', true);
        $input_pass = $this->input->post('password', true);

        if ($input_user && $input_pass) {

            $username = $input_user;
            $password = $this->bocrypter->Encrypt($input_pass);
            /* Post Method update for Security 28 aug 16 */

            /*
              if(isset($_POST['username']) && isset($_POST['password'])){

              $username = $_POST['username'];
              $password = $this->bocrypter->Encrypt($_POST['password']);
             */

            $checkLogin = $this->admin_users_model_maker->checkUsernamePassword($username);

            if (!empty($checkLogin)) {

                // check the forgot password flag // 
                if ($checkLogin['forgotPassword'] == 0) {

                    if ($checkLogin['adminUserName'] == $username && $checkLogin['encryptedPassword'] == $password) {
                        $moduleActionId = explode(",", $checkLogin['moduleActionId']);
                        $tableData = $this->admin_users_model_maker->getUserRoles($moduleActionId);
                        $roleData = $this->createSessionFormatData($tableData);
                        $userData = array('logged_in' => TRUE,
                            'adminUserId' => $checkLogin['adminUserId'],
                            'username' => $username,
                            'group' => $checkLogin['userGroupName'],
                            'moduleActionId' => $roleData['moduleActionId'],
                            'moduleCodes' => $roleData['moduleCode'],
                            'moduleNames' => $roleData['moduleName'],
                            'authorizationModules' => $checkLogin['authorizationModules'],
                            'contentSetupModules' => $checkLogin['contentSetupModules'],
                            'serviceRequestModules' => $checkLogin['serviceRequestModules'],
                            'reportTypeModules' => $checkLogin['reportTypeModules'],
                            'actionCodes' => $roleData['actionCode'],
                            'actionNames' => $roleData['actionName']);
                        $this->session->set_userdata($userData);

                        $this->output->set_template('theme2');
                        $this->load->view('dashboard.php');
                    } else {
                        $data['message'] = "Wrong username or password";
                        $this->load->view('admin_login/login_view.php', $data);
                    }
                } else {
                    $data['message'] = "";
                    $this->load->view('admin_login/change_password_view.php', $data);
                }
            } else {

                $data['message'] = 'The user "' . $username . '" does not exist or may be inactive or your group is inactive';
                $this->load->view('admin_login/login_view.php', $data);
            }
        } else {
            $data['message'] = "";
            $this->load->view('admin_login/login_view.php', $data);
        }
    }

    public function dashboard() {
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        } else {
            $this->output->set_template('theme2');
            $this->load->view('dashboard.php');
        }
    }

    public function logout() {
        $this->login_model->delete_session();
        redirect('/admin_login/index');
    }

    public function forgotPassword() {
        $data['message'] = "";
        $this->load->view('admin_login/forgot_password_view.php', $data);
    }

    public function changePassword() {

        $userName = $this->input->post('username');
        $oldPassword = $this->bocrypter->Encrypt($this->input->post('oldPassword'));
        $newPassword = $this->bocrypter->Encrypt($this->input->post('newPassword'));

        $checkLogin = $this->admin_users_model_maker->checkUsernamePassword($userName);

        if (!empty($checkLogin)) {

            if ($checkLogin['adminUserName'] == $userName && $checkLogin['encryptedPassword'] == $oldPassword) {

                $updateData['encryptedPassword'] = $newPassword;
                $updateData['forgotpassword'] = 0;
                $this->db->update('admin_users', $updateData, array('adminUserName' => $userName));
                $this->db->update('admin_users_mc', $updateData, array('adminUserName' => $userName));


                $this->db->set('passwordChangeTms', 'passwordChangeTms +' . 1, FALSE);
                $this->db->where('adminUserName', $userName);
                $this->db->update('admin_users');


                $this->db->set('passwordChangeTms', 'passwordChangeTms +' . 1, FALSE);
                $this->db->where('adminUserName', $userName);
                $this->db->update('admin_users_mc');

                $data['message'] = "Password changed successfully";
                $this->load->view('admin_login/login_view.php', $data);
            } else {
                $data['message'] = "wrong username/old password.";
                $this->load->view('admin_login/change_password_view.php', $data);
            }
        } else {
            $data['message'] = "wrong username/old password.";
            $this->load->view('admin_login/change_password_view.php', $data);
        }
    }

    public function retriveForgotPassword() {
        $userName = $this->input->post('username');
        $email = $this->bocrypter->Encrypt($this->input->post('userEmail'));
        $DBdata = $this->admin_users_model_maker->getUserEmail($userName, $email);
        if (!empty($DBdata)) {
            $maildata['to'] = $this->bocrypter->Decrypt($DBdata['email']);
            $maildata['subject'] = "Retrive Forgotten Password";
            $maildata['body'] = "<p>Dear Sir/Madam,</p>
                                 <p>Your old password is - " . $this->bocrypter->Decrypt($DBdata['encryptedPassword']) . "</p>
                                 <p>Thanks & Regards, <br/>EBL SKY AdminPanel</p>";
            $response = $this->common_model->send_mail($maildata);
            if ($response == 1) {
                // change the forgotpassword flag //
                $update['forgotpassword'] = 1;
                $this->db->update('admin_users', $update, array('adminUserName' => $userName, 'email' => $email));
                $this->db->update('admin_users_mc', $update, array('adminUserName' => $userName, 'email' => $email));
                $data['message'] = "Old password has been sent to your provided email. Please check and login.";
                $this->load->view('admin_login/login_view.php', $data);
            } else {
                $data['message'] = "Mail sending failed. Please Try again";
                $this->load->view('admin_login/forgot_password_view.php', $data);
            }
        } else {
            $data['message'] = "The provided Username/Email is wrong";
            $this->load->view('admin_login/forgot_password_view.php', $data);
        }
    }

    public function createSessionFormatData($data) {
        $var = "";
        $sessionData['moduleActionId'] = "";
        $sessionData['moduleCode'] = "";
        $sessionData['moduleName'] = "";
        $sessionData['actionCode'] = "";
        $sessionData['actionName'] = "";
        foreach ($data as $index => $value) {
            $sessionData['moduleActionId'] .= "," . $value['moduleActionId'];
            $maId = $value['moduleActionId'];
            $mId = $value['moduleId'];
            if ($var != $mId) {
                $sessionData['moduleCode'] .= "|" . $value['moduleCode'];
                $sessionData['moduleName'] .= "|" . $value['moduleName'];
                $sessionData['actionCode'] .= "#" . $value['actionCode'];
                $sessionData['actionName'] .= "#" . $value['actionName'];
                $var = $mId;
            } else {
                $sessionData['actionCode'] .= "," . $value['actionCode'];
                $sessionData['actionName'] .= "," . $value['actionName'];
            }
        }

        $sessionData['moduleActionId'] = ltrim($sessionData['moduleActionId'], ",");
        $sessionData['moduleCode'] = ltrim($sessionData['moduleCode'], "|");
        $sessionData['moduleName'] = ltrim($sessionData['moduleName'], "|");
        $sessionData['actionCode'] = ltrim($sessionData['actionCode'], "#");
        $sessionData['actionName'] = ltrim($sessionData['actionName'], "#");

        return $sessionData;
    }

}
