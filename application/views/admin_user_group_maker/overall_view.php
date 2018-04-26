<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                <?= $pageTitle ?>
                <?php if (ci_check_permission("canAddAdminUserGroup")): ?>
                    <a href="<?php echo base_url(); ?>admin_user_group_maker/selectModule/Add" class="btn btn-primary btn-xs pull-right">
                        <i class="fa fa-plus"></i> Add Admin User Group
                    </a>
                <?php endif; ?>
            </h3>
        </div>
    </div>
</div>

<div class="container" id="AdminUserGroupModule" data-ng-controller="AdminUserGroupController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="selectAll" /></th>
                            <th>SL#</th>                            
                            <th>Admin User Group</th>
                            <th>Lock/Unlock</th>
                            <th>Active/Inactive</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in data track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isLocked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.userGroupName}}</td>
                            <td>
                                <span data-ng-class="{'text-danger': i.isLocked == '1', 'text-success': i.isLocked == '0'}">{{i.isLocked=='1' ? 'Locked' : 'Unlocked'}}</span>
                            </td>
                            <td>
                                <span data-ng-class="{'text-success': i.isActive == '1', 'text-danger': i.isActive == '0'}">{{i.isActive=='1' ? 'Active' : 'Inactive'}}</span>
                            </td>
                            <td>
                                <span data-ng-class="{'text-success': i.mcStatus == '1', 'text-danger': i.mcStatus == '0'}">{{i.mcStatus=='1' ? 'Approved' : 'Wait for approve'}}</span>
                            </td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if (ci_check_permission("canEditAdminUserGroup")): ?>
                                            <li>
                                                <a href="<?= base_url() ?>admin_user_group_maker/editModule/{{i.userGroupId}}/edit">
                                                    <i class="glyphicon glyphicon-pencil"></i> Edit
                                                </a>
                                            </li>
                                        <?php endif; ?>                            
                                        <?php if (ci_check_permission("canSetuPermissionAdminUserGroup")): ?>
                                            <li>
                                                <a href="<?= base_url() ?>admin_user_group_maker/set_permission/{{i.userGroupId}}">
                                                    <i class="glyphicon glyphicon-cog"></i> Set Permission
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canActiveAdminUserGroup")): ?>
                                            <li>
                                                <a data-ng-click="activate(i.userGroupId);" data-ng-if="i.isActive == 0">
                                                    <i class="glyphicon glyphicon-ok"></i> Active
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canInactiveAdminUserGroup")): ?>
                                            <li>
                                                <a data-ng-click="deactivate(i.userGroupId);" data-ng-if="i.isActive == 1">
                                                    <i class="glyphicon glyphicon-remove"></i> Inactive
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canLockAdminUserGroup")): ?>
                                            <li>
                                                <a data-ng-click="lock(i.userGroupId);" data-ng-if="i.isLocked == 0">
                                                    <i class="glyphicon glyphicon-ok"></i> Lock
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canUnlockAdminUserGroup")): ?>
                                            <li>
                                                <a data-ng-click="unlock(i.userGroupId);" data-ng-if="i.isLocked == 1">
                                                    <i class="glyphicon glyphicon-remove"></i> Unlock
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="data.length <= 0">
                            <td colspan="7">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>

<?php
ci_add_js(asset_url() . 'app/admin_group/admin_group_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.adminGroups = <?= $adminGroups ?>;
</script>

