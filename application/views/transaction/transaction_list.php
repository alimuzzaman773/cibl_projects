<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">Transaction List </h2>
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
                        <th>Client ID</th>
                        <th>From Account</th>
                        <th>To Account</th>
                        <th>Amount</th>
                        <th>Is Reverse</th>
                        <th>Created Date</th>
                        <th>Success</th>
                        <th>Narration</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="i in transaction_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                        <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                        <td>{{i.eblSkyId}}</td>
                        <td>{{i.cfId}}</td>
                        <td>{{i.clientId}}</td>
                        <td>{{i.fromAccNo}}</td>
                        <td>{{i.toAccNo}}</td>
                        <td>{{i.amount}}</td>
                        <td>{{i.isReverse}}</td>
                        <td>{{i.creationDtTm}}</td>
                        <td>{{i.isSuccess}}</td>
                        <td>{{i.narration}}</td>

                        <td>
                            <div class="dropdown pull-right">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a style="cursor: pointer" ng-click="transactionReverse(i.transferId);">
                                            <i class="glyphicon glyphicon-send"></i> Reverse
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-ng-show="transaction_list.length <= 0">
                        <td colspan="13">No data found</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <dir-pagination-controls on-page-change="pageChanged(newPageNumber)" template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
            </div>             
        </div>
    </div>
</div>