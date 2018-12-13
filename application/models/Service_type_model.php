<?php

class Service_type_model extends CI_Model {

    //put your code here
    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
    }

    public function get_all_service_type_by_module($modulename) {
        // $this->db->order_by("serviceID","desc");
        $query = $this->db->get_where('service_type', array('moduleName' => $modulename, 'isActive' => 1));

        // $query=$this->db->order_by("serviceID","desc");
        return $query->result();
    }

    public function get_all_service_type_by_module1($modulename) {

        $query = $this->db->get('service_type');
        // $this->db->where("typeCode = '$typecode'");
        return $query->result();
    }

}
