<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_login extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('my_session');
        $this->load->library('BOcrypter');
        $this->load->model('login_model');
        $this->load->model('admin_users_model_maker');
        $this->load->model('common_model');
    }

    public function index() {
        $data['css'] = "";
        $data['js'] = "";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        if ($this->my_session->logged_in):
            redirect(base_url() . "admin_login/dashboard");
        endif;
        $data['pageTitle'] = "Login";
        $data['body_template'] = "login.php";
        $this->load->view("site_template.php", $data);

//        $data['message'] = "";
//        $this->load->view('admin_login/login_view.php', $data);
    }

    public function checkUserDetails() {

        /* Post Method update for Security 28 aug 16 */
        $username = $this->input->post('username', true);
        $password = $this->input->post('password');

        $result = $this->my_session->log_in($username, $password);

        if ($result === false || $result === true) {
            redirect(base_url());
        }

        if (is_array($result) && $result['success'] == true && (int) $result['forgotPassword'] != 0) {
            $data['message'] = "";
            $this->load->view('admin_login/change_password_view.php', $data);
        }

        redirect(base_url());
        die();
    }

    function dashboard() 
    {
        //d($_SESSION);
        $data['pageTitle'] = "Dashboard";
        $data['body_template'] = "dashboard/dashboard.php";
        $this->load->view("site_template.php", $data);
    }

    function logout() {
        $this->load->library('my_session');
        $this->my_session->log_out();
        redirect(base_url());
    }

    public function forgotPassword() {
        $data['pageTitle'] = "Forgot Password";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "forgot_password.php";
        $this->load->view('site_template.php', $data);
//        $data['message'] = "";
//        $this->load->view('admin_login/forgot_password_view.php', $data);
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
            
            $mailData["to"] = $this->bocrypter->Decrypt($DBdata['email']);
            $mailData["from"] = "simple@ebl-com.bd";
            $mailData["fromName"] = "PMONEY";
            $mailData["subject"] = "Retrieve Forgotten Password";
            $mailData['body'] = "<p>Dear Sir/Madam,</p>
                                 <p>Your password is - " . $this->bocrypter->Decrypt($DBdata['encryptedPassword']) . "</p>
                                 <p>Thanks & Regards, <br/>Premier Bank Limited</p>";

            $this->load->model("mailer_model");
            $response = $this->mailer_model->sendMail($mailData);
            
            if ($response["success"]) {
                // change the forgotpassword flag //
                $update['forgotpassword'] = 1;
                $this->db->update('admin_users', $update, array('adminUserName' => $userName, 'email' => $email));
                $this->db->update('admin_users_mc', $update, array('adminUserName' => $userName, 'email' => $email));
                $data['message'] = "Old password has been sent to your provided email. Please check and login.";
                $data['body_template'] = "login.php";
                $this->load->view('site_template.php', $data);
            } else {
                $data['message'] = "Mail sending failed. Please Try again";
                $data['body_template'] = "forgot_password.php";
                $this->load->view('site_template.php', $data);
            }
        } else {
            $data['message'] = "The provided Username/Email is wrong";
            $data['body_template'] = "forgot_password.php";
            $this->load->view('site_template.php', $data);
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
