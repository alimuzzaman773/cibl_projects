<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of profile
 *
 * @author Arif sTalKer Majid
 */
Class Profile extends MX_Controller {
    function __construct() {
        parent::__construct();        
        $this->load->library('my_session');
        
        $this->my_session->checkSession();
        //load_controller_lang(__CLASS__);
    }
    
    function index()
    {
        /** initialization **/        
        $data['css'] = "";
                
        $data['js'] = "";        
        
        $data['pageTitle'] = "Profile Information";
        $data['base_url'] = base_url();
        
        $data['css_files'] = array();
        $data['js_files'] = array();
        
        $data['body_template'] = "profile/profile_view.php";
        $this->load->view('site_template.php',$data);  
    }
    
    function edit_profile()
    {
        Modules::load("mod_profile");
        $profile = new Mod_profile();
        
        $profile->edit_profile();
    }
    
    function edit_password()
    {
        Modules::load("mod_profile");
        $profile = new Mod_profile();
        
        $profile->edit_password();
    }

}

/** end of class Profile **/