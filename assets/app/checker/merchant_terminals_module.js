var app = app || {};
var MerchantTmCheckerModule = angular.module('MerchantTmCheckerModule',[])
.controller('MerchantTmCheckerCtrl',['$scope','$http',function($scope, $http){
        $scope.merchant_terminals = [];
        $scope.init = function(){
        	console.log(app.merchant_terminals_data);
            $scope.merchant_terminals = app.merchant_terminals_data;
        };
        $scope.init();
}]);
app.addModules('MerchantTmCheckerModule', 'MerchantTmCheckerModule');
