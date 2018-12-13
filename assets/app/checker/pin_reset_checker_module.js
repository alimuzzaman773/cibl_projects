var app = app || {};
var PinResetCheckerModule = angular.module('PinResetCheckerModule',[])
.controller('PinResetCheckerController',['$scope','$http',function($scope, $http){
        $scope.pin_checker_data = [];

        $scope.init = function(){
            $scope.pin_reset_data = app.pin_reset_data;
        };
        
        $scope.init();
}]);
app.addModules('PinResetCheckerModule', 'PinResetCheckerModule');
