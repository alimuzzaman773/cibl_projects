var app = app || {};

var AppUserModule = angular.module("AppUserModule", ["angularUtils.directives.dirPagination"]);

AppUserModule.controller("AppUsersController", ["$scope", "$http", function ($scope, $http) {
    $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

    $scope.app_users = [];
    $scope.totalCount = 0;
    $scope.per_page = 20;
    $scope.currentPageNumber = 1;
    
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


    $scope.lastPage = {};
    $scope.getResultsPage = function (pageNumber) {
        var $params = {
            limit: $scope.per_page,
            offset: $scope.per_page * (pageNumber - 1),
            get_count: true,
            messageId : $scope.messageInfo.messageId
        };

        /*if ($scope.searchParams.type_code !== null
                && $.trim($scope.searchParams.type_code) !== "") {
            $params.type_code = $scope.searchParams.type_code;
        }*/

        $scope.lastPage = angular.copy(pageNumber)

        app.showModal();
        $http({
            method: "get",
            url: app.baseUrl + "notification/ajax_get_app_users",
            params: $params
        }).then(function (response) {
            app.hideModal();
            
            
            var $result = response.data;
            
            angular.forEach($result.app_users, function(v,i){
                $result.app_users[i].checked = v.messageLogId > 0 ? true : false;
            });
            
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

    $scope.toggleAll = function() {
        var toggleStatus = $scope.isAllSelected;
        angular.forEach($scope.app_users, function(itm){ 
            itm.checked = toggleStatus; 
        });
    };
    
    $scope.save = function() {
        if(!confirm('Do you really want to save it?')){
            return false;
        }
        var $items = [];
        angular.forEach($scope.app_users, function(v,i){
            $items.push({messageLogId : v.messageLogId, skyId : v.skyId, checked : (v.checked == true) ? 1 : 0})
        });
        
        console.log($items);
        
        var $params = {
            messageId : $scope.messageInfo.messageId,
            items : JSON.stringify($.parseJSON(angular.toJson($items)))        
        };
        
        app.showModal();
        $http({
          method: 'POST',
          url: app.baseUrl + "notification/save_message_users",
          data : jQuery.param($params)
        }).then(function(response) {            
            app.hideModal();
            console.log(response);
            if(response.data.success){
                alert("data has been saved");
                $scope.getResultsPage($scope.lastPage);
                return false;
            }
            alert(response.data.msg);
            return false;
                        
        }, function(response) {                      
            console.log(response);
            app.hideModal();
            alert("There was a problem. Please try again later");
        });
        return false;
    };

    $scope.messageInfo = {};
    $scope.init = function () {
        $scope.messageInfo = app.messageInfo;
        $scope.getResultsPage(1);
    };

    $scope.init();

}]);
app.addModules("AppUserModule", "AppUserModule");
