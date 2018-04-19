var app = app || {};

var AppUserModule = angular.module("AppUserModule", ["angularUtils.directives.dirPagination"]);

AppUserModule.controller("AppUsersController", ["$scope", "$http", function ($scope, $http) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

    $scope.app_users = [];
    $scope.totalCount = 0;
    $scope.per_page = 10;
    $scope.currentPageNumber = 1;
    
    $scope.setStatus = function($i){
        if ($scope.app_users[$i].mcStatus === "1") {
            $scope.app_users[$i].status = "Approved";
            $scope.app_users[$i].statusColor = "bg-success";
        } else if ($scope.app_users[$i].mcStatus === "0") {
            $scope.app_users[$i].status = "Waiting for approval";
            $scope.app_users[$i].statusColor = "bg-danger";
        } else if ($scope.app_users[$i].mcStatus === "2") {
            $scope.app_users[$i].status = "Rejected";
            $scope.app_users[$i].statusColor = "bg-danger";
        }
    };
    
    $scope.lock_unlock = function($u, $action, $url){
        var esb_id = [];
        var sky_id = [];
        var selected_action_name = $action;
        if($u == undefined){
            $.each($scope.app_users, function (i, record) {
                if (record.isSelected == true) {
                    esb_id.push(record.eblSkyId);
                    sky_id.push(record.skyId);
                }
            });            
        }
        else {
            esb_id.push($u.eblSkyId);
            sky_id.push($u.skyId);
        }

        if (esb_id.length <= 0) {
            alert("Error: No user Is Selected");
            return false;
        }

        esb_id = esb_id.join("|");
        sky_id = sky_id.join("|");
        var dataToSave = {"eblSkyId": esb_id, "skyId": sky_id, "selectedActionName": selected_action_name};
        $.ajax({
            type: "POST",
            data: dataToSave,
            dataType: 'json',
            url: app.baseUrl + "client_registration/"+ $url,
            success: function (data) {
                if (data.success == true) {
                    alert("Operation successful");
                    window.location = app.baseUrl+"client_registration";
                    return false;
                }
                
                alert(data.msg);
            },
            error: function (error) {
                alert(error.status + "<--and--> " + error.statusText);
            }
        });
        return false;
    };
    
    $scope.activate_deactivate = function($u, $action, $url){
        var esb_id = [];
        var sky_id = [];
        var selected_action_name = $action;
        if($u == undefined){
            $.each($scope.app_users, function (i, record) {
                if (record.isSelected == true) {
                    esb_id.push(record.eblSkyId);
                    sky_id.push(record.skyId);
                }
            });            
        }
        else {
            esb_id.push($u.eblSkyId);
            sky_id.push($u.skyId);
        }

        if (esb_id.length <= 0) {
            alert("Error: No user Is Selected");
            return false;
        }

        esb_id = esb_id.join("|");
        sky_id = sky_id.join("|");
        var dataToSave = {"eblSkyId": esb_id, "skyId": sky_id, "selectedActionName": selected_action_name};
        $.ajax({
            type: "POST",
            data: dataToSave,
            dataType: 'json',
            url: app.baseUrl + "client_registration/"+ $url,
            success: function (data) {
                if (data.success == true) {
                    alert("Operation successful");
                    window.location = app.baseUrl+"client_registration";
                    return false;
                }
                
                alert(data.msg);
            },
            error: function (error) {
                alert(error.status + "<--and--> " + error.statusText);
            }
        });
        return false;
    };
    
    $scope.deletedUser = function (item) {	
       var r = confirm("Are you sure to delete this user?");
        if (r == true) {
            $.ajax({
                type: "POST",
                data: {"skyId": item.skyId},
                url: app.baseUrl + "client_registration/userDelete",
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
    

    $scope.searchParams = {
    };

    $scope.pagination = {
        current: 1
    };

    $scope.pageChanged = function (newPage) {
        $scope.getResultsPage(newPage);
    };

    $scope.resetSearch = function () {
        $scope.searchParams = {
        };

        $scope.getResultsPage(1);
        return false;
    };

    $scope.getResultsPage = function (pageNumber) {
        var $params = {
            limit: $scope.per_page,
            offset: $scope.per_page * (pageNumber - 1),
            get_count: true
        };

        /*if ($scope.searchParams.type_code !== null
                && $.trim($scope.searchParams.type_code) !== "") {
            $params.type_code = $scope.searchParams.type_code;
        }*/

        app.showModal();
        $http({
            method: "get",
            url: app.baseUrl + "client_registration/ajax_get_app_users",
            params: $params
        }).then(function (response) {
            app.hideModal();
            var $result = response.data;
            $scope.app_users = $result.app_users;
            $scope.totalCount = $result.total;
            $scope.currentPageNumber = pageNumber;
            $scope.pagination.current = $scope.currentPageNumber;

        }, function (response) {
            app.hideModal();
            alert("There was a problem, please try again later");
            $scope.loading = false;
        });
    };

    $scope.upper_range = function () {
        var range = $scope.per_page * $scope.currentPageNumber;
        if (range > $scope.totalCount) {
            return $scope.totalCount;
        }
        return range;
    };

    $scope.getTypeRequest = function () {
        $scope.searchParams.type_code = $scope.type_code;
        $scope.getResultsPage(1);
    };

    $scope.init = function () {
        $scope.getResultsPage(1);
    };

    $scope.init();

}]);

AppUserModule.controller("AppUsersDeviceCOntroller", ["$scope", "$http", function ($scope, $http) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

    $scope.skyId = null;
    $scope.eblSkyId = null;
    $scope.devices = [];
    
    $scope.setStatus = function($i){
        if ($scope.devices[$i].mcStatus === "1") {
            $scope.devices[$i].status = "Approved";
            $scope.devices[$i].statusColor = "bg-success";
        } else if ($scope.devices[$i].mcStatus === "0") {
            $scope.devices[$i].status = "Waiting for approval";
            $scope.devices[$i].statusColor = "bg-danger";
        } else if ($scope.devices[$i].mcStatus === "2") {
            $scope.devices[$i].status = "Rejected";
            $scope.devices[$i].statusColor = "bg-danger";
        }
    };
    
    $scope.activate = function ($device) {
        var skyId = $scope.skyId;
        var eblSkyId = $scope.eblSkyId;

        var imei_no = [];
        var selected_action_name = 'Active';
        if($device == undefined){
            $.each($scope.devices, function (i, record) {
                if (record.isSelected == true) {
                    imei_no.push(record.imeiNo);
                }
            });            
        }
        else {
            imei_no.push($device.imeiNo);
        }

        if (imei_no.length <= 0) {
            alert("Error: No Device Is Selected");
            return false;
        }

        var $imeiString = imei_no.join("|");

        var dataToSave = {"imeiNo": $imeiString, "selectedActionName": selected_action_name};
        $.ajax({
            type: "POST",
            data: dataToSave,
            url: app.baseUrl + "client_registration/deviceActive",
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    alert("Device activation is successful");
                    window.location = app.baseUrl + "client_registration/deviceInfo?skyId=" + $scope.skyId + "&eblSkyId=" + $scope.eblSkyId;
                    return false;
                }                
                alert(data.msg);
            },
            error: function (error) {
                alert(error.status + "<--and--> " + error.statusText);
            }
        });
    };
    
    $scope.deactivate = function ($device) {
        var skyId = $scope.skyId;
        var eblSkyId = $scope.eblSkyId;

        var imei_no = [];
        var selected_action_name = 'Inactive';
        if($device == undefined){
            $.each($scope.devices, function (i, record) {
                if (record.isSelected == true) {
                    imei_no.push(record.imeiNo);
                }
            });            
        }
        else {
            imei_no.push($device.imeiNo);
        }

        if (imei_no.length <= 0) {
            alert("Error: No Device Is Selected");
            return false;
        }

        var $imeiString = imei_no.join("|");

        var dataToSave = {"imeiNo": $imeiString, "selectedActionName": selected_action_name};
        $.ajax({
            type: "POST",
            data: dataToSave,
            dataType: 'json',
            url: app.baseUrl + "client_registration/deviceInactive",
            success: function (data) {
                if (data.success == true) {
                    alert("Device deactivation is successful");
                    window.location = app.baseUrl + "client_registration/deviceInfo?skyId=" + $scope.skyId + "&eblSkyId=" + $scope.eblSkyId;
                    return false;
                }   
                
                alert(data.msg);
            },
            error: function (error) {
                alert(error.status + "<--and--> " + error.statusText);
            }
        });
    };
    
    $scope.lock_unlock = function ($device, $action, $url) {
        var skyId = $scope.skyId;
        var eblSkyId = $scope.eblSkyId;

        var imei_no = [];
        var selected_action_name = $action;
        if($device == undefined){
            $.each($scope.devices, function (i, record) {
                if (record.isSelected == true) {
                    imei_no.push(record.imeiNo);
                }
            });            
        }
        else {
            imei_no.push($device.imeiNo);
        }

        if (imei_no.length <= 0) {
            alert("Error: No Device Is Selected");
            return false;
        }

        var $imeiString = imei_no.join("|");

        var dataToSave = {"imeiNo": $imeiString, "selectedActionName": selected_action_name};

        $.ajax({
            type: "POST",
            data: dataToSave,
            dataType : 'json',
            url: app.baseUrl + "client_registration/" + $url,
            success: function (data) {
                if (data.success == true) {
                    alert("Operation Completed Successfully");
                    window.location = app.baseUrl + "client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
                    return false;
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

    $scope.init = function () {
        console.log("init AppUsersDevices");
        $scope.skyId = app.skyId;
        $scope.eblSkyId = app.eblSkyId;
        $scope.devices = app.devices;
        
        console.log($scope);
    };

    $scope.init();

}]);

app.addModules("AppUserModule", "AppUserModule");
