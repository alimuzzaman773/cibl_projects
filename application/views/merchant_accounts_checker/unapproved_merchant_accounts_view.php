<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                Merchant Accounts Checker
            </h3>
        </div>
    </div>
</div>

<div class="container" id="MerchantAcCheckerModule" data-ng-controller="MerchantAcCheckerCtrl">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th>SL#</th>
                            <th>Merchant Code</th>
                            <th>Merchant Name</th>
                            <th>Maker Action</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in merchant_accounts track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.merchantCode}}</td>
                            <td>{{i.merchantName}}</td>
                            <td>{{i.makerAction}}</td>
                            <td>{{i.mcStatus}}</td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">

                                        <li>
                                            <a href="<?= base_url() ?>merchant_accounts_checker/getMerchantAcForApproval/{{i.merchantId}}">
                                                <i class="glyphicon glyphicon-pencil"></i> Approve
                                            </a>
                                        </li> 
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="merchant_accounts.length <= 0">
                            <td colspan="10">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>

<?php
ci_add_js(asset_url() . 'app/checker/merchant_accounts_module.js');
?>
<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.merchant_accounts_data = <?= $merchantAccounts ?>;
</script>