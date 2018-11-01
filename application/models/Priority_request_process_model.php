<?php

class Priority_request_process_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
    }

    public function get_token_by_code($code) {
        $query = $this->db->get_where('token', array('code' => $code));
        return $query->row_array();
    }

    public function getAllRequest() {
        $this->db->order_by("serviceRequestID", "desc");
        $this->db->select('service_request.serviceRequestID,
                           service_request.typeCode,
                           service_request.referenceNo,
                           service_request.name,
                           service_request.contactNo,
                           service_request.email,
                           service_request.requestDtTm,
                           service_request.status,
                           service_type.serviceName,
                           apps_users.eblSkyId'
        );
        $this->db->from('service_request');
        $this->db->join('service_type', 'service_request.typeCode = service_type.serviceTypeCode');
        $this->db->join('apps_users', 'service_request.skyId = apps_users.skyId');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllRequestByTypeCode($params = array()) {

        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(serviceRequestID) as total");
        } else {
            $this->db->select('service_request.serviceRequestID,
                           service_request.typeCode,
                           service_request.referenceNo,
                           service_request.name,
                           service_request.contactNo,
                           service_request.email,
                           service_request.requestDtTm,
                           service_request.status,
                           service_type.serviceName,
                           apps_users.eblSkyId', FALSE);
        }

        $this->db->from('service_request');
        $this->db->join('service_type', 'service_request.typeCode = service_type.serviceTypeCode', "inner");
        $this->db->join('apps_users', 'service_request.skyId = apps_users.skyId', "left");

        if (isset($params['type_code']) && trim($params['type_code']) != "") {
            $this->db->where("typeCode", $params['type_code']);
        }

        if (isset($params['apps_id']) &&  trim($params['apps_id']) != "") {
            $this->db->where("apps_users.eblSkyId", $params['apps_id']);
        }

        if (isset($params['reference_no']) && trim($params['reference_no']) != "") {
            $this->db->where("service_request.referenceNo", $params['reference_no']);
        }

        if (isset($params['customer_name']) && trim($params['customer_name']) != "") {
            $this->db->like("service_request.name", $params['customer_name'], "both");
        }

        if (isset($params['mobile_no']) &&  trim($params['mobile_no']) != "") {
            $this->db->where("service_request.contactNo", $params['mobile_no']);
        }

        if (isset($params['limit']) && (int) $params['limit'] > 0) {
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        }

        $this->db->order_by("serviceRequestID", "desc");


        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query : false;
    }

    public function statusChange($id, $mailData, $bodyInstruction) {
        $dateTime = input_date();
        if ($bodyInstruction) {
            $previousInstruction = "";
            $this->db->where('serviceRequestID', $id);
            $queryInstruction = $this->db->get('service_request');
            if ($queryInstruction->num_rows() > 0) {
                $row = $queryInstruction->row();
                $previousInstruction = $row->mailBodyInstruction;
            }
            $data['mailBodyInstruction'] = $previousInstruction . "<br>" . $dateTime . "<br>" . $this->session->userdata('fullName') . "<br>" . $bodyInstruction;
        }


        $data['status'] = 1;
        $data['updateDtTm'] = $dateTime;
        $data['updatedBy'] = $this->session->userdata('adminUserId');
        $this->db->where('serviceRequestID', $id);
        $this->db->update('service_request', $data);

        $priorityReqMailArr = array();
        $data = array();
        $mailArr = array();

        if ($mailData['to']) {
            $mailToArr = explode(";", $mailData['to']);
            for ($i = 0; $i < count($mailToArr); $i++) {
                $mailArr[] = $mailToArr[$i];
            }
        }

        if ($mailData['cc']) {
            $mailCcArr = explode(";", $mailData['cc']);
            for ($i = 0; $i < count($mailCcArr); $i++) {
                $mailArr[] = $mailCcArr[$i];
            }
        }
        if ($mailData['bcc']) {
            $mailBccArr = explode(";", $mailData['bcc']);
            for ($i = 0; $i < count($mailBccArr); $i++) {
                $mailArr[] = $mailBccArr[$i];
            }
        }

        $this->db->where_in('receivedMail', $mailArr);
        $this->db->where('requestApplyId', $id);
        $query = $this->db->get('priority_request_mail');
        if ($query->num_rows() > 0) {
            $results = $query->result_array();
            foreach ($results as $result) {
                if (in_array($result['receivedMail'], $mailArr)) {
                    if (in_array($result['receivedMail'], $mailToArr)) {
                        $updateArr['toCounter'] = $result['toCounter'] + 1;
                        $updateArr['ccCounter'] = $result['ccCounter'];
                        $updateArr['bccCounter'] = $result['bccCounter'];
                        $mailToArr = array_diff($mailToArr, [$result['receivedMail']]);
                    }
                    if (in_array($result['receivedMail'], $mailCcArr)) {
                        $updateArr['toCounter'] = $result['toCounter'];
                        $updateArr['ccCounter'] = $result['ccCounter'] + 1;
                        $updateArr['bccCounter'] = $result['bccCounter'];
                        $mailCcArr = array_diff($mailCcArr, [$result['receivedMail']]);
                    }
                    if (in_array($result['receivedMail'], $mailBccArr)) {
                        $updateArr['toCounter'] = $result['toCounter'];
                        $updateArr['ccCounter'] = $result['ccCounter'];
                        $updateArr['bccCounter'] = $result['bccCounter'] + 1;
                        $mailBccArr = array_diff($mailBccArr, [$result['receivedMail']]);
                    }
                    $updateArr['updatedDtTm'] = date("Y-m-d G:i:s");
                    $updateArr['updatedBy'] = $this->session->userdata('adminUserId');
                    $this->db->where('priorityRequestMailId', $result['priorityRequestMailId']);
                    $this->db->update('priority_request_mail', $updateArr);
                    $mailArr = array_diff($mailArr, [$result['receivedMail']]);
                }
            }

            if ($mailArr) {
                foreach ($mailArr as $mailArray => $receivedMail) {
                    $data['requestApplyId'] = $id;
                    $data['receivedMail'] = $receivedMail;


                    if (in_array($receivedMail, $mailToArr)) {
                        $data['toCounter'] = 1;
                        $data['ccCounter'] = 0;
                        $data['bccCounter'] = 0;
                    }
                    if (in_array($receivedMail, $mailCcArr)) {
                        $data['toCounter'] = 0;
                        $data['ccCounter'] = 1;
                        $data['bccCounter'] = 0;
                    }
                    if (in_array($receivedMail, $mailBccArr)) {
                        $data['toCounter'] = 0;
                        $data['ccCounter'] = 0;
                        $data['bccCounter'] = 1;
                    }

                    $data['createdBy'] = $this->session->userdata('adminUserId');
                    $data['updatedBy'] = $this->session->userdata('adminUserId');
                    $data['createdDtTm'] = $dateTime;
                    $data['updatedDtTm'] = $dateTime;
                    $priorityReqMailArr[] = $data;
                }

                $this->db->insert_batch('priority_request_mail', $priorityReqMailArr);
            }
        } else {
            for ($i = 0; $i < count($mailArr); $i++) {
                $data['requestApplyId'] = $id;
                $data['receivedMail'] = $mailArr[$i];
                $data['toCounter'] = 0;
                $data['ccCounter'] = 0;
                $data['bccCounter'] = 0;
                if (in_array($mailArr[$i], $mailToArr)) {
                    $data['toCounter'] = 1;
                }
                if (in_array($mailArr[$i], $mailCcArr)) {
                    $data['ccCounter'] = 1;
                }
                if (in_array($mailArr[$i], $mailBccArr)) {
                    $data['bccCounter'] = 1;
                }
                $data['createdBy'] = $this->session->userdata('adminUserId');
                $data['updatedBy'] = $this->session->userdata('adminUserId');
                $data['createdDtTm'] = $dateTime;
                $data['updatedDtTm'] = $dateTime;
                $priorityReqMailArr[] = $data;
            }
            $this->db->insert_batch('priority_request_mail', $priorityReqMailArr);
        }
    }

    public function getSingleRequestById($id) {
        $query = $this->db->get_where('service_request', array('serviceRequestID' => $id));
        return $query->row_array();
    }

    public function getAllServiceTypeByModule($modulename) {
        $query = $this->db->get_where('service_type', array('moduleName' => $modulename, 'isActive' => 1));
        return $query->result();
    }

    public function insert_service_request($data) {
        return $this->db->insert('service_request', $data);
    }

}
