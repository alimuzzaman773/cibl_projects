var ToolsModuleApp = angular.module("ToolsModuleApp", ['ngRoute', 'ui.autocomplete', 'ui.date', 'ngSanitize', 'angularUtils.directives.dirPagination', 'ui.date', 'ngTagsInput']);

ToolsModuleApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.when('/tools', {
            templateUrl: app.baseUrl + 'tools/tools_form',
            controller: 'ToolsController'
        }).when('/soap', {
            templateUrl: app.baseUrl + 'tools/soap_form',
            controller: 'ToolsController'
        }).otherwise({
            redirectTo: '/tools'
        });
    }
]);

ToolsModuleApp.controller('ToolsController', ['$scope', '$http', '$routeParams', '$location', function ($scope, $http, $routeParams, $location) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.tools = {};
        $scope.form_row = [];
        $scope.soap = {};

        $scope.form_row = [
            {key: 'stakeHolderId', value: 'Admin'},
            {key: 'userId', value: 'Admin'},
            {key: 'password', value: '1'}
        ];

        $scope.addRow = function () {
            $scope.form_row.push({key: '', value: ''});
        };

        $scope.removeRow = function (index) {
            if ($scope.form_row.length > 1) {
                $scope.form_row.splice(index, 1);
            }
        };

        $scope.toolsSubmit = function () {
            var toolsParams = {
                url: $scope.tools.url,
                rows: JSON.stringify($scope.form_row)
            };
            app.showModal();
            $http({
                method: 'POST',
                url: app.baseUrl + 'tools/get_response',
                data: jQuery.param(toolsParams)
            }).success(function (data) {
                app.hideModal();

                $scope.tools.result = data.msg;
            }).error(function (data) {
                app.hideModal();
                alert("There was a problem, please try again.");
            });
        };

        $scope.soapSubmit = function () {
            var soapParams = {
                soap_obj: $scope.soap.soap_obj
            };
            app.showModal();
            $http({
                method: 'POST',
                url: app.baseUrl + 'tools/get_soap_result',
                data: jQuery.param(soapParams)
            }).success(function (data) {
                app.hideModal();
                console.log(data);
                $scope.soap.result = data.msg;
            }).error(function (data) {
                app.hideModal();
                alert("There was a problem, please try again.");
            });
        };

        $scope.init = function () {

        };
        $scope.init();
    }]);
app.addModules("ToolsModuleApp", "ToolsModuleApp");