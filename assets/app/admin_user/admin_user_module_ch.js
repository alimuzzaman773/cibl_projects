var app = app || {};
var AdminUserChModule = angular.module('AdminUserChModule',[])
.controller('AdminUserChController',['$scope','$http',function($scope, $http){
        $scope.data = [];

        $scope.init = function(){
            $scope.data = app.adminUsersCh;
        };
        $scope.init();
}]);
app.addModules('AdminUserChModule', 'AdminUserChModule');
