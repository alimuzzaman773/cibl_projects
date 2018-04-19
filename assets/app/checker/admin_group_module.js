var app = app || {};
var AdminGroupModule = angular.module('AdminGroupModule',[])
.controller('AdminGroupController',['$scope','$http',function($scope, $http){
        $scope.admin_group_data = [];
        $scope.init = function(){
            $scope.admin_group_data = app.admin_group_data;
        };
        
        $scope.init();
}]);
app.addModules('AdminGroupModule', 'AdminGroupModule');
