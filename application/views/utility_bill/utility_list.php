<div class="border">
   <h3 class="title-underlined">Utility Bill Payment List</h3>
    <div class="row">
        <div class="col-2 col-sm-2 col-md-2">
            <div class="form-group">
                <label>From Date: </label> 
                <input type="text" name="from_date" id="from_date" class="form-control form-control-sm" ng-model="searchParams.from_date" data-ng-click="showDP('from_date');" placeholder="Select from date">
            </div>
        </div>
        <div class="col-2 col-sm-2 col-md-2">
            <div class="form-group">
                <label>To Date: </label> 
                <input type="text" name="to_date" id="to_date" class="form-control form-control-sm" ng-model="searchParams.to_date" data-ng-click="showDP('to_date');" placeholder="Select to date">
            </div>
        </div>
        <div class="col-2 col-sm-2 col-md-2">
            <label>Status</label>
            <select class="form-control form-control-sm" ng-model="searchParams.status">
                <option value="">Please select a status</option>
                <option value="Y">Success</option>
                <option value="N">Failed</option>
            </select>
        </div>
        <div class="col-2 col-sm-2 col-md-2">
            <label>Utility Name</label>
            <select class="form-control form-control-sm" ng-model="searchParams.utility">
                <option value="">Please select</option>
                <option value="dpdc">DPDC</option>
                <option value="desco">DESCO</option>
                <option value="top_up">Mobile Top-Up</option>
                <option value="wasa">WASA</option>
                <option value="ois">OIS</option>
                <option value="buft">BUFT</option>
                <option value="titas_meter">Titas Meter</option>
                <option value="titas_non_meter">Titas Non Meter</option>
                <option value="titas_demand_note">Titas Demand Note</option>
            </select>
        </div>
        <div class="col-2 col-sm-2 col-md-2">
            <div class="form-group">
                <label>Search</label> 
                <input type="text" class="form-control form-control-sm" ng-model="searchParams.utility_name" placeholder="Search"/>
            </div>
        </div>
        <div class="col-2 col-sm-2 col-md-2">
            <div class="form-group">
                <label style="display:block" class="hidden-xs">&nbsp;&nbsp;</label>
                <button class="btn btn-success btn-sm" data-ng-click="getResultsPage(1)">
                    <i class="fa fa-search" aria-hidden="true"></i> Search
                </button>
                <button class="btn btn-success btn-sm" data-ng-click="resetSearch();">
                    <i class="fa fa-refresh" aria-hidden="true"></i> Reset
                </button>
            </div>
        </div>
    </div>
    <div class="col-12 text-right" data-ng-show="totalCount > 0">        
        <span class="color_008 bold"> Displaying: {{ ((per_page * currentPageNumber) - (per_page - 1))}} to {{ upper_range()}} of {{ totalCount}}</span>            
    </div>
    <div class="table-responsive">        
        <table class="table bg_white table-striped table-bordered table-hover" >          
            <thead>
                <tr class="bg-primary">
                    <th>SI</th>
                    <th>Date</th>
                    <th>Utility Name</th>
                    <th>From Account</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr dir-paginate="i in bill_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                    <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                    <td>{{i.created}}</td>
                    <td>{{i.utility_name}}</td>
                    <td>{{i.bpt_from_ac}}</td>
                    <td>{{i.bpt_amount}}</td>
                    <td>{{i.isSuccess=='Y' ? 'Success' : 'Failed'}}</td>
                    <td>
                        <div class="dropdown pull-right" ng-if="i.utility_name !=='top_up'">
                            <a class="btn btn-success dropdown-toggle btn-xs color_white" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" ng-click="utilityReverse(i.payment_id)" style="cursor: pointer"><i class="glyphicon glyphicon-arrow-up"></i> Reverse Bill</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr data-ng-show="bill_list.length <= 0">
                    <td colspan="7">No data found</td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <dir-pagination-controls on-page-change="pageChanged(newPageNumber)" template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
        </div>                
    </div>
</div>