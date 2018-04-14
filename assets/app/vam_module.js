var app = app || {};
var vamModule = angular.module('vamModule',[])
.controller('vamController',['$scope','$http',function($scope, $http){
        $scope.vam = [];

        $scope.init = function(){
            console.log("vamModule initialized");
            $scope.vam = initialData;
        };
        
        $scope.init();
}]);
app.addModules('vamModule', 'vamModule');
