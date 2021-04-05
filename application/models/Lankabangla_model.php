<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lankabangla_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function generateFiles() {

        $result = $this->getTransactions();

        if (!$result["success"]) {
            return array(
                "success" => false,
                "msg" => $result["msg"]
            );
        }

        $this->load->library('Phpexcel/PHPExcel');
        $visaExcel = new PHPExcel();
        $masterExcel = new PHPExcel();
        $date = date("ymd");
        $sentData = array();
        $files = array();

        //visa card excel generate
        $vc = 0;
        foreach ($result["visaColumns"] as $vcol) {
            $visaExcel->getActiveSheet()->setCellValueByColumnAndRow($vc, 1, $vcol);
            $vc++;
        }

        $vr = 2;
        if (count($result["visaRows"]) > 0) {
            foreach ($result["visaRows"] as $visa) {
                $visaExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $vr, "");
                $visaExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(1, $vr, $visa["cardNo"],PHPExcel_Cell_DataType::TYPE_STRING2);
                $visaExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $vr, number_format($visa["amount"],2,".",""));
                $visaExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $vr, "");
                $visaExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $vr, $visa["currency"]);

                $vid = array(
                    "payment_id" => $visa["paymentId"],
                    "file_sent" => "Y"
                );
                array_push($sentData, $vid);

                $vr++;
            }
            
            $visaFileName = 'pbl_ibank_vis_' . $date . '.xls';
            $files[] = $visaFileName;
            
            $visaWriter = PHPExcel_IOFactory::createWriter($visaExcel, 'Excel5');
            $visaWriter->save(LANKABANGLA_FTP . $visaFileName);
        }

        //master card excel generate
        $mc = 0;
        foreach ($result["masterColumns"] as $mcol) {
            $masterExcel->getActiveSheet()->setCellValueByColumnAndRow($mc, 1, $mcol);
            $mc++;
        }


        $mr = 2;
        if (count($result["masterRows"]) > 0) {
            foreach ($result["masterRows"] as $master) {
                $masterExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $mr, "");
                $masterExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow(1, $mr, $master["cardNo"], PHPExcel_Cell_DataType::TYPE_STRING2);
                $masterExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $mr, number_format($master["amount"],2,".",""));
                $masterExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $mr, "");
                $masterExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $mr, $master["currency"]);

                $mid = array(
                    "payment_id" => $master["paymentId"],
                    "file_sent" => "Y"
                );
                array_push($sentData, $mid);

                $mr++;
            }
            
            $masterFileName = 'pbl.ibank_mas_' . $date . '.xls';
            $files[] = $masterFileName;
            
            $masterWriter = PHPExcel_IOFactory::createWriter($masterExcel, 'Excel5');
            $masterWriter->save(LANKABANGLA_FTP . $masterFileName);

            $txtSuccess = $this->writeTextFile($result["masterRows"]);
            if($txtSuccess['success']):
                $files[] = $txtSuccess['fileName'];
            endif;
        }
        
        if(count($sentData) > 0):
            try {
                $this->db->trans_begin();
                $this->db->update_batch('ssl_bill_payment', $sentData, 'payment_id');            
                
                if($this->db->trans_status() === false):
                    throw new Exception("Exception in transaction ".__CLASS__." | ".__FILE__." | ".__LINE__);
                endif;
                
                $this->db->trans_commit();
                return array(
                    'success' => true,
                    'files' => $files,
                    'sentData' => $sentData
                );
            }
            catch(Exception $e){
                $this->db->trans_rollback();
                return array(
                    'success' => false,
                    'msg' => $e->getMessage(),
                    'sentData' => $sentData
                );
            }
        endif;
        
    }

    function writeTextFile($masterData = array()) {

        $this->load->helper('file');
        $date = date("ymd");

        $detail = "";
        $sum = 0;
        foreach ($masterData as $data) {
            $detail .= "1" . str_pad($data["cardNo"], 25, " ", STR_PAD_RIGHT) 
                    . str_pad($data["name"], 30, " ", STR_PAD_RIGHT) 
                    . str_pad("PAYMENT RECEIVED - THANK YOU", 30, " ", STR_PAD_RIGHT) 
                    . "BDT" 
                    . str_pad($data["amount"] * 100, 12, "0", STR_PAD_LEFT) 
                    . "N"
                    . date("Ymd") 
                    . "" 
                    . str_pad("0", 25, "0", STR_PAD_RIGHT) 
                    . 'PREM000000000000000000000000000000000000000000000000000000000000'.PHP_EOL;
            $sum += (float)$data["amount"];
        }

        $sumTotal = (float)$sum * 100;
        
        $totalRecord = (int) count($masterData);
        
        $total = "2" . str_pad($sumTotal, 17, "0", STR_PAD_LEFT) 
                . str_pad($totalRecord, 6, "0", STR_PAD_LEFT)
                . "0000"
                . $date . str_pad("", 160, "0", STR_PAD_RIGHT);
        $text = $detail . $total;

        $txtFile = 'pbl.ibank_mas_' . $date . '.txt';
        $path = LANKABANGLA_FTP . $txtFile;
        $success = write_file($path, $text, 'a');
        
        return array(
            'success' => $success,
            'fileName' => $txtFile
        );
    }

    private function getTransactions() {

        $query = $this->db->select("sp.payment_id, sp.bill_response, at.amount")
                ->from("ssl_bill_payment sp")
                ->join("apps_transaction at", "sp.bp_transfer_id=at.transferId", "inner")
                ->where("sp.utility_name", "lankabangla")
                ->where("at.isSuccess", "Y")
                ->where("sp.file_sent", "N")
                ->get();


        if ($query->num_rows() <= 0) {
            return array(
                "success" => false,
                "msg" => "There are no transaction found"
            );
        }

        $visa = array();
        $master = array();
        foreach ($query->result() as $row) {
            $res = json_decode($row->bill_response, true);
            if (!isset($res["data"])) {
                continue;
            }
            $trn = $res["data"];
            $cardType = getLankabangaCardType($trn["cardNo"]);
            if ($cardType == "VISA") {
                $v = array(
                    "paymentId" => $row->payment_id,
                    "name" => trim($trn["name"]),
                    "cardNo" => trim($trn["cardNo"]),
                    "amount" => trim($row->amount),
                    "currency" => "50"
                );
                $visa[] = $v;
            }

            if ($cardType == "MASTER") {
                $m = array(
                    "paymentId" => $row->payment_id,
                    "name" => trim($trn["name"]),
                    "cardNo" => trim($trn["cardNo"]),
                    "amount" => trim($row->amount),
                    "currency" => "50"
                );
                $master[] = $m;
            }
        }

        $vColumns = array("SBK_NUM", "SBK_PAN", "SBK_SUM", "SBK_FIO", "SBK_ACUR");
        $mColumns = array("SBK_NUM", "SBK_PAN", "SBK_SUM", "SBK_FIO", "SBK_ACUR");

        return array(
            "success" => true,
            "visaColumns" => $vColumns,
            "masterColumns" => $mColumns,
            "visaRows" => $visa,
            "masterRows" => $master
        );
    }

}
