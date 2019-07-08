<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">Request Account List </h2>
    </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="form-group col-sm-2 col-xs-12">
            <label>Search</label>
            <input type="text" class="form-control" data-ng-model="searchParams.search" placeholder="Search by Apps ID, User Name, Customer ID, Father or Mother Name" title="Search by Apps ID, User Name, Customer ID, Father or Mother Name" />
        </div>
        <div class="form-group col-sm-2 col-xs-12">
            <label>From Date</label>
            <input type="text" readonly="true" class="form-control" data-ng-model="searchParams.from_date" id="fromDate" />                
        </div>
        <div class="form-group col-sm-2 col-xs-12">
            <label>To Date</label>
            <input type="text" readonly="true" class="form-control" data-ng-model="searchParams.to_date" id="toDate" />                
        </div>
        <div class="form-group col-sm-2 col-xs-12">
            <label>Approved Status</label>
            <select class="form-control" data-ng-model="searchParams.approved_status">
                <option value="pending">Pending</option>
                <option value="completed">Approved</option>
            </select>
        </div>
        <div class="form-group col-sm-2 col-xs-12">
            <label>Request Type</label>
            <select class="form-control" data-ng-model="searchParams.request_type">
                <option value="account">Account</option>
                <option value="card">Card</option>
            </select>
        </div>
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
                        <th>User Name</th>
                        <th>Father Name</th>
                        <th>Mother Name</th>
                        <th>User Email</th>
                        <th>User Mobile</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Entity No</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="i in account_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                        <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                        <td>{{i.eblSkyId}}</td>
                        <td>{{i.cfId}}</td>
                        <td>{{i.userName}}</td>
                        <td>{{i.fatherName}}</td>
                        <td>{{i.motherName}}</td>
                        <td>{{i.userEmail}}</td>
                        <td>{{i.userMobNo1}}</td>
                        <td>{{i.gender}}</td>
                        <td>{{i.dob}}</td>
                        <td>{{i.entityNumber}}</td>
                        <td>{{i.type}}</td>
                        <td>{{i.approve_status}}</td>
                        <td>{{i.created}}</td>
                        <td>
                            {{i.approv_status}}
                            <div class="dropdown pull-right" ng-show="i.approve_status == 'pending'">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?= base_url() . "call_center/#/account_details/" ?>{{i.skyId}}">
                                            <i class="glyphicon glyphicon-flash"></i> Details
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-ng-show="account_list.length <= 0">
                        <td colspan="14">No data found</td>
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
            $(document).ready(function (e) {
                //populateBranchList("branchId");

                $("#fromDate").datepicker({yearRange: 'c-10:c+10', dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true}).on('focusin', function () {
                    $(this).prop("readonly", true);
                }).on('focusout', function () {
                    $(this).prop("readonly", false);
                });
                $("#toDate").datepicker({yearRange: 'c-10:c+10', dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true}).on('focusin', function () {
                    $(this).prop("readonly", true);
                }).on('focusout', function () {
                    $(this).prop("readonly", false);
                });
            });</script>