<title>Device</title>
<div class="breadcrum">Device</div>

<div class="container" style="margin-top:50px">

    <input hidden type="text" name="skyId" id="skyId" value="<?= $skyId ?>" size="20"/>
    <input hidden type="text" name="eblSkyId" id="eblSkyId" value="<?= $eblSkyId ?>" size="20"/>

    <table width="300" border="0" cellpadding="5">
        <tr>
            <th align="left" scope="">Select Action</th>
            <td>
                <select id="actionSelect" name="actionSelect" data-bind="event: {change: $root.actionFunction}">
                    <option value="all">Select Action</option>

                </select>
            </td>
        </tr>
    </table>
    <br/>
     <table width="50" border="0" cellpadding="2">
        <tr>
            <td width="100">
                <button style="display: block;" id="back" data-bind="click :$root.back" class="btn btn-success">Back
                </button>
            </td>
            <td width="100">
                <button style="display: none;" id="addDevice" data-bind="click :$root.addDevice"
                        class="btn btn-success">Add Device
                </button>
            </td>
            <td width="100">
                <button style="display: none;" id="lock" data-bind="click :$root.lock" class="btn btn-success">Lock
                </button>
            </td>
            <td width="100">
                <button style="display: none;" id="unlock" data-bind="click :$root.unlock" class="btn btn-success">
                    Unlock
                </button>
            </td>
            <td width="100">
                <button style="display: none;" id="active" data-bind="click :$root.active" class="btn btn-success">
                    Active
                </button>
            </td>
            <td width="100">
                <button style="display: none;" id="inactive" data-bind="click :$root.inactive" class="btn btn-success">
                    Inactive
                </button>
            </td>
        </tr>
    </table>
    <br/>


    <table data-bind="dataTable: { dataSource : records, rowTemplate: 'rowTemplate',
           options: {
           bAutoWidth : false,
           aoColumnDefs: [
           { bSortable: false, aTargets: [0,1] },
           { bSearchable: false, aTargets: [0,1] }
           ],
           aaSorting: [],
           aLengthMenu: [[50, 100, 150, -1], [50, 100, 150, 'All']],
           iDisplayLength: 50,
           aoColumns: [
           {  mDataProp:'deviceId' },
           {  mDataProp:'skyId' },
           {  mDataProp:'eblSkyId' },
           {  mDataProp:'imeiNo' },
           {  mDataProp:'verify' },
           {  mDataProp:'varyfiedDtTm' },
           {  mDataProp:'active' },
           {  mDataProp:'lock' },
           {  mDataProp:'status' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
        <tr>

            <th style="width:5%; text-align: center">Actions</th>
            <th style="text-align:center; width:10%">
<!--
                <button style="text-align:center" id="selectAll" data-bind="click :$root.selectAll"
                        class="btn btn-primary">Select All
                </button>
                <button style="text-align:center" id="deselectAll" data-bind="click :$root.deselectAll, visible: false"
                        class="btn btn-primary">Deselect All
                </button>
-->
            </th>


            <th hidden style="text-align:center">Device ID</th>
            <th hidden style="text-align:center">SKY ID</th>
            <th style="text-align:center">ESB ID</th>
            <th style="text-align:center">IMEI No.</th>
            <th style="text-align:center">Is Verified</th>
            <th style="text-align:center">Verify Date</th>
            <th style="text-align:center">Active/Inactive</th>
            <th style="text-align:center">Lock/Unlock</th>
            <th style="text-align:center">Status</th>

        </tr>
        </thead>

        <tbody>
        </tbody>

    </table>

    <script id="rowTemplate" type="text/html">
        <td style="text-align:center">
            <button data-bind="click: $root.editDevice, visible: edit" class="btn btn-warning">Edit</button>
        </td>
        <td style="text-align:center"><input type="checkbox" data-bind="checked: isProcessed, visible: check"></td>
        <td hidden style="text-align:center" data-bind="text:deviceId"></td>
        <td hidden style="text-align:center" data-bind="text:skyId"></td>
        <td style="text-align:center" data-bind="text:eblSkyId"></td>
        <td style="text-align:center" data-bind="text:imeiNo"></td>
        <td style="text-align:center" data-bind="text:verify, style:{color: verifyColor}"></td>
        <td style="text-align:center" data-bind="text:varyfiedDtTm"></td>
        <td style="text-align:center" data-bind="text:active, style:{color: activeColor}"></td>
        <td style="text-align:center" data-bind="text:lock, style:{color: lockColor}"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
    </script>


  


</div>


<style>
    input {
        float: center;
        border: 1px solid #848484;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        border-radius: 30px;
        outline: 0;
        height: 25px;
        width: 100px;
        padding-left: 10px;
        padding-right: 10px;
    }
</style>


<script type="text/javascript" charset="utf-8">
    var initialData = jQuery.parseJSON('<?= $deviceInfo ?>'); //data for building initial table
    var vm = function () {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function (i, record) {   //build the checkboxes as observable
            record.isProcessed = ko.observable(false);
            record.edit = ko.observable(false);
            record.check = ko.observable(false);


            record.deviceId = record.deviceId;
            record.skyId = record.skyId;
            record.eblSkyId = record.eblSkyId;
            record.imeiNo = record.imeiNo;
            record.varyfiedDtTm = record.varyfiedDtTm;

            if (record.isVaryfied === "1") {
                record.verify = "YES";
                record.verifyColor = ko.observable("green")
            } else if (record.isVaryfied === "0") {
                record.verify = "NO";
                record.verifyColor = ko.observable("red")
            }

            if (record.isActive === "1") {
                record.active = "Active";
                record.activeColor = ko.observable("green")
            } else if (record.isActive === "0") {
                record.active = "Inactive";
                record.activeColor = ko.observable("red")
            }

            if (record.isLocked === "1") {
                record.lock = "Lock";
                record.lockColor = ko.observable("red")
            } else if (record.isLocked === "0") {
                record.lock = "Unlock";
                record.lockColor = ko.observable("green")
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
                document.getElementById("addDevice").style.display = "block";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
                document.getElementById("lock").style.display = "none";
                document.getElementById("unlock").style.display = "none";
            }

            $.each(self.records(), function (i, record) {
                action = document.getElementById("actionSelect").value;

                switch (action) {

                    case "edit":

                        if (record.isVaryfied === "1") {
                            record.edit(false);
                        } else if (record.isVaryfied === "0") {
                            record.edit(true);
                        }

                        document.getElementById("addDevice").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "active":
                        record.check(true);
                        document.getElementById("addDevice").style.display = "none";
                        document.getElementById("active").style.display = "block";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "inactive":
                        record.check(true);
                        document.getElementById("addDevice").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "block";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "lock":
                        record.check(true);
                        document.getElementById("addDevice").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "block";
                        document.getElementById("unlock").style.display = "none";
                        break;

                    case "unlock":
                        record.check(true);
                        document.getElementById("addDevice").style.display = "none";
                        document.getElementById("active").style.display = "none";
                        document.getElementById("inactive").style.display = "none";
                        document.getElementById("lock").style.display = "none";
                        document.getElementById("unlock").style.display = "block";
                        break;
                }

            })
        };

        self.back = function (item) {
            window.location = "<?php echo base_url(); ?>client_registration";
        };

        self.editDevice = function (item) {
            window.location = "<?php echo base_url(); ?>client_registration/editDevice?eblSkyId=" + item.eblSkyId + "&skyId=" + item.skyId + "&imeiNo=" + item.imeiNo + "&deviceId=" + item.deviceId + "&selectedActionName=" + $("#actionSelect option:selected").text();
        };

        self.addDevice = function () {

            var skyId = $("#skyId").val();
            var eblSkyId = $("#eblSkyId").val();

            if (skyId === "") {
                alert("Not authorised to add device");
            }
            else {
                window.location = "<?php echo base_url(); ?>client_registration/addDeviceInfo?eblSkyId=" + eblSkyId + "&skyId=" + skyId + "&selectedActionName=" + $("#actionSelect option:selected").text();
            }
        };


        self.active = function () {

            var skyId = $("#skyId").val();
            var eblSkyId = $("#eblSkyId").val();

            var imei_no = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    imei_no = imei_no + "|" + record.imeiNo;
                }
            });

            if (imei_no == "") {
                alert("Error: No Device Is Selected");
                return false;
            }

            imei_no = imei_no.substring(1);
            var dataToSave = {"imeiNo": imei_no, "selectedActionName": selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>client_registration/deviceActive",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected Devices are active");
                        window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };

        self.inactive = function () {

            var skyId = $("#skyId").val();
            var eblSkyId = $("#eblSkyId").val();

            var imei_no = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    imei_no = imei_no + "|" + record.imeiNo;
                }
            });

            if (imei_no == "") {
                alert("Error: No Device Is Selected");
                return false;
            }

            imei_no = imei_no.substring(1);
            var dataToSave = {"imeiNo": imei_no, "selectedActionName": selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>client_registration/deviceInactive",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected devices are inactive");
                        window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });

        };

        /********************************************************************************************************************************/

        self.lock = function () {

            var skyId = $("#skyId").val();
            var eblSkyId = $("#eblSkyId").val();

            var imei_no = "";

            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    imei_no = imei_no + "|" + record.imeiNo;
                }
            });

            if (imei_no == "") {
                alert("Error: No Device Is Selected");
                return false;
            }
            imei_no = imei_no.substring(1);
            var dataToSave = {"imeiNo": imei_no, "selectedActionName": selected_action_name};

            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>client_registration/deviceLock",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected Device are locked.");
                        window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
                    }

                    else if (data == 2) {
                        alert("Don't try like this");
                        window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };


        self.unlock = function () {

            var skyId = $("#skyId").val();
            var eblSkyId = $("#eblSkyId").val();

            var imei_no = "";

            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    imei_no = imei_no + "|" + record.imeiNo;
                }
            });

            if (imei_no == "") {
                alert("Error: No Device Is Selected");
                return false;
            }

            imei_no = imei_no.substring(1);
            var dataToSave = {"imeiNo": imei_no, "selectedActionName": selected_action_name};

            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>client_registration/deviceUnlock",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected device are unlocked.");
                        window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
                    }

                    else if (data == 2) {
                        alert("Don't try like this");
                        window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };
        /********************************************************************************************************************************/

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


