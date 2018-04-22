<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                PIN Create Checker
            </h3>
        </div>
    </div>
</div>

<div class="container" id="PinCreateCheckerModule" data-ng-controller="PinCreateCheckerController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th>SL#</th>
                            <th>Total Pin</th>
                            <th>Maker Action</th>
                            <th>Maker Action By</th>
                            <th>Maker Action Date Time</th>
                            <th>Maker Remark</th>
                            <th>Checker Action</th>
                            <th>Checker Action Date Time</th>
                            <th>Checker Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in pin_checker_data track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.totalPin}}</td>
                            <td>{{i.makerAction}}</td>
                            <td>{{i.fullName}}</td>
                            <td>{{i.makerActionDt}} {{i.makerActionTm}}</td>
                            <td>{{i.makerActionComment}}</td>
                            <td>{{i.checkerAction}}</td>
                            <td>{{i.checkerActionDt}} {{i.checkerActionTm}}</td>
                            <td>{{i.checkerActionComment}}</td>
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
                        <tr data-ng-show="pin_checker_data.length <= 0">
                            <td colspan="10">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>


<?php
ci_add_js(asset_url() . 'app/checker/pin_create_checker_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.pin_checker_data = <?= $unApprovedRequest ?>;
</script>