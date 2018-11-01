<h2 class="title-underlined">Priority Requests</h2>

<div class="container" id="PriorityModuleApp" data-ng-controller="PriorityController">    

    <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <label>Request Type</label> 
                <select class="form-control input-sm" ng-model="searchParams.type_code">
                    <option value="">All Request</option>
                    <?php foreach ($service_list as $item) { ?>
                        <option value="<?= $item->serviceTypeCode ?>"><?= $item->serviceName ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Apps ID</label> 
                <input type="text"
                       placeholder="Apps ID"
                       class="input-sm form-control"
                       ng-model="searchParams.apps_id"/>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Reference Number</label> 
                <input type="text"
                       placeholder="Reference Number"
                       class="input-sm form-control"
                       ng-model="searchParams.reference_no"/>
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
                <label>Action</label> 
                <div class="button-group">
                    <button class="btn btn-primary btn-sm"
                            data-ng-click="getResultsPage(1)">
                        <i class="glyphicon glyphicon-search"></i> Search
                    </button>

                    <button class="btn btn-primary btn-sm"
                            data-ng-click="resetSearch();">
                        <i class="glyphicon glyphicon-refresh"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr class="bg-primary">
                <th>SL#</th>
                <th>Service Name</th>
                <th>Apps User ID</th>
                <th>Reference Number</th>
                <th>Name</th>
                <th>Contact No</th>
                <th>Email</th>
                <th>Request Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr dir-paginate="item in priority_list | itemsPerPage: per_page track by $index"
                total-items="totalCount" current-page="pagination.current">
                <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                <td>{{ item.serviceName}}</td>
                <td>{{ item.eblSkyId}}</td>
                <td>{{ item.referenceNo}}</td>
                <td>{{ item.name}}</td>
                <td>{{ item.contactNo}}</td>
                <td>{{ item.email}}</td>
                <td>{{ item.requestDtTm}}</td>
                <td>
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if (ci_check_permission("canEmailPriorityRequest")): ?>
                                <li>
                                    <a href="<?= base_url() . "priority_request_process/processRequestById/" ?>{{item.serviceRequestID}}">
                                        <i class="glyphicon glyphicon-envelope"></i> Email
                                    </a>
                                </li>  
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr data-ng-show="priority_list.length <= 0">
                <td colspan="13">No data found</td>
            </tr>
        </tbody>
    </table>
    <div class="box-footer clearfix  pull-right">
        <dir-pagination-controls on-page-change="pageChanged(newPageNumber)"
                                 template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
    </div>   
</div>

<?php
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/directives/dirPagination.js");
ci_add_js(asset_url() . 'app/service_request/priority_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};

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