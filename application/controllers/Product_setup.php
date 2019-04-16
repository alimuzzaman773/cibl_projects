<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_setup extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library("my_session");
        $this->my_session->checkSession();
        $this->load->library('grocery_CRUD');
    }

    function index() {
        $this->my_session->authorize("canViewProduct");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Product');
            $crud->set_table(TBL_PRODUCT_SETUP);

            $crud->required_fields('type', 'parentName', 'childName', 'productName');

            $crud->unique_fields('productName');

            $crud->columns('productName', 'productDetails', 'isActive', 'productOrder');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('pc_id', 'type', 'parentName', 'childName', 'productName', 'tagline', 'productDetails', 'isActive', 'productOrder', 'creationDtTm', 'updateDtTm');
            $crud->edit_fields('pc_id', 'type', 'parentName', 'childName', 'productName', 'tagline', 'productDetails', 'isActive', 'productOrder', 'updateDtTm');

            $crud->change_field_type('creationDtTm', 'hidden', $time);
            $crud->change_field_type('updateDtTm', 'hidden', $time);
            $crud->change_field_type('createdBy', 'hidden', $creatorId);
            $crud->change_field_type('updatedBy', 'hidden', $creatorId);
            $crud->change_field_type('pc_id', 'hidden', '-1');

            $crud->display_as('type', 'Type');
            $crud->display_as('parentName', 'Category');
            $crud->display_as('childName', 'Sub-Category');
            $crud->display_as('tagline', 'Tagline');
            $crud->display_as('productDetails', 'Description');
            $crud->display_as('productName', 'Product Name');
            $crud->display_as('productOrder', 'Product Order');
            $crud->display_as('isActive', 'Is Active');

            $crud->display_as('productOrder', 'Product Order');
            $crud->display_as('isActive', 'Is Active');

            $categoryList = array(
                NULL => ''
            );

            $typeList = array('product' => 'Products', 'partner' => 'EMI Partners', 'benefit' => 'Discount Partners');
            $crud->change_field_type('type', 'dropdown', $typeList);
            $crud->change_field_type('parentName', 'dropdown', $categoryList);
            $crud->change_field_type('childName', 'dropdown', $categoryList);

            $this->db->select("*")
                    ->from(TBL_PRODUCT_CATEGORIES);
            $cRes = $this->db->get();
            foreach ($cRes->result() as $r):
                $categoryList[] = $r;
            endforeach;


            if (!ci_check_permission("canAddProduct")):
                $crud->unset_add();
            endif;

            if (!ci_check_permission("canEditProduct")):
                $crud->unset_edit();
            endif;

            if (!ci_check_permission("canDeleteProduct")):
                $crud->unset_delete();
            endif;

            $productId = (int) $this->uri->segment(4);
            $resP = $this->getProductInfo($productId);

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Product";
            $output->base_url = base_url();

            $output->categories = $categoryList;

            $output->productInfo = array();
            if ($resP) {
                $output->productInfo = $resP->row();
            }

            $output->crudState = $crud->getState();

            $output->body_template = "product/index.php";

            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    function getProductInfo($pid) {
        
        $this->db->select("*")
                ->from(TBL_PRODUCT_SETUP)
                ->where("productId", $pid);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return $result;
        }
        return false;
    }

    
    function categories() {

        $this->my_session->authorize("canViewProduct");
        try {
            $crud = new grocery_CRUD();
            $crud->set_theme(TABLE_THEME);
            $crud->set_subject('Category Setup');
            $crud->set_table(TBL_PRODUCT_CATEGORIES);

            $crud->columns('name', 'created', 'updated');

            $crud->display_as('type', 'Type');
            $crud->display_as('parent_id', 'Parent Category');
            $crud->display_as('name', 'Category Name');
            $crud->display_as('created', 'Created Date');
            $crud->display_as('updated', 'Updated Date');

            $time = date("Y-m-d H:i:s");
            $creatorId = $this->my_session->userId;

            $crud->add_fields('type', 'parent_id', 'name', 'created_by', 'updated_by', 'created', 'updated');
            $crud->edit_fields('type', 'parent_id', 'name', 'updated', 'updated_by');

           $categories = array(
                NULL => ''
            );

            $crud->change_field_type('parent_id', 'dropdown', $categories);
            $crud->change_field_type('created', 'hidden', $time);
            $crud->change_field_type('updated', 'hidden', $time);
            $crud->change_field_type('created_by', 'hidden', $creatorId);
            $crud->change_field_type('updated_by', 'hidden', $creatorId);

           //$crud->set_relation('parent_id', "product_categories", 'pc_id');
           //$crud->set_relation('pc_id', "product_categories", 'name');


            $this->db->select("*")
                     ->from(TBL_PRODUCT_CATEGORIES);
            if ($this->uri->segment(3) == "edit" && $this->uri->segment(4) > 0):
                $pc_id = (int) $this->uri->segment(4);
                $this->db->where_not_in("pc_id", array($pc_id));
            endif;
            $res = $this->db->get();
            
            foreach ($res->result() as $r):
                $categories[] = $r;
            endforeach;

            
            $categoryInfo = NULL;
            if ($this->uri->segment(3) == "edit" && $this->uri->segment(4) > 0):
                $this->db->reset_query();
                $this->db->select("*")
                         ->from(TBL_PRODUCT_CATEGORIES)
                         ->where("pc_id", $this->uri->segment(4));
                $catRes = $this->db->get();
                $categoryInfo = $catRes->row();
            endif;
            
            
            $crud->callback_add_field('type', function () {
                $idata = array();
                $idata['type'] = array('product' => 'Products', 'partner' => 'EMI Partners', 'benefit' => 'Benefit Partners');
                $idata['value'] = "";
                return $this->load->view("product_category/type_dd.php", $idata,true);
            });
            
            $crud->callback_edit_field('type', function ($value, $primary_key) {
                $idata = array();
                $idata['type'] = array('product' => 'Products', 'partner' => 'EMI Partners', 'benefit' => 'Benefit Partners');
                $idata['value'] = $value;
                return $this->load->view("product_category/type_dd.php", $idata,true);
            });
            
            $crud->callback_add_field('parent_id', function () {
                $categoriesList = $this->db->select("*")
                                           ->from(TBL_PRODUCT_CATEGORIES)->get()->result();
                
                $idata = array();
                $idata['categoryList'] = $categoriesList;
                $idata['catInfo'] = NULL;
                return $this->load->view("product_category/parent_dd.php", $idata,true);
            });
            
            $crud->callback_edit_field('parent_id', function ($value, $primary_key) {
                $categoriesList = $this->db->select("*")
                                           ->from(TBL_PRODUCT_CATEGORIES)->get()->result();
                
                $idata = array();
                $idata['categoryList'] = $categoriesList;
                $idata['catInfo'] = $value;
                return $this->load->view("product_category/parent_dd.php", $idata,true);
            });
            
            
            $crud->unset_delete();

            if (!ci_check_permission("canAddProduct")):
                $crud->unset_add();
            endif;
            if (!ci_check_permission("canEditProduct")):
                $crud->unset_edit();
            endif;
            if (!ci_check_permission("canReadProduct")):
                $crud->unset_read();
            endif;
            if (!ci_check_permission("canExportProduct")):
                $crud->unset_export();
            endif;
            if (!ci_check_permission("canPrintProduct")):
                $crud->unset_print();
            endif;

            $output = $crud->render();
            $output->css = "";
            $output->js = "";
            $output->pageTitle = "Category Setup";
            $output->base_url = base_url();
            $output->categories = $categories;
            $output->categoryInfo = $categoryInfo;
            $output->crudState = $crud->getState();

            $output->body_template = "product_category/index.php";
            $this->load->view("site_template.php", $output);
        } catch (Exception $e) {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

}
