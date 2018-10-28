<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Help_setup_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getComplaintInfoList($params = array()) {
        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(ci.complaintInfoId) as total");
        } else {
            $this->db->select('ci.*', FALSE);
        }

        $this->db->from(TBL_COMPLAINT_INFO . " ci");

        if (isset($params['complaintInfoId']) && (int) $params['complaintInfoId']):
            $this->db->where("ci.complaintInfoId", $params['complaintInfoId']);
        endif;

        if (isset($params['search']) && trim($params['search']) != ""):
            $this->db->group_start()
                    ->or_like("ci.empName", $params['search'], 'both')
                    ->or_like("ci.email", $params['search'], 'both')
                    ->group_end();
        endif;

        if (isset($params['limit']) && (int) $params['limit'] > 0):
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        endif;

        $result = $this->db->order_by("ci.complaintInfoId", "DESC")->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

}
