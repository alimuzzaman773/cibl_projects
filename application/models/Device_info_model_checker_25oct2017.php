<?php


class Device_info_model_checker extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        
        $this->load->database();
    }


    public function getUnapprovedDevice()
    {
        $this->db->order_by("deviceId","desc");
        $this->db->where('device_info_mc.mcStatus =', 0);
        $this->db->where('device_info_mc.makerActionBy !=', $this->session->userdata('adminUserId'));
        $this->db->select('device_info_mc.*, apps_users_mc.eblSkyId');
        $this->db->from('device_info_mc');
        $this->db->join('apps_users_mc', 'apps_users_mc.skyId = device_info_mc.skyId');
        $query = $this->db->get();
        return $query->result();
    }


    public function getDeviceById($id)
    {
        $this->db->select('device_info_mc.*,

                          device_info.deviceId as deviceId_c,
                          device_info.skyId as skyId_c,
                          device_info.imeiNo as imeiNo_c,
                          device_info.isVaryfied as isVaryfied_c,
                          device_info.varyfiedDtTm as varyfiedDtTm_c,
                          device_info.mcStatus as mcStatus_c,
                          device_info.makerAction as makerAction_c,
                          device_info.makerActionDt as makerActionDt_c,
                          device_info.makerActionTm as makerActionTm_c,
                          device_info.makerActionBy as makerActioBy_c,
                          device_info.checkerActionComment as checkerActionComment_c,
                          device_info.checkerActionDt as checkerActionDt_c,
                          device_info.checkerActionTm as checkerActionTm_c,
                          device_info.checkerActionBy as checkerActionBy_c,
                          device_info.isPublished as isPublished_c,
                          device_info.isActive as isActive_c,


                          apps_users_mc.eblSkyId');

        $this->db->from('device_info_mc');
        $this->db->join('device_info', 'device_info.deviceId = device_info_mc.deviceId', 'left');
        $this->db->join('apps_users_mc', 'apps_users_mc.skyId = device_info_mc.skyId');

        $this->db->where('device_info_mc.deviceId', $id);
        $query =$this->db->get();
        return $query->row_array();
    }



    public function countDevice($skyId)
    {
        $this->db->where('skyId', $skyId);
        $this->db->from('device_info');
        $count = $this->db->count_all_results();  
        return $count;
    }



    public function getUserPublishedInfo($id)
    {
        $this->db->select('apps_users_mc.isPublished, apps_users_mc.userMobNo1, apps_users_mc.userMobNo2');
        $query = $this->db->get_where('apps_users_mc', array('skyId' => $id));
        $result = $query->row_array();
        return $result;
    }


    public function UpdateInsertCheckerApprove($id, $data)
    {
        $this->db->where('deviceId', $id);
        $this->db->update('device_info_mc', $data);
        $query = $this->db->get_where('device_info_mc', array('deviceId' => $id));
        $result = $query->row_array();
        $this->db->insert('device_info', $result);


        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
                             'adminUserId' => $this->session->userdata('adminUserId'),
                             'adminUserName' => $this->session->userdata('username'),
                             'tableName' => 'device_info',
                             'moduleName' => 'device_module',
                             'moduleCode' => '02',
                             'actionCode' => $result['makerActionCode'],
                             'actionName' => $result['makerAction'],
                             'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }




    public function UpdateUpdateCheckerApprove($id, $data)
    {
        $this->db->where('deviceId', $id);
        $this->db->update('device_info_mc', $data);

        $query = $this->db->get_where('device_info_mc', array('deviceId' => $id));
        $result = $query->row_array();

        $this->db->where('deviceId', $id);
        $this->db->update('device_info', $result);

        // prepare data for activity log //
        $activityLog = array('activityJson' => json_encode($result),
                             'adminUserId' => $this->session->userdata('adminUserId'),
                             'adminUserName' => $this->session->userdata('username'),
                             'tableName' => 'device_info',
                             'moduleName' => 'device_module',
                             'moduleCode' => '02',
                             'actionCode' => $result['makerActionCode'],
                             'actionName' => $result['makerAction'],
                             'creationDtTm' => date("Y-m-d G:i:s"));
        $this->db->insert('bo_activity_log', $activityLog);
    }



    public function checkerReject($id, $data)
    {
        $this->db->where('deviceId', $id);
        $this->db->update('device_info_mc', $data);
    }


}