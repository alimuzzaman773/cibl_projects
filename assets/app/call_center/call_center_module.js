var CallCenterModuleApp = angular.module("CallCenterModuleApp", ['ngRoute', 'ui.autocomplete', 'ui.date', 'ngSanitize', 'angularUtils.directives.dirPagination', 'ui.date']);

CallCenterModuleApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider
                .when('/user_list', {
                    templateUrl: app.baseUrl + 'call_center/user_list',
                    controller: 'CallCenterController'
                })
                .when('/user_approve/:uid?', {
                    templateUrl: app.baseUrl + 'call_center/user_approve',
                    controller: 'CallCenterController'
                })
                .when('/confirmation/:uid?', {
                    templateUrl: app.baseUrl + 'call_center/confirmation',
                    controller: 'CallCenterController'
                })
                .otherwise({
                    redirectTo: '/user_list'
                });
    }
]);

CallCenterModuleApp.controller('CallCenterController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.user_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 7;
        $scope.currentPageNumber = 1;
        $scope.uid = $routeParams.uid;
        $scope.user = {};

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.getResultsPage = function (pageNumber, childId) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/user_list', params: $params})
                    .then(function (response) {
                        app.hideModal();
                        var $result = response.data;
                        $scope.user_list = $result.user_list;
                        $scope.totalCount = $result.total;
                        $scope.currentPageNumber = pageNumber;
                        $scope.pagination.current = $scope.currentPageNumber;
                    }, function () {
                        app.hideModal();
                        alert("There was a problem, please try again later")
                        $scope.loading = false;
                    });
        };

        $scope.upper_range = function () {
            var range = $scope.per_page * $scope.currentPageNumber;
            if (range > $scope.totalCount) {
                return $scope.totalCount;
            }
            return range;
        };

        $scope.get_user_info = function () {

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/get_user_info/' + $scope.uid})
                    .success(function (data) {
                        $scope.user = data.user_info;
                        app.hideModal();
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.approveUser = function (userId) {
            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/user_approve/' + userId})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success === false) {
                            alert(data.msg);
                            return false;
                        }
                        window.location.href = app.baseUrl + "call_center/#/confirmation/" + data.user_id;
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.approvedConfirmation = function () {
            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/approve_confirmation/' + $scope.uid})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success === false) {
                            alert(data.msg);
                            return false;
                        }
                        if (confirm("Successfully approved!")) {
                            window.location.href = app.baseUrl + "call_center/#/user_list/";
                        }
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.init = function () {
            $scope.getResultsPage(1);
            if ($routeParams.uid !== undefined) {
                $scope.get_user_info();
            }
        };
        $scope.init();
    }]);

app.addModules("CallCenterModuleApp", "CallCenterModuleApp");