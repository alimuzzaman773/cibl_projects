<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                <?= $pageTitle ?>
                <a href="<?php echo base_url(); ?>pin_generation" class="btn btn-primary btn-xs pull-right">
                    <span class="glyphicon glyphicon-plus"></span> Create Pin
                </a>
            </h3>
        </div>
    </div>
</div>
<div class="container" id="PinModule" data-ng-controller="PinController">
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
                            <th>ESB ID</th>
                            <th>Destroyed/Active</th>
                            <th>Used/Unused</th>
                            <th>Printed/Not</th>
                            <th>Reset/Not</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr dir-paginate="i in data_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                            <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                            <td>{{i.eblSkyId}}</td>
                            <td>
                                <span data-ng-class="{'text-success': i.isActive == '1', 'text-danger': i.isActive == '0'}">{{i.isActive=='1' ? 'Active' : 'Inactive'}}</span>
                            </td>
                            <td>
                                <span data-ng-class="{'text-success': i.isUsed == '1', 'text-danger': i.isUsed == '0'}">{{i.isUsed=='1' ? 'Used' : 'Unused'}}</span>
                            </td>
                            <td>
                                <span data-ng-class="{'text-success': i.isPrinted == '1', 'text-danger': i.isPrinted == '0'}">{{i.isPrinted=='1' ? 'Printed' : 'Not Printed'}}</span>
                            </td>
                            <td>
                                <span data-ng-class="{'text-success': i.isReset == '1', 'text-danger': i.isReset == '0'}">{{i.isReset=='1' ? 'Reset' : 'Not Reset'}}</span>
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
                                        <li>
                                            <a href="<?= base_url() . "pin_generation" ?>">
                                                <i class="glyphicon glyphicon-edit"></i> Create Pin
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
ci_add_js(asset_url() . 'app/pin/pin.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
</script>
