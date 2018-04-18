var app = app || {};
var PasswordModule = angular.module('PasswordModule',[])
.controller('PasswordController',['$scope','$http',function($scope, $http){
        $scope.password_policy = [];
        $scope.init = function(){
            $scope.password_policy = app.password_policy;
        };
     
        $scope.init();
}]);
app.addModules('PasswordModule', 'PasswordModule');
