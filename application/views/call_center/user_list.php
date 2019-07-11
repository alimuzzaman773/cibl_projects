<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">User List </h2>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" id="resetModal" role="dialog" aria-labelledby="resetModal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                Please select the PIN Sending Channel
            </div>
            <div class="modal-body">
                <select class="form-control" data-ng-model="otp_channel_pin">
                    <option value="sms">SMS</option>
                    <option value="email">EMAIL</option>
                </select>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-ng-click="sendPasswordResetPin(resetSkyId);">
                    Confirm and send PIN
                </button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="form-group col-sm-3 col-xs-12">
            <label>Search</label>
            <input type="text" class="form-control" data-ng-model="searchParams.search" placeholder="Search by Apps ID, User Name, Customer ID, Father or Mother Name" title="Search by Apps ID, User Name, Customer ID, Father or Mother Name" />
        </div>
        <div class="form-group col-sm-3 col-xs-12">
            <label>Branch</label>
            <select class="form-control" data-ng-model="searchParams.branch">
                <option ng-repeat="branch in branch_list" value="{{branch.branchCode}}">{{branch.ATMName}}</option>
            </select>
        </div>
        <!--
        <div class="form-group col-sm-2 col-xs-2">
            <label>Status</label>
            <select class="form-control" data-ng-model="searchParams.status">
                <option value="0">New Request</option>
                <option value="1">Approved</option>
                <option value="2">Rejected</option>
                <option value="3">Waiting for Approval</option>
            </select>
        </div>
        -->
        <div class="form-group col-sm-3 col-xs-12">
            <label>From Date</label>
            <input type="text" readonly="true" class="form-control" data-ng-model="searchParams.from_date" id="fromDate" />                
        </div>
        <div class="form-group col-sm-3 col-xs-12">
            <label>To Date</label>
            <input type="text" readonly="true" class="form-control" data-ng-model="searchParams.to_date" id="toDate" />                
        </div>
        <div class="form-group col-sm-3 col-xs-12">
            <label>Status (Registration)</label>
            <select class="form-control" data-ng-model="searchParams.is_regester">
                <option value="1">Activated</option>
                <option value="0">Waiting Activation</option>
            </select>
        </div>
        <div class="form-group col-sm-3 col-xs-12">
            <label>Password Reset</label>
            <select class="form-control" data-ng-model="searchParams.password_reset">
                <option value="0">Accepted</option>
                <option value="1">New Request</option>
            </select>
        </div>
        <!--
        <div class="form-group col-sm-4 col-xs-6">
            <label>Filter By:</label>
            <select class="form-control" data-ng-model="searchParams.filter_by">
                <option value="activation">Waiting Account Activation</option>
                <option value="passwordReset">Waiting Password Reset</option>
                <option value="activationPending24">Activation Pending More Than 24 Hours</option>
                <option value="passwordResetPending24">Password Reset Request Pending More Than 24 Hours</option>
            </select>
        </div>
        -->
        <div class="form-group col-xs-6 col-sm-2">
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
    <div class="col-xs-12 col-sm-12">
        <div class="table-responsive">        
            <table class="table table-bordered table-condensed table-striped table-hover" >          
                <thead>
                    <tr class="bg-primary">
                        <th>SI#</th>
                        <th>Apps ID</th>
                        <th>CIF ID</th>
                        <th>Request Type</th>
                        <th>Request Number</th>
                        <th>Customer Type</th>
                        <th>User Name</th>
                        <th>Father Name</th>
                        <th>Mother Name</th>
                        <th>User Email</th>
                        <th>User Mobile</th>
                        <th>Current Address</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Remarks</th>
                        <th>Branch</th>
                        <th>Registration Date</th>
                        <!--
                        <th>Maker</th>
                        <th>Maker Date</th>
                        <th>Checker</th>
                        <th>Checker Date</th>
                        -->
                        <th>Rejected</th>
                        <th>Status</th>
                        <th>IsReset</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="i in user_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                        <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                        <td>{{i.eblSkyId}}</td>
                        <td>{{i.cfId}}</td>
                        <td>{{i.entityType}}</td>
                        <td>{{i.entityNumber}}</td>
                        <td>{{i.concentrationName}}</td>
                        <td>{{i.userName}}</td>
                        <td>{{i.fatherName}}</td>
                         <td>{{i.motherName}}</td>
                        <td>{{i.userEmail}}</td>
                        <td>{{i.userMobNo1}}</td>
                        <td>{{i.currAddress}}</td>
                        <td>{{i.gender}}</td>
                        <td>{{i.dob}}</td>
                        <td>{{i.remarks}}</td>
                        <td>{{i.branchName}}</td>
                        <td>{{i.created_on}}</td>
                        <!--
                        <td>{{i.makerName}}</td>
                        <td>{{i.checkerActionDt}} {{i.checkerActionTm}}</td>
                        <td>{{i.checkerName}}</td>
                        <td>{{i.makerActionDt}} {{i.makerActionTm}}</td>
                        -->
                        <td>{{i.isRejected == 1 ? 'Rejected' : ''}}</td>
                        <td ng-class="{'bg-success' : (i.skyIdOriginal > 0), 'bg-primary' : (i.skyIdOriginal <= 0 || i.skyIdOriginal == null)}">
                            {{i.skyIdOriginal > 0 ? 'Activated' : 'Waiting Activation'}}
                        </td>
                        <td>
                            <span class="text-danger" data-ng-if="i.isRejected != '1' && i.passwordReset == '1' && i.isPublished == '1'">Request for password request</span>
                        </td>
                        <td>
                            <div class="dropdown pull-right">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li data-ng-if="i.isRejected != '1' && i.isPublished == '0'">
                                        <a href="<?= base_url() . "call_center/#/user_approve/" ?>{{i.skyId}}">
                                            <i class="glyphicon glyphicon-flash"></i> Details
                                        </a>
                                    </li>
									<!--
                                    <li data-ng-if="i.isRejected != '1' && i.isPublished == '1'">
                                        <a style="cursor: pointer" ng-click="sendPasswordResetPin(i.skyId);">
                                            <i class="glyphicon glyphicon-send"></i> Send Password Reset Pin
                                        </a>
                                    </li>
									-->
                                    <li data-ng-if="i.isRejected != '1' && i.passwordReset == '1' && i.isPublished == '1'">
                                        <a style="cursor: pointer" ng-click="showResetModal(i.skyId, 'pin_send');">
                                            <i class="glyphicon glyphicon-send"></i> Send Password Reset Pin
                                        </a>
                                    </li>
                                    
                                    <!--
                                    <li data-ng-if="i.isPublished == '0'">
                                        <a style="cursor: pointer" ng-click="showResetModal(i.skyId, 'pin_resend');">
                                            <i class="glyphicon glyphicon-send"></i> Resend PIN To New User
                                        </a>
                                    </li>
                                    -->
                                    <li data-ng-if="i.isRejected == '0' && i.isPublished == '0'">
                                        <a style="cursor: pointer" ng-click="rejectRequest(i.skyId);">
                                            <i class="glyphicon glyphicon-trash"></i> Reject User
                                        </a>
                                    </li>
                                    <li>
                                        <a ng-if="i.skyIdOriginal <= 0" href="<?= base_url() . "call_center/#/remove/" ?>{{i.skyId}}">
                                            <i class="glyphicon glyphicon-trash"></i> Remove
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-ng-show="user_list.length <= 0">
                        <td colspan="21">No data found</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <dir-pagination-controls on-page-change="pageChanged(newPageNumber)" template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
            </div>             
        </div>
    </div>
</div>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    $(document).ready(function(e){
    //populateBranchList("branchId");

    $("#fromDate").datepicker({yearRange:'c-10:c+10', dateFormat:'yy-mm-dd', changeMonth:true, changeYear:true}).on('focusin', function(){
    $(this).prop("readonly", true);
    }).on('focusout', function(){
    $(this).prop("readonly", false);
    });
    $("#toDate").datepicker({yearRange:'c-10:c+10', dateFormat:'yy-mm-dd', changeMonth:true, changeYear:true}).on('focusin', function(){
    $(this).prop("readonly", true);
    }).on('focusout', function(){
    $(this).prop("readonly", false);
    });
    });</script>