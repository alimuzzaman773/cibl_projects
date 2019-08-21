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
        $scope.removed_account = [];

        //---SUBMIT BUTTON----
        $scope.saveItems = function () {
            $scope.v_errors = [];
            var iData = {
                skyId: $scope.skyId,
                cfId: $scope.cfId,
                clientId: $scope.clientId,
                prepaidId: $scope.prepaidId,
                userName: $scope.userName,
                userEmail: $scope.userEmail,
                userMobNo1: $scope.userMobNo1,
                userMobNo2: $scope.userMobNo2,
                currAddress: $scope.currAddress,
                parmAddress: $scope.parmAddress,
                billingAddress: $scope.billingAddress,
                isOwnAccTransfer: $scope.isOwnAccTransfer,
                isEnterAccTransfer: $scope.isEnterAccTransfer,
                isOtherAccTransfer: $scope.isOtherAccTransfer,
                isAccToCardTransfer: $scope.isAccToCardTransfer,
                isCardToAccTransfer: $scope.isCardToAccTransfer,
                isUtilityTransfer: $scope.isUtilityTransfer,
                isQrPayment: $scope.isQrPayment,
                dataDelete: $.parseJSON(angular.toJson($scope.removed_account)) //$scope.removed_account.toString()
            };

            app.showModal();

            $http({method: 'POST', url: app.baseUrl + 'api/client_registration/update_user/', data: jQuery.param(iData)})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success == false) {
                            $scope.v_errors = data.msg;
                            return false;
                        }

                        if (data.success == true) {
                            alert("Data has been saved successfully!");
                            $("#form_data")[0].reset();
                            window.location = app.baseUrl + "client_registration/index";
                            return false;
                        }

                        alert(data.msg);
                        return;


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
                        $scope.prepaidId = data.data.prepaidId;
                        $scope.userName = data.data.userName;
                        $scope.userEmail = data.data.userEmail;
                        $scope.userMobNo1 = data.data.userMobNo1;
                        $scope.userMobNo2 = data.data.userMobNo2;
                        $scope.currAddress = data.data.currAddress;
                        $scope.parmAddress = data.data.parmAddress;
                        $scope.billingAddress = data.data.billingAddress;
                        $scope.isOwnAccTransfer = app.parseInt(data.data.isOwnAccTransfer);
                        $scope.isEnterAccTransfer = app.parseInt(data.data.isEnterAccTransfer);
                        $scope.isOtherAccTransfer = app.parseInt(data.data.isOtherAccTransfer);
                        $scope.isAccToCardTransfer = app.parseInt(data.data.isAccToCardTransfer);
                        $scope.isCardToAccTransfer = app.parseInt(data.data.isCardToAccTransfer);
                        $scope.isUtilityTransfer = app.parseInt(data.data.isUtilityTransfer);
                        $scope.isQrPayment = app.parseInt(data.data.isQrPayment);

                        $scope.user_accounts = data.accounts;

                        app.hideModal();
                    })
                    .error(function (data) {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
        };

        $scope.remove_ac = function ($id, $val, $index) {
            if ($val > 0) {
                $scope.removed_account.push($id)
            } else {
                $scope.removed_account.splice($index, 1);
                return false;
            }
        };

        $scope.select_all = 1;
        $scope.check_all = function () {
            angular.forEach($scope.removed_account, function (v, i) {
                v.include = $scope.select_all;
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
