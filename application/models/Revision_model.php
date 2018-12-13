<?php

class Revision_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function getAppsUser($skyId)
    {
        $result = $this->db->where("skyId", $skyId)
                           ->get("apps_users");
        if($result->num_rows() > 0){
            return $result;
        }
        return false;
    }
    
    function add($row)
    {
        try {
            $this->db->insert("apps_users_revisions", $row);
            return array(
                'success' => true,
                'revisionId' => $this->db->insert_id()
            );
        }
        catch(Exception $ex){
            return array(
                'success' => false,
                "msg" => $ex->getMessage()
            );
        }
    }
    

}

/**endoffile**/