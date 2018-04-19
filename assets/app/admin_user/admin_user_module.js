var app = app || {};
var AdminUserModule = angular.module('AdminUserModule',[])
.controller('AdminUserController',['$scope','$http',function($scope, $http){
        $scope.data = [];

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

        $scope.init = function(){
            $scope.data = app.adminUsers;
        };
        $scope.init();
}]);
app.addModules('AdminUserModule', 'AdminUserModule');