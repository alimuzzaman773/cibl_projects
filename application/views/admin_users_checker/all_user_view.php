<title>Admin User</title>
<div class="breadcrum">Admin Users</div>

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
           {  mDataProp:'adminUserId' },
           {  mDataProp:'adminUserName' },
           {  mDataProp:'userGroupName' },
           {  mDataProp:'makerAction' },
           {  mDataProp:'status' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
            <tr>
                <th hidden style="text-align: center" >ID</th>
                <th style="text-align: center" >Admin User Name</th>
                <th style="text-align: center" >Admin User Group</th>
                <th style="text-align: center" >Maker Action</th>
                <th style="text-align: center" >Status</th>
                <th style="text-align: center" >Action</th>
            </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden style="text-align:center" data-bind="text:adminUserId"></td>
        <td style="text-align:center" data-bind="text:adminUserName"></td>
        <td style="text-align:center" data-bind="text:userGroupName"></td>
        <td style="text-align:center" data-bind="text:makerAction"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
        <td style="text-align:center"><button data-bind="click: $root.approveOrReject" class="btn btn-warning">Approve/Reject</button></td>
    </script>


</div>



<style>
input {
    float:center;
    border: 1px solid #848484; 
    -webkit-border-radius: 30px; 
    -moz-border-radius: 30px; 
    border-radius: 30px; 
    outline:0; 
    height:25px; 
    width: 100px; 
    padding-left:10px; 
    padding-right:10px; 
}
</style>



<script type="text/javascript" charset="utf-8">

  var initialData = <?= $adminUsers ?>;//data for building initial table
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
    })

    self.approveOrReject = function(item){
        window.location = "<?php echo base_url(); ?>admin_users_checker/getUserForApproval/" + item.adminUserId;
    }
  }
  ko.applyBindings(new vm());
    
</script>

