<?php

class Product_request_process_model extends CI_Model {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
    }

    public function insert_product_request($data) {
        return $this->db->insert('product_apply_request', $data);
    }

    public function getProductRequestByDate($params = array()) {

        if (isset($params['count']) && $params['count'] == true) {
            $this->db->select("COUNT(applyId) as total");
        } else {
            $this->db->select('*', FALSE);
        }

        $this->db->from('product_apply_request');

        if ((isset($params['from_date']) && trim($params['from_date']) != "") && (isset($params['to_date']) && trim($params['to_date']) != "")) {
            $this->db->where('creationDtTm >=', $params["from_date"])
                    ->where('creationDtTm <=', $params["to_date"]);
        }

        if(isset($params['search']) && trim($params['search']) != "") {
            $this->db->group_start()
                    ->or_like("product_apply_request.productName", $params['search'], 'both')
                    ->or_like("product_apply_request.email", $params['search'], 'both')
                    ->or_like("product_apply_request.contactNo", $params['search'], 'both')
                    ->group_end();
        }

        $this->db->order_by("applyId", "desc");

        if (isset($params['limit']) && (int) $params['limit'] > 0) {
            $offset = (isset($params['offset'])) ? $params['offset'] : 0;
            $this->db->limit($params['limit'], $offset);
        }
        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query : false;
    }

    public function statusChange($id, $mailData, $bodyInstruction) {
        $dateTime = input_date();
        if ($bodyInstruction) {
            $previousInstruction = "";
            $this->db->where('applyId', $id);
            $queryInstruction = $this->db->get('product_apply_request');
            if ($queryInstruction->num_rows() > 0) {
                $row = $queryInstruction->row();
                $previousInstruction = $row->mailBodyInstruction;
            }
            $data['mailBodyInstruction'] = $previousInstruction . "<br>" . $dateTime . "<br>" . $this->my_session->userInfo['fullName'] . "<br>" . $bodyInstruction;
        }

        $data['status'] = 1;
        $data['updatedBy'] = $this->my_session->userId;
        $data['updateDtTm'] = $dateTime;
        $this->db->where('applyId', $id);
        $this->db->update('product_apply_request', $data);

        $productReqMailArr = array();
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
        $query = $this->db->get('product_request_mail');
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
                    $updateArr['updatedBy'] = $this->my_session->userId;
                    $this->db->where('productRequestMailId', $result['productRequestMailId']);
                    $this->db->update('product_request_mail', $updateArr);
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

                    $data['createdBy'] = $this->my_session->userId;
                    $data['updatedBy'] = $this->my_session->userId;
                    $data['createdDtTm'] = $dateTime;
                    $data['updatedDtTm'] = $dateTime;
                    $productReqMailArr[] = $data;
                }

                $this->db->insert_batch('product_request_mail', $productReqMailArr);
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
                $data['createdBy'] = $this->my_session->userId;
                $data['updatedBy'] = $this->my_session->userId;
                $data['createdDtTm'] = $dateTime;
                $data['updatedDtTm'] = $dateTime;
                $productReqMailArr[] = $data;
            }
            $this->db->insert_batch('product_request_mail', $productReqMailArr);
        }
    }

    public function getSingleRequestById($id) {
        $query = $this->db->get_where('product_apply_request', array('applyId' => $id));
        return $query->row_array();
    }

}
