<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">Request Account List </h2>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="i in account_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                        <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                        <td>{{i.eblSkyId}}</td>
                        <td>{{i.cfId}}</td>
                        <td>{{i.fatherName}}</td>
                        <td>{{i.motherName}}</td>
                        <td>{{i.userName}}</td>
                        <td>{{i.userEmail}}</td>
                        <td>{{i.userMobNo1}}</td>
                        <td>{{i.gender}}</td>
                        <td>{{i.dob}}</td>
                        <td>{{i.entityNumber}}</td>
                        <td>{{i.type}}</td>
                        <td>{{i.approve_status}}</td>
                        <td>
                            {{i.approv_status}}
                            <div class="dropdown pull-right" ng-show="i.approve_status =='pending'">
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