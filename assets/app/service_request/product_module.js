var app = app || {};
var ProductModule = angular.module('ProductModule',[])
.controller('ProductController',['$scope','$http',function($scope, $http){
        $scope.vam = [];

        $scope.init = function(){
            $scope.product_data = app.productData;
        };
        
        $scope.init();
}]);
app.addModules('ProductModule', 'ProductModule');
