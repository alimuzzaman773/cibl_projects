var app = app || {};
var AdminUserModule = angular.module('AdminUserModule',["angularUtils.directives.dirPagination"])
.controller('AdminUserController',['$scope','$http',function($scope, $http){
        
        $scope.data = [];
        $scope.user_list=[];
        $scope.totalCount = 0;
        $scope.per_page = 10;
        $scope.currentPageNumber = 1;

         $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.upper_range = function () {
            var range = $scope.per_page * $scope.currentPageNumber;
            if (range > $scope.totalCount) {
                return $scope.totalCount;
            }
            return range;
        };

        $scope.searchParams = {
            user_name: "",
            group_id: "",
            email: "",
            lock_status: 0
        };

        $scope.activate = function($id){
            if(!confirm("Are you sure?")){
                return false;
            }
            
            var $postId = [];
            if($id == undefined){
                angular.forEach($scope.data,function(v,i){
                    if(v.isChecked == true){
                        $postId.push(v.adminUserId);
                    }
                });
            }
            else if(app.parseInt($id) > 0) {
                $postId.push($id);
            }
            
            if($postId.length <= 0){
                alert('Please select a item for activating');
                return false;
            }
            app.showModal();
            var dataToSave = {"adminUserId" : $postId.join("|"), "selectedActionName" : 'Active'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "admin_users_maker/adminUserActive",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected item are active");
                        window.location = app.baseUrl+ "admin_users_maker";
                    }
                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = app.baseUrl+"admin_users_maker";
                    }
                },
                error: function(error) {
                    app.hideModal();
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };
        
        $scope.deactivate = function($id){
            if(!confirm("Are you sure?")){
                return false;
            }
            
            var $postId = [];
            if($id == undefined){
                angular.forEach($scope.data,function(v,i){
                    if(v.isChecked == true){
                        $postId.push(v.adminUserId);
                    }
                });
            }
            else if(app.parseInt($id) > 0) {
                $postId.push($id);
            }
            
            if($postId.length <= 0){
                alert('Please select a item id for deactivating');
                return false;
            }
            
            app.showModal();
            
            var dataToSave = {"adminUserId" : $postId.join("|"), "selectedActionName" : 'Inactive'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "admin_users_maker/adminUserInactive",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected item are inactive");
                        window.location = app.baseUrl+ "admin_users_maker";
                    }
                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = app.baseUrl+"admin_users_maker";
                    }
                },
                error: function(error) {
                    app.hideModal();
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };
        
        $scope.lock = function($id){
            if(!confirm("Are you sure?")){
                return false;
            }
            
            var $postId = [];
            if($id == undefined){
                angular.forEach($scope.data,function(v,i){
                    if(v.isChecked == true){
                        $postId.push(v.adminUserId);
                    }
                });
            }
            else if(app.parseInt($id) > 0) {
                $postId.push($id);
            }
            
            if($postId.length <= 0){
                alert('Please select a item for lock');
                return false;
            }
            app.showModal();
            var dataToSave = {"adminUserId" : $postId.join("|"), "selectedActionName" : 'Lock'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "admin_users_maker/adminUserLock",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected item is lock");
                        window.location = app.baseUrl+ "admin_user_group_maker";
                    }
                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = app.baseUrl+"admin_user_group_maker";
                    }
                },
                error: function(error) {
                    app.hideModal();
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };
        
        $scope.unlock = function($id){
            if(!confirm("Are you sure?")){
                return false;
            }
            
            var $postId = [];
            if($id == undefined){
                angular.forEach($scope.data,function(v,i){
                    if(v.isChecked == true){
                        $postId.push(v.adminUserId);
                    }
                });
            }
            else if(app.parseInt($id) > 0) {
                $postId.push($id);
            }
            
            if($postId.length <= 0){
                alert('Please select a item id for unlock');
                return false;
            }
            
            app.showModal();
            
            var dataToSave = {"adminUserId" : $postId.join("|"), "selectedActionName" : 'Unlock'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "admin_users_maker/adminUserUnlock",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected item is unlock");
                        window.location = app.baseUrl+ "admin_user_group_maker";
                    }
                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = app.baseUrl+"admin_user_group_maker";
                    }
                },
                error: function(error) {
                    app.hideModal();
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };
        
         $scope.getResultsPage = function (pageNumber) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            if ($scope.searchParams.user_name !== null
                    && $.trim($scope.searchParams.user_name) !== "") {
                 $params.user_name = $scope.searchParams.user_name;
            }

            if ($scope.searchParams.group_id !== null
                    && parseInt($scope.searchParams.group_id) > 0) {
                $params.group_id = $scope.searchParams.group_id;
            }

            if ($scope.searchParams.email !== null
                    && $.trim($scope.searchParams.email) !== "") {
                 $params.email = $scope.searchParams.email;
            }
            
            if ($scope.searchParams.lock_status !== null
                    && parseInt($scope.searchParams.lock_status) <= 1) {
                $params.lockStatus = $scope.searchParams.lock_status;
            }

            app.showModal();
            $http({
                method: "get",
                url: app.baseUrl + "admin_users_maker/ajax_get_admin_users",
                params: $params
            }).then(function (response) {
                 app.hideModal();
                var $result = response.data;
                $scope.user_list = $result.user_list;
                $scope.totalCount = $result.total;
                $scope.currentPageNumber = pageNumber;
                $scope.pagination.current = $scope.currentPageNumber;

            }, function (response) {
                app.hideModal();
                alert("There was a problem, please try again later");
                $scope.loading = false;
            });
        };
        
        $scope.resetSearch = function () {
         $scope.searchParams = {
            user_name: "",
            group_id: "",
            email: "",
            lock_status: 0
        };

        $scope.getResultsPage(1);
        return false;
        };

        $scope.init = function(){
              $scope.getResultsPage(1);
              //$scope.data = app.adminUsers;
        };
        $scope.init();
}]);
app.addModules('AdminUserModule', 'AdminUserModule');
