<h2 class="title-underlined">User Group Permissions<a href="<?= base_url() . "admin_user_group_maker" ?>" class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-backward"></span> Back to user group list</a></h2>
<form class="forms" action="<?= $base_url . 'admin_user_group_maker/update_permission/' ?>" method="post" />
<table cellpadding="0" cellspacing="1" border="0" style="width:100%" class="table table-bordered table-striped">
    <tr>
        <th colspan="2">User Group Information</th>        
    </tr>        
    <tr>
        <td class="td" width="180px"><label>Name</label></td>
        <td class="td"><?= $uginfo->userGroupName ?></td>
    </tr>
    <tr>
        <td class="td"><label>Permissions</label></td>
        <td class="td form-inline">            
            <?php
            //d($permissions['category']);
            foreach ($permissions['category'] as $k => $p):
                echo '<h1 class="title-underlined">' . $k . '</h1>';
                foreach ($permissions['category'][$k] as $v):
                    //$checked = (isset($v->name)) ? "checked" : "";
                    $checked = (isset($existingPermission[$v->permissionId])) ? "checked" : "";
                    ?>
                    <div class="form-group col-xs-12 col-md-6 col-sm-6">
                        <label>
                            <input type="checkbox" value="<?= $v->permissionId ?>" name="permissions[]" <?= $checked ?>/> <?= $v->name ?>
                        </label>                
                        <small><i><?= $v->description ?></i></small>
                    </div>    
                    <?php
                endforeach;
            endforeach;
            ?>
        </td>
    </tr>
    <tr>
        <td class="td"><div class="loading"></div></td>
        <td class="td">            
            <input type="hidden" value="edit_permissions" name="action" />
            <input type="hidden" value="<?= $uginfo->userGroupId ?>" name="userGroupId" />
            <button type="submit" name="edit_permission" class="btn btn-primary">
                <i class="glyphicon glyphicon-check"></i> Update Permissions
            </button>    
        </td>
    </tr>
</table>
</form>