<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                Apps User Checker
            </h3>
        </div>
    </div>
</div>

<div class="container" id="AppsUserCheckerModule" data-ng-controller="AppsUserCheckerController">
    <div class="row">
        <div class="col-xs-3 col-sm-2">
                <div class="form-group">
                    <label>From Date</label> 
                    <input type="text" name="fromDate" id="fromDate" class="form-control input-sm" ng-model="searchParams.from_date" placeholder="Search by From Date"/>
                </div>
            </div>
            <div class="col-xs-3 col-sm-2">
                <div class="form-group">
                    <label>To Date</label> 
                    <input type="text" name="toDate" id="toDate"  class="form-control input-sm" ng-model="searchParams.to_date" placeholder="Search by To Date"/>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3">
                <div class="form-group">
                    <label>Apps ID</label> 
                    <input type="text" placeholder="Search by Apps ID" class="form-control input-sm" data-ng-model="searchParams.eblSkyId" />
                </div>
            </div>
            <div class="col-xs-3 col-sm-3">
                <div class="form-group">
                    <label style="display:block" class="hidden-xs">&nbsp;&nbsp;</label>
                    <button class="btn btn-primary btn-sm" data-ng-click="getResultsPage(1)">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                    <button class="btn btn-primary btn-sm" data-ng-click="resetSearch();">
                        <i class="glyphicon glyphicon-refresh"></i> Reset
                    </button>
                </div>
            </div>
    </div>
    <div class="col-md-12 col-sm-12 text-right" data-ng-show="totalCount > 0">        
    <span class="label label-primary"> Displaying: {{ ((per_page * currentPageNumber) - (per_page - 1))}} to {{ upper_range()}} of {{ totalCount}}</span>            
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th>SL#</th>
                            <th>App ID</th>
                            <th>Maker Action</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr data-ng-repeat="i in apps_user_data track by $index"> -->
                        <tr dir-paginate="i in apps_user_data | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.eblSkyId}}</td>
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
                                        <?php if (ci_check_permission("canApproveAppsUser")): ?>
                                        <li>
                                            <a href="<?= base_url() ?>client_registration_checker/getAppsUserForApproval/{{i.skyId}}">
                                                <i class="glyphicon glyphicon-pencil"></i> Approve
                                            </a>
                                        </li> 
                                        <?php endif;?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="apps_user_data.length <= 0">
                            <td colspan="10">No data found</td>
                        </tr>
                    </tbody>
                </table>
                <div class="box-footer clearfix  pull-right">
                    <dir-pagination-controls on-page-change="pageChanged(newPageNumber)"
                                             template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
                </div>              
            </div>
        </div>
    </div>
</div>

<script src="<?php echo asset_url() ?>js/jqueryui/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo asset_url() ?>css/jqueryui/jquery-ui-1.9.2.css"/>

<?php
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/directives/dirPagination.js");
ci_add_js(asset_url() . 'app/checker/apps_user_checker_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    $("#fromDate, #toDate").datepicker({
        dateFormat: 'yy-mm-dd'
        //constrainInput: true
    }).on('focusin', function () {
        $(this).prop("readonly", true);
    }).on('focusout', function () {
        $(this).prop("readonly", false);
    });
</script>