var app = app || {};
var AdminUserGroupModule = angular.module('AdminUserGroupModule',[])
.controller('AdminUserGroupController',['$scope','$http',function($scope, $http){
        $scope.data = [];

        $scope.activate = function($id){
            if(!confirm("Are you sure?")){
                return false;
            }
            
            var $postId = [];
            if($id == undefined){
                angular.forEach($scope.data,function(v,i){
                    if(v.isChecked == true){
                        $postId.push(v.userGroupId);
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
            var dataToSave = {"userGroupId" : $postId.join("|"), "selectedActionName" : 'Active'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "admin_user_group_maker/groupActive",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected item are active");
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
        
        $scope.deactivate = function($id){
            if(!confirm("Are you sure?")){
                return false;
            }
            
            var $postId = [];
            if($id == undefined){
                angular.forEach($scope.data,function(v,i){
                    if(v.isChecked == true){
                        $postId.push(v.userGroupId);
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
            
            var dataToSave = {"userGroupId" : $postId.join("|"), "selectedActionName" : 'Inactive'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "admin_user_group_maker/groupInactive",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected item are inactive");
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
        
        $scope.lock = function($id){
            if(!confirm("Are you sure?")){
                return false;
            }
            
            var $postId = [];
            if($id == undefined){
                angular.forEach($scope.data,function(v,i){
                    if(v.isChecked == true){
                        $postId.push(v.userGroupId);
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
            var dataToSave = {"userGroupId" : $postId.join("|"), "selectedActionName" : 'Lock'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "admin_user_group_maker/groupLock",
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
                        $postId.push(v.userGroupId);
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
            
            var dataToSave = {"userGroupId" : $postId.join("|"), "selectedActionName" : 'Unlock'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "admin_user_group_maker/groupUnlock",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected item are unlock");
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

        $scope.init = function(){
            $scope.data = app.adminGroups;
        };
        $scope.init();
}]);
app.addModules('AdminUserGroupModule', 'AdminUserGroupModule');
