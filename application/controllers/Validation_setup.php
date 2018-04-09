<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Validation_setup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');

        $this->load->database();
        $this->load->helper('url');

        $this->load->model('login_model');
        $this->load->library('session');
        if ($this->login_model->check_session()) {
            redirect('/admin_login/index');
        }

        $this->load->library('grocery_CRUD');
        $this->output->set_template('theme1');
    }

    public function _crud_view($output = null)
    {
        $this->load->view('default_view.php', $output);
    }

    public function index($params = null)
    {
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('validation_group_mc');

        $codes = $this->getValidationCodes();
        $crud->field_type('vCodes', 'multiselect', $codes);

        $crud->fields('validationGroupName', 'message', 'example', 'vgCode', 'vCodes',
            'wrongAttempts', 'passHistorySize', 'passExpiryPeriod',
            'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod', 'isActive', 'createdBy', 'createdDtTm',
            'mcStatus', 'makerAction', 'makerActionCode', 'makerActionDt',
            'makerActionTm', 'checkerActionDt', 'checkerActionTm', 'makerActionBy',
            "checkerAction", "checkerActionComment", "checkerActionBy", 'isPublished');

        $crud->columns('validationGroupName', 'message', 'example',
            'wrongAttempts', 'passHistorySize', 'passExpiryPeriod',
            'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod', 'makerAction');

        $crud->required_fields('validationGroupName', 'message', 'example',
            'wrongAttempts', 'passHistorySize', 'passExpiryPeriod',
            'warningPeriod', 'hibernationPeriod', 'pinExpiryPeriod'
        );

        $crud->callback_before_insert(array($this, 'add_data'));
        $crud->callback_before_update(array($this, 'update_data'));

        $crud->display_as('validationGroupName', 'Group Name')
            ->display_as('message', 'Message')
            ->display_as('example', 'Example')
            ->display_as('wrongAttempts', 'Unsuccessful Attempts Allowed')
            ->display_as('passHistorySize', 'Password History Size')
            ->display_as('passExpiryPeriod', 'Password Expiry Period (Days)')
            ->display_as('warningPeriod', 'Warning Period (days)')
            ->display_as('hibernationPeriod', 'Hibernation Period (Days)')
            ->display_as('pinExpiryPeriod', 'Pin Expiry Period (Days)');

        $crud->field_type("createdBy", "hidden");
        $crud->field_type("createdDtTm", "hidden");
        $crud->field_type("isActive", "hidden");
        $crud->field_type("makerActionBy", "hidden");
        $crud->field_type("makerAction", "hidden");
        $crud->field_type("makerActionDt", "hidden");
        $crud->field_type("makerActionTm", "hidden");
        $crud->field_type("makerActionCode", "hidden");
        $crud->field_type("mcStatus", "hidden");
        $crud->field_type("checkerActionDt", "hidden");
        $crud->field_type("checkerActionComment", "hidden");
        $crud->field_type("checkerAction", "hidden");
        $crud->field_type("checkerActionTm", "hidden");
        $crud->field_type("checkerActionBy", "hidden");
        $crud->field_type("isPublished", "hidden");

        $moduleCodes = $this->session->userdata('moduleCodes');
        $actionCodes = $this->session->userdata('actionCodes');
        $moduleCodes = explode("|", $moduleCodes);
        $actionCodes = explode("#", $actionCodes);
        $index = array_search(password_policy_module, $moduleCodes);

        if ($index > -1) {
            $moduleWiseActionCodes = $actionCodes[$index];

            if ((strpos($moduleWiseActionCodes, "edit")) < -1) {
                $crud->unset_edit();
            } else if ((strpos($moduleWiseActionCodes, "detailView")) < -1) {
                $crud->unset_read();
            }
        } else {
            echo "not allowed";
        }

        $crud->unset_add();
        $crud->unset_delete();
        $output = $crud->render();
        $this->_crud_view($output);
    }

    function getValidationCodes()
    {
        $this->db->select('vCode, validationName');
        $this->db->from('validation');
        $this->db->where('isActive', 1);
        $query = $this->db->get();
        $codes = $query->result();
        $data = array();
        if ($codes) {
            foreach ($codes as $key => $value) {
                $data[$value->vCode] = $value->validationName;
            }
            return $data;
        } else {
            $data[] = "No Validation Created";
            return $data;
        }
    }

    function add_data($post_array)
    {
        $adminId = $this->session->userdata('adminUserId');
        $post_array['mcStatus'] = 0;
        $post_array['makerAction'] = "Add";
        $post_array['makerActionCode'] = "add";
        $post_array['makerActionDt'] = date("Y-m-d");
        $post_array['makerActionTm'] = date("G:i:s");
        $post_array['makerActionBy'] = $adminId;
        $post_array['isPublished'] = 0;
        $post_array['createdDtTm'] = input_date();
        $post_array['createdBy'] = $adminId;
        return $post_array;
    }

    function update_data($post_array)
    {
        $adminId = $this->session->userdata('adminUserId');;
        $post_array['mcStatus'] = 0;
        $post_array['makerAction'] = "Edit";
        $post_array['makerActionCode'] = "edit";
        $post_array['makerActionDt'] = date("Y-m-d");
        $post_array['makerActionTm'] = date("G:i:s");
        $post_array['makerActionBy'] = $adminId;
        return $post_array;
    }
}