<?php

class Banking_service_request_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert_banking_request($data) {
        return $this->db->insert('service_request_bank', $data);
    }

    public function get_skyid($id) {
        $this->db->select('skyId');
        $this->db->where('eblSkyId', $id);
        $query = $this->db->get('apps_users');
        return $query->row_array();
    }

    public function is_active($code) {
        $this->db->select('isActive');
        $this->db->where('serviceTypeCode', $code);
        $query = $this->db->get('service_type');
        return $query->row_array();
    }

    public function getAllServiceTypeByModule($moduleName, $pServiceTypeCode, $cServiceTypeCode = NULL) {
        if ($cServiceTypeCode == NULL) {
            $query = $this->db->get_where('service_type', array('moduleName' => $moduleName, 'isActive' => 1, 'pServiceTypeCode' => $pServiceTypeCode));
            return $query->result();
        } else {
            $query = $this->db->get_where('service_type', array('moduleName' => $moduleName, 'isActive' => 1, 'pServiceTypeCode' => $cServiceTypeCode));
            return $query->result();
        }
    }

    function getChildService($serviceId) {
        $query = $this->db->select("*")
                ->from("service_type")
                ->where("isActive", 1)
                ->where("pServiceTypeCode", $serviceId)
                ->get();
        return $query->num_rows() > 0 ? $query : false;
    }

    /*
      public function getAllBankingRequest() {
      $this->db->order_by("serviceId", "desc");
      $this->db->select('service_request_bank.serviceId,
      service_request_bank.skyId,
      service_request_bank.typeCode,
      service_request_bank.referenceNo,
      service_request_bank.mobileNo,
      service_request_bank.reason,
      service_request_bank.requestDtTm,
      service_request_bank.status1,
      apps_users.userName,
      apps_users.eblSkyId,
      service_type.serviceName');

      $this->db->from('service_request_bank');
      $this->db->join('service_type', 'service_request_bank.typeCode = service_type.serviceTypeCode');
      $this->db->join('apps_users', 'service_request_bank.skyId = apps_users.skyId');
      $query = $this->db->get();
      return $query->result();
      }

      public function getAllBankingRequestByTypeCode($typeCode) {
      $this->db->order_by("serviceId", "desc");
      $this->db->select('service_request_bank.serviceId,
      service_request_bank.skyId,
      service_request_bank.typeCode,
      service_request_bank.referenceNo,
      service_request_bank.mobileNo,
      service_request_bank.reason,
      service_request_bank.requestDtTm,
      service_request_bank.status1,
      apps_users.userName,
      apps_users.eblSkyId,
      service_type.serviceName');


      $this->db->from('service_request_bank');
      $this->db->join('service_type', 'service_request_bank.typeCode = service_type.serviceTypeCode');
      $this->db->join('apps_users', 'service_request_bank.skyId = apps_users.skyId');
      $this->db->where("typeCode = '$typeCode'");
      $query = $this->db->get();
      return $query->result();
      }
     * */

    function getAllBankingRequest($params = array()) {
        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(service_request_bank.serviceId) as total");
        } else {
            $this->db->select('service_request_bank.serviceId,
                           service_request_bank.skyId,
                           service_request_bank.typeCode,
                           service_request_bank.referenceNo,
                           service_request_bank.mobileNo,
                           service_request_bank.reason,
                           service_request_bank.requestDtTm,
                           service_request_bank.status1,
                           apps_users.userName,
                           apps_users.eblSkyId,
                           service_type.serviceName', FALSE);
        }

        $this->db->from('product_apply_request');
        $this->db->from('service_request_bank');
        $this->db->join('service_type', 'service_request_bank.typeCode = service_type.serviceTypeCode');
        $this->db->join('apps_users', 'service_request_bank.skyId = apps_users.skyId');

        if (isset($params['type_code']) && trim($params['type_code']) != "") {
            $this->db->where("service_request_bank.typeCode", $params['type_code']);
        }

        $this->db->order_by("service_request_bank.serviceId", "desc");

        if (isset($params['limit']) && (int) $params['limit'] > 0) {
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        }
        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query : false;
    }

    public function getSingleRequestById($id) {
        $this->db->select('apps_users.userName,
                           apps_users.eblSkyId,
                           apps_users.clientId,
                           apps_users.userMobNo1,
                           apps_users.userEmail,
                           service_request_bank.*,
                           service_type.serviceName');


        $this->db->from('service_request_bank');
        $this->db->join('service_type', 'service_request_bank.typeCode = service_type.serviceTypeCode');
        $this->db->join('apps_users', 'service_request_bank.skyId = apps_users.skyId');
        $this->db->where("service_request_bank.serviceId = '$id'");
        $query = $this->db->get();
        return $query->row_array();
    }

    public function statusChange($id, $mailData, $bodyInstruction) {
        $dateTime = input_date();

        if ($bodyInstruction) {
            $previousInstruction = "";
            $this->db->where('serviceId', $id);
            $queryInstruction = $this->db->get('service_request_bank');
            if ($queryInstruction->num_rows() > 0) {
                $row = $queryInstruction->row();
                $previousInstruction = $row->mailBodyInstruction;
            }
            $data['mailBodyInstruction'] = $previousInstruction . "<br>" . $dateTime . "<br>" . $this->session->userdata('fullName') . "<br>" . $bodyInstruction;
        }


        $data['status1'] = 1;
        $data['updateDtTm'] = $dateTime;
        $data['updatedBy'] = $this->session->userdata('adminUserId');
        $this->db->where('serviceId', $id);
        $this->db->update('service_request_bank', $data);


        $bankingReqMailArr = array();
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
        $query = $this->db->get('banking_request_mail');
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
                    $this->db->where('bankingRequestMailId', $result['bankingRequestMailId']);
                    $this->db->update('banking_request_mail', $updateArr);
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
                    $bankingReqMailArr[] = $data;
                }

                $this->db->insert_batch('banking_request_mail', $bankingReqMailArr);
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
                $bankingReqMailArr[] = $data;
            }
            $this->db->insert_batch('banking_request_mail', $bankingReqMailArr);
        }
    }

}
