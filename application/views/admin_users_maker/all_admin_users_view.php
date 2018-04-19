<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                <?= $pageTitle ?>
                <a href="<?php echo base_url(); ?>admin_users_maker/addNewUser/Add" class="btn btn-primary btn-xs pull-right">
                    <i class="fa fa-plus"></i> Add Admin User
                </a>
            </h3>
        </div>
    </div>
</div>

<div class="container" id="AdminUserModule" data-ng-controller="AdminUserController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="selectAll" /></th>
                            <th>SL#</th>                            
                            <th>Full Name</th>
                            <th>Admin User Group</th>
                            <th>Email</th>
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
                            <td>{{i.adminUserName}}</td>
                            <td>{{i.userGroupName}}</td>
                            <td>{{i.email}}</td>
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
                                        <li>
                                            <a href="<?= base_url() ?>admin_users_maker/editUser/{{i.adminUserId}}/edit">
                                                <i class="glyphicon glyphicon-pencil"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a data-ng-click="activate(i.adminUserId);" data-ng-if="i.isActive == 0">
                                                <i class="glyphicon glyphicon-ok"></i> Active
                                            </a>
                                        </li>
                                        <li>
                                            <a data-ng-click="deactivate(i.adminUserId);" data-ng-if="i.isActive == 1">
                                                <i class="glyphicon glyphicon-remove"></i> Inactive
                                            </a>
                                        </li>
                                        <li>
                                            <a data-ng-click="lock(i.adminUserId);" data-ng-if="i.isLocked == 0">
                                                <i class="glyphicon glyphicon-ok"></i> Lock
                                            </a>
                                        </li>
                                        <li>
                                            <a data-ng-click="unlock(i.adminUserId);" data-ng-if="i.isLocked == 1">
                                                <i class="glyphicon glyphicon-remove"></i> Unlock
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="data.length <= 0">
                            <td colspan="9">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>

<?php
ci_add_js(asset_url() . 'app/admin_user/admin_user_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.adminUsers = <?= $adminUsers ?>;
</script>
