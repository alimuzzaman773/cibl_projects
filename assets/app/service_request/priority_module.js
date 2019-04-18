var PriorityModuleApp = angular.module("PriorityModuleApp", ["angularUtils.directives.dirPagination"]);

PriorityModuleApp.controller("PriorityController", ["$scope", "$http", function ($scope, $http) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.priority_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 10;
        $scope.currentPageNumber = 1;

        $scope.searchParams = {
            search: ""
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
                to_date: "",
                search: ""
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

            if ($scope.searchParams.type_code !== null
                    && $.trim($scope.searchParams.type_code) !== "") {
                $params.type_code = $scope.searchParams.type_code;
            }

            if($scope.searchParams.search !== null && $.trim($scope.searchParams.search) !== ""){
                $params.search = $scope.searchParams.search;
            }

            app.showModal();
            $http({
                method: "get",
                url: app.baseUrl + "priority_request_process/get_requests_ajax",
                params: $params
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                $scope.priority_list = $result.priority_list;
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

        $scope.getTypeRequest = function () {
            $scope.searchParams.type_code = $scope.type_code;
            $scope.getResultsPage(1);
        };

        $scope.init = function () {
            $scope.getResultsPage(1);
        };

        $scope.init();

    }]);

app.addModules("PriorityModuleApp", "PriorityModuleApp");