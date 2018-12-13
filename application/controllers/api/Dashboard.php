<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->library('my_session');
        $this->my_session->checkSession();
    }

    function get_apps_user_stats()
    {
        $this->load->model("dashboard_model");
        $result = $this->dashboard_model->getAppsUserStats();        
        
        $data = array(
            'totalId' => 0,
            'totalActiveId' => 0,
            'totalInactiveId' => 0,
            'totalLockedId' => 0
        );
        if($result):
            foreach($result->result() as $r):
                $data['totalId'] += (int)$r->totalActiveId;
                $data['totalActiveId'] += (int)$r->totalActiveId;
                $data['totalInactiveId'] += (int)$r->totalInActiveId;
                $data['totalLockedId'] += (int)$r->totalLockedId;
            endforeach;
        endif;
        //d($data);
        $data['result'] = $this->load->view('dashboard/get_apps_user_stats.php', $data,true);
        my_json_output($data);
    }
    
    function get_apps_registration_stats()
    {
        $this->load->model("dashboard_model");
        $result = $this->dashboard_model->getAppsUserRegistrationStats();
        
        $data = array(
            'totalId' => 0,
            'registrationType' => array(),
            'passwordResetCount' => 0,
            'activationRequest' => 0,
            'activationPending24' => 0,
            'passwordResetPending24' => 0
        );
        if($result):
            foreach($result->result() as $r):
                $data['totalId'] += (int)$r->totalId;
                if(!isset($data['registrationType'][$r->entityType])):
                    $data['registrationType'][$r->entityType] = 0;
                endif;
                $data['registrationType'][$r->entityType] += $r->registrationCount;
            endforeach;
        endif;
        
        $passResult = $this->dashboard_model->getAppsUserPasswordRequest();
        if($passResult):
            $data['passwordResetCount'] = $passResult->row()->passwordResetCount;
        endif;
     
        $p = array(
            'skyIdOriginal' => 0,
        );        
        $activationResult = $this->dashboard_model->getUserList($p);        
        if($activationResult):
            $data['activationRequest'] = $activationResult->row()->total;
        endif;
        
        $p = array(
            'skyIdOriginal' => 0,
            'activationPending24' => 24*60*60,
        );        
        $activationResult = $this->dashboard_model->getUserList($p);        
        if($activationResult):
            $data['activationPending24'] = $activationResult->row()->total;
        endif;
        
        $p = array(
            'skyIdOriginal' => 1,
            'passwordResetPending24' => 24*60*60,
            'passwordReset' => 1
        );        
        $activationResult = $this->dashboard_model->getUserList($p);                
        if($activationResult):
            //echo $this->db->last_query();
            //d($activationResult->row());
            $data['passwordResetPending24'] = $activationResult->row()->total;
        endif;
        
        $data['result'] = $this->load->view('dashboard/get_apps_registration_stats.php', $data, true);
        my_json_output($data);
        
    }

}
