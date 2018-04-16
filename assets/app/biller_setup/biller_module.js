var app = app || {};
var billerModule = angular.module('billerModule',[])
.controller('billerController',['$scope','$http',function($scope, $http){
        $scope.biller_data = [];
        $scope.init = function(){
            $scope.biller_data = billerData;
        };
        $scope.init();
}]);
app.addModules('billerModule', 'billerModule');
