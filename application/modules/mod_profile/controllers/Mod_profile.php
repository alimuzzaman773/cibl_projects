<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of mod_profile
 *
 * @author Arif sTalKer Majid
 */
Class Mod_profile extends MX_Controller {
    function __construct() {
        parent::__construct();        
        $this->load->library("BOcrypter");
        $this->load->library('my_session');        
        $this->my_session->checkSession();        
    }
    
    function index()
    {
        $id = $this->my_session->userId;     
        $data = array();
        $this->load->model('Admin_users_model_maker');
        $result = $this->Admin_users_model_maker->getAdminUserById($id);
        if(!$result){
            redirect(base_url());
            die();
        }
        $data['uinfo'] = $result;
                
        /** initialization **/        
        $data['css'] = "";
                
        $data['js'] = "";  
        
        $data['css_files'] = array();
        $data['js_files'] = array();
        
        $data['pageTitle'] = "Profile Information";
        $data['base_url'] = base_url();
        
        $this->load->view("mod_profile_view.php",$data);
    }
    
    function edit_profile()
    {
        $action = "";
        if(array_key_exists("action", $_POST)){
            $action = $_POST['action'];
        }
        if($action == "edit_profile"){
            if(isset($_POST['action'])){ 
                
                $this->load->library(array("my_functions", "security"));
                
                $userid = $this->my_session->userId;
                $email = $this->input->post("email", true);
                $phone = $this->input->post("phone", true);
                
                
                $this->load->library("form_validation");
                $this->form_validation->CI =& $this;
                                
                $this->form_validation->set_rules('phone', 'Phone number', 'trim|required|xss_clean|required');
                $this->form_validation->set_rules('email', 'Email', 'callback_checkEmail');
                
                if ($this->form_validation->run() == FALSE)
                {
                    $errors['errors'] = validation_errors();
                    $errors['errorState'] = true;
                    echo json_encode($errors);
                }
                else
                {                                      
                    $time = time();

                    $userdata = array('email'=> $email,'phone'=> $phone);                    
                    $this->load->model('users_model');
                    $value = $this->users_model->updateUserInfo($userdata,$userid);
                    if($value){
                        $success['success'] = true;
                        $success['msg'] = "Your Information has been updated.";                        
                    }else{
                        $success['success'] = false;
                        $success['msg'] = "Your Information could not be updated. Please try again later.";                        
                    }
                    echo json_encode($success);
                }                
            }
        }else{
            die();
        }            
    }
    
       
    function checkEmail($str){
        
        $id = $this->my_session->userId;
        $old_name = "";
        $result = null;
        
        if(!filter_var($str, FILTER_VALIDATE_EMAIL))
        {
            $this->form_validation->set_message("checkEmail","The %s format address is not valid.");
            return false;
        }
        
        if(!empty($id) && is_numeric($id))
        {
            $this->db->where("user_id", $id);            
            $result = $this->db->get(TBL_USERS);
            if($result->num_rows() > 0)
            {
                foreach($result->result() as $row)
                {
                    $old_name = $row->email;
                }
                
                $this->db->where_not_in("email", array($old_name));
                $this->db->where("email", $str);
                //$this->db->where("userId", $id);
                $num_rows = $this->db->get(TBL_USERS)->num_rows();
                if($num_rows > 0)
                {
                    $this->form_validation->set_message("checkEmail","The %s already Exist. Please try a different email address.");
                    return false;
                }
            }
        }   
    }
    
    
    function edit_password()
    {
        $action = "";
        if(array_key_exists("action", $_POST)){
            $action = $_POST['action'];
        }
        if($action == "edit_password"){
            if(isset($_POST['action'])){ 
                
                $this->load->library(array("my_functions", "security"));
                
                $userid = (int)$this->my_session->userId;
                $oldpass = $this->input->post("oldpassword", true);
                $newpass = $this->input->post("newpassword", true);
                $confpass = $this->input->post("confirmpassword", true);
                
                $this->load->library("form_validation");
                $this->form_validation->CI =& $this;
                                
                $this->form_validation->set_rules('oldpassword', 'Old Password', 'trim|xss_clean|required|callback_checkOldPass');
                $this->form_validation->set_rules('newpassword', 'New Password', 'trim|xss_clean|required|matches[confirmpassword]');                
                $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'trim|xss_clean|required');
                
                if ($this->form_validation->run() == FALSE)
                {
                    $json = array(
                        'success' => FALSE,
                        'msg' => validation_errors()
                    );
                    echo json_encode($json);
                    die();
                }
                
                if($oldpass == $newpass){
                    $success['success'] = false;
                    $success['msg'] = "Your new password and old password is same";  
                    echo json_encode($success);
                    die();
                }
                
                try 
                {
                    $this->load->library('BOcrypter');

                    $this->db->trans_begin();
                    
                    $data = array(
                        "encryptedPassword" => $this->bocrypter->Encrypt($newpass)
                    );

                    $datetime = date("Y-m-d H:i:s");
                    
                    $this->db->reset_query();
                    $this->db->set('passwordChangeDtTm', $datetime);
                    $this->db->set('passwordChangeTms', 'passwordChangeTms +' . 1, FALSE);
                    
                    $this->db->where('adminUserId',$userid);
                    $this->db->update("admin_users_mc", $data);

                    $this->db->reset_query();
                    $this->db->set('passwordChangeDtTm', $datetime);
                    $this->db->set('passwordChangeTms', 'passwordChangeTms +' . 1, FALSE);
                    
                    $this->db->where('adminUserId',$userid);
                    $this->db->update("admin_users", $data);
                    
                    if($this->db->trans_status() === false):
                        throw new Exception('Could not update your password due to exception');
                    endif;
                    
                    $this->db->trans_commit();
                    $json = array(
                        'success' => true,
                        'msg' => 'Your password has been changed successfully'
                    );
                    my_json_output($json);                    
                }
                catch(Exception $ex)
                {
                    $this->db->trans_rollback();
                    $json = array(
                        'success' => false,
                        'msg' => $ex->getMessage()
                    );
                    my_json_output($json);
                }
                
            }
        }    
    }
    
    function checkOldPass($str)
    {
        $this->load->library("BOcrypter");
        $userid = (int)$this->my_session->userId;
        
        $this->db->where("adminUserId",  $userid);
        $result = $this->db->get("admin_users_mc");
        
        if($result->num_rows() <= 0)
        {
            $this->form_validation->set_message('checkOldPass', 'No user Accounts found');
            return FALSE;
        }
        
        $pass = $this->bocrypter->Decrypt($result->row()->encryptedPassword);
        if(trim($pass) != trim($str)):
            $this->form_validation->set_message('checkOldPass', 'The %s you entered is not correct.' /*.$str." :: ".$pass." :: ". json_encode($result->row())*/);
            return false;
        endif;
        return true;
    }
}

/** end of class Mod_profile **/