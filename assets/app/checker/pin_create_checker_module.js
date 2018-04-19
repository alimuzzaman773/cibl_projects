var app = app || {};
var PinCreateCheckerModule = angular.module('PinCreateCheckerModule',[])
.controller('PinCreateCheckerController',['$scope','$http',function($scope, $http){
        $scope.pin_checker_data = [];

        $scope.init = function(){
            $scope.pin_checker_data = app.pin_checker_data;
        };
        
        $scope.init();
}]);
app.addModules('PinCreateCheckerModule', 'PinCreateCheckerModule');
