<title>Admin User</title>
<div class="breadcrum">Admin Users</div>

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
    <button style="display: none;" id="addAdminUser" data-bind="click :$root.addAdminUser" class="btn btn-success">Add Admin User</button>

<br>

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
           {  mDataProp:'fullName' },
           {  mDataProp:'adminUserName' },
           {  mDataProp:'userGroupName' },
           {  mDataProp:'bankName' },
           {  mDataProp:'email' },
           {  mDataProp:'isLocked' },
           {  mDataProp:'isActive' },
           {  mDataProp: 'status' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
            <tr>
                <th hidden style="text-align: center" >ID</th>
                <th style="text-align: center" >Action</th>
                <th style="text-align:center">
<!--                    
<button style="text-align:center" id="selectAll" data-bind="click :$root.selectAll" class="btn btn-primary">Select All</button>
<button style="text-align:center" id="deselectAll" data-bind="click :$root.deselectAll, visible: false" class="btn btn-primary">Deselect All</button>
-->
                </th>
                <th style="text-align: center" >Full Name</th>
                <th style="text-align: center" >Admin User ID</th>
                <th style="text-align: center" >Admin User Group</th>
                <th style="text-align: center" >Bank Name</th>
                <th style="text-align: center" >Email</th>
                <th hidden style="text-align: center" >Maker Action By</th>
                <th style="text-align: center" >Lock/Unlock</th>
                <th style="text-align: center" >Active/Inactive</th>
                <th style="text-align: center" >Status</th>
               
            </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden style="text-align:center" data-bind="text:adminUserId"></td>
        <td style="text-align:center"><button data-bind="click: $root.editAdminUser, visible: edit" class="btn btn-warning">Edit</button></td>
        <td style="text-align:center"><input type="checkbox" data-bind="checked: isProcessed, visible: check"></td>  
        <td style="text-align:center" data-bind="text:fullName"></td>
        <td style="text-align:center" data-bind="text:adminUserName"></td>
        <td style="text-align:center" data-bind="text:userGroupName"></td>
        <td style="text-align:center" data-bind="text:bankName"></td>
        <td style="text-align:center" data-bind="text:email"></td>
        <td hidden style="text-align:center" data-bind="text:makerActionBy"></td>
        <td style="text-align:center" data-bind="text:lock, style:{color: lockColor}"></td>
        <td style="text-align:center" data-bind="text:active, style:{color: activeColor}"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
         
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

            record.isProcessed = ko.observable(false);
            record.edit = ko.observable(false);
            record.check = ko.observable(false);

            record.adminUserId = record.adminUserId;
            record.adminUserName = record.adminUserName;
            record.userGroupName = record.userGroupName;
            record.bankName = record.bankName;
            record.email = record.email;
            record.dob = record.dob;


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
                document.getElementById("addAdminUser").style.display = "block";
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
                document.getElementById("addAdminUser").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
                break;


                case "active":
                record.check(true);
                document.getElementById("addAdminUser").style.display = "none";
                document.getElementById("active").style.display = "block";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
                break;


                case "inactive":
                record.check(true);
                document.getElementById("addAdminUser").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "block";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
                break;

                case "lock":
                record.check(true);
                document.getElementById("addAdminUser").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "block";
                document.getElementById("unlock").style.display = "none";
                break;

                case "unlock":
                record.check(true);
                document.getElementById("addAdminUser").style.display = "none";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "block";
                break;
              }
            })
        }


        self.addAdminUser = function(item){
            window.location = "<?php echo base_url(); ?>admin_users_maker/addNewUser/" + $("#actionSelect option:selected").text();
        }

        self.editAdminUser = function(item){
            window.location = "<?php echo base_url(); ?>admin_users_maker/editUser/" + item.adminUserId  + "/" + $("#actionSelect option:selected").text();
        }


        self.active = function(){

            var admin_user_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    admin_user_id = admin_user_id+"|"+record.adminUserId;
                }
            })

            if(admin_user_id == ""){
                alert("Error: No User Is Selected");
                return false;
            }

            admin_user_id = admin_user_id.substring(1);
            var dataToSave = {"adminUserId" : admin_user_id, "selectedActionName" : selected_action_name};

            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>admin_users_maker/adminUserActive",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected users are active.");
                        window.location = "<?= base_url() ?>admin_users_maker";
                    }

                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = "<?= base_url() ?>admin_users_maker";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }


        self.inactive = function(){

            var admin_user_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    admin_user_id = admin_user_id+"|"+record.adminUserId;
                }
            })

            if(admin_user_id == ""){
                alert("Error: No User Is Selected");
                return false;
            }

            admin_user_id = admin_user_id.substring(1);
            var dataToSave = {"adminUserId" : admin_user_id, "selectedActionName" : selected_action_name};

            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>admin_users_maker/adminUserInactive",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected users are inactive.");
                        window.location = "<?= base_url() ?>admin_users_maker";
                    }

                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = "<?= base_url() ?>admin_users_maker";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }



        self.lock = function(){

            var admin_user_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    admin_user_id = admin_user_id+"|"+record.adminUserId;
                }
            })

            if(admin_user_id == ""){
                alert("Error: No User Is Selected");
                return false;
            }

            admin_user_id = admin_user_id.substring(1);
            var dataToSave = {"adminUserId" : admin_user_id, "selectedActionName" : selected_action_name};

            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>admin_users_maker/adminUserLock",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected users are locked.");
                        window.location = "<?= base_url() ?>admin_users_maker";
                    }

                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = "<?= base_url() ?>admin_users_maker";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }


        self.unlock = function(){

            var admin_user_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    admin_user_id = admin_user_id+"|"+record.adminUserId;
                }
            })

            if(admin_user_id == ""){
                alert("Error: No User Is Selected");
                return false;
            }

            admin_user_id = admin_user_id.substring(1);
            var dataToSave = {"adminUserId" : admin_user_id, "selectedActionName" : selected_action_name};

            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>admin_users_maker/adminUserUnlock",
                success: function(data) {
                    if (data == 1) {
                        alert("Selected users are unlocked.");
                        window.location = "<?= base_url() ?>admin_users_maker";
                    }

                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = "<?= base_url() ?>admin_users_maker";
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