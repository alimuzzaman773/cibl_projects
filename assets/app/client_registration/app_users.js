var AppUsersModuleApp = angular.module("AppUsersModuleApp", ['ngRoute', 'ui.autocomplete', 'ui.date', 'ngSanitize', 'angularUtils.directives.dirPagination']);

AppUsersModuleApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider.
                when('/form/:skyId?', {
                    //templateUrl: app.baseUrl + 'data/data_add',
                    templateUrl: function ($routeParams) {
                        return app.baseUrl + 'client_registration/edit_form/' + $routeParams.skyId;
                    },
                    controller: 'AppUsersAddController'
                }).
                when('/remove/:skyId?', {
                    templateUrl: function ($routeParams) {
                        return app.baseUrl + 'client_registration/remove_user/' + $routeParams.skyId;
                    },
                    controller: 'AppUsersRemoveController'
                })
                .otherwise({
                    redirectTo: '/'
                });
    }
]);

AppUsersModuleApp.controller('AppUsersAddController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.skyId = $routeParams.skyId;
        $scope.v_errors = [];

        //---SUBMIT BUTTON----
        $scope.saveItems = function () {
            $scope.v_errors = [];
            var iData = {
                skyId: $scope.skyId,
                cfId: $scope.cfId,
                clientId: $scope.clientId,
                userName: $scope.userName,
                currAddress: $scope.currAddress,
                parmAddress: $scope.parmAddress,
                billingAddress: $scope.billingAddress
            };

            app.showModal();

            $http({method: 'POST', url: app.baseUrl + 'api/client_registration/update_user/', data: jQuery.param(iData)})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success == false) {
                            $scope.v_errors = data.msg;
                            return false;
                        }

                        if (data.success) {
                            alert("AppUsers entries has been saved successfully!");
                            $("#form_data")[0].reset();
                            window.location = app.baseUrl + "client_registration/index";
                        } else {
                            alert("Please try again!");
                        }

                        //$scope.skyId = data.skyId;
                    })
                    .error(function (data) {
                        app.hideModal();
                        alert("There was a problem, please try again.");
                    });

            return false;
        };

        $scope.get_user = function () {
            console.log($scope.skyId);
            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/client_registration/get_user/' + $scope.skyId})
                    .success(function (data) {
                        $scope.skyId = data.data.skyId;
                        $scope.eblSkyId = data.data.eblSkyId;
                        $scope.cfId = data.data.cfId;
                        $scope.clientId = data.data.clientId;
                        $scope.userName = data.data.userName;
                        $scope.currAddress = data.data.currAddress;
                        $scope.parmAddress = data.data.parmAddress;
                        $scope.billingAddress = data.data.billingAddress;

                        app.hideModal();
                    })
                    .error(function (data) {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
        };

        $scope.init = function () {
            if ($scope.skyId > 0) {
                $scope.get_user();
            }
        };

        $scope.init();

    }]);

AppUsersModuleApp.controller('AppUsersRemoveController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.skyId = $routeParams.skyId;
        $scope.v_errors = [];
        $scope.user = [];
        $scope.user_accounts = [];
        $scope.reason = "";
        $scope.eblSkyId = "";

        $scope.get_user = function () {
            console.log($scope.skyId);
            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/client_registration/get_user/' + $scope.skyId})
                    .success(function (data) {
                        $scope.skyId = data.data.skyId;
                        $scope.user = data.data;
                        $scope.eblSkyId = data.data.eblSkyId;
                        $scope.user_accounts = data.accounts;

                        app.hideModal();
                    })
                    .error(function (data) {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
        };

        $scope.remove_user = function ($id) {
            $scope.v_errors = [];

            if (!confirm("Do you really want to remove this user?")) {
                return false;
            }

            var iData = {
                skyId: $id,
                reason: $scope.reason,
                eblSkyId: $scope.eblSkyId
            };

            app.showModal();

            $http({method: 'POST', url: app.baseUrl + 'api/client_registration/remove_user/', data: jQuery.param(iData)})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success == false) {
                            $scope.v_errors = data.msg;
                            return false;
                        }
                        window.location = app.baseUrl + "client_registration/index";
                    })
                    .error(function (data) {
                        app.hideModal();
                        alert("There was a problem, please try again.");
                    });

            return false;
        };

        $scope.init = function () {
            if ($scope.skyId > 0) {
                $scope.get_user();
            }
        };

        $scope.init();

    }]);

app.addModules("AppUsersModuleApp", "AppUsersModuleApp");
