<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_model extends CI_Model 
{
    function __construct() {
        parent::__construct();
    }
    
    function getAppsUserStats($p = array())
    {
        $this->db->select("count(IF(au.isActive = 1 ,au.skyId, NULL)) as totalActiveId, 
                           count(IF(au.isActive = 0 ,au.skyId, NULL)) as totalInActiveId, 
                           count(IF(au.isLocked = 1 ,au.skyId, NULL)) as totalLockedId,
                           au.isActive",false);
        
        $this->db->from('apps_users au');
                
        
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query : false;
    }
    
    function getAppsUserRegistrationStats($p = array())
    {
        $this->db->select("count(aum.skyId) as totalId, ra.entityType, count(ra.raId) as registrationCount",false);
        
        $this->db->from('apps_users_mc aum')
                ->join('apps_users au', "au.skyId = aum.skyId", "left")
                ->join('registration_attempts ra', 'ra.skyId = aum.skyId', 'left');
        
        $this->db->where('(au.skyId IS NULL OR au.skyId <= 0)', null, false)
                 ->where("ra.skyId > 0", null,false);
                
        $query = $this->db->group_by("ra.entityType")->get();
        return $query->num_rows() > 0 ? $query : false;
    }
    
    function getAppsUserPasswordRequest($p = array())
    {
        $this->db->select("count(au.passwordReset) as passwordResetCount",false);        
        $this->db->from('apps_users au')
                 ->where("au.passwordReset", 1);
                
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query : false;
    }
    
    function getUserList($p = array())
    {
        $dateTime = date("Y-m-d H:i:s");
        
        //if(isset($p['skyIdOriginal']) && $p['skyIdOriginal'] == 0){
            $this->db->select("count(aum.skyId) as total",false);
        //}
        
        /*$this->db->select("aum.*, au.skyId as skyIdOriginal, ra.created_on as registrationDate, ra.entityType,"
                        . " TIMESTAMPDIFF(SECOND, CONCAT(aum.makerActionDt, ' ', aum.makerActionTm), '{$dateTime}') as activationDiff,"
                        . " TIMESTAMPDIFF(SECOND, aum.passwordResetDtTm, '{$dateTime}') as passwordResetDiff",false);
        */
        $this->db->from('apps_users_mc aum')
                ->join('apps_users au', "au.skyId = aum.skyId", "left")
                ->join('registration_attempts ra', 'ra.skyId = aum.skyId', 'left');
                
        if(isset($p['skyIdOriginal']) && $p['skyIdOriginal'] == 0){
            $this->db->where('(au.skyId IS NULL OR au.skyId <= 0)', null, false);
            if(isset($p['fromdate']) && isset($p['todate'])){
                $this->db->where("ra.created_on between {$this->db->escape($p['fromdate'])} AND {$this->db->escape($p['todate'])}", null, false);
            }
        }
        
        if(isset($p['skyIdOriginal']) && (int)$p['skyIdOriginal'] > 0){
            $this->db->where('(au.skyId > 0)', null, false);
            if(isset($p['fromdate']) && isset($p['todate'])){
                $this->db->where("ra.created_on between {$this->db->escape($p['fromdate'])} AND {$this->db->escape($p['todate'])}", null, false);
            }
        }
        
        if(isset($p['passwordReset']) && $p['passwordReset'] != NULL)
        {
            $this->db->where('aum.passwordReset', $p['passwordReset']);
        }
        
        if(isset($p['activationPending24']) && (int)$p['activationPending24']  > 0){
            $this->db->where("TIMESTAMPDIFF(SECOND, CONCAT(aum.makerActionDt, ' ', aum.makerActionTm), '{$dateTime}') > {$this->db->escape($p['activationPending24'])}", null,false);
        }
        
        if(isset($p['passwordResetPending24']) && (int)$p['passwordResetPending24']  > 0){            
            $this->db->where("TIMESTAMPDIFF(SECOND, aum.passwordResetDtTm, '{$dateTime}') > {$this->db->escape($p['passwordResetPending24'])}", null,false);
        }
        
        $sql = '';
        if(isset($p['skyIdOriginal']) && $p['skyIdOriginal'] == 1):
            $sql = $this->db->get_compiled_select();
            $sql = "select count(*) as total from ({$sql}) as intbl";
        else:
            $sql = $this->db->get_compiled_select();
        endif;
        
        $query = $this->db->query($sql);
        return $query->num_rows() > 0 ? $query : false;
    }
}

/** end of file **/