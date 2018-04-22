<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class User_group_permission {

    var $permission = array();

    function getPermissionCheckBoxes($currentPermissionArray) {
        $checkboxlist = "";

        $parray = $this->get_permissions_array();
        foreach ($parray as $key => $val):
            if ($key == "DELIMITER"):
                $checkboxlist .= "<br clear='all'/>";
            elseif ($val['moduleName'] != ""):
                $checkboxlist .= "<h3 style='border-bottom:1px dotted #ccc'>" . $val['moduleName'] . "</h3>";
            else:
                $checked = (isset($currentPermissionArray[$key])) ? "checked" : "";
                $checkboxlist .= '<label style="cursor:pointer" for="permissions_' . $key . '"><input type="checkbox" class="checkbox" id="permissions_' . $key . '" ' . $checked . ' name="permissions[' . $key . ']" value="' . $val['name'] . '" /> ' . $val['name'] . '</label><br />';
            endif;

        endforeach;

        return $checkboxlist;
    }

    function convert_permission_to_array($permissionString) {
        $pstring = explode(";", $permissionString);

        $parray = array();
        foreach ($pstring as $key => $val) {
            $parray[$val] = $val;
        }
        array_pop($parray);
        return $parray;
    }

    function convert_permission_to_string($permission) {
        $pstring = "";
        if (is_array($permission)):
            foreach ($permission as $key => $val) {
                $pstring .= $val . ";";
            }
        endif;

        return $pstring;
    }

    function get_permissions_array() {
        
        $ci = & get_instance();
        $ci->load->model("admin_user_group_model_maker");
        
        $result = $ci->admin_user_group_model_maker->getModules();
        
        return $result;
        
//        $permission = $this->permission;
//
//        $permission = $this->get_menu_array();
//
//        $permission['HEADER_Users'] = "Users";
//        $permission['canViewUser'] = "canViewUser";
//        $permission['canAddUser'] = "canAddUser";
//        $permission['canEditUser'] = "canEditUser";
//        $permission['canDeleteUser'] = "canDeleteUser";
//        $permission['DELIMITER'] = true;
//
//        $permission['HEADER_User_Groups'] = "User Groups";
//        $permission['canViewUserGroup'] = "canViewUserGroup";
//        $permission['canAddUserGroup'] = "canAddUserGroup";
//        $permission['canEditUserGroup'] = "canEditUserGroup";
//        $permission['canDeleteUserGroup'] = "canDeleteUserGroup";
//        $permission['canSetUserGroupPermission'] = "canSetUserGroupPermission";
//        $permission['DELIMITER'] = true;
//
//        $permission['HEADER_Setup'] = "Setup";
//        $permission['canManageRegions'] = "canManageRegions";
//        $permission['DELIMITER'] = true;
//
//        $permission['HEADER_Document'] = "Document";
//        $permission['canAddDocument'] = "canAddDocument";
//        $permission['canEditDocument'] = "canEditDocument";
//        $permission['canViewDocument'] = "canViewDocument";
//        $permission['canDeleteDocument'] = "canDeleteDocument";
//        $permission['canViewDocumentPermissions'] = "canViewDocumentPermissions";
//        $permission['canEditDocumentPermissions'] = "canEditDocumentPermissions";
//        $permission['canDeleteDocumentPermissions'] = "canDeleteDocumentPermissions";
//        $permission['canViewUserDocumentList'] = "canViewUserDocumentList";
//        $permission['canViewDocumentSearch'] = "canViewDocumentSearch";
//        $permission['DELIMITER'] = true;
//
//        $permission['HEADER_Folder'] = "Folder";
//        $permission['canViewFolder'] = "canViewFolder";
//        $permission['canAddFolder'] = "canAddFolder";
//        $permission['canEditFolder'] = "canEditFolder";
//        $permission['canDeleteFolder'] = "canDeleteFolder";
//        $permission['DELIMITER'] = true;
//        
//        $permission['HEADER_Warehouse'] = "Warehouse";
//        $permission['canViewWarehouse'] = "canViewWarehouse";
//        $permission['canAddWarehouse'] = "canAddWarehouse";
//        $permission['canEditWarehouse'] = "canEditWarehouse";
//        $permission['canDeleteWarehouse'] = "canDeleteWarehouse";
//        $permission['DELIMITER'] = true;
//
//        return $permission;
    }

    function get_menu_array() {
        $permission = $this->permission;
        $permission['HEADER_Menu_Assignment'] = "Menu Assigment";
        $permission['canViewHomeMenu'] = "canViewHomeMenu";
        $permission['canViewSetupMenu'] = "canViewSetupMenu";
        $permission['canViewDocumentMenu'] = "canViewDocumentMenu";
        $permission['DELIMITER'] = true;

        return $permission;
    }

}

?>