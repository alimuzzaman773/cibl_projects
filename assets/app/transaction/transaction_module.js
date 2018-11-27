var TransactionModuleApp = angular.module("TransactionModuleApp", ['ngRoute', 'ui.autocomplete', 'ui.date', 'ngSanitize', 'angularUtils.directives.dirPagination', 'ui.date']);

TransactionModuleApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider
                .when('/transaction_list', {
                    templateUrl: app.baseUrl + 'transaction/transaction_list',
                    controller: 'TransactionController'
                })
                .otherwise({
                    redirectTo: '/transaction_list'
                });
    }
]);

TransactionModuleApp.controller('TransactionController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.transaction_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 12;
        $scope.currentPageNumber = 1;


        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.getResultsPage = function (pageNumber) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/transaction/transaction_list', params: $params})
                    .then(function (response) {
                        app.hideModal();
                        var $result = response.data;
                        $scope.transaction_list = $result.transaction_list;
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

        $scope.transactionReverse = function (trnId) {

            var r = confirm("Are you sure to reverse this transaction?");
            if (!r) {
                return false;
            }

            var $params = {
                transaction_id: trnId
            };

            app.showModal();
            $http({method: 'post', url: app.baseUrl + 'api/transaction/reverse_transaction/', data: jQuery.param($params)})
                    .then(function (response) {
                        var res=response.data;
                        app.hideModal();
                        if (!res.success) {
                            alert(res.msg);
                            return true;
                        }
                        console.log(res);
                        alert("Successfully reverse the transaction batch No: "+res.batch_no);
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

app.addModules("TransactionModuleApp", "TransactionModuleApp");