<title>Limit Package</title>
<div class="breadcrum">Limit Package Approve/Reject</div>
<div class="container" style="margin-top:50px">



<br><br>
    <div id="showGroups" data-bind="visible: records().length > 0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th hidden style="text-align: center" >ID</th>
                    <th style="text-align: center" >Group Name</th>
                    <th style="text-align: center" >Maker Action</th>
                    <th style="text-align: center" >Status</th>
                    <th style="text-align: center" >Action</th>
                </tr>
            </thead>
            <tbody data-bind="foreach: records">
                <tr>
                    <td hidden style="text-align:left" data-bind="text:appsGroupId"></td>
                    <td style="text-align:center" data-bind="text:userGroupName"></td>
                    <td style="text-align:center" data-bind="text:makerAction"></td>
                    <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
                    <td style="text-align:center"><button data-bind="click: $root.approveOrReject" class="btn btn-warning">Approve/Reject</button></td>
                </tr>
            </tbody>
        </table>    
    </div>

    <div class="well" data-bind="visible: records().length == 0">
        <b>No Package For Approval/Reject</b>
    </div>


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
    var initialData = <?= $packages ?>; //data for building initial table
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
            window.location = "<?php echo base_url(); ?>transaction_limit_setup_checker/getPackageForApproval/" + item.appsGroupId;
        }


    }
    ko.applyBindings(new vm());
    
</script>

