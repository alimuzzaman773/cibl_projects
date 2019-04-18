<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_type extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->model(array('admin_user_group_model_checker', 'login_model'));
    }

    function index()
    {
        $this->my_session->checkSession();
        
        try{
            /* This is only for the autocompletion */
            $crud = new grocery_CRUD();
            $crud->unset_jquery();

            $crud->set_theme(TABLE_THEME);
            $crud->set_table('account_categories');            
            $crud->set_subject('Account Category');
            
            $crud->required_fields(array('name','machine_name', 'start_index', 'end_index'));
            
            $crud->set_rules('name', 'Name', 'trim|required|xss_clean|min_length[1]');  
            $crud->set_rules('start_index', 'Start Index', 'trim|xss_clean|required|greater_than[0]');
            $crud->set_rules('end_index', 'End Index', 'trim|xss_clean|required|greater_than[0]');
            $crud->set_rules('machine_name', 'Machine Name', 'trim|xss_clean|callback__checkMachineName');
            
            $crud->columns('name','machine_name', 'start_index', 'end_index');
            
            $time = date("Y-m-d H:i:s");
            $crud->add_fields('name','machine_name', 'start_index', 'end_index','created','updated');
            $crud->edit_fields('name','machine_name', 'start_index', 'end_index', 'updated');
            
            $crud->change_field_type('created', 'hidden', $time);
            $crud->change_field_type('updated', 'hidden', $time);           
            
            if(!isset($this->my_session->permissions['canAddAccountCategory']))
            {                
                $crud->unset_add();
            }
            
            if(!isset($this->my_session->permissions['canEditAccountCategory']))
            {                
                $crud->unset_edit();
            }
            
            if(!isset($this->my_session->permissions['canDeleteAccountCategory']))
            {                
                $crud->unset_delete();
            }
            $crud->unset_delete();
            $crud->unset_print();
                        
            $output = $crud->render();
            
            /** initialization **/
            $output->css = "";        
            $output->js = "";        
            $output->pageTitle = "Account Category Setup";
            $output->base_url = base_url();
            $output->body_template = "account_type/index.php";
            
            $output->crudState = $crud->getState();
            
            $this->load->view("site_template.php",$output);            

        }catch(Exception $e){
            show_error($e->getMessage().' --- '.$e->getTraceAsString());
        }
    }
    
    function _checkMachineName($machineName)
    {
        if(trim($this->input->post("machine_name",true)) == ""){
            $this->form_validation->set_message("_checkMachineName","Machine Name is required");            
            return false;
        }       
        
        $ugId = (int)$this->uri->segment(4);
        $this->db->select("*")
                 ->from('account_categories')
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

}
