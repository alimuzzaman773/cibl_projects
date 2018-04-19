<h1 class="title-underlined">
    Device
    
    <a href="<?=base_url()."client_registration/addDeviceInfo?eblSkyId={$eblSkyId}&skyId={$skyId}"?>" class="btn btn-primary pull-right">
        <i class="glyphicon glyphicon-plus"></i> Add Device
    </a>
</h1>

<div class="clearfix table-responsive form-inline" id="AppUserModule" data-ng-controller="AppUsersDeviceCOntroller">
    <input hidden type="text" name="skyId" id="skyId" value="<?= $skyId ?>" size="20"/>
    <input hidden type="text" name="eblSkyId" id="eblSkyId" value="<?= $eblSkyId ?>" size="20"/>
    
    <table class="table table-striped table-bordered" id="referenceTable">
        <thead>
            <tr class="bg-primary">
                <th>#SL</th>
                <th>Device ID</th>
                <th>SKY ID</th>
                <th>ESB ID</th>
                <th>IMEI No.</th>
                <th>Is Verified</th>
                <th>Verify Date</th>
                <th>Active/Inactive</th>
                <th>Lock/Unlock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <tr data-ng-repeat="d in devices track by $index">
                <td>{{$index + 1}}</td>
                <td data-ng-bind="d.deviceId"></td>
                <td data-ng-bind="d.skyId"></td>
                <td data-ng-bind="d.eblSkyId"></td>
                <td data-ng-bind="d.imeiNo"></td>
                <td data-ng-bind="d.isVaryfied"></td>
                <td data-ng-bind="d.varyfiedDtTm"></td>
                <td data-ng-bind="d.isActive"></td>
                <td data-ng-bind="d.isLocked"></td>
                <td data-ng-class="{'sds' : setStatus($index)}" data-ng-bind="d.status"></td>                
                <td>
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php //if(ci_check_permission("canEditSite")): ?>
                            <li>
                                <a href="<?=base_url()?>client_registration/editDevice?eblSkyId={{d.eblSkyId}}&skyId={{d.skyId}}&imeiNo={{d.imeiNo}}&deviceId={{d.deviceId}}">
                                    <i class="glyphicon glyphicon-edit"></i> Edit
                                </a>
                            </li>
                            <?php //endif;?>
                            <?php //if(ci_check_permission("canViewElectricityReading")): ?>
                            <li data-ng-if="d.isLocked == 0">
                                <a data-ng-click="lock_unlock(d, 'Lock', 'deviceLock');">
                                    <i class="glyphicon glyphicon-lock"></i> Lock
                                </a>
                            </li> 
                            <?php //endif;?>
                            <?php //if(ci_check_permission("canViewAdvancePaymentModule")): ?>
                            <li data-ng-if="d.isLocked == 1">
                                <a data-ng-click="lock_unlock(d, 'Unlock', 'deviceUnlock');">
                                    <i class="glyphicon glyphicon-refresh"></i> Unlock
                                </a>
                            </li> 
                            <?php //endif;?>
                            <?php //if(ci_check_permission("canViewAdvancePaymentModule")): ?>
                            <li data-ng-if="d.isActive == 0">
                                <a data-ng-click="activate(d);">
                                    <i class="glyphicon glyphicon-flash"></i> Activate
                                </a>
                            </li> 
                            <?php //endif;?>
                            <?php //if(ci_check_permission("canViewAdvancePaymentModule")): ?>
                            <li data-ng-if="d.isActive == 1">
                                <a data-ng-click="deactivate(d);">
                                    <i class="glyphicon glyphicon-flash"></i> Deactivate
                                </a>
                            </li> 
                            <?php //endif;?>                            
                        </ul>
                    </div>
                </td>                
            </tr>
        </tbody>

    </table>
</div>


<?php
ci_add_js(asset_url()."angularjs/directives/dirPagination.js");  
ci_add_js(asset_url()."app/app_users.js")  
?> 

<script>
var app = app || {};
app.skyId = '<?=$skyId?>';
app.eblSkyId = '<?=$eblSkyId?>';
</script>

<script type="text/javascript" charset="utf-8">
    var initialData = app.devices = jQuery.parseJSON('<?= $deviceInfo ?>'); //data for building initial table
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

    //ko.applyBindings(new vm());
</script>