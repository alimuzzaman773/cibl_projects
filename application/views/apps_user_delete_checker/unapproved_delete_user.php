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