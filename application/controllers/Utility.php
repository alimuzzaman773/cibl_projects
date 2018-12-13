<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Description of Pdf
 *
 * @author Arif sTalKer Majid
 */
Class Utility extends MX_Controller {
    function __construct() {
        parent::__construct();        
        ini_set('max_execution_time', 300); 
        $this->load->library('my_session');        
        $this->my_session->checkSession();
    }
    
    
    function output($outputContent = null, $outputType = null, $outputPaperSizeType = "A4")
    {
        $output = array("pdf","print","excel","zip");
        
        if($outputContent == null):
            $data['html'] = $this->input->post('outputContent');
        else:
            $data['html'] = $outputContent ;
        endif;
        
        if($outputType == null):
            $outputType = $this->input->post('outputType',true);
        else:
            $outputType = $outputType ;
        endif;
                
        //$outputType = (in_array($outputType, $output))?$outputType:"pdf";
        
        //$data['html'] = $this->input->post('outputContent');
        //$outputType = $this->input->post('outputType',true);
        
        if($outputPaperSizeType == null):
            $data['paperSize'] = $this->input->post('outputPaperSizeType',true);       
        else:
            $data['paperSize'] = $outputPaperSizeType ;
            $postPaperSize = $this->input->post('outputPaperSizeType',true);       
            $data['paperSize'] = $postPaperSize != NULL ? $postPaperSize : $data['paperSize'];
        endif;
        
        $outputType = (in_array($outputType, $output))?$outputType:"pdf";
        
        $noHeader = $this->input->post("noHeader",true);
        $addPageNo = $this->input->post("addPageNo",true);
        
        if($outputType == "pdf"):
            $this->__generate_pdf($data,$noHeader, $addPageNo);
        elseif($outputType == "excel"):
            $this->__generate_excel($data);
        elseif($outputType == "zip"):
            $this->__generate_zip($data);
        else:
            $this->__generate_print($data);
        endif;               
    }
    
    function __generate_zip($data)
    {
        $this->load->helper("file");
        
        $filename = "temporary-".date("d-m-y-his").".xls";;
        $filepath = ABS_SERVER_PATH.ASSETS_FOLDER."uploads/files/";
        
        $this->load->library('zip');
        
        
        //$file=date("his").".xls";        
        //header("Content-type: application/vnd.ms-excel");
        //header("Content-Disposition: attachment; filename=$file");
        $data = $this->load->view("common_pdf_view.php",$data,true);
        
        $this->zip->add_data($filename, $data);
        $this->zip->archive($filepath.$filename.".zip");

        // Download the file to your desktop. Name it "my_backup.zip"
        //$this->zip->download(date("ymd-his").'.zip');
        
        echo "<a href='".  base_url().ASSETS_FOLDER."uploads/files/".$filename.".zip'>Download</a>";
        die();
    }
    
    function __generate_excel($data)
    {
        $this->load->helper("download");
        
        $file=date("his").".xls";        
        //header("Content-type: application/vnd.ms-excel");
        //header("Content-Disposition: attachment; filename=$file");
        $excel = $this->load->view("common_pdf_view.php",$data,true);        
        //echo $data;
        //exit();
        
        //include_once APPPATH.'third_party/PHPExcel.php';
        
        //$objPHPExcel = new PHPExcel_Reader_HTML();
        

        force_download($file, $excel, true);

    }
    
    function test(){
        include_once APPPATH.'third_party/PHPExcel.php';
        
        //$objPHPExcel = new PHPExcel_Reader_HTML();
        
        // Put the html into a temporary file
        //$tmpfile = "temporary-".time();
        //file_put_contents(APPPATH."../assets/uploads/files/$tmpfile".'.html', $excel);

        // Read the contents of the file into PHPExcel Reader class
        $reader = new PHPExcel_Reader_HTML; 
        $content = $reader->load(ABS_SERVER_PATH."assets/uploads/files/temporary-1469696746.html"); 

        // Pass to writer and output as needed
        $objWriter = PHPExcel_IOFactory::createWriter($content, 'Excel2007');
        
        //header("Content-type: application/vnd.ms-excel");
        //header("Content-Disposition: attachment; filename=$tmpfile".".xls");
        
        $objWriter->save(ABS_SERVER_PATH."assets/uploads/files/temporary-test.xlsx");
        
    }
    
    function __generate_pdf($data = array(), $noHeader = false, $addPageNo = false)
    {        
        $data['base_url'] = base_url();       
        $tbl = $this->load->view("common_pdf_view.php",$data,true);
        //include_once (APPPATH.'third_party/mpdf/mpdf.php');
        
        $paperSize = array("A4", "A4-L");
        
        $data['paperSize'] = (isset($data['paperSize']) && in_array($data['paperSize'], $paperSize)) ? $data['paperSize'] : 'A4';
        
        $mpdf = new \Mpdf\Mpdf(array('mode' => 'utf-8', /*'orientation' => $data['paperSize'],*/ 'tempDir' =>"/tmp"));        
        
        if(!$noHeader):
            @$mpdf->SetHTMLHeader('<div style="text-align: right;"><img src="'.ABS_SERVER_PATH.ASSETS_FOLDER."images/logo.png".'" height="50px"/></div>', 'O', true);            
        endif;
        
        if($addPageNo):
            @$mpdf->SetFooter("{PAGENO}");            
        endif;
        
        @$mpdf->WriteHTML($tbl);
        @$mpdf->Output(date("his").".pdf","D");
        exit;        
    }
    
    function __generate_print($data = array())
    {
        $data['base_url'] = base_url();        
        $tbl = $this->load->view("common_pdf_view.php",$data);
    }
    
    function mail_report()
    {
        $recipients = $this->input->post("recipients",true);
        $messagebody = $this->input->post("message",true);
        $data['html'] = $this->input->post('outputContent');
        
        if(!$recipients || count($recipients) <= 0){
            $json['success'] = false;
            $json['msg'] = "No recipients founds";
            echo json_encode($json);
            die();
        }
        
        $reportName = "Report-".date("Ymd-his");
        $fileName = ABS_SERVER_PATH.ASSETS_FOLDER."temp/".$reportName.".pdf";
        
        $tbl = $this->load->view("common_pdf_view.php",$data,true);
        include_once (APPPATH.'third_party/mpdf/mpdf.php');
        $mpdf = new mPDF();
        $mpdf->WriteHTML($tbl);
        $mpdf->Output($fileName,"F");
        
        if(!file_exists($fileName)):
            if(is_file($fileName)):
                @unlink($fileName);
            endif;
            
            $json['success'] = false;
            $json['msg'] = "Cannot load attachment for mailing. Please contact system admin.";
            echo json_encode($json);
            die();
        endif;
        
        include APPPATH."libraries/phpmailer/5.2.9/PHPMailerAutoload.php";
        $mailer = new PHPMailer(true);
        try{            
            $mailer->IsHTML(true);
                    
            $mailer->IsSMTP();                    
            $mailer->SMTPDebug = 0;  
            $mailer->SMTPAuth = true;  
            $mailer->SMTPSecure = 'ssl'; 
            $mailer->Host = 'smtp.index-agro.com';
            $mailer->Port = 465; 
            $mailer->Username = "sales_force@index-agro.com";  
            $mailer->Password = "sales_force123*";
            
            foreach($recipients as $r):
                $mailer->AddAddress($r);
            endforeach;
            
            $mailer->AddReplyTo("sales_force@index-agro.com", "Index Sales Force");
            $mailer->From = "sales_force@index-agro.com";
            $mailer->FromName = "Index Sales Force";
            
            $mailer->Subject = "Index Sales Force >> Report";
            $mailer->Body = $messagebody;
            $mailer->AddAttachment($fileName, $reportName . "." ."pdf");            
            $mailer->Send();
            
            if(is_file($fileName)):
                @unlink($fileName);
            endif;
            
            $json['success'] = true;
            $json['msg'] = "Report has been mailed.";
            echo json_encode($json);
            die();
        }
        catch(phpmailerException $e){
            if(is_file($fileName)):
                @unlink($fileName);
            endif;
            $json['success'] = false;
            $json['msg'] = "Problem mailing attachment.";
            $json['mailerException'] = $e->getMessage();
            echo json_encode($json);
            die();
        }
        catch(Exception $e){
            if(is_file($fileName)):
                @unlink($fileName);
            endif;
            $json['success'] = false;
            $json['msg'] = "Problem mailing attachment.";
            $json['simpleException'] = $e->getMessage();
            echo json_encode($json);
            die();
        }
    }
    
    function test_mail(){
        $this->load->library("phpmailer");
        $mailer = new PHPMailer(true);
        try{           
            
            $mailer->IsHTML(true);
                    
            $mailer->IsSMTP();                    
            $mailer->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
            $mailer->SMTPAuth = true;  // authentication enabled
            $mailer->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
            $mailer->Host = 'smtp.gmail.com';
            $mailer->Port = 465; 
            $mailer->Username = "s.arif.majid@gmail.com";  
            $mailer->Password = "arifstalkermajid"; 

           
            $mailer->AddAddress("arif_avi@hotmail.com");      
            /** developement **/
            
            $mailer->AddReplyTo("arif@cibl-bd.com", "Arif Majid");
            $mailer->From = "info@cibl-bd.com";
            $mailer->FromName = "CIBL AOQMS";
            
            $mailer->Subject = "AOQMS Report Mailed";
            $mailer->Body = "test body";               
            $mailer->Send();
            
            $json['success'] = true;
            $json['msg'] = "Report has been mailed.";
            echo json_encode($json);
            die();            
        }
        catch(phpmailerException $e){            
            $json['success'] = false;
            $json['msg'] = "Problem mailing attachment.";
            $json['mailerException'] = $e->getMessage();
            echo json_encode($json);
            die();
        }
        catch(Exception $e){            
            $json['success'] = false;
            $json['msg'] = "Problem mailing attachment.";
            $json['simpleException'] = $e->getMessage();
            echo json_encode($json);
            die();
        }
    }
    
    function download()
    {
        $filename = $this->input->get("filename");
        if(!$filename){
            show_404();
            die();
        }
        
        $this->load->helper("download");
        
        $filepath = ABS_SERVER_PATH."assets/uploads/files/".$filename;
        if(!file_exists($filepath)){
            show_error("no file found");
            die();
        }
        force_download($filepath, NULL, true);
    }
    
}

/**end of pdf class**/