<title>Pin Request</title>
<div class="breadcrum">Pin Request</div>

<div class="container" style="margin-top:50px">


<br><br>

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
           {  mDataProp:'requestId' },
           {  mDataProp:'totalPin' },
           {  mDataProp:'makerAction' },
           {  mDataProp:'makerActionComment' },
           {  mDataProp:'fullName' },
           {  mDataProp:'makerActionDt' },
           {  mDataProp:'checkerActionDt' },
           {  mDataProp:'status' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
            <tr>
                <th hidden style="text-align: center" >Request ID</th>
                <th style="text-align: center" >Action</th>
                <th style="text-align: center" >Total Pin</th>
                <th style="text-align: center" >Maker Action</th>
                <th style="text-align: center" >Maker Remark</th>
                <th style="text-align: center" >Requested By</th>
                <th style="text-align: center" >Request Date</th>
                <th style="text-align: center" >Approve Date</th>
                <th style="text-align: center" >Status</th>
            </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden style="text-align:center" data-bind="text:requestId"></td>
        <td style="text-align:center"><button data-bind="click: $root.editRequest, visible: edit" class="btn btn-warning">Edit</button></td>
        <td style="text-align:center" data-bind="text:totalPin"></td>
        <td style="text-align:center" data-bind="text:makerAction"></td>
        <td style="text-align:center" data-bind="text:makerActionComment"></td>
        <td style="text-align:center" data-bind="text:fullName"></td>
        <td style="text-align:center" data-bind="text:makerActionDt"></td>
        <td style="text-align:center" data-bind="text:checkerActionDt"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
    </script>

  


    <table width="100" border="0" cellpadding="2">
        <tr>
            <td width="100"><button style="display: block;" id="newRequest" data-bind="click :$root.newRequest" class="btn btn-success">New Request</button></td>
            <td><a href="<?php echo base_url(); ?>pin_generation/viewPinByAction" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back</span></a> </td>
        </tr>
    </table>

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
    var initialData = <?= $pinRequest ?>;

    var vm = function() {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function(i, record) {  //build the checkboxes checked/unchecked
            

            if(record.mcStatus === "1"){
                record.edit = ko.observable(false);
                record.status = "Approved";
                record.statusColor = ko.observable("green");
            }else if(record.mcStatus === "0"){
                document.getElementById("newRequest").style.display = "none";
                record.edit = ko.observable(true);
                record.status = "Wait for approve";
                record.statusColor = ko.observable("red");
            }else if(record.mcStatus === "2"){
                document.getElementById("newRequest").style.display = "none";
                record.edit = ko.observable(true);
                record.status = "Rejected";
                record.statusColor = ko.observable("red");
            }
        })









        self.newRequest = function(item){
            window.location = "<?php echo base_url(); ?>pin_generation/newRequest/Create";
            //alert($("#selectedActionName").val());
        }

        self.editRequest = function(item){
            window.location = "<?php echo base_url(); ?>pin_generation/editRequest/" + item.requestId  + "/Create";
            //alert("edit request");
        }





    }
    ko.applyBindings(new vm());
    
</script>



