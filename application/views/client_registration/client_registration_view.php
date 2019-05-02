<h1 class="title-underlined">
    Apps User
    <?php if (ci_check_permission("canAddAppUser")): ?>
        <a href="<?php echo base_url() . 'apps_users/addAppsUser/Add'; ?>" class="btn btn-primary pull-right hidden">
            <i class="glyphicon glyphicon-plus"></i> Add User
        </a>
    <?php endif; ?>
</h1>
<div class="table-responsive" id="AppUserModule" data-ng-controller="AppUsersController as AppUsersController">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="form-group col-sm-4 col-xs-6">
                <label>Search</label>
                <input type="text" class="form-control" data-ng-model="searchParams.search" placeholder="Search by Apps ID, User Name, Customer ID, Father or Mother Name" />
            </div>
            <div class="form-group col-sm-3 col-xs-6">
                <label>Is Locked?</label>
                <select class="form-control" data-ng-model="searchParams.isLocked">
                    <option value=""></option>
                    <option value="1">Locked</option>                    
                    <option value="0">Unlocked</option>                    
                </select>
            </div>
            <div class="form-group col-sm-3 col-xs-6">
                <label>Is Active?</label>
                <select class="form-control" data-ng-model="searchParams.isActive">
                    <option value=""></option>
                    <option value="1">Active</option>                    
                    <option value="0">Inactive</option>                    
                </select>
            </div>
            <div class="form-group col-xs-6 col-sm-2">
                <label style="display:block" class="hidden-xs">&nbsp;&nbsp;</label>
                <button class="btn btn-primary btn-sm" data-ng-click="getResultsPage(1)">
                    <i class="glyphicon glyphicon-search"></i>
                </button>

                <button class="btn btn-primary btn-sm" data-ng-click="resetSearch();">
                    <i class="glyphicon glyphicon-refresh"></i> Reset
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 text-right" data-ng-show="totalCount > 0">        
        <span class="label label-primary"> Displaying: {{ ((per_page * currentPageNumber) - (per_page - 1))}} to {{ upper_range()}} of {{ totalCount}}</span>            
    </div>
    <table class="table table-striped table-bordered" id="referenceTable">
        <thead>
            <tr class="bg-primary">
                <th>SI#</th>
                <th>ID</th>
                <th>Apps ID</th>
                <th>User ID</th>
                <th>CIF ID</th>
                <th>Father's name</th>
                <th>Mother's name</th>
                <th>Status</th>
                <th>User Name</th>
                <th>User Group</th>
                <th>Date of Birth</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Branch</th>
                <th>Checker Comment</th>
                <th>Checker Action</th>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            <tr dir-paginate="a in app_users | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                <td>{{$index + 1}}</td>
                <td>{{a.skyId}}</td>
                <td>{{a.eblSkyId}}</td>
                <td>{{a.userName2}}</td>
                <td>{{a.cfId}}</td>
                <td>{{a.fatherName}}</td>
                <td>{{a.motherName}}</td>
                <td data-ng-class="{'test' : setStatus($index), 'bg-success' : a.isLocked == 0, 'bg-danger' : a.isLocked == 1}">{{a.isLocked == 1 ? 'Locked' : 'Unlocked'}}</td>
                <td data-ng-class="{'bg-success' : a.isActive == 1, 'bg-danger' : a.isActive == 0}">{{a.isActive == 1 ? 'Active' : 'Inactive'}}</td>
                <td class="{{a.statusColor}}">{{a.status}}</td>                
                <td>{{a.userName}}</td>
                <td>{{a.userGroupName}}</td>
                <td>{{a.dob}}</td>
                <td>{{a.userEmail}}</td>
                <td>{{a.gender}}</td>
                <td>{{a.branchName}}</td>
                <td>{{a.checkerActionComment}}</td>
                <td>{{a.checkerAction}}</td>
                <td>
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                            Action <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php if (ci_check_permission("canEditAppUserLimitPackage")): ?>
                                <li class="hidden">
                                    <a href="<?= base_url() ?>client_registration/update_limit_package/{{a.skyId}}">
                                        <i class="glyphicon glyphicon-refresh"></i> Update Limit Package
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (ci_check_permission("canEditAppUser")): ?>
                                <li>
                                    <a href="<?= base_url() . "client_registration/edit/#/form/" ?>{{a.skyId}}">
                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                    </a>
    <!--                                    <a href="<?= base_url() ?>apps_users/editAppsUser?eblSkyId={{a.eblSkyId}}&cfId={{a.cfId}}&clientId={{a.clientId}}&skyId={{a.skyId}}&selectedActionName=Edit">
                                        <i class="glyphicon glyphicon-edit"></i> Edit
                                    </a>-->
                                </li>
                            <?php endif; ?>
                            <?php if (ci_check_permission("canDeleteAppUser")): ?>
                                <li>
                                    <a href="<?= base_url() . "client_registration/edit/#/remove/" ?>{{a.skyId}}">
                                        <i class="glyphicon glyphicon-trash"></i> Remove
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (ci_check_permission("canViewAppUserDevice")): ?>
                                <li>
                                    <a href="<?= base_url() ?>client_registration/deviceInfo?skyId={{a.skyId}}&eblSkyId={{a.eblSkyId}}">
                                        <i class="glyphicon glyphicon-dashboard"></i> View Devices
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (ci_check_permission("canViewAppUser")): ?>
                                <li>
                                    <a href="<?= base_url() ?>client_registration/viewUser?skyId={{a.skyId}}">
                                        <i class="glyphicon glyphicon-list-alt"></i> Detail View
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (ci_check_permission("canLockAppUser")): ?>
                                <li data-ng-if="a.isLocked == 0">
                                    <a data-ng-click="lock_unlock(a, 'Lock', 'userLock');">
                                        <i class="glyphicon glyphicon-lock"></i> Lock
                                    </a>
                                </li> 
                            <?php endif; ?>
                            <?php if (ci_check_permission("canUnlockAppUser")): ?>
                                <li data-ng-if="a.isLocked == 1">
                                    <a data-ng-click="lock_unlock(a, 'Unlock', 'userUnlock');">
                                        <i class="glyphicon glyphicon-refresh"></i> Unlock
                                    </a>
                                </li> 
                            <?php endif; ?>
                            <?php if (ci_check_permission("canActiveAppUser")): ?>
                                <li data-ng-if="a.isActive == 0">
                                    <a data-ng-click="activate_deactivate(a, 'Active', 'userActive');">
                                        <i class="glyphicon glyphicon-flash"></i> Active
                                    </a>
                                </li> 
                            <?php endif; ?>
                            <?php if (ci_check_permission("canInactiveAppUser")): ?>
                                <li data-ng-if="a.isActive == 1">
                                    <a data-ng-click="activate_deactivate(a, 'Inactive', 'userInactive');">
                                        <i class="glyphicon glyphicon-flash"></i> Inactive
                                    </a>
                                </li> 
                            <?php endif; ?>
                            <?php if (ci_check_permission("canDeleteAppUser")): ?>
                                <li class="hidden">
                                    <a data-ng-click="deleteUser(a)">
                                        <i class="glyphicon glyphicon-trash"></i> Delete
                                    </a>
                                </li> 
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr data-ng-show="app_users.length <= 0">
                <td colspan="18">No data found</td>
            </tr>
        </tbody>
    </table>
    <div class="box-footer clearfix text-center">
        <dir-pagination-controls on-page-change="pageChanged(newPageNumber)" template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
    </div>  
</div>

<?php
ci_add_js(asset_url() . "angularjs/directives/dirPagination.js");
ci_add_js(asset_url() . "app/directives/custom_directives.js");
ci_add_js(asset_url() . "app/app_users.js")
?>    

<script type="text/javascript" charset="utf-8">
    var initialData = <?= '[]' ?>; //data for building initial table

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
    //ko.applyBindings(new vm());


</script>
