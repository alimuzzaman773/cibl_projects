<h2 class="title-underlined">Banking Requests</h2>

<div class="container" id="BankingModuleApp" data-ng-controller="BankingController">    
    <div style="margin-bottom: 15px">
        <div class="row">
            <!--<div class="col-xs-3 col-sm-3">
                <div class="form-group">
                    <label>Service Type</label> 
                    <select class="form-control input-sm" ng-model="parent_code" ng-change="getRequest()">
                        <option value="">Parent Service</option>
                        <?php foreach ($parentServiceList as $item) { ?>
                            <option value="<?= $item->serviceTypeCode ?>"><?= $item->serviceName ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>-->
            <div class="col-xs-3 col-sm-2">
                <div class="form-group">
                    <label>From Date</label> 
                    <input type="text" name="fromDate" id="fromDate" class="form-control input-sm" ng-model="searchParams.from_date" placeholder="Search by From Date"/>
                </div>
            </div>
            <div class="col-xs-3 col-sm-2">
                <div class="form-group">
                    <label>To Date</label> 
                    <input type="text" name="toDate" id="toDate"  class="form-control input-sm" ng-model="searchParams.to_date" placeholder="Search by To Date"/>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3">
                <div class="form-group">
                    <label>Service Type</label> 
                    <select class="form-control input-sm" ng-model="type_code" ng-change="getServiceRequest()">
                        <option value="">All Request</option>
                        <option data-ng-repeat="i in child_list" value="{{i.serviceTypeCode}}">{{i.parentServiceName}} - {{i.serviceName}}</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3">
                <div class="form-group">
                    <label>Apps ID</label> 
                    <input type="text" placeholder="Search by Apps ID" class="form-control" data-ng-model="searchParams.eblSkyId" />
                </div>
            </div>
            <div class="col-xs-3 col-sm-3">
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
    </div>
    <div class="col-md-12 col-sm-12 text-right" data-ng-show="totalCount > 0">        
        <span class="label label-primary"> Displaying: {{ ((per_page * currentPageNumber) - (per_page - 1))}} to {{ upper_range()}} of {{ totalCount}}</span>            
    </div>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr class="bg-primary">
                <th>SL#</th>
                <th>Service Name</th>
                <th>Apps User ID</th>
                <th data-ng-if="type_code == 'lp'">Limit Package</th>
                <th>Reference No.</th>
                <th>User Name</th>
                <th>Contact No.</th>
                <th>Reason</th>
                <th>Remarks</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr dir-paginate="item in banking_list | itemsPerPage: per_page track by $index"
                total-items="totalCount" current-page="pagination.current" data-ng-class="{'bg-success': item.status2 == 1, 'bg-success': item.status1 == 1}">
                <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                <td>{{ item.serviceName}}</td>
                <td>{{ item.eblSkyId}}</td>
                <td data-ng-if="type_code == 'lp'">
                    {{item.userGroupName}} <button class="btn btn-xs btn-default pull-right hidden" data-ng-click="showPackage(item.serviceId);"><i class="glyphicon glyphicon-eye-open"></i></button><br />
                    <small style="display:none" id="lp-{{item.serviceId}}">
                        <b>oatMinTxnLim:</b> {{item.oatMinTxnLim}}, <b>oatMaxTxnLim:</b> {{item.oatMaxTxnLim}}, <b>oatDayTxnLim:</b> {{item.oatDayTxnLim}}, <b>oatNoOfTxn:</b> {{item.oatNoOfTxn}} <br />
                        <b>eatMinTxnLim:</b> {{item.eatMinTxnLim}}, <b>eatMaxTxnLim:</b> {{item.eatMaxTxnLim}}, <b>eatDayTxnLim:</b> {{item.eatDayTxnLim}}, <b>eatNoOfTxn:</b> {{item.eatNoOfTxn}} <br />
                        <b>obtMinTxnLim:</b> {{item.obtMinTxnLim}}, <b>obtMaxTxnLim:</b> {{item.obtMaxTxnLim}}, <b>obtDayTxnLim:</b> {{item.obtDayTxnLim}}, <b>obtNoOfTxn:</b> {{item.obtNoOfTxn}} <br />
                        <b>pbMinTxnLim:</b> {{item.pbMinTxnLim}}, <b>pbMaxTxnLim:</b> {{item.pbMaxTxnLim}}, <b>pbDayTxnLim:</b> {{item.pbDayTxnLim}}, <b>pbNoOfTxn:</b> {{item.pbNoOfTxn}}
                    </small>
                </td>
                <td>{{ item.referenceNo}}</td>
                <td>{{ item.userName}}</td>
                <td>{{ item.mobileNo}}</td>
                <td>{{ item.reason}}</td>
                <td>{{ item.remarks}}</td>
                <td>{{ item.requestDtTm}}</td>
                <td>{{ item.status1}}</td>
                <td>
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if (ci_check_permission("canEmailBankingRequest")): ?>
                            <li data-ng-if="item.typeCode != 'lp' && item.status1 == 0">
                                <a href="<?= base_url() . "banking_service_request/processRequestById/" ?>{{item.serviceId}}">
                                    <i class="glyphicon glyphicon-envelope"></i> Email
                                </a>
                            </li>  
                            <li data-ng-if="item.typeCode == 'lp' && item.status2 == 0">
                                <a href="<?=base_url().'client_registration/update_limit_package/'?>{{item.skyId}}?requestedId={{item.requestedAppsGroupId}}&serviceId={{item.serviceId}}">
                                    <i class="glyphicon glyphicon-alert"></i> Update Limit Package
                                </a>
                            </li>
                            <?php endif;?>
                            <?php if (ci_check_permission("canActivateUserLimitPackage")): ?>
                            <li class="hidden" data-ng-if="type_code == 'lp' && item.status1 == '0'">
                                <a style="cursor: pointer" data-ng-click="activateLimitPackage(item.serviceId)">
                                    <i class="glyphicon glyphicon-alert"></i> Activate Limit Package
                                </a>
                            </li>  
                            <?php endif;?>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr data-ng-show="banking_list.length <= 0">
                <td colspan="11">No data found</td>
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
ci_add_js(asset_url() . 'app/service_request/banking_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.filterTypeCode = '<?=$this->input->get('typeCode',true)?>';
    
    $("#fromDate, #toDate").datepicker({
        dateFormat: 'yy-mm-dd',
        constrainInput: true
    }).on('focusin', function () {
        $(this).prop("readonly", true);
    }).on('focusout', function () {
        $(this).prop("readonly", false);
    });
</script>

<?php if(true == false): ?>
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
<?php endif; ?>













<!--
<h2 class="title-underlined">Banking Requests</h2>

<div class="container" style="margin-top:50px">
    <form method="post" id="bankingRequestForm" name="bankingRequestForm" action="<?php echo base_url(); ?>banking_service_request/getRequests">
    <table border="0" cellpadding="5">         
        <tr>
            <th align="left" scope="">Select Service Type</th>
            <td>
                <select id="Prequest" name="Prequest"  onchange="generateDropDown();" class="form-control">
                    <option value="0">Select Service Type</option>
<?php foreach ($parentServiceList as $item) { ?>
                                  <option value="<?= $item->serviceTypeCode ?>"><?= $item->serviceName ?></option>
<?php } ?>
                   
                </select>
            </td>
            <td>
                <select id="Crequest" name="Crequest"  onchange="generateList();" class="form-control">
                    <option value="0">Select Service Type</option>
<?php foreach ($childServiceList as $item) { ?>
                                  <option value="<?= $item->serviceTypeCode ?>"><?= $item->serviceName ?></option>
<?php } ?>
                   
                </select>
            </td>
        </tr>   
    </table>
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
           {  mDataProp:'serviceId' },
           {  mDataProp:'skyId' },
           {  mDataProp:'typeCode' },
           {  mDataProp:'serviceName'},
           {  mDataProp:'eblSkyId'},
           {  mDataProp:'referenceNo'},
           {  mDataProp:'userName'},
           {  mDataProp:'mobileNo'},
           {  mDataProp:'reason'},
           {  mDataProp:'requestDtTm'}
           ]}}" class="table table-striped table-bordered" id="referenceTable">
    <thead>
        <tr>
            <th hidden scope="col">ID</th>
            <th style="text-align: center">Action</th>
            <th hidden scope="col">Sky ID</th>                  
            <th hidden scope="col">Type Code</th>
            <th scope="col">Service Name</th>
            <th scope="col">Apps User ID</th>
            <th scope="col">Reference No.</th>
            <th scope="col">User Name</th>
            <th scope="col">Contact No.</th>
            <th scope="col">Reason</th>
            <th scope="col">Request Date</th>
        </tr>
    </thead>

    <tbody>
    </tbody>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden data-bind="text: serviceId"></td>
        <td style="text-align: center">
        <button data-bind="click: $root.sendMail, visible: mail" class="btn btn-warning">Email</button>
        <button data-bind="click: $root.sendMail, visible: mailSent" class="btn btn-success">Mail Sent</button>
        </td>
        <td hidden data-bind="text: skyId"></td>
        <td hidden data-bind="text: typeCode"></td>
        <td><span data-bind="text: serviceName"></span></td>
        <td><span data-bind="text: eblSkyId"></span></td>
        <td><span data-bind="text: referenceNo"></span></td>
        <td><span data-bind="text: userName"></span></td>
        <td><span data-bind="text: mobileNo"></span></td>
        <td><span data-bind="text: reason"></span></td>
        <td><span data-bind="text: requestDtTm"></span></td>    
    </script>

</div>



<script type="text/javascript" charset="utf-8">

    $('#Prequest').val("<?= $cServiceTypeCode ?>");
    $('#Crequest').val("<?= $pServiceTypeCode ?>");
    
    function generateDropDown()
    {
      $("#bankingRequestForm").submit();
    };

    function generateList()
    {
      $("#bankingRequestForm").submit();
    };



    var vm = function()
    {
      var self = this;
      self.records = ko.observableArray(<?= $BankingRequestList ?>);
      $.each(self.records(), function(i, record) { 

            record.mailSent = ko.observable(false);
            record.mail = ko.observable(true);      

            if(record.status1 === "1"){
              record.mailSent = ko.observable(true);
              record.mail = ko.observable(false);
            }
            else if(record.status1 === "0"){
              record.mailSent = ko.observable(false);
              record.mail = ko.observable(true);
            }
        })


      self.sendMail = function(item){
        window.location = "<?php echo base_url(); ?>banking_service_request/processRequestById/" + item.serviceId;
      }


    }
    ko.applyBindings(new vm());
    
</script>

-->