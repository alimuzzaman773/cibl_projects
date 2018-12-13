<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Win_print_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function post_login($user, $pass) {
        $res = $this->authentication($user, $pass);
        if ($res['success'] == true) {
            $response['admin_id'] = $res['admin_id'];
            $response['user_name'] = $res['user_name'];
            $response['full_name'] = $res['full_name'];
            $row['success'] = true;
            $row['data'] = $response;
            return $row;
        } else {
            return $res;
        }
    }

    public function get_pin_list($user, $pass) {
        $res = $this->authentication($user, $pass);
        if ($res['success'] == true) {
            $query = $this->db->select('*')
                    ->from('generate_eblskyid')
                    ->where('isPrinted', 0)
                    ->where('mcStatus', 1)
                    ->get();
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $rows) {
                    $pin['generateId'] = $rows->generateId;
                    $pin['eblSkyId'] = $rows->eblSkyId;
                    $pin['isActive'] = ($rows->isActive == 1) ? "Active" : "Destroy";
                    $pin['isUsed'] = ($rows->isUsed == 1) ? "Used" : "Not Used";
                    $pin['isPrinted'] = ($rows->isPrinted == 1) ? "Printed" : "Not Printed";
                    $pin['isReset'] = ($rows->isReset == 1) ? "Reset" : "Not Reset";
                    $pin['checkerAction'] = $rows->checkerAction;
                    $pin['isPrinted'] = ($rows->isPrinted == 1) ? "Printed" : "Not Printed";
                    $pin['pin'] = $this->bocrypter->Decrypt($rows->pin);
                    $printData[] = $pin;
                }
                $data['success'] = true;
                $data['total'] = $query->num_rows();
                $data['data'] = $printData;
                return $data;
            } else {
                $response['success'] = false;
                $response['msg'] = "There are no PIN for printed";
                return $response;
            }
        } else {
            return $res;
        }
    }

    public function post_post_pin($user, $pass, $pin) {
        $res = $this->authentication($user, $pass);
        if ($res['success'] == true) {
            $this->db->trans_start();
            $pin_id = explode(",", $pin);
            foreach ($pin_id as $eblSkyId) {
                $data['eblSkyId'] = $eblSkyId;
                $data['isReset'] = 0;
                $data['isPrinted'] = 1;
                $data['mcStatus'] = 1;
                $data['makerAction'] = "Print";
                $data['makerActionCode'] = "print";
                $data['makerActionDt'] = date("Y-m-d");
                $data['makerActionTm'] = date("G:i:s");
                $data['makerActionBy'] = $res['admin_id'];
                $printedData[] = $data;
            }
            $this->db->update_batch('generate_eblskyid', $printedData, 'eblSkyId');
            // create activity log array to json encode //
            $pinPrintActivity['totalPinPrinted'] = count($pin_id);
            $pinPrintActivity['printedPinRange'] = $printedData[0]['eblSkyId'] . ' to ' . $printedData[count($pin_id) - 1]['eblSkyId'];
            $pinPrintActivity['makerActionBy'] = $res['admin_id'];
            $pinPrintActivity['makerActionDt'] = date("Y-m-d");
            $pinPrintActivity['makerActionTm'] = date("G:i:s");

            // prepare data for activity log //
            $activityLog = array(
                'activityJson' => json_encode($pinPrintActivity),
                'adminUserId' => $res['admin_id'],
                'adminUserName' => $res['user_name'],
                'tableName' => 'generate_eblskyid',
                'moduleName' => 'pin_module',
                'moduleCode' => '03',
                'actionCode' => 'print',
                'actionName' => 'Print',
                'creationDtTm' => date("Y-m-d G:i:s"));
            $this->db->insert('bo_activity_log', $activityLog);
            $response['success'] = true;
            $response['msg'] = "Successfully updated your PIN";
            $this->db->trans_complete();
            return $response;
        } else {
            return $res;
        }
    }

    private function authentication($user, $pass) {
        $query = $this->db->select('a.adminUserId, a.adminUserName, a.fullName, a.isActive as adminActive, a.isLocked as adminLock, g.isActive as groupActive, g.isLocked as groupLock, g.moduleCodes, g.actionCodes')
                ->from('admin_users a')
                ->join('admin_users_group g', 'a.adminUserGroup=g.userGroupId')
                ->where('adminUserName', $user)
                ->where('encryptedPassword', $pass)
                ->get();
        $response = array();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $moduleCodes = explode("|", $row->moduleCodes);
            $moduleIndex = array_search('03', $moduleCodes);
            $actionCodeIndex = strpos($row->actionCodes, 'print');
            if ($moduleIndex > -1 && $actionCodeIndex > -1) {
                if (($row->adminActive == 1 && $row->adminLock == 0) && ($row->groupActive == 1 && $row->groupLock == 0)) {
                    $response['success'] = true;
                    $response['admin_id'] = $row->adminUserId;
                    $response['user_name'] = $row->adminUserName;
                    $response['full_name'] = $row->fullName;
                } else {
                    $response['success'] = false;
                    $response['msg'] = "You or your group may be locked or inactive";
                }
            } else {
                $response['success'] = false;
                $response['msg'] = "You are not authorized person";
            }
        } else {
            $response['success'] = false;
            $response['msg'] = "Invalid username or password";
        }
        return $response;
    }

}
