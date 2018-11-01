var ProductModuleApp = angular.module("ProductModuleApp", ["angularUtils.directives.dirPagination"]);

ProductModuleApp.controller("ProductController", ["$scope", "$http", function ($scope, $http) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.product_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 10;
        $scope.currentPageNumber = 1;

        $scope.searchParams = {
            product_id: 0,
            customer_name: "",
            mobile_no: "",
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
                product_id: 0,
                customer_name: "",
                mobile_no: "",
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

            if ($scope.searchParams.product_id !== null
                    && parseInt($scope.searchParams.product_id) > 0) {
                $params.product_id = $scope.searchParams.product_id;
            }

            if ($scope.searchParams.customer_name !== null
                    && $.trim($scope.searchParams.customer_name) !== "") {
                 $params.customer_name = $scope.searchParams.customer_name;
            }

            if ($scope.searchParams.mobile_no !== null
                    && $.trim($scope.searchParams.mobile_no) !== "") {
                 $params.mobile_no = $scope.searchParams.mobile_no;
            }

            if (($scope.searchParams.from_date !== null
                    && $.trim($scope.searchParams.from_date) !== "") &&
                    ($scope.searchParams.to_date !== null
                            && $.trim($scope.searchParams.to_date) !== "")) {
                $params.from_date = $scope.searchParams.from_date;
                $params.to_date = $scope.searchParams.to_date;
            }

            app.showModal();
            $http({
                method: "get",
                url: app.baseUrl + "product_request_process/get_requests_ajax",
                params: $params
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                $scope.product_list = $result.product_list;
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

app.addModules("ProductModuleApp", "ProductModuleApp");