var PriorityModuleApp = angular.module("PriorityModuleApp", ["angularUtils.directives.dirPagination"]);

PriorityModuleApp.controller("PriorityController", ["$scope", "$http", function ($scope, $http) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.priority_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 10;
        $scope.currentPageNumber = 1;

        $scope.searchParams = {
            typeCode: "",
            appsId: "",
            referenceNo: "",
            customerName: "",
            mobileNo: ""
        };

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                typeCode: "",
                appsId: "",
                referenceNo: "",
                customerName: "",
                mobileNo: ""
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
                $params.typeCode = $scope.searchParams.type_code;
            }

            if ($scope.searchParams.apps_id !== null
                    && $.trim($scope.searchParams.apps_id) !== "") {
                $params.appsId = $scope.searchParams.apps_id;
            }

            if ($scope.searchParams.reference_no !== null
                    && $.trim($scope.searchParams.reference_no) !== "") {
                $params.referenceNo = $scope.searchParams.reference_no;
            }

            if ($scope.searchParams.customer_name !== null
                    && $.trim($scope.searchParams.customer_name) !== "") {
                $params.customerName = $scope.searchParams.customer_name;
            }

            if ($scope.searchParams.mobile_no !== null
                    && $.trim($scope.searchParams.mobile_no) !== "") {
                $params.mobileNo = $scope.searchParams.mobile_no;
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

        $scope.init = function () {
            $scope.getResultsPage(1);
        };

        $scope.init();

    }]);

app.addModules("PriorityModuleApp", "PriorityModuleApp");