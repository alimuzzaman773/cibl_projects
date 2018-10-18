<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_services extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('my_session');
        
        $this->load->library('grocery_CRUD');
    }
    
    function index()
    {
        $this->my_session->checkSession();
        
        try{
            /* This is only for the autocompletion */
            $crud = new grocery_CRUD();
            $crud->unset_jquery();

            $crud->set_theme(TABLE_THEME);
            $crud->set_table('api_services');            
            $crud->set_subject('Api Services');
            
            $crud->required_fields(array('name','machine_name', 'api_url', 'method'));
            
            $crud->set_rules('name', 'Name', 'trim|required|xss_clean');  
            $crud->set_rules('api_url', 'API Url', 'trim|xss_clean|required');
            $crud->set_rules('machine_name', 'Machine Name', 'trim|xss_clean|callback__checkMachineName');
            
            $crud->columns('name','machine_name', 'api_url');
            
            $time = date("Y-m-d H:i:s");
            $crud->add_fields('name','machine_name', 'method', 'api_url','config_data','created','updated');
            $crud->edit_fields('name','machine_name', 'method', 'api_url', 'config_data', 'updated');
            
            $crud->change_field_type('created', 'hidden', $time);
            $crud->change_field_type('updated', 'hidden', $time);           
            
            $crud->callback_add_field('user_level', array($this, 'add_field_callback_level'));
            $crud->callback_edit_field('user_level', array($this, 'add_field_callback_level_selected'));
            
            $crud->unset_texteditor('config_data','api_url');
            
            $crud->callback_add_field('config_data', function () {
                $cData['config_data'] = array();
                return $this->load->view('api_services/crud_config.php', $cData, true);
            });
            
            $crud->callback_edit_field('config_data', function ($value, $primaryKey) {
                /*$cd = json_decode($value,true);
                $configArray = array();
                foreach($cd as $k => $v):
                    $configArray['key'] = 
                endforeach;*/
                $cData['config_data'] = json_decode($value,true);
                
                return $this->load->view('api_services/crud_config.php', $cData, true);
            });
            
            $crud->callback_before_insert(array($this,'changeConfigData'));
            $crud->callback_before_update(array($this,'changeConfigData'));
            
            if(!isset($this->my_session->permissions['canAddUserGroup']))
            {                
             //   $crud->unset_add();
            }
            
            if(!isset($this->my_session->permissions['canEditUserGroup']))
            {                
               // $crud->unset_edit();
            }
            
            if(!isset($this->my_session->permissions['canDeleteUserGroup']))
            {                
                $crud->unset_delete();
            }
            $crud->unset_delete();
                        
            //if(isset($this->my_session->permissions['canSetUserGroupPermission'])){
                $crud->add_action("Fields", "", base_url().'api_services/fields/', 'glyphicon glyphicon-user');
            //}
            
            $output = $crud->render();
            
            /** initialization **/
            $output->css = "";        
            $output->js = "";        
            $output->pageTitle = "Api Services";
            $output->base_url = base_url();
            $output->body_template = "api_services/index.php";
            
            $output->crudState = $crud->getState();
            
            $this->load->view("site_template.php",$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    
    function changeConfigData($post_array, $primary_key = null) {
        $configData = array();
        $pk = $this->input->post('config_data_key',true);
        $pv = $this->input->post('config_data_val',true);
        foreach($pk as $k => $v):
            $item = array(
                'key' => $v,
                'val' => $pv[$k]
            );
            $configData[] = $item;
        endforeach;
        
        $post_array['config_data'] = json_encode($configData);
        unset($post_array['config_data_key']);
        unset($post_array['config_data_value']);

        return $post_array;
    }        
    
    function _checkMachineName($machineName)
    {
        if(trim($this->input->post("machine_name",true)) == ""){
            $this->form_validation->set_message("_checkMachineName","Machine Name is required");            
            return false;
        }       
        
        $ugId = (int)$this->uri->segment(4);
        $this->db->select("*")
                 ->from('api_services')
                 ->where("machine_name",$machineName);
        if($ugId > 0):
            $this->db->where_not_in("id",array($ugId));
        endif;
        $result = $this->db->get();
        if($result->num_rows() > 0):
            $this->form_validation->set_message("_checkMachineName","Please select a different machine name.");            
            return false;
        endif;
    }
    
    function fields()
    {
        $this->my_session->checkSession();
        
        try{
            /* This is only for the autocompletion */
            $crud = new grocery_CRUD();
            $crud->unset_jquery();

            $crud->set_theme(TABLE_THEME);
            $crud->set_table('api_service_fields');            
            $crud->set_subject('Api Service Fields');
            
            $crud->set_relation('api_service_id','api_services','{name}');
            
            $crud->required_fields(array('api_service_id','label', 'field_name', 'field_type', 'field_type_cast', 'is_required'));
            
            $crud->set_rules('api_service_id', 'Service ID', 'trim|required|xss_clean|integer|greater_than[0]');  
            $crud->set_rules('label', 'Field Label', 'trim|xss_clean|required|callback__checkFieldLabel');
            $crud->set_rules('field_name', 'Field Name', 'trim|xss_clean|callback__checkFieldName');
            
            $crud->columns('api_service_id','label', 'field_name', 'field_type', 'field_type_cast', 'is_required');
            
            $time = date("Y-m-d H:i:s");
            $crud->add_fields('api_service_id','label', 'field_name', 'field_type', 'field_format', 'data', 'field_type_cast', 'is_required','created','updated');
            $crud->edit_fields('api_service_id','label', 'field_name', 'field_type','field_format', 'data', 'field_type_cast', 'is_required', 'updated');
            
            $crud->change_field_type('created', 'hidden', $time);
            $crud->change_field_type('updated', 'hidden', $time);           
            
            $is_required_array = array(
                '1' => 'yes',
                '0' => 'no'
            );
            $crud->change_field_type('is_required', 'dropdown', $is_required_array);           
            
            $crud->unset_texteditor('data');
            
            if(!isset($this->my_session->permissions['canAddUserGroup']))
            {                
             //   $crud->unset_add();
            }
            
            if(!isset($this->my_session->permissions['canEditUserGroup']))
            {                
               // $crud->unset_edit();
            }
            
            if(!isset($this->my_session->permissions['canDeleteUserGroup']))
            {                
                $crud->unset_delete();
            }
            $crud->unset_delete();
                        
            
            $output = $crud->render();
            
            /** initialization **/
            $output->css = "";        
            $output->js = "";        
            $output->pageTitle = "Api Service Fields";
            $output->base_url = base_url();
            $output->body_template = "api_services/fields.php";
            
            $output->crudState = $crud->getState();
            
            $this->load->view("site_template.php",$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    
    function _checkFieldLabel($label){
        $fieldId = (int)$this->uri->segment(5);
        $api_service_id = (int) $this->input->post('api_service_id', true);
        $this->db->select("*")
                 ->from('api_service_fields')
                 ->where('api_service_id', $api_service_id)
                 ->where("label",$label);
        if($fieldId > 0):
            $this->db->where_not_in("field_id",array($fieldId));
        endif;
        $result = $this->db->get();
        if($result->num_rows() > 0):
            $this->form_validation->set_message("_checkFieldLabel","Please select a different field label.". $this->db->last_query());            
            return false;
        endif;
        
        return true;
    }
    
    function _checkFieldName($name){
        $fieldId = (int)$this->uri->segment(5);
        $api_service_id = (int) $this->input->post('api_service_id', true);
        $this->db->select("*")
                 ->from('api_service_fields')
                 ->where('api_service_id', $api_service_id)
                 ->where("field_name",$name);
        if($fieldId > 0):
            $this->db->where_not_in("field_id",array($fieldId));
        endif;
        $result = $this->db->get();
        if($result->num_rows() > 0):
            $this->form_validation->set_message("_checkFieldName","Please select a different field name.");            
            return false;
        endif;
        
        return true;
    }
    
    function render($service_id)
    {
        //$this->my_session->checkSession();
        
        $this->load->model("api_service_model");
        
        $params = array(
            'api_service_id' => $service_id
        );
        $res = $this->api_service_model->getServiceInfo($params);
        if(!$res):
            show_error('no service found');
            die();
        endif;
        
        $serviceInfo = $res->row();
        
        $res = $this->api_service_model->getServiceFields($params);
        if(!$res):
            show_error('no service fields found');
            die();
        endif;
        
        $serviceFields = $res->result();
        
        
        $data['serviceInfo'] = $serviceInfo;
        $data['serviceFields'] = $serviceFields;
        //d($data);
        $data['body_template'] = 'api_services/service_form.php';
        $this->load->view('api_service_template.php', $data);
        
    }
    
    function execute_service($service_id)
    {
        $post = $this->input->post(null,false);
        
        $this->load->model("api_service_model");
        
        $params = array(
            'api_service_id' => $service_id
        );
        $res = $this->api_service_model->getServiceInfo($params);
        if(!$res):
            $data = array(
                'success' => false,
                'msg' => 'no service info found'
            );
            my_json_output($data);
        endif;
        
        $serviceInfo = $res->row();
        
        $res = $this->api_service_model->getServiceFields($params);
        if(!$res):
            $data = array(
                'success' => false,
                'msg' => 'no service fields found'
            );
            my_json_output($data);
        endif;
        
        $serviceFields = $res->result();
        
        $this->load->library('form_validation');
        
        $fieldParams = array();
        foreach($serviceFields as $f):
            $fieldParams[$f->name] = $this->input->post($f->name,true);
            if((int)$f->is_required == 1):
                $this->form_validation->set_rules($f->field_name, $f->label, 'trim|required');
            endif;
        endforeach;
        
        if($this->form_validation->run() == false):
            $data = array(
                'success' => false,
                'msg' => validation_errors('<p>','</p>')
            );
            my_json_output($data);
        endif;
        
        $apiUrl = $serviceInfo->api_url;
        
        include_once APPPATH . "libraries/Requests.php";
        Requests::register_autoloader();

        try {
            $url = $apiUrl;
            $request = Requests::post($url, array(), http_build_query($fieldParams));

            $result = array(
                "success" => $request->success,
                "data" => $request->body,
                "request" => $request
            );
            return $result;
        } catch (Exception $e) {
            $error = array(
                "success" => false,
                "msg" => $e->getMessage()
            );
            return $error;
        }
        
    }
}

/* end of file */