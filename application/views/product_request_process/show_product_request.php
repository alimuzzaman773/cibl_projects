<h2 class="title-underlined">Product Requests</h2>

<div class="container" id="ProductModuleApp" data-ng-controller="ProductController">    

    <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <label>Product Type</label> 
                <select class="form-control input-sm" ng-model="searchParams.product_id">
                    <option value="">All Product</option>
                    <?php foreach ($service_list as $item) { ?>
                        <option value="<?= $item->productId ?>"><?= $item->productName ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Customer Name</label> 
                <input type="text"
                       placeholder="Customer Name"
                       class="input-sm form-control"
                       ng-model="searchParams.customer_name"/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Mobile No</label> 
                <input type="text"
                       placeholder="Mobile Number"
                       class="input-sm form-control"
                       ng-model="searchParams.mobile_no"/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>From Date</label> 
                <input type="text" 
                       id="fromDate" 
                       class="form-control input-sm" 
                       ng-model="searchParams.from_date" 
                       placeholder="Search by From Date"/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>To Date</label> 
                <input type="text" 
                       id="toDate" 
                       class="form-control input-sm" 
                       ng-model="searchParams.to_date"
                       placeholder="Search by To Date"/>
            </div>
        </div>

        <div class="col-sm-2">
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

    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr class="bg-primary">
                <th>SL#</th>
                <th>Name</th>
                <th>Contact No</th>
                <th>Prefer Call Date</th>
                <th>Email</th>
                <th>Location</th>
                <th>Product Name</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr dir-paginate="item in product_list | itemsPerPage: per_page track by $index"
                total-items="totalCount" current-page="pagination.current">
                <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                <td>{{ item.name}}</td>
                <td>{{ item.contactNo}}</td>
                <td>{{ item.preferredCallDtTm}}</td>
                <td>{{ item.email}}</td>
                <td>{{ item.myLocation}}</td>
                <td>{{ item.productName}}</td>
                <td>{{ item.requestDtTm}}</td>
                <td>
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if (ci_check_permission("canEmailProductRequest")): ?>
                                <li>
                                    <a href="<?= base_url() . "product_request_process/processRequestById/" ?>{{item.applyId}}">
                                        <i class="glyphicon glyphicon-envelope"></i> Email
                                    </a>
                                </li>  
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr data-ng-show="product_list.length <= 0">
                <td colspan="13">No data found</td>
            </tr>
        </tbody>
    </table>
    <div class="box-footer clearfix  pull-right">
        <dir-pagination-controls on-page-change="pageChanged(newPageNumber)"
                                 template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
    </div>   
</div>
<script src="<?php echo asset_url() ?>js/jqueryui/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo asset_url() ?>css/jqueryui/jquery-ui-1.9.2.css"/>
<?php
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/directives/dirPagination.js");
ci_add_js(asset_url() . 'app/service_request/product_module.js');
?>

<script type="text/javascript" charset="utf-8">
                var app = app || {};


                $("#fromDate, #toDate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    constrainInput: true
                });
</script>

<!--

    <table data-bind="dataTable: { dataSource : records, rowTemplate: 'rowTemplate',
           options: {
           bAutoWidth : false,
           aoColumnDefs: [
           { bSortable: false, aTargets: [] },
           { bSearchable: false, aTargets: [] }
           ],
           aaSorting: [],
           aLengthMenu: [[50, 100, 150, -1], [50, 100, 150, 'All']],
           iDisplayLength: 50,
           aoColumns: [
           {  mDataProp:'serviceRequestID' },
           {  mDataProp:'typeCode' },
           {  mDataProp:'serviceName'},
           {  mDataProp:'eblSkyId'},
           {  mDataProp:'referenceNo'},
           {  mDataProp:'name'},
           {  mDataProp:'contactNo'},
           {  mDataProp:'email'},
           {  mDataProp:'requestDtTm'}
           ]}}" class="table table-striped table-bordered" id="referenceTable">
        <thead>
            <tr>
                <th hidden scope="col">ID</th>
                <th style="text-align: center">Action</th>               
                <th hidden scope="col">Type Code</th>
                <th scope="col">Service Name</th>
                <th scope="col">Apps User ID</th>
                <th scope="col">Reference No.</th>
                <th scope="col">Name</th>
                <th scope="col">Contact No.</th>
                <th scope="col">Email</th>
                <th scope="col">Request Date</th>
            </tr>
        </thead>

        <tbody>
        </tbody>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden data-bind="text: serviceRequestID"></td>
        <td style="text-align: center">
            <button data-bind="click: $root.sendMail, visible: mail" class="btn btn-warning">Email</button>
            <button data-bind="click: $root.sendMail, visible: mailSent" class="btn btn-success">Mail Sent</button>
        </td> 
        <td hidden data-bind="text: typeCode"></td>
        <td><span data-bind="text: serviceName"></span></td>
        <td><span data-bind="text: eblSkyId"></span></td>
        <td><span data-bind="text: referenceNo"></span></td>
        <td><span data-bind="text: name"></span></td>
        <td><span data-bind="text: contactNo"></span></td>
        <td><span data-bind="text: email"></span></td>
        <td><span data-bind="text: requestDtTm"></span></td>   
    </script>

</div>


<script type="text/javascript" charset="utf-8">

    $('#request').val("<?= $TypeCode ?>");
    function generateList()
    {
    $("#priorityRequestForm").submit();
    };
    var vm = function()
    {
    var self = this;
    self.records = ko.observableArray(<?= $requestList ?>);
    $.each(self.records(), function(i, record) {

    record.mailSent = ko.observable(false);
    record.mail = ko.observable(true);
    if (record.status === "1"){
    record.mailSent = ko.observable(true);
    record.mail = ko.observable(false);
    }
    else if (record.status === "0"){
    record.mailSent = ko.observable(false);
    record.mail = ko.observable(true);
    }
    })

            self.sendMail = function(item){
            window.location = "<?php echo base_url(); ?>priority_request_process/processRequestById/" + item.serviceRequestID;
            }
    }
    ko.applyBindings(new vm());

</script>

-->



















<!--
<h2 class="title-underlined">Product Requests</h2>

<form method="get" id="productRequestForm" name="productRequestForm" action="<?php echo base_url(); ?>product_request_process/getRequest">
    <div class="container">
        <div class="row">       
            <div class="col-xs-4 col-sm-3">
                <div class="form-group">
                    <label>From Dates</label> 
                    <input type="text" name="fromDate" id="fromDate" class="form-control input-sm" placeholder="Enter From Date"/>
                </div>
            </div>
            <div class="col-xs-4 col-sm-3">
                <div class="form-group">
                    <label>To Date</label> 
                    <input type="text" name="toDate" id="toDate" required class="form-control input-sm" placeholder="Enter To Date"/>
                </div>
            </div>
            <div class="col-xs-4 col-sm-2">
                <div class="form-group">
                    <label style="display:block" class="hidden-xs">&nbsp;&nbsp;</label>
                    <input type="submit"  value="Generate List" class="btn btn-primary btn-sm" />
                </div>
            </div>       
        </div>
    </div>
</form>

<table data-bind="dataTable: { dataSource : records, rowTemplate: 'rowTemplate',
           options: {
           bAutoWidth : false,
           aoColumnDefs: [
           { bSortable: false, aTargets: [] },
           { bSearchable: false, aTargets: [] }
           ],
           aaSorting: [],
           aLengthMenu: [[50, 100, 150, -1], [50, 100, 150, 'All']],
           iDisplayLength: 50,
           aoColumns: [
           {  mDataProp:'applyId' },
           {  mDataProp:'productId' },
           {  mDataProp:'name' },
           {  mDataProp:'contactNo'},
           {  mDataProp:'preferredCallDtTm'},
           {  mDataProp:'email'},
           {  mDataProp:'myLocation'},
           {  mDataProp:'productName'},
           {  mDataProp:'creationDtTm'},
           ]}}" class="table table-striped table-bordered" id="referenceTable">
    <thead>
        <tr>
            <th hidden scope="col">Apply ID</th>
            <th style="text-align: center">Action</th>
            <th hidden scope="col">Product ID</th>
            <th scope="col">Name</th>
            <th scope="col">Contact No.</th>
            <th scope="col">Preferred Call Date</th>
            <th scope="col">Email</th>
            <th scope="col">Location</th>
            <th scope="col">Product Name</th>
            <th scope="col">Request Date</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script id="rowTemplate" type="text/html">
    <td hidden data-bind="text: applyId"></td>
    <td style="text-align: center">
        <button data-bind="click: $root.sendMail, visible: mail" class="btn btn-warning">Email</button>
        <button data-bind="click: $root.sendMail, visible: mailSent" class="btn btn-success">Mail Sent</button>
    </td>   
    <td hidden data-bind="text: productId"></td>
    <td><span data-bind="text: name"></span></td>
    <td><span data-bind="text: contactNo"></span></td>
    <td><span data-bind="text: preferredCallDtTm"></span></td>
    <td><span data-bind="text: email"></span></td>
    <td><span data-bind="text: myLocation"></span></td>
    <td><span data-bind="text: productName"></span></td>
    <td><span data-bind="text: creationDtTm"></span></td> 
</script>
</div>

        <script src="<?php echo base_url(); ?>assets/js/jqueryui/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jqueryui/jquery-ui-1.9.2.css"/>
<script>

    $(function () {
    $("#fromDate").datepicker({
    dateFormat: 'yy-mm-dd',
            constrainInput: true
    });
    $("#toDate").datepicker({
    dateFormat: 'yy-mm-dd',
            constrainInput: true
    });
    });
    $('#fromDate').val("<?= $fromDate ?>");
    $('#toDate').val("<?= $toDate ?>");</script>



<script type="text/javascript" charset="utf-8">
    var vm = function()
    {
    var self = this;
    self.records = ko.observableArray(<?= $productRequest ?>);
    $.each(self.records(), function(i, record) {

    record.mailSent = ko.observable(false);
    record.mail = ko.observable(true);
    if (record.status === "1"){
    record.mailSent = ko.observable(true);
    record.mail = ko.observable(false);
    }
    else if (record.status === "0"){
    record.mailSent = ko.observable(false);
    record.mail = ko.observable(true);
    }
    })


            self.sendMail = function(item){
            window.location = "<?php echo base_url(); ?>product_request_process/processRequestById/" + item.applyId + "/" + $('#fromDate').val() + "/" + $('#toDate').val();
            }
    }
    ko.applyBindings(new vm());

</script>
-->