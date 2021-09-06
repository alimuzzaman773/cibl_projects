var app = app || {};
var MerchantAcCheckerModule = angular.module('MerchantAcCheckerModule',[])
.controller('MerchantAcCheckerCtrl',['$scope','$http',function($scope, $http){
        $scope.merchant_accounts = [];
        $scope.init = function(){
        	console.log(app.merchant_accounts_data);
            $scope.merchant_accounts = app.merchant_accounts_data;
        };
        $scope.init();
}]);
app.addModules('MerchantAcCheckerModule', 'MerchantAcCheckerModule');
