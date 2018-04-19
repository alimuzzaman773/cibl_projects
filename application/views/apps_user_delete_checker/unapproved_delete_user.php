<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                Apps User Delete Checker
            </h3>
        </div>
    </div>
</div>

<div class="container" id="UserDeleteCheckerModule" data-ng-controller="UserDeleteCheckerController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th>SL#</th>
                            <th>ESB ID</th>
                            <th>User Group</th>
                            <th>Maker Action</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in user_delete_data track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.eblSkyId}}</td>
                            <td>{{i.userGroupName}}</td>
                            <td>{{i.makerAction}}</td>
                            <td>                                
                                <span data-ng-class="{'text-success': i.mcStatus == '1', 'text-warning': i.isActive == '0'}">{{i.isActive=='1' ? 'Active' : 'Inactive'}}</span>
                            </td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?= base_url() ?>apps_user_delete_checker/getAppsUserForApproval/{{i.skyId}}">
                                                <i class="glyphicon glyphicon-pencil"></i> Approve
                                            </a>
                                        </li> 

                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="user_delete_data.length <= 0">
                            <td colspan="10">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>


<?php
ci_add_js(asset_url() . 'app/checker/user_delete_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.user_delete_data = <?= $deleteUser ?>;
</script>















<!--
<title>Apps User Delete</title>
<div class="breadcrum">Apps Users Delete Approve/Reject</div>

<div class="container" style="margin-top:50px">

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
           {  mDataProp:'skyId' },
           {  mDataProp:'eblSkyId' },
           {  mDataProp:'userGroupName' },
           {  mDataProp:'makerAction' },
           {  mDataProp:'mcStatus' },
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
        <tr>
            <th hidden style="text-align:center" >SKY ID</th>
            <th style="text-align:center" >ESB ID</th>
            <th style="text-align:center" >User Group</th>
            <th style="text-align:center" >Maker Action</th>
            <th style="text-align:center" >Status</th>
            <th style="text-align: center" >Action</th>
        </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden data-bind="text:skyId"></td>
        <td data-bind="text:eblSkyId"></td>
        <td style="text-align:center" data-bind="text:userGroupName"></td>
        <td style="text-align:center" data-bind="text:makerAction"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
        <td style="text-align:center"><button data-bind="click: $root.approveOrReject" class="btn btn-warning">Approve/Reject</button></td>
    </script>
</div>


<script type="text/javascript" charset="utf-8">
    var initialData = <?= $deleteUser ?>; //data for building initial table
    var vm = function() {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function(i, record) {   //build the checkboxes as observable

            if(record.mcStatus === "1"){
                record.status = "Approved";
                record.statusColor = ko.observable("green");
            }else if(record.mcStatus === "0"){
                record.status = "Wait for approve";
                record.statusColor = ko.observable("red");
            }else if(record.mcStatus === "2"){
                record.status = "Rejected";
                record.statusColor = ko.observable("red");
            }

        });
        self.approveOrReject = function(item){
            window.location = "<?php echo base_url(); ?>apps_user_delete_checker/getAppsUserForApproval/" + item.skyId;
        }
    };
    ko.applyBindings(new vm());

</script>
-->