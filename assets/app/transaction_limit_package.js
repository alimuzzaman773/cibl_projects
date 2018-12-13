var app = app || {};

var TLPModule = angular.module("TLPModule", []);

TLPModule.controller("TLPController", ["$scope", "$http", function ($scope, $http) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

    $scope.packages = [];

    $scope.init = function () {
        console.log("init TLP MODULE");
        $scope.packages = app.packages;

        console.log($scope);
    };
    
    $scope.activate_deactivate = function($u, $action, $url){
        var apps_group_id = [];
        var selected_action_name = $action;
        if($u == undefined){
            $.each($scope.packages, function (i, record) {
                if(record.isChecked == true){
                    apps_group_id.push($u.appsGroupId);
                }
            });
        }
        else {
            apps_group_id.push($u.appsGroupId);
        }
        
        if(apps_group_id.length <= 0){
            alert("Error: No Package Is Selected");
            return false;
        }
        
        var $apps_group_ids = apps_group_id.join("|");
        
        app.showModal();
        
        var dataToSave = {"appsGroupId" : $apps_group_ids, "selectedActionName" : selected_action_name};
        $.ajax({
            type: "POST",
            data: dataToSave,
            dataType : 'json',
            url: app.baseUrl + "transaction_limit_setup_maker/"+$url,
            success: function(data) {
                app.hideModal();
                if (data.success == true) {
                    app.showModal();
                    alert("Operation completed successfully");
                    window.location = app.baseUrl + "transaction_limit_setup_maker";
                    return false;
                }
                alert(data.msg);                
            },
            error: function(error) {
                app.hideModal();
                alert(error.status + "<--and--> " + error.statusText);
            }
        });
        return false;
    };

    $scope.init();

}]);

app.addModules("TLPModule","TLPModule");