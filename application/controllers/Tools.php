<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tools extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
    }

    function index() {
        $data['css'] = "";
        $data['js'] = "";

        $data['pageTitle'] = "Tools";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $data['body_template'] = "tools/tools.php";
        $this->load->view('site_template.php', $data);
    }

    function tools_form() {
        $this->load->view('tools/tools_form.php', array());
    }

    function soap_form() {
        $this->load->view('tools/soap_form.php', array());
    }

    function get_response() {
        $params["url"] = $this->input->post("url");
        $params["rows"] = $this->input->post("rows");

        $this->load->library('form_validation');

        $this->form_validation->set_rules('url', 'URL', 'trim|required');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );

            my_json_output($json);
        endif;

        $query = array();
        foreach (json_decode($params["rows"]) as $row) {
            $query[] = $row->key . "=" . $row->value;
        }

        $par = implode('&', $query);
        $url = $params["url"] . "?" . $par;

        include_once APPPATH . "libraries/Requests.php";
        Requests::register_autoloader();

        try {
            $request = Requests::get($url);

            $body = array(
                "request_url" => $request->url,
                "cbs_response" => $request->body
            );

            $data = array(
                "success" => $request->success,
                "msg" => json_encode($body),
                "request" => $request
            );
            my_json_output($data);
        } catch (Exception $e) {

            $data = array(
                "success" => FALSE,
                "msg" => $e->getMessage()
            );
            my_json_output($data);
        }
    }

    function get_soap_result() {
        $soapObj = $this->input->post("soap_obj");

        $this->load->library('form_validation');

        $this->form_validation->set_rules('soap_obj', 'Soap Object', 'trim|required');

        if ($this->form_validation->run() == FALSE):
            $json = array(
                "success" => false,
                "msg" => validation_errors('<p>', '</p>')
            );

            my_json_output($json);
        endif;


        include_once APPPATH . "libraries/Requests.php";
        Requests::register_autoloader();

        try {
            $url = "http://172.16.16.8:5939";
            $request = Requests::post($url, array(), $soapObj);

            $res = array(
                "success" => $request->success,
                "msg" => $request->body,
                "request" => $request
            );
            my_json_output($res);
        } catch (Exception $e) {

            $data = array(
                "success" => FALSE,
                "msg" => $e->getMessage()
            );
            my_json_output($data);
        }
    }

}
