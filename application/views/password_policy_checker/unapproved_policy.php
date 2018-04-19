<div class="container">
     <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                Password Policy
            </h3>
        </div>
    </div>
</div>

<div class="container" id="PasswordModule" data-ng-controller="PasswordController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="selectAll" /></th>
                            <th>SL#</th>                            
                            <th>Group Name</th>
                            <th>VG Code</th>
                            <th>Version Code</th>
                            <th>Wrong Attempts</th>
                            <th>Expiry Period</th>
                            <th>Status</th>
                            <th>Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in password_policy track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.validationGroupName}}</td>
                            <td>{{i.vgCode}}</td>
                            <td>{{i.vCodes}}</td>
                            <td>{{i.wrongAttempts}}</td>
                            <td>{{i.passExpiryPeriod}}</td>
                            <td>                                
                                <span data-ng-class="{'text-success': i.isActive=='1', 'text-warning': i.isActive=='0'}">{{i.isActive=='1' ? 'Active' : 'Inactive'}}</span>
                            </td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?= base_url() ?>password_policy_checker/getPasswordPolicyApproval/{{i.validationGroupId}}">
                                                <i class="glyphicon glyphicon-pencil"></i> Approve
                                            </a>
                                        </li> 
         
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="password_policy.length <= 0">
                            <td colspan="10">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>


<?php
ci_add_js(asset_url() . 'app/checker/password_policy_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.password_policy = <?= $passwordPolicy ?>;
</script>