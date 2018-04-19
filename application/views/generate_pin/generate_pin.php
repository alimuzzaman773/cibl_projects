<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                <?= $pageTitle ?>
                <a href="<?php echo base_url(); ?>pin_generation/viewPinByAction" class="btn btn-primary btn-xs pull-right">
                    <span class="glyphicon glyphicon-plus"></span> Pin List
                </a>
            </h3>
        </div>
    </div>
</div>
<div class="container" id="PinRequestModule" data-ng-controller="PinRequestController">
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
                            <th>Total Pin</th>
                            <th>Maker Action</th>
                            <th>Maker Remark</th>
                            <th>Requested Byt</th>
                            <th>Request Date</th>
                            <th>Approve Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr dir-paginate="i in data_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                            <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                            <td>{{i.totalPin}}</td>
                            <td>{{i.makerAction}}</td>
                            <td>{{i.makerActionComment}}</td>
                            <td>{{i.fullName}}</td>
                            <td>{{i.makerActionDt}}</td>
                            <td>{{i.checkerActionDt}}</td>
                            <td>
                                <span data-ng-class="{'text-success': i.mcStatus == '1', 'text-danger': i.mcStatus == '0'}">{{i.mcStatus=='1' ? 'Approved' : 'Wait for approve'}}</span>
                            </td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?= base_url() . "pin_generation/newRequest/Create" ?>{{i.generateId}}">
                                                <i class="glyphicon glyphicon-collapse-up"></i> New Request
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="data_list.length <= 0">
                            <td colspan="8">No data found</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <dir-pagination-controls on-page-change="pageChanged(newPageNumber)" template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
                </div>                
                <div>
                </div>
            </div>
        </div>
    </div>    
</div>    

<?php
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/directives/dirPagination.js");
ci_add_js(asset_url() . 'app/pin/pin_request.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
</script>



