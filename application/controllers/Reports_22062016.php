<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Dhaka');
        
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('reports_model');
        $this->load->model('login_model');
        $this->load->library('session');
        if($this->login_model->check_session()){
            redirect('/admin_login/index');
        }
    }

    //Active User Report Form Pick
    public function user_status()
    {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(user_status, $moduleCodes);
        if($index > -1){
        $this->output->set_template('theme2');
        $status = $this->input->get('status');
        try {
            $data['rows'] = array();
            if ($status):
                $r = $this->reports_model->get_user_status($status);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
            $this->load->view('reports/user_status', $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }         
        }else{
            echo "not allowed";
        }
    }

    //Fund transfer Form Adding
    public function fund_transfer()
    {

        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(fund_transfer, $moduleCodes);
        if($index > -1){

        $this->output->set_template('theme2');
        $type = $this->input->get('type');
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        try {
            $data['rows'] = array();
            $value=range_validation($from, $to);
            if($value=="ok"){
            if ($type && $from && $to):
                $r = $this->reports_model->get_fund_transfer($type, $from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
           }
            $data['msg']=$value;
            $this->load->view('reports/fund_transfer', $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        }else{
            echo "not allowed";
        }
    }


    //Other Bank Fund transfer Form Adding
    public function other_fund_transfer()
    {

        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(other_fund_transfer, $moduleCodes);
        if($index > -1){

        $this->output->set_template('theme2');
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        try {
            $data['rows'] = array();
            $value=range_validation($from, $to);
            if($value=="ok"){
            if ($from && $to):
                $r = $this->reports_model->get_other_fund_transfer($from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
           }
            $data['msg']=$value;
            $this->load->view('reports/other_fund_transfer', $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        }else{
            echo "not allowed";
        }

    }

    //Bill pay date by date
    public function bill_pay()
    {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(billing_info, $moduleCodes);
        if($index > -1){
        $this->output->set_template('theme2');
        $type = $this->input->get('type');
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        try {
            $data['rows'] = array();
            $value=range_validation($from, $to);
            if($value=="ok"){
            if ($type && $from && $to):
                $r = $this->reports_model->get_bill_pay($type, $from, $to);
                if ($r):
                    $data['rows'] = $r;
                    $data['type'] = $type;
                endif;
            endif;
           }
            $data['msg']=$value;
            $this->load->view('reports/bill_pay', $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        }else{
            echo "not allowed";
        }

    }

    //All customer information
    public function customer_info()
    {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(customer_info, $moduleCodes);
        if($index > -1){

        $this->output->set_template('theme2');
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        try {
            $data['rows'] = array();
            $value=range_validation($from, $to);
            if($value=="ok"){
            if ($from && $to):
                $r = $this->reports_model->get_customer_info($from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
           }
            $data['msg']=$value;
            $this->load->view('reports/customer_info', $data);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        }else{
            echo "not allowed";
        }

    }

    //User login information date to date
    public function user_login_info()
    {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(user_login_info, $moduleCodes);
        if($index > -1){
        $this->output->set_template('theme2');
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        try {
            $data['rows'] = array();
            $value=range_validation($from, $to);
            if($value=="ok"){
            if ($from && $to):
                $r = $this->reports_model->get_login_info($from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
           endif;
         }              
        $data['msg']=$value;       

        $this->load->view('reports/user_login_info', $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        }else{
            echo "not allowed";
        }
    }


    //User ID modification from BO
    public function id_modification()
    {
        $moduleCodes = $this->session->userdata('reportTypeModules');
        $moduleCodes = explode("|", $moduleCodes);
        $index = array_search(user_id_modification, $moduleCodes);
        if($index > -1){

        $this->output->set_template('theme2');
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        try {
            $data['rows'] = array();
            $value=range_validation($from, $to);
            if($value=="ok"){
            if ($from && $to):
                $r = $this->reports_model->get_id_modification($from, $to);
                if ($r):
                    $data['rows'] = $r;
                endif;
            endif;
         }              
        $data['msg']=$value;  
        $this->load->view('reports/id_modification', $data);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        }else{
            echo "not allowed";
        }

    }
}
