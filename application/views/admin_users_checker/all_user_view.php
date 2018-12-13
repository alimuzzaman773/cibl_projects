<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                <?= $pageTitle ?>
            </h3>
        </div>
    </div>
</div>

<div class="container" id="AdminUserChModule" data-ng-controller="AdminUserChController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th>SL#</th>
                            <th>Name</th>
                            <th>Admin User Name</th>
                            <th>Admin User Group</th>
                            <th>Maker Action</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in data track by $index">
                            <td>{{($index + 1)}}</td>
                            <td>{{i.fullName}}</td>
                            <td>{{i.adminUserName}}</td>
                            <td>{{i.userGroupName}}</td>
                            <td>{{i.makerAction}}</td>
                            <td>
                                <span data-ng-class="{'text-success': i.mcStatus == '1', 'text-danger': i.mcStatus == '0'}">{{i.mcStatus=='1' ? 'Approved' : 'Wait for approve'}}</span>
                            </td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if (ci_check_permission("canApproveAdminUser")): ?>
                                            <li>
                                                <a href="<?= base_url() ?>admin_users_checker/getUserForApproval/{{i.adminUserId}}">
                                                    <i class="glyphicon glyphicon-pencil"></i> Approval
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
ci_add_js(asset_url() . 'app/admin_user/admin_user_module_ch.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.adminUsersCh = <?= $adminUsers ?>;
</script>

