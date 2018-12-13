var app = app || {};
var UserDeleteCheckerModule = angular.module('UserDeleteCheckerModule',[])
.controller('UserDeleteCheckerController',['$scope','$http',function($scope, $http){
        $scope.user_delete_data = [];

        $scope.init = function(){
            $scope.user_delete_data = app.user_delete_data;
        };
        
        $scope.init();
}]);
app.addModules('UserDeleteCheckerModule', 'UserDeleteCheckerModule');
