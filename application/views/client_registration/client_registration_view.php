<title>Apps User</title>
<div class="breadcrum">Apps Users</div>

<div class="container" style="margin-top:40px">


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

    <br><br>

    <button style="display: none;" id="addUser" data-bind="click :$root.addUser" class="btn btn-success">Add User</button>
    <button style="display: none;" id="active" data-bind="click :$root.active" class="btn btn-success">Active</button>
    <button style="display: none;" id="inactive" data-bind="click :$root.inactive" class="btn btn-success">Inactive
    </button>
    <button style="display: none;" id="lock" data-bind="click :$root.lock" class="btn btn-success">Lock</button>
    <button style="display: none;" id="unlock" data-bind="click :$root.unlock" class="btn btn-success">Unlock</button>
    <br/>
    <br/>

    <table data-bind="dataTable: { dataSource : records, rowTemplate: 'rowTemplate',
           options: {
           bAutoWidth : false,
           aoColumnDefs: [
           { bSortable: false, aTargets: [4] },
           { bSearchable: false, aTargets: [4] }
           ],
           aaSorting: [],
           aLengthMenu: [[50, 100, 150, -1], [50, 100, 150, 'All']],
           iDisplayLength: 50,
           aoColumns: [
           {  mDataProp:'skyId' },
           {  mDataProp:'eblSkyId' },
           {  mDataProp:'cfId' },
           {  mDataProp:'clientId' },
           {  mDataProp:'isLocked' },
           {  mDataProp:'isActive' },
           {  mDataProp:'status' },
           {  mDataProp:'userName' },
           {  mDataProp:'userGroupName' },
           {  mDataProp:'userEmail' },
           {  mDataProp:'dob' },
           {  mDataProp:'gender' }           
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
        <tr>

            <th style="width:10%; text-align:center" colspan="4">Actions</th>
            <th style="text-align:center">
                <!--
                <button style="text-align:center" id="selectAll" data-bind="click :$root.selectAll" class="btn btn-primary">Select All</button>
                <button style="text-align:center" id="deselectAll" data-bind="click :$root.deselectAll, visible: false" class="btn btn-primary">Deselect All</button>
                -->
            </th>

            <th hidden style="text-align:center">SKY ID</th>
            <th style="text-align:center">ESB ID</th>
            <th style="text-align:center">CFID</th>
            <th style="text-align:center">Client ID</th>
            <th style="text-align:center">Lock/Unlock</th>
            <th style="text-align:center">Active/Inactive</th>
            <th style="text-align:center">Status</th>
            <!--<th style="text-align:center" colspan="3">Device Count</th>-->
            <th style="text-align:center">User Name</th>
            <th style="text-align:center">User Group</th>
            <th style="text-align:center">Date of Birth</th>
            <th style="text-align:center">Email</th>
            <th style="text-align:center">Gender</th>

        </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">

        <td style="text-align:center">
            <button data-bind="click: $root.viewUser, visible: detailView" class="btn btn-warning">Detail View</button>
        </td>
        <td style="text-align:center">
            <button data-bind="click: $root.editUser, visible: edit" class="btn btn-warning">Edit</button>
        </td>
        <td style="text-align:center">
            <button data-bind="click: $root.deletedUser, visible: deleted" class="btn btn-danger">Delete</button>
        </td>
        <td style="text-align:center">
            <button data-bind="click: $root.addDevice, visible: device" class="btn btn-warning">Device</button>
        </td>
        <td style="text-align:center"><input type="checkbox" data-bind="checked: isProcessed, visible: check"></td>


        <td hidden data-bind="text:skyId"></td>
        <td data-bind="text:eblSkyId"></td>
        <td data-bind="text:cfId"></td>
        <td data-bind="text:clientId"></td>

        <td style="text-align:center" data-bind="text:lock, style:{color: lockColor}"></td>
        <td style="text-align:center" data-bind="text:active, style:{color: activeColor}"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
	<!--
        <td data-bind="text:totalDevice"></td>
        <td data-bind="text:verifiedDevice"></td>
        <td data-bind="text:nonVerifiedDevice"></td>
	-->
        <td style="text-align:center" data-bind="text:userName"></td>
        <td style="text-align:center" data-bind="text:userGroupName"></td>
        <td style="text-align:center" data-bind="text:dob"></td>
        <td style="text-align:center" data-bind="text:userEmail"></td>
        <td style="text-align:center" data-bind="text:gender"></td>


    </script>

</div>


<script type="text/javascript" charset="utf-8">
    var initialData = <?= $appsUsers ?>; //data for building initial table
    
    var vm = function () {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function (i, record) {   //build the checkboxes as observable

            record.isProcessed = ko.observable(false);
            record.device = ko.observable(false);
            record.edit = ko.observable(false);
            record.check = ko.observable(false);
            record.detailView = ko.observable(false);
            record.deleted = ko.observable(false);
	    
            /*
            record.verifiedDevice = "Verified Device: 0";
            record.nonVerifiedDevice = "Nonverified Device: 0";
            record.totalDevice = "Total Device: 0";
            */

            /*
            for (var v = 0; v < verifiedDevice.length; v++) {

                if (record.skyId === verifiedDevice[v].skyId) {

                    record.verifiedDevice = "Verified Device: " + verifiedDevice[v].deviceCount;
                }
            }


            for (var nv = 0; nv < nonVerifiedDevice.length; nv++) {

                if (record.skyId === nonVerifiedDevice[nv].skyId) {

                    record.nonVerifiedDevice = "Non Verified Device: " + nonVerifiedDevice[nv].deviceCount;
                }
            }

            for (var t = 0; t < totalDevice.length; t++) {

                if (record.skyId === totalDevice[t].skyId) {

                    record.totalDevice = "Total Device: " + totalDevice[t].deviceCount;
                }
            }
            */

            if (record.isActive == 1) {
                record.active = "Active";
                record.activeColor = ko.observable("green");
            }
            else if (record.isActive == 0) {
                record.active = "Inactive";
                record.activeColor = ko.observable("red");
            }

            if (record.isLocked == 1) {
                record.lock = "Locked";
                record.lockColor = ko.observable("red");
            }
            else if (record.isLocked == 0) {
                record.lock = "Unlocked";
                record.lockColor = ko.observable("green");
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
            /*
            if (record.verifiedDevice > 0) {
                record.btnDeleteUser = ko.observable(false);
            } else {
                record.btnDeleteUser = ko.observable(true);
            }
            */

        });


        self.selectAll = function () {
            document.getElementById("selectAll").style.display = "none";
            document.getElementById("deselectAll").style.display = "block";
            $.each(self.records(), function (i, record) {
                record.isProcessed(true);
            })
        };


        self.deselectAll = function () {
            document.getElementById("selectAll").style.display = "block";
            document.getElementById("deselectAll").style.display = "none";
            $.each(self.records(), function (i, record) {
                record.isProcessed(false);
            })
        };


        self.actionFunction = function () {

            var action = document.getElementById("actionSelect").value;
            if (action === "add") {
                document.getElementById("addUser").style.display = "block";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
            }

            $.each(self.records(), function (i, record) {
                action = document.getElementById("actionSelect").value;

                switch (action) {

                    case "detailView":
                        record.detailView(true);
                        document.getElementById("addUser").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;


                    case "addDevice":
                        record.device(true);
                        document.getElementById("addUser").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "edit":
                        record.edit(true);
                        document.getElementById("addUser").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "delete":
                        record.deleted(true);
                        document.getElementById("addUser").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "active":
                        record.check(true);
                        document.getElementById("addUser").style.display = "none";
                        document.getElementById("active").style.display = "block";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "inactive":
                        record.check(true);
                        document.getElementById("addUser").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "block";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "lock":
                        record.check(true);
                        document.getElementById("addUser").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "block";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "unlock":
                        record.check(true);
                        document.getElementById("addUser").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "block";
                        break;

                }
            })
        };


        self.active = function () {

            var esb_id = "";
            var sky_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    esb_id = esb_id + "|" + record.eblSkyId;
                    sky_id = sky_id + "|" + record.skyId;
                }
            });

            if (esb_id == "") {
                alert("Error: No user Is Selected");
                return false;
            }

            esb_id = esb_id.substring(1);
            sky_id = sky_id.substring(1);
            var dataToSave = {"eblSkyId": esb_id, "skyId": sky_id, "selectedActionName": selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>client_registration/userActive",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected users are active");
                        window.location = "<?= base_url() ?>client_registration";
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };

        self.inactive = function () {

            var esb_id = "";
            var sky_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    esb_id = esb_id + "|" + record.eblSkyId;
                    sky_id = sky_id + "|" + record.skyId;
                }
            });

            if (esb_id == "") {
                alert("Error: No user Is Selected");
                return false;
            }

            esb_id = esb_id.substring(1);
            sky_id = sky_id.substring(1);
            var dataToSave = {"eblSkyId": esb_id, "skyId": sky_id, "selectedActionName": selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>client_registration/userInactive",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected Users are inactive");
                        window.location = "<?= base_url() ?>client_registration";
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };


        self.lock = function () {

            var esb_id = "";
            var sky_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    esb_id = esb_id + "|" + record.eblSkyId;
                    sky_id = sky_id + "|" + record.skyId;
                }
            });

            if (esb_id == "") {
                alert("Error: No user Is Selected");
                return false;
            }

            esb_id = esb_id.substring(1);
            sky_id = sky_id.substring(1);
            var dataToSave = {"eblSkyId": esb_id, "skyId": sky_id, "selectedActionName": selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>client_registration/userLock",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected Users are Locked");
                        window.location = "<?= base_url() ?>client_registration";
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });

        };


        self.unlock = function () {

            var esb_id = "";
            var sky_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    esb_id = esb_id + "|" + record.eblSkyId;
                    sky_id = sky_id + "|" + record.skyId;
                }
            });

            if (esb_id == "") {
                alert("Error: No user Is Selected");
                return false;
            }

            esb_id = esb_id.substring(1);
            sky_id = sky_id.substring(1);
            var dataToSave = {"eblSkyId": esb_id, "skyId": sky_id, "selectedActionName": selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>client_registration/userUnlock",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected Users are Unlocked");
                        window.location = "<?= base_url() ?>client_registration";
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };


        self.viewUser = function (item) {
            window.location = "<?php echo base_url(); ?>client_registration/viewUser?skyId=" + item.skyId;
        };


        self.addUser = function (item) {
            window.location = "<?php echo base_url(); ?>apps_users/addAppsUser/" + $("#actionSelect option:selected").text();
        };


        self.editUser = function (item) {
            window.location = "<?php echo base_url(); ?>apps_users/editAppsUser?eblSkyId=" + item.eblSkyId + "&cfId=" + item.cfId + "&clientId=" + item.clientId + "&skyId=" + item.skyId + "&selectedActionName=" + $("#actionSelect option:selected").text();
        };

        self.addDevice = function (item) {
            window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + item.skyId + "&eblSkyId=" + item.eblSkyId;
        };

        self.deletedUser = function (item) {
	
           var r = confirm("Are you sure to delete this user?");
            if (r == true) {
                $.ajax({
                    type: "POST",
                    data: {"skyId": item.skyId},
                    url: "<?= base_url() ?>client_registration/userDelete",
                    success: function (res) {

                        if (res == 1) {
                            alert("User can't delete because of user device already verified.");
                           
                            
                        } else {
                            window.location = "<?= base_url() ?>client_registration";
                        }
                    },
                    error: function (error) {
                        alert(error.status + "<--and--> " + error.statusText);
                    }
                });
            } else {
                return false;
            }
        };

    };
    ko.applyBindings(new vm());


</script>


<script type="text/javascript" charset="utf-8">

    var actionCodes = <?php echo $actionCodes ?>;
    var actionNames = <?php echo $actionNames ?>;

    var sel = document.getElementById('actionSelect');
    for (var i = 0; i < actionCodes.length; i++) {
        var opt = document.createElement('option');
        opt.innerHTML = actionNames[i];
        opt.value = actionCodes[i];
        sel.appendChild(opt);
    }
</script>



