<div class="row">
    <div class="col-sm-12">
        <h3 class="title-underlined">Transaction List</h3>
    </div>

    <div class="modal fade bs-example-modal-sm" tabindex="-1" id="paymentModal" role="dialog" aria-labelledby="paymentModal">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    Payment Remarks
                </div>
                <div class="modal-body">
                    <textarea type="text" name="from_date" rows="5" id="remarks" class="form-control form-control-sm" ng-model="payment_remarks" placeholder="Payment Remarks"></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" data-ng-click="confirmQrPayment(qrPayementId);">
                        Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="form-group">
            <label>From Date: </label> 
            <input type="text" name="from_date" id="from_date" class="form-control form-control-sm" ng-model="searchParams.from_date" data-ng-click="showDP('from_date');" placeholder="Select from date">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="form-group">
            <label>To Date: </label> 
            <input type="text" name="to_date" id="to_date" class="form-control form-control-sm" ng-model="searchParams.to_date" data-ng-click="showDP('to_date');" placeholder="Select to date">
        </div>
    </div>
    <div class="col-2 col-sm-2 col-md-2 hide">
        <label>Status</label>
        <select class="form-control form-control-sm" ng-model="searchParams.status">
            <option value="">Show All</option>
            <option value="Y">Success</option>
            <option value="N">Failed</option>
        </select>
    </div>
    <div class="col-2 col-sm-2 col-md-2">
        <label>Payment Status</label>
        <select class="form-control form-control-sm" ng-model="searchParams.payment_status">
            <option value="">Show All</option>
            <option value="0">Unpaid</option>
            <option value="1">Paid</option>
        </select>
    </div>
    <div class="col-2 col-sm-2 col-md-2 hide">
        <label>Transaction Type</label>
        <select class="form-control form-control-sm" ng-model="searchParams.transaction_type">
            <option value="">Show All</option>
            <option value="cbs_to_cms">CBS to CMS</option>
            <option value="cms_to_cbs">CMS to CBS</option>
            <option value="cbs_to_cbs">CBS to CBS</option>
        </select>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="form-group">
            <label>From Account</label> 
            <input type="text" class="form-control form-control-sm" ng-model="searchParams.from_account" placeholder="Ex: 123456"/>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="form-group">
            <label>Batch Number</label> 
            <input type="text" class="form-control form-control-sm" ng-model="searchParams.batch_number" placeholder="Ex: 123456"/>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="form-group">
            <label>Search</label> 
            <input type="text" class="form-control form-control-sm" ng-model="searchParams.search" placeholder="Search"/>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
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
    <table class="table bg_white table-striped table-bordered table-hover" style="font-size: 11px">          
        <thead>
            <tr class="bg-primary">
                <th>SI</th>
                <th>Transaction Date</th>
                <th>Apps ID</th>
                <th>Username</th>
                <th>CIF ID</th>
                <th>Client ID</th>
                <th>Transaction Mode</th>
                <th>From Account</th>
                <th>To Account</th>
                <th>Narration</th>
                <th>Batch Number</th>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Disputed</th>
                <th>Paid By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr dir-paginate="i in transaction_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                <td>{{i.creationDtTm}}</td>
                <td>{{i.eblSkyId}}</td>
                <td>{{i.userName}}</td>
                <td>{{i.cfId}}</td>
                <td>{{i.clientId}}</td>
                <td>{{i.transactionMode}}</td>
                <td>{{i.fromAccNo}}</td>
                <td>{{i.toAccNo}}</td>
                <td>{{i.narration}}</td>
                <td>{{i.crossRefNo}}</td>
                <td>{{i.transferId}}</td>
                <td>{{i.amount}}</td>
                <td>{{i.isSuccess=='Y' ? 'Success' : 'Failed'}}</td>
                <td data-ng-class="{'test' : setStatus($index), 'bg-success' : i.paymentStatus == '1', 'bg-danger' : i.paymentStatus == '0'}">{{i.paymentStatus == '0' ? 'Unpaid' : 'Paid'}}</td>
                <td>{{i.disputed}}</td>
                <td>{{i.adminUserName}}</td>
                <td>
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if (ci_check_permission("canSendTrnReference")): ?>
                                <li>
                                    <a data-ng-click="sendTrnReference(i.transferId)" href="#">
                                        <i class="glyphicon glyphicon-send"></i> Send Reference No
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (ci_check_permission("canUpdateQrPaymentStatus")): ?>
                                <li ng-if="i.paymentStatus == '0' && i.isSuccess == 'Y'">
                                    <a ng-click="createPaymentModal(i.qrtId);" style="cursor: pointer">
                                        <i class="fa fa-money"></i> Paid
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr data-ng-show="transaction_list.length <= 0">
                <td colspan="15">No data found</td>
            </tr>
        </tbody>
    </table>
    <div class="text-center">
        <dir-pagination-controls on-page-change="pageChanged(newPageNumber)" template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
    </div>                
</div>
