<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Settings
 *
 * @author Arif sTalKer Majid
 */
Class Settings extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('my_session');
        $this->my_session->checkSession();
        /* $this->my_session->hasModuleAccess(array(ADMIN_LEVEL))
          ->authorize("canManageSiteSettings"); */
    }

    function index() {
        /** initialization * */
        $data['css'] = "";
        $data['js'] = "";
        $data['pageTitle'] = "Application Settings";
        $data['base_url'] = base_url();

        $data['css_files'] = array();
        $data['js_files'] = array();

        $settings = array();

        $this->load->model(array("settings_model"));
        $result = $this->settings_model->getSettingsInfo();
        if ($result) {
            foreach ($result->result() as $r) {
                $settings[$r->code] = unserialize($r->value);
            }
        }
        $data['settings'] = $settings;

        $data['body_template'] = "settings/index.php";
        $this->load->view("site_template.php", $data);
    }

    function save_settings() {
        $settings = $this->input->post(NULL);

        $time = date("Y-m-d H:i:s");
        $data = array();
        foreach ($settings as $k => $v) {
            $data[$k] = array(
                "name" => str_replace("_", " ", $k),
                "code" => $k,
                "value" => serialize($v),
                "updateDtTm" => $time
            );
        }

        $this->load->model("settings_model");
        $result = $this->settings_model->save($data);

        echo json_encode($result);
        die();
    }

}

/** end of settings.php **/