<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Win_print extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        $this->load->model('win_print_model');
        $this->load->library('BOcrypter');
    }

    //login admin user
    public function login()
    {
        $login_data = $this->input->post('login_data', true);
        if ($login_data) {
            $data = json_decode($login_data);
            $user = $data->username;
            $pass = $this->bocrypter->Encrypt($data->password);
            $r = $this->win_print_model->post_login($user, $pass);
            echo json_encode($r);
        } else {
            $res['success'] = false;
            $res['msg'] = "Please provide valid data";
            echo json_encode($res);
        }
        die();
    }

    //All PIN not printed data
    public function pin_list()
    {
        $pin_data = $this->input->post('pin_data', true);
        if ($pin_data) {
            $data = json_decode($pin_data);
            $user = $data->username;
            $pass = $this->bocrypter->Encrypt($data->password);
            $r = $this->win_print_model->get_pin_list($user, $pass);
            echo json_encode($r);
        } else {
            $res['success'] = false;
            $res['msg'] = "Please provide valid data";
            echo json_encode($res);
        }
        die();
    }

    //updated printed PIN
    public function post_pin()
    {
        $update_data = $this->input->post('update_data', true);
        if ($update_data) {
            $data = json_decode($update_data);
            $user = $data->username;
            $pass = $this->bocrypter->Encrypt($data->password);
            $pin = $data->pin_id;
            $r = $this->win_print_model->post_post_pin($user, $pass, $pin);
            echo json_encode($r);
        } else {
            $res['success'] = false;
            $res['msg'] = "Please provide valid data";
            echo json_encode($res);
        }
        die();
    }
}