var app = app || {};

var PinRequestModule = angular.module('PinRequestModule', ['angularUtils.directives.dirPagination']);

PinRequestModule.controller("PinRequestController", ["$scope", "$http", function ($scope, $http) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.data_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 10;
        $scope.currentPageNumber = 1;

        $scope.searchParams = {
            from_date: "",
            to_date: ""
        };

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                from_date: "",
                to_date: ""
            };

            $scope.getResultsPage(1);
            return false;
        };

        $scope.getResultsPage = function (pageNumber) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            app.showModal();
            $http({
                method: "get",
                url: app.baseUrl + "pin_generation/get_pin_request_list",
                params: $params
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                $scope.data_list = $result.data_list;
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

        $scope.init = function () {
            $scope.getResultsPage(1);
        };

        $scope.init();

    }]);

app.addModules("PinRequestModule", "PinRequestModule");