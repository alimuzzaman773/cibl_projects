var ProductModuleApp = angular.module("ProductModuleApp", ["angularUtils.directives.dirPagination"]);

ProductModuleApp.controller("ProductController", ["$scope", "$http", function ($scope, $http) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.product_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 10;
        $scope.currentPageNumber = 1;
        $scope.productNames = [];

        $scope.searchParams = {
            product_name: "",
            from_date: "",
            to_date: "",
            search: "",
            status: ""
        };

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                product_name: "",
                from_date: "",
                to_date: "",
                search: "",
                status: ""
            };

            $scope.getResultsPage(1);
            return false;
        };

        $scope.getProductNames = function() {
            $http({
                method: 'get',
                url: app.baseUrl + "product_request_process/get_requests_ajax",
                params: {get_product_names: 'true'}
            }).then(function(response){
                $scope.productNames = response.data.product_list;
                console.log($scope.productNames);
            })
        }

        $scope.getResultsPage = function (pageNumber) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };
            
            if (($scope.searchParams.from_date !== null
                    && $.trim($scope.searchParams.from_date) !== "") &&
                    ($scope.searchParams.to_date !== null
                            && $.trim($scope.searchParams.to_date) !== "")) {
                $params.from_date = $scope.searchParams.from_date;
                $params.to_date = $scope.searchParams.to_date;
            }

            if($scope.searchParams.search !== null && $.trim($scope.searchParams.search) !== ""){
                $params.search = $scope.searchParams.search;
            }

            if($scope.searchParams.product_name !== null && $.trim($scope.searchParams.product_name) !== ""){
                $params.product_name = $scope.searchParams.product_name;
            }
          
            if ($scope.searchParams.status !== null
                    && $.trim($scope.searchParams.status) !== "") {
                $params.status = $scope.searchParams.status;
            }
            
            /*if ($scope.searchParams.customer_mobile !== null
                    && $.trim($scope.searchParams.customer_mobile) !== "") {
                $params.customer_mobile = $scope.searchParams.customer_mobile;
            }

            if ($scope.searchParams.customer_email !== null
                    && $.trim($scope.searchParams.customer_email) !== "") {
                $params.customer_email = $scope.searchParams.customer_email;
            }*/
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
            $scope.getProductNames();
            $scope.getResultsPage(1);
        };

        $scope.init();

    }]);

app.addModules("ProductModuleApp", "ProductModuleApp");