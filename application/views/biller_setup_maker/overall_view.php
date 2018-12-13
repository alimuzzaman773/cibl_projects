<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                Biller Setup
                <?php if (ci_check_permission("canAddBillerSetup")): ?>
                    <a href="<?php echo base_url(); ?>biller_setup_maker/addNewBiller/Add" class="btn btn-primary btn-xs pull-right">
                        <i class="fa fa-plus"></i> Add Biller
                    </a>
                <?php endif; ?>
            </h3>
        </div>
    </div>
</div>

<div class="container" id="billerModule" data-ng-controller="billerController">
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
                            <th>Biller Order</th>
                            <th>Bill Type</th>
                            <th>Status</th>
                            <th>Checker Comment</th>
                            <th>Checker Action</th> 
                            <th colspan="2">Active/Inactive</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in biller_data track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.billerName}}</td>
                            <td>{{i.cfId}}</td>
                            <td>{{i.billerCode}}</td>
                            <td>{{i.billerOrder}}</td>
                            <td>{{i.billTypeName}}</td>
                            <td>{{i.mcStatus}}</td>
                            <td>{{i.checkerActionComment}}</td>
                            <td>{{i.checkerAction}}</td>
                            <td>                                
                                <span data-ng-class="{'text-success': i.isActive == '1', 'text-warning': i.isActive == '0'}">{{i.isActive=='1' ? 'Active' : 'Inactive'}}</span>
                            </td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if (ci_check_permission("canEditBillerSetup")): ?>
                                            <li>
                                                <a href="<?= base_url() ?>biller_setup_maker/editBiller/{{i.billerId}}/edit">
                                                    <i class="glyphicon glyphicon-pencil"></i> Edit
                                                </a>
                                            </li> 
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canActiveBillerSetup")): ?>
                                            <li>
                                                <a data-ng-click="activate(i.billerId);" data-ng-if="i.isActive == 0">
                                                    <i class="glyphicon glyphicon-ok"></i> Active
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (ci_check_permission("canInactiveBillerSetup")): ?>
                                            <li>
                                                <a data-ng-click="deactivate(i.billerId);" data-ng-if="i.isActive == 1">
                                                    <i class="glyphicon glyphicon-remove"></i> Inactive
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="biller_data.length <= 0">
                            <td colspan="15">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>


<?php
ci_add_js(asset_url() . 'app/biller_setup/biller_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.billerData = <?= $billerData ?>;
</script>
