
<div class="container">
     <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                Password Policy
            </h3>
        </div>
    </div>
</div>

<div class="container" id="PasswordModule" data-ng-controller="PasswordController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="selectAll" /></th>
                            <th>SL#</th>                            
                            <th>Group Name</th>
                            <th>VG Code</th>
                            <th>Version Code</th>
                            <th>Wrong Attempts</th>
                            <th>Expiry Period</th>
                            <th>Status</th>
                            <th>Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in password_policy track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.validationGroupName}}</td>
                            <td>{{i.vgCode}}</td>
                            <td>{{i.vCodes}}</td>
                            <td>{{i.wrongAttempts}}</td>
                            <td>{{i.passExpiryPeriod}}</td>
                            <td>                                
                                <span data-ng-class="{'text-success': i.isActive=='1', 'text-warning': i.isActive=='0'}">{{i.isActive=='1' ? 'Active' : 'Inactive'}}</span>
                            </td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?= base_url() ?>password_policy_checker/getPasswordPolicyApproval/{{i.validationGroupId}}">
                                                <i class="glyphicon glyphicon-pencil"></i> Approve
                                            </a>
                                        </li> 
         
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="password_policy.length <= 0">
                            <td colspan="10">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>


<?php
ci_add_js(asset_url() . 'app/checker/password_policy_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.password_policy = <?= $passwordPolicy ?>;
</script>







<!--
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
           {  mDataProp:'validationGroupId' },
           {  mDataProp:'validationGroupName' },
           {  mDataProp:'vgCode' },
           {  mDataProp:'vCodes' },
           {  mDataProp:'wrongAttempts' },
           {  mDataProp:'passExpiryPeriod' },
           {  mDataProp:'status' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
        <tr>
            <th hidden style="text-align: center" >ID</th>
            <th style="text-align: center" >Group Name</th>
            <th style="text-align: center" >VG Code</th>
            <th style="text-align: center" >Version Code</th>
            <th style="text-align: center" >Wrong Attempts</th>
            <th style="text-align: center" >Expiry Period</th>
            <th style="text-align: center" >Status</th>
            <th style="text-align: center" >Action</th>
        </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden style="text-align:center" data-bind="text:validationGroupId"></td>
        <td style="text-align:center" data-bind="text:validationGroupName"></td>
        <td style="text-align:center" data-bind="text:vgCode"></td>
        <td style="text-align:center" data-bind="text:vCodes"></td>
        <td style="text-align:center" data-bind="text:wrongAttempts"></td>
        <td style="text-align:center" data-bind="text:passExpiryPeriod"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
        <td style="text-align:center"><button data-bind="click: $root.approveOrReject" class="btn btn-warning">Approve/Reject</button></td>
    </script>
</div>


<script type="text/javascript" charset="utf-8">

    var initialData = <?= $passwordPolicy ?>;
    var vm = function() {

        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function(i, record) {  //build the checkboxes checked/unchecked

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
            window.location = "<?php echo base_url(); ?>password_policy_checker/getPasswordPolicyApproval/" + item.validationGroupId;
        }
    };
    ko.applyBindings(new vm());

</script>
-->