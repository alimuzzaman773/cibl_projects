<h1 class="title-underlined">
    Transaction Limit Package
    <?php if (ci_check_permission("canAddLimitPackage")): ?>
        <a href="<?= base_url() . "transaction_limit_setup_maker/createGroup/Add" ?>" class="btn btn-primary pull-right">
            Add Package
        </a>
    <?php endif; ?>
</h1>
<div class="clearfix table-responsive form-inline" id="TLPModule" data-ng-controller="TLPController">

    <div class="form-group col-md-6 col-sm-6 col-xs-12 hidden">
        <label>Select Action</label>
        <select id="actionSelect" class="form-control" name="actionSelect" data-bind="event: {change: $root.actionFunction}">
            <option value="all">Select Action</option>
        </select>        
    </div>

    <div id="showGroups" class="table-responsive">
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr class="bg-primary">
                    <th>ID</th>
                    <th style="text-align: center" >Group Name</th>
                    <th style="text-align: center" >Own Account Transfer</th>
                    <th style="text-align: center" >EBL Account Transfer</th>
                    <th style="text-align: center" >Other Bank Transfer</th>
                    <th style="text-align: center" >Bills Pay</th>
                    <th style="text-align: center" >Active/Inactive</th>
                    <th style="text-align: center" >Status</th>
                    <th style="text-align: center" >Checker Comment</th>
                    <th style="text-align: center" >Checker Action</th>
                    <th style="text-align: center" >Action</th>
                </tr>
            </thead>
            <tbody>
                <tr data-ng-repeat="text in packages track by $index">
                    <td style="text-align:left" data-ng-bind="text.appsGroupId"></td>
                    <td style="text-align:center" data-ng-bind="text.userGroupName"></td>
                    <td style="text-align:center">
                        <i class="glyphicon glyphicon-check" data-ng-if="text.oatMinTxnLim > 0"></i>                        
                    </td>
                    <td style="text-align:center">
                        <i class="glyphicon glyphicon-check" data-ng-if="text.eatMinTxnLim > 0"></i>                        
                    </td>
                    <td style="text-align:center">
                        <i class="glyphicon glyphicon-check" data-ng-if="text.obtMinTxnLim > 0"></i>
                    </td>
                    <td style="text-align:center">
                        <i class="glyphicon glyphicon-check" data-ng-if="text.pbMinTxnLim > 0"></i>
                    </td>
                    <td>
                        <span data-ng-class="{'text-success': text.isActive == '1', 'text-danger': text.isActive == '0'}">{{text.isActive=='1' ? 'Active' : 'Inactive'}}</span>
                    </td>
                    <td>
                        <span data-ng-class="{'text-success': text.mcStatus == '1', 'text-danger': text.mcStatus == '0'}">{{text.mcStatus=='1' ? 'Approved' : 'Wait for approve'}}</span>
                    </td>
                    <td>{{text.checkerActionComment}}</td>
                    <td>{{text.checkerAction}}</td>
                    <td>
                        <div class="dropdown pull-right">
                            <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <?php if (ci_check_permission("canEditLimitPackage")): ?>
                                    <li>
                                        <a href="<?= base_url() ?>transaction_limit_setup_maker/editTransactionLimitPackage/{{text.appsGroupId}}/Edit">
                                            <i class="glyphicon glyphicon-edit"></i> Edit
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php if (ci_check_permission("canActiveLimitPackage")): ?>
                                    <li data-ng-if="text.isActive == 0">
                                        <a data-ng-click="activate_deactivate(text, 'Active', 'packageActive');">
                                            <i class="glyphicon glyphicon-flash"></i> Active
                                        </a>
                                    </li> 
                                <?php endif; ?>
                                <?php if (ci_check_permission("canInactiveLimitPackage")): ?>
                                    <li data-ng-if="text.isActive == 1">
                                        <a data-ng-click="activate_deactivate(text, 'Inactive', 'packageInactive');">
                                            <i class="glyphicon glyphicon-flash"></i> Inactive
                                        </a>
                                    </li> 
                                <?php endif; ?>                                
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>    
    </div>
</div>

<?php
ci_add_js(asset_url() . "app/transaction_limit_package.js");
?>

<script type="text/javascript" charset="utf-8">
    var initialData = <?= $packages ?>; //data for building initial table
    app.packages = initialData;
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



        self.actionFunction = function () {

            var action = document.getElementById("actionSelect").value;
            if (action === "add") {
                document.getElementById("addPackage").style.display = "block";
                document.getElementById("active").style.display = "none";
                document.getElementById("inactive").style.display = "none";
            }

            $.each(self.records(), function (i, record) {
                action = document.getElementById("actionSelect").value;

                switch (action) {

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

        self.addPackage = function (item) {
            window.location = "<?php echo base_url(); ?>transaction_limit_setup_maker/createGroup/" + $("#actionSelect option:selected").text();
        }

        self.editPackage = function (item) {
            window.location = "<?php echo base_url(); ?>transaction_limit_setup_maker/editTransactionLimitPackage/" + item.appsGroupId + "/" + $("#actionSelect option:selected").text();
        }


        self.active = function () {
            var apps_group_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    apps_group_id = apps_group_id + "|" + record.appsGroupId;
                }
            })
            if (apps_group_id == "") {
                alert("Error: No Package Is Selected");
                return false;
            }
            apps_group_id = apps_group_id.substring(1);
            var dataToSave = {"appsGroupId": apps_group_id, "selectedActionName": selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>transaction_limit_setup_maker/packageActive",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected packages are active");
                        window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                    } else if (data == 2) {
                        alert("Don't try like this");
                        window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }

        self.inactive = function () {
            var apps_group_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if (record.isProcessed() == true) {
                    apps_group_id = apps_group_id + "|" + record.appsGroupId;
                }
            })
            if (apps_group_id == "") {
                alert("Error: No Package Is Selected");
                return false;
            }
            apps_group_id = apps_group_id.substring(1);
            var dataToSave = {"appsGroupId": apps_group_id, "selectedActionName": selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>transaction_limit_setup_maker/packageInactive",
                success: function (data) {
                    if (data == 1) {
                        alert("Selected packages are inactive");
                        window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                    } else if (data == 2) {
                        alert("Don't try like this");
                        window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                    }
                },
                error: function (error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }
    }
    //ko.applyBindings(new vm());

</script>



<script type="text/javascript" charset="utf-8">

</script>