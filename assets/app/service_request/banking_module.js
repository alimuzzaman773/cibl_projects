var BankingModuleApp = angular.module("BankingModuleApp", ["angularUtils.directives.dirPagination"]);

BankingModuleApp.controller("BankingController", ["$scope", "$http", function ($scope, $http) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.banking_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 20;
        $scope.currentPageNumber = 1;

        $scope.searchParams = {
            type_code: ""
        };

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                type_code: ""
            };

            $scope.filterTypeCode = '';

            $scope.getResultsPage(1);
            return false;
        };

        $scope.getResultsPage = function (pageNumber) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            if ($scope.searchParams.type_code !== null
                    && $.trim($scope.searchParams.type_code) !== "") {
                $params.type_code = $scope.searchParams.type_code;
            }

            app.showModal();
            $http({
                method: "get",
                url: app.baseUrl + "banking_service_request/get_requests_ajax",
                params: $params
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                $scope.banking_list = $result.banking_list;
                $scope.totalCount = $result.total;
                $scope.currentPageNumber = pageNumber;
                $scope.pagination.current = $scope.currentPageNumber;
            }, function (response) {
                app.hideModal();
                alert("There was a problem, please try again later");
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

        $scope.getRequest = function () {
            app.showModal();
            var $params = {
                type_code: $scope.parent_code
            };
            $http({
                method: "get",
                url: app.baseUrl + "banking_service_request/get_child_service",
                params: $params
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                $scope.child_list = $result.child_list;
            }, function (response) {
                app.hideModal();
                alert("There was a problem, please try again later");
                $scope.loading = false;
            });
        };

        $scope.getServiceRequest = function () {
            $scope.searchParams.type_code = $scope.type_code;
            $scope.getResultsPage(1);
        };

        $scope.activateLimitPackage = function ($serviceId) {
            if (!confirm('Do you really want to activate this package of the user? Before Activating please make sure: you have fixed the limit in the internet banking for this user')) {
                return fasle;
            }

            app.showModal();
            var $params = {
                service_id: $serviceId
            };
            $http({
                method: "post",
                url: app.baseUrl + "banking_service_request/activate_limit_package",
                data: jQuery.param($params),
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                //$scope.child_list = $result.child_list;
                if ($result.success == false) {
                    alert($result.msg);
                    return false;
                }

                alert("Limit package successfully activated for this user");
            }, function (response) {
                app.hideModal();
                alert("There was a problem, please try again later");
            });
            return false;
        }

        $scope.showPackage = function ($serviceId) {
            $("#lp-" + $serviceId).slideToggle();
            return false;
        };

        $scope.filterTypeCode = '';
        $scope.init = function () {
            console.log('init banking request module');
            $scope.filterTypeCode = app.filterTypeCode;

            $scope.getRequest();

            if ($scope.filterTypeCode != '') {
                $scope.searchParams.type_code = $scope.filterTypeCode;
                $scope.type_code = $scope.filterTypeCode;
                //$scope.getResultsPage(1);                
            }
            $scope.getResultsPage(1);
        };

        $scope.init();

    }]);

app.addModules("BankingModuleApp", "BankingModuleApp");