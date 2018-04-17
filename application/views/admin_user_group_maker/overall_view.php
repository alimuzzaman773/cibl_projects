<title>Admin User Group</title>
<div class="breadcrum">Admin User Group</div>

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
    <button style="display: none;" id="lock" data-bind="click :$root.lock" class="btn btn-success">Lock</button>
    <button style="display: none;" id="unlock" data-bind="click :$root.unlock" class="btn btn-success">Unlock</button>
    <button style="display: none;" id="addGroup" data-bind="click :$root.addGroup" class="btn btn-success">Add Group</button>
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
                    <th style="text-align: center" >Admin User Group</th>
                    <th style="text-align: center" >Lock/Unlock</th>
                    <th style="text-align: center" >Active/Inactive</th>
                    <th style="text-align: center" >Status</th>                   
                </tr>
            </thead>
            <tbody data-bind="foreach: records">
                <tr>
                    <td hidden style="text-align:center" data-bind="text:userGroupId"></td>
                    <td style="text-align:center"><button data-bind="click: $root.editGroup, visible: edit" class="btn btn-warning">Edit</button></td>
                    <td style="text-align:left"><input type="checkbox" data-bind="checked: isProcessed, visible: check"></td>
                    <td style="text-align:center" data-bind="text:userGroupName"></td>
                    <td style="text-align:center" data-bind="text:lock, style:{color: lockColor}"></td>
                    <td style="text-align:center" data-bind="text:active, style:{color: activeColor}"></td>
                    <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>

                </tr>
            </tbody>
        </table>    
    </div>

    <div class="well" data-bind="visible: records().length == 0">
        <b>No Group Created Yet</b>
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
    var initialData = <?= $groups ?>;
    var vm = function() {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function(i, record) {  
            record.isProcessed = ko.observable(false);
            record.edit = ko.observable(false);
            record.check = ko.observable(false);


            record.userGroupId = record.userGroupId;
            record.userGroupName = record.userGroupName;

            if(record.isLocked === "1"){
                record.lock = "Locked";
                record.lockColor = ko.observable("red");
            }else if(record.isLocked === "0"){
                record.lock = "Unlocked";
                record.lockColor = ko.observable("green");
            }

            if(record.isActive === "1"){
                record.active = "Active";
                record.activeColor = ko.observable("green");
            }else if(record.isActive === "0"){
                record.active = "Inactive";
                record.activeColor = ko.observable("red");
            }


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



        self.selectAll = function(){
            document.getElementById("selectAll").style.display = "none";
            document.getElementById("deselectAll").style.display = "block";
            $.each(self.records(), function(i, record){
                record.isProcessed(true);
            })
        }


        self.deselectAll = function(){
            document.getElementById("selectAll").style.display = "block";
            document.getElementById("deselectAll").style.display = "none";
            $.each(self.records(), function(i, record){
                record.isProcessed(false);
            })
        }


        self.actionFunction = function(){

            var action = document.getElementById("actionSelect").value;
            if(action === "add"){
                document.getElementById("addGroup").style.display = "block";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
            }

            $.each(self.records(), function(i, record) { 
              action = document.getElementById("actionSelect").value;

              switch(action){

                case "edit":
                record.edit(true);
                document.getElementById("addGroup").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
                break;

                case "active":
                record.check(true);
                document.getElementById("addGroup").style.display = "none";
                document.getElementById("active").style.display = "block";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
                break;

                case "inactive":
                record.check(true);
                document.getElementById("addGroup").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "block";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
                break;

                case "lock":
                record.check(true);
                document.getElementById("addGroup").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "block";
                document.getElementById("unlock").style.display = "none";
                break;

                case "unlock":
                record.check(true);
                document.getElementById("addGroup").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "block";
                break;
              } 
            })
        }


        self.addGroup = function(item){
            window.location = "<?php echo base_url(); ?>admin_user_group_maker/selectModule/" + $("#actionSelect option:selected").text();
        }


        self.editGroup = function(item){
            window.location = "<?php echo base_url(); ?>admin_user_group_maker/editModule/" + item.userGroupId + "/" + $("#actionSelect option:selected").text();
        }


        self.active = function(){
            var user_group_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    user_group_id = user_group_id+"|"+record.userGroupId;
                }
            })
            if(user_group_id == ""){
                alert("Error: No Group Is Selected");
                return false;
            }
            user_group_id = user_group_id.substring(1);
            var dataToSave = {"userGroupId" : user_group_id, "selectedActionName" : selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>admin_user_group_maker/groupActive",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected groups are active");
                        window.location = "<?= base_url() ?>admin_user_group_maker";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }


        self.inactive = function(){
            var user_group_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    user_group_id = user_group_id+"|"+record.userGroupId;
                }
            })
            if(user_group_id == ""){
                alert("Error: No Group Is Selected");
                return false;
            }
            user_group_id = user_group_id.substring(1);
            var dataToSave = {"userGroupId" : user_group_id, "selectedActionName" : selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>admin_user_group_maker/groupInactive",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected groups are inactive");
                        window.location = "<?= base_url() ?>admin_user_group_maker";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }



        self.lock = function(){
            var user_group_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    user_group_id = user_group_id+"|"+record.userGroupId;
                }
            })
            if(user_group_id == ""){
                alert("Error: No Group Is Selected");
                return false;
            }
            user_group_id = user_group_id.substring(1);
            var dataToSave = {"userGroupId" : user_group_id, "selectedActionName" : selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>admin_user_group_maker/groupLock",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected groups are Locked");
                        window.location = "<?= base_url() ?>admin_user_group_maker";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }



        self.unlock = function(){
            var user_group_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    user_group_id = user_group_id+"|"+record.userGroupId;
                }
            })
            if(user_group_id == ""){
                alert("Error: No Group Is Selected");
                return false;
            }
            user_group_id = user_group_id.substring(1);
            var dataToSave = {"userGroupId" : user_group_id, "selectedActionName" : selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>admin_user_group_maker/groupUnlock",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected groups are Unlocked");
                        window.location = "<?= base_url() ?>admin_user_group_maker";
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