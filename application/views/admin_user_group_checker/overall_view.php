<div class="container" id="AdminGroupModule" data-ng-controller="AdminGroupController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <h3 class="title-underlined">Admin User Group Checker</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            
            <table class="table table-bordered table-condensed table-striped table-hover" >          
                <thead>
                    <tr class="bg-primary">
                        <th>SL#</th>
                        <th>Admin User Group</th>
                        <th>Maker Action</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-ng-repeat="i in admin_group_data track by $index">
                        <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                        <td>{{($index + 1)}}</td>
                        <td>{{i.userGroupName}}</td>
                        <td>{{i.makerAction}}</td>
                        <td>
                            <span data-ng-class="{'text-success': i.status == '1', 'text-danger': i.status == '0'}">{{i.status=='1' ? 'Approved' : 'Wait for approve'}}</span>
                        </td>
                        <td>
                            <div class="dropdown pull-right">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <?php if (ci_check_permission("canApproveAdminUserGroup")): ?>
                                    <li>
                                        <a href="<?= base_url() ?>admin_user_group_checker/getGroupForApproval/{{i.userGroupId}}">
                                            <i class="glyphicon glyphicon-pencil"></i> Approve
                                        </a>
                                    </li> 
                                    <?php endif;?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-ng-show="admin_group_data.length <= 0">
                        <td colspan="10">No data found</td>
                    </tr>
                </tbody>
            </table>               
        </div>
    </div>
</div>


<?php
ci_add_js(asset_url() . 'app/checker/admin_group_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.admin_group_data = <?= $groups ?>;
</script>
