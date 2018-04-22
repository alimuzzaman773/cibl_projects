var app = app || {};
var BillerSetupCheckerModule = angular.module('BillerSetupCheckerModule',[])
.controller('BillerSetupCheckerController',['$scope','$http',function($scope, $http){
        $scope.biller_setup_data = [];

        $scope.init = function(){
            $scope.biller_setup_data = app.biller_setup_data;
        };
        
        $scope.init();
}]);
app.addModules('BillerSetupCheckerModule', 'BillerSetupCheckerModule');
