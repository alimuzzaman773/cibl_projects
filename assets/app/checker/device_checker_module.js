var app = app || {};
var DeviceCheckerModule = angular.module('DeviceCheckerModule',[])
.controller('DeviceCheckerController',['$scope','$http',function($scope, $http){
        $scope.device_checker_data = [];
        $scope.init = function(){
            $scope.device_checker_data = app.device_checker_data;
        };
        
        $scope.init();
}]);
app.addModules('DeviceCheckerModule', 'DeviceCheckerModule');
