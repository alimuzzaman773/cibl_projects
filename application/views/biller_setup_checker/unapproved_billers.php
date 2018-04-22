<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                Biller Setup Checker
            </h3>
        </div>
    </div>
</div>

<div class="container" id="BillerSetupCheckerModule" data-ng-controller="BillerSetupCheckerController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th>SL#</th>
                            <th>Biller Name</th>
                            <th>CIF ID</th>
                            <th>Biller Code</th>
                            <th>Bill Type</th>
                            <th>Maker Action</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in biller_setup_data track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.billerName}}</td>
                            <td>{{i.cfId}}</td>
                            <td>{{i.billerCode}}</td>
                            <td>{{i.billTypeName}}</td>
                            <td>{{i.makerAction}}</td>
                            <td>{{i.mcStatuss}}</td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if (ci_check_permission("canApproveBillerSetup")): ?>
                                        <li>
                                            <a href="<?= base_url() ?>biller_setup_checker/getBillerFroApproval/{{i.billerId}}">
                                                <i class="glyphicon glyphicon-pencil"></i> Approve
                                            </a>
                                        </li> 
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="biller_setup_data.length <= 0">
                            <td colspan="10">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>

<?php
ci_add_js(asset_url() . 'app/checker/biller_setup_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.biller_setup_data = <?= $unapprovedBillers ?>;
</script>
