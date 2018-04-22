var app = app || {};
var AppsUserCheckerModule = angular.module('AppsUserCheckerModule',[])
.controller('AppsUserCheckerController',['$scope','$http',function($scope, $http){
        $scope.apps_user_data = [];
        $scope.init = function(){
            $scope.apps_user_data = app.apps_user_data;
        };
        $scope.init();
}]);
app.addModules('AppsUserCheckerModule', 'AppsUserCheckerModule');
