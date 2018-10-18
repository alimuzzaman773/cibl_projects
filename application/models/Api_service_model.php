<?php 

class Api_service_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getServiceInfo($p = array()) 
    {
        $this->db->select("s.*")
                 ->from('api_services s');
        if(isset($p['api_service_id']) && (int)$p['api_service_id'] > 0):
            $this->db->where('s.api_service_id', $p['api_service_id']);
        endif;
        
        $result = $this->db->get();
        return $result->num_rows() > 0 ? $result : false;
                 
    }
    
    function getServiceFields($p = array()) 
    {
        $this->db->select("f.*")
                 ->from('api_service_fields f')
                 ->join('api_services s', 's.api_service_id = f.api_service_id', 'inner');
        if(isset($p['api_service_id']) && (int)$p['api_service_id'] > 0):
            $this->db->where('f.api_service_id', $p['api_service_id']);
        endif;
        
        $result = $this->db->get();
        return $result->num_rows() > 0 ? $result : false;
                 
    }
}