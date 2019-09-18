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
        <div class="form-group col-lg-4 col-xs-6">
            <label>Search</label>
            <input type="text" class="form-control input-sm" data-ng-model="searchParams.search" placeholder="Search by Apps ID, User Name, Customer ID, Father or Mother Name" />
        </div>
        <div class="form-group col-lg-4 col-xs-6">
            <label>Is Locked?</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isLocked">
                <option value=""></option>
                <option value="1">Locked</option>                    
                <option value="0">Unlocked</option>                    
            </select>
        </div>
        <div class="form-group col-lg-4 col-xs-6">
            <label>Is Active?</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isActive">
                <option value=""></option>
                <option value="1">Active</option>                    
                <option value="0">Inactive</option>                    
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-xs-12">
            <b>View Only Options</b>
        </div>
        <div class="form-group col-lg-2 col-xs-6">
            <label>Is Own Account Transfer</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isOwnAccTransfer">
                <option value=""></option>
                <option value="1">Yes</option>                    
                <option value="0">No</option>                    
            </select>
        </div>
        <div class="form-group col-lg-2 col-xs-6">
            <label>Is Inter Account Transfer</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isInterAccTransfer">
                <option value=""></option>
                <option value="1">Yes</option>                    
                <option value="0">No</option>                    
            </select>
        </div>
        <div class="form-group col-lg-2 col-xs-6">
            <label>Is Other Account Transfer</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isOtherAccTransfer">
                <option value=""></option>
                <option value="1">Yes</option>                    
                <option value="0">No</option>                    
            </select>
        </div>
        <div class="form-group col-lg-2 col-xs-6">
            <label>Is Account to Card Transfer</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isAccToCardTransfer">
                <option value=""></option>
                <option value="1">Yes</option>                    
                <option value="0">No</option>                    
            </select>
        </div>
        <div class="form-group col-lg-2 col-xs-6">
            <label>Is Card to Account Transfer</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isCardToAccTransfer">
                <option value=""></option>
                <option value="1">Yes</option>                    
                <option value="0">No</option>                    
            </select>
        </div>
        <div class="form-group col-lg-2 col-xs-6">
            <label>Is Utility Transfer</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isUtilityTransfer">
                <option value=""></option>
                <option value="1">Yes</option>                    
                <option value="0">No</option>                    
            </select>
        </div>
        <div class="form-group col-lg-2 col-xs-6">
            <label>Is QR Payment</label>
            <select class="form-control input-sm" data-ng-model="searchParams.isQrPayment">
                <option value=""></option>
                <option value="1">Yes</option>                    
                <option value="0">No</option>                    
            </select>
        </div>
        <!--        <div class="form-group col-sm-3 col-xs-6">
                    <label>View Only Options</label>
                    <select class="form-control" data-ng-model="searchParams.trOptions">
                        <option value="">Show all</option>
                        <option value="isOwnAccTransfer">Is Own Account Transfer</option>
                        <option value="isInterAccTransfer">Is Inter Account Transfer</option>
                        <option value="isOtherAccTransfer">Is Other Account Transfer</option>
                        <option value="isAccToCardTransfer">Is Account To Card Transfer</option>
                        <option value="isCardToAccTransfer">Is Card To Account Transfer</option>
                        <option value="isUtilityTransfer">Is Utility Transfer</option>
                        <option value="isQrPayment">Is QR Payment</option>
                    </select>
                </div>
                <div ng-if="searchParams.trOptions !== ''" class="form-group col-sm-2 col-xs-6">
                    <label>View Only State</label>
                    <select class="form-control" data-ng-model="searchParams.viewOnlyBool">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>-->
        <div class="form-group col-xs-6 col-sm-2">
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
                            <th>SI#</th>
                            <th>SKY ID</th>
                            <th>Apps ID</th>
                            <th>User ID</th>
                            <th>CIF ID</th>
                            <th>Maker Action</th>
                            <th>Father's name</th>
                            <th>Mother's name</th>
                            <th>User Name</th>
                            <th>User Group</th>
                            <th>Date of Birth</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Branch</th>
                            <th>View Only Options</th>
                            <th>Status</th>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr data-ng-repeat="i in apps_user_data track by $index"> -->
                        <tr dir-paginate="i in apps_user_data | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{$index + 1}}</td>
                            <td>{{i.skyId}}</td>
                            <td>{{i.eblSkyId}}</td>
                            <td>{{i.userName2}}</td>
                            <td>{{i.cfId}}</td>
                            <td>{{i.makerAction}}</td>
                            <td>{{i.fatherName}}</td>
                            <td>{{i.motherName}}</td>
                            <td>{{i.userName}}</td>
                            <td>{{i.userGroupName}}</td>
                            <td>{{i.dob}}</td>
                            <td>{{i.userEmail}}</td>
                            <td>{{i.gender}}</td>
                            <td>{{i.branchName}}</td>
                            <td class="view-only">
                                <span>Own Account Transfer:
                                    <label>{{(i.isOwnAccTransfer == '1') ? 'Yes' : 'No'}}</label>
                                </span>
                                <span>Inter Account Transfer:
                                    <label>{{(i.isInterAccTransfer == '1') ? 'Yes' : 'No'}}</label>
                                </span>
                                <span>Other Account Transfer:
                                    <label>{{(i.isOtherAccTransfer == '1') ? 'Yes' : 'No'}}</label>
                                </span>
                                <span>Account to Card Transfer:
                                    <label>{{(i.isAccToCardTransfer == '1') ? 'Yes' : 'No'}}</label>
                                </span>
                                <span>Card To Account Transfer:
                                    <label>{{(i.isCardToAccTransfer == '1') ? 'Yes' : 'No'}}</label>
                                </span>
                                <span>Utility Transfer:
                                    <label>{{(i.isUtilityTransfer == '1') ? 'Yes' : 'No'}}</label>
                                </span>
                                <span>QR Payment:
                                    <label>{{(i.isQrPayment == '1') ? 'Yes' : 'No'}}</label>
                                </span>
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
                                        <?php if (ci_check_permission("canApproveAppsUser")): ?>
                                            <li>
                                                <a href="<?= base_url() ?>client_registration_checker/getAppsUserForApproval/{{i.skyId}}">
                                                    <i class="glyphicon glyphicon-pencil"></i> Approve
                                                </a>
                                            </li> 
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="apps_user_data.length <= 0">
                            <td colspan="16">No data found</td>
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