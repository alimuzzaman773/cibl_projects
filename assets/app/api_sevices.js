var app = app || {};
var config_data_module = angular.module("config_data_module", []);
config_data_module.controller("ConfigDataController", ["$scope", "$http", function ($scope) {
        $scope.config_data = [];
        
        $scope.addItem = function(){
            $scope.config_data.push({key: '', val:''});
        };
        $scope.removeItem = function($index){
            $scope.config_data.splice($index,1);
        };
        
        $scope.init = function(){
            console.log('init config data module');
            $scope.config_data = app.api_config_data;
        };

        $scope.init();
}]);
app.addModules('config_data_module', 'config_data_module');