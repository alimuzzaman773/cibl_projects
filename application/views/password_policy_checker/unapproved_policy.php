<title>Password Policy</title>
<div class="breadcrum">Password Policy Approve/Reject</div>

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

