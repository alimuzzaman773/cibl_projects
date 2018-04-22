<title>Limit Package</title>
<div class="breadcrum">Transaction Limit Package</div>
<div class="container" style="margin-top:50px">


    <table width="300" border="0" cellpadding="5">         
        <tr>
            <th align="left" scope=""><font color="green">Select Action</font></th>
            <td>
                <select id="actionSelect" name="actionSelect" data-bind="event: {change: $root.actionFunction}">
                    <option value="all">Select Action</option>
                </select>
            </td>
        </tr>   
    </table>



    <br>
    <br>
    <button style="display: none;" id="active" data-bind="click :$root.active" class="btn btn-success">Active</button>
    <button style="display: none;" id="inactive" data-bind="click :$root.inactive" class="btn btn-success">Inactive</button>
    <button style="display: none;" id="addPackage" data-bind="click :$root.addPackage" class="btn btn-success">Add Package</button>
    <br>

    <div id="showGroups" data-bind="visible: records().length > 0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th hidden style="text-align: center" >ID</th>
                    <th style="text-align: center" >Action</th>
                    <th style="text-align:left">
                        <!--                        
                        <button style="text-align:left" id="selectAll" data-bind="click :$root.selectAll" class="btn btn-primary">Select All</button>
                        <button style="text-align:left" id="deselectAll" data-bind="click :$root.deselectAll, visible: false" class="btn btn-primary">Deselect All</button>
                        -->

                    </th>
                    <th style="text-align: center" >Group Name</th>
                    <th style="text-align: center" >Own Account Transfer</th>
                    <th style="text-align: center" >EBL Account Transfer</th>
                    <th style="text-align: center" >Other Bank Transfer</th>
                    <th style="text-align: center" >Bills Pay</th>
                    <th style="text-align: center" >Active/Inactive</th>
                    <th style="text-align: center" >Status</th>
                </tr>
            </thead>
            <tbody data-bind="foreach: records">
                <tr>
                    <td hidden style="text-align:left" data-bind="text:appsGroupId"></td>
                    <td style="text-align:center"><button data-bind="click: $root.editPackage, visible: edit" class="btn btn-warning">Edit</button></td>
                    <td style="text-align:left"><input type="checkbox" data-bind="checked: isProcessed, visible: check"></td>
                    <td style="text-align:center" data-bind="text:userGroupName"></td>
                    <td style="text-align:center"><input type="checkbox" onclick="return false" data-bind="checked: ownChecked"></td>
                    <td style="text-align:center"><input type="checkbox" onclick="return false" data-bind="checked: eblChecked"></td>
                    <td style="text-align:center"><input type="checkbox" onclick="return false" data-bind="checked: otherChecked"></td>
                    <td style="text-align:center"><input type="checkbox" onclick="return false" data-bind="checked: billsPayChecked"></td>
                    <td style="text-align:center" data-bind="text:active, style:{color: activeColor}"></td>
                    <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>

                </tr>
            </tbody>
        </table>    
    </div>

    <div class="well" data-bind="visible: records().length == 0">
        <b>No Package Created Yet</b>
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
    var vm = function () {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function (i, record) {  //build the checkboxes checked/unchecked
            record.isProcessed = ko.observable(false);
            record.edit = ko.observable(false);
            record.check = ko.observable(false);

            record.appGroupId = record.appsGroupId;
            record.userGroupName = record.userGroupName;


            if (record.isActive === "1") {
                record.active = "Active";
                record.activeColor = ko.observable("green");
            } else if (record.isActive === "0") {
                record.active = "Inactive";
                record.activeColor = ko.observable("red");
            }

            if (record.oatMinTxnLim > 0) {
                record.ownChecked = ko.observable(true);
            } else if (record.oatMinTxnLim == 0) {
                record.ownChecked = ko.observable(false);
            }


            if (record.eatMinTxnLim > 0) {
                record.eblChecked = ko.observable(true);
            } else if (record.eatMinTxnLim == 0) {
                record.eblChecked = ko.observable(false);
            }


            if (record.obtMinTxnLim > 0) {
                record.otherChecked = ko.observable(true);
            } else if (record.obtMinTxnLim == 0) {
                record.otherChecked = ko.observable(false);
            }


            if (record.pbMinTxnLim > 0) {
                record.billsPayChecked = ko.observable(true);
            } else if (record.pbMinTxnLim == 0) {
                record.billsPayChecked = ko.observable(false);
            }


            if (record.mcStatus === "1") {
                record.status = "Approved";
                record.statusColor = ko.observable("green");
            } else if (record.mcStatus === "0") {
                record.status = "Wait for approve";
                record.statusColor = ko.observable("red");
            } else if (record.mcStatus === "2") {
                record.status = "Rejected";
                record.statusColor = ko.observable("red");
            }

        })


        self.selectAll = function () {
            document.getElementById("selectAll").style.display = "none";
            document.getElementById("deselectAll").style.display = "block";
            $.each(self.records(), function (i, record) {
                record.isProcessed(true);
            })
        }

        self.deselectAll = function () {
            document.getElementById("selectAll").style.display = "block";
            document.getElementById("deselectAll").style.display = "none";
            $.each(self.records(), function (i, record) {
                record.isProcessed(false);
            })
        }



        self.actionFunction = function (){

            var action = document.getElementById("actionSelect").value;
            if(action === "add"){
                document.getElementById("addPackage").style.display = "block";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
            }

            $.each(self.records(), function(i, record) { 
              action = document.getElementById("actionSelect").value;

              switch(action){

                case "edit":
                record.edit(true);
                document.getElementById("addPackage").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                break;

                case "active":
                record.check(true);
                document.getElementById("addPackage").style.display = "none";
                document.getElementById("active").style.display = "block";
                document.getElementById("inactive").style.display = "none";
                break;

                case "inactive":
                record.check(true);
                document.getElementById("addPackage").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "block";
                break;

              }

            })
        }

        self.addPackage = function(item){
            window.location = "<?php echo base_url(); ?>transaction_limit_setup_maker/createGroup/" + $("#actionSelect option:selected").text();
        }

        self.editPackage = function(item){
            window.location = "<?php echo base_url(); ?>transaction_limit_setup_maker/editTransactionLimitPackage/" + item.appsGroupId  + "/" + $("#actionSelect option:selected").text();
        }


        self.active = function(){
            var apps_group_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    apps_group_id = apps_group_id+"|"+record.appsGroupId;
                }
            })
            if(apps_group_id == ""){
                alert("Error: No Package Is Selected");
                return false;
            }
            apps_group_id = apps_group_id.substring(1);
            var dataToSave = {"appsGroupId" : apps_group_id, "selectedActionName" : selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>transaction_limit_setup_maker/packageActive",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected packages are active");
                        window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                    }
                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }

        self.inactive = function(){
            var apps_group_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    apps_group_id = apps_group_id+"|"+record.appsGroupId;
                }
            })
            if(apps_group_id == ""){
                alert("Error: No Package Is Selected");
                return false;
            }
            apps_group_id = apps_group_id.substring(1);
            var dataToSave = {"appsGroupId" : apps_group_id, "selectedActionName" : selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>transaction_limit_setup_maker/packageInactive",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected packages are inactive");
                        window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                    }
                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }





    }
    ko.applyBindings(new vm());
    
</script>



<script type="text/javascript" charset="utf-8">
    var actionCodes = <?php echo $actionCodes ?>;
    var actionNames = <?php echo $actionNames ?>;
    
    var sel = document.getElementById('actionSelect');
    for(var i = 0; i < actionCodes.length; i++) {
        var opt = document.createElement('option');
        opt.innerHTML = actionNames[i];
        opt.value = actionCodes[i];
        sel.appendChild(opt);
    }
</script>