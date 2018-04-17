var app = app || {};
var AdminUserModule = angular.module('AdminUserModule',[])
.controller('AdminUserController',['$scope','$http',function($scope, $http){
        $scope.data = [];

        $scope.init = function(){
            $scope.data = app.adminUsers;
        };
        $scope.init();
}]);
app.addModules('AdminUserModule', 'AdminUserModule');
