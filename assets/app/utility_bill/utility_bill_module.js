var UtilityBillModuleApp = angular.module("UtilityBillModuleApp", ['ngRoute', 'ui.autocomplete', 'ui.date', 'ngSanitize', 'angularUtils.directives.dirPagination', 'ui.date']);

UtilityBillModuleApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider
                .when('/utility_list', {
                    templateUrl: app.baseUrl + 'utility_bill/utility_list',
                    controller: 'UtilityBillController'
                })
                .when('/reverse_utility/:pid?', {
                    templateUrl: app.baseUrl + 'utility_bill/reverse_utility',
                    controller: 'UtilityBillController'
                })
                .otherwise({
                    redirectTo: '/utility_list'
                });
    }
]);

UtilityBillModuleApp.controller('UtilityBillController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.bill_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 12;
        $scope.currentPageNumber = 1;


        // Date Picker
        $scope.dateOptions = {
            changeYear: true,
            changeMonth: true,
            yearRange: 'c-10:c+10',
            dateFormat: "yy-mm-dd"
        };

        $scope.showDP = function ($id) {
            $("#" + $id).datepicker($scope.dateOptions);
            $("#" + $id).datepicker('show');
        };

        $scope.searchParams = {
            utility_name: '',
            utility: '',
            status: 'Y',
            from_date: '',
            to_date: ''
        };

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                utility_name: '',
                utility: '',
                status: '',
                from_date: '',
                to_date: ''
            };

            $scope.getResultsPage(1);
            return false;
        };

        $scope.selected_item = {
            label: "",
            value: "",
            name: ""
        };

        $scope.getResultsPage = function (pageNumber) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            if ($scope.searchParams.status != null
                    && $.trim($scope.searchParams.status) != '') {
                $params.status = $scope.searchParams.status;
            }

            if ($scope.searchParams.utility != null
                    && $.trim($scope.searchParams.utility) != '') {
                $params.utility = $scope.searchParams.utility;
            }

            if ($scope.searchParams.utility_name != null
                    && $.trim($scope.searchParams.utility_name) != '') {
                $params.search = $scope.searchParams.utility_name;
            }

            if ($scope.searchParams.from_date != null
                    && $.trim($scope.searchParams.from_date) != '') {
                $params.fromdate = $scope.searchParams.from_date;
            }

            if ($scope.searchParams.to_date != null
                    && $.trim($scope.searchParams.to_date) != '') {
                $params.todate = $scope.searchParams.to_date;
            }

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/utility_bill/get_utility_bill_list/', params: $params})
                    .then(function (response) {
                        app.hideModal();
                        var $result = response.data;
                        $scope.bill_list = $result.bill_list;
                        $scope.totalCount = $result.total;
                        $scope.currentPageNumber = pageNumber;

                        $scope.pagination.current = $scope.currentPageNumber;

                    }, function (response) {
                        app.hideModal();
                        console.log(response);
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

        $scope.utilityReverse = function (paymentId) {

            var r = confirm("Are you sure to reverse this utility bill?");
            if (!r) {
                return false;
            }

            var $params = {
                payment_id: paymentId
            };

            app.showModal();
            $http({method: 'post', url: app.baseUrl + 'api/utility_bill/reverse_utility/', data: jQuery.param($params)})
                    .then(function (response) {
                        app.hideModal();

                        var res = response.data;
                        if (!res.success) {
                            alert(res.msg);
                            return true;
                        }
                        alert("Utility successfully reverse");
                        return true;
                    }, function () {
                        app.hideModal();
                        alert("There was a problem, please try again later")
                        $scope.loading = false;
                    });
        };

        $scope.init = function () {
            $scope.getResultsPage(1);
        };
        $scope.init();
    }]);

app.addModules("UtilityBillModuleApp", "UtilityBillModuleApp");