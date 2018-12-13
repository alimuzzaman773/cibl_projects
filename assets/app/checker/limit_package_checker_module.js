var app = app || {};
var LimitPackageCheckerModule = angular.module('LimitPackageCheckerModule',[])
.controller('LimitPackageCheckerController',['$scope','$http',function($scope, $http){
        $scope.limit_package_checker_data = [];

        $scope.init = function(){
            $scope.limit_package_checker_data = app.limit_package_checker_data;
        };
        
        $scope.init();
}]);
app.addModules('LimitPackageCheckerModule', 'LimitPackageCheckerModule');
