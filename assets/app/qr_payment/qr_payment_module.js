var QrModuleApp = angular.module("QrModuleApp", ['ngRoute', 'ui.autocomplete', 'ui.date', 'ngSanitize', 'angularUtils.directives.dirPagination', 'ui.date']);

QrModuleApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider
                .when('/transaction_list', {
                    templateUrl: app.baseUrl + 'qr_payment/transaction_list',
                    controller: 'TransactionController'
                })
                .otherwise({
                    redirectTo: '/transaction_list'
                });
    }
]);

QrModuleApp.controller('TransactionController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.transaction_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 12;
        $scope.currentPageNumber = 1;
        $scope.tid = $routeParams.tid;
        $scope.transaction_info = {};


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
            search: '',
            status: 'Y',
            from_date: '',
            to_date: '',
            from_account: '',
            batch_number: '',
            payment_status: ''
        };

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                search: '',
                status: '',
                from_date: '',
                to_date: '',
                from_account: '',
                batch_number: '',
                payment_status: ''
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

            if ($scope.searchParams.payment_status != null
                    && $.trim($scope.searchParams.payment_status) != '') {
                $params.payment_status = $scope.searchParams.payment_status;
            }

            if ($scope.searchParams.search != null
                    && $.trim($scope.searchParams.search) != '') {
                $params.search = $scope.searchParams.search;
            }

            if ($scope.searchParams.from_date != null
                    && $.trim($scope.searchParams.from_date) != '') {
                $params.fromdate = $scope.searchParams.from_date;
            }

            if ($scope.searchParams.to_date != null
                    && $.trim($scope.searchParams.to_date) != '') {
                $params.todate = $scope.searchParams.to_date;
            }

            if ($scope.searchParams.from_account != null
                    && $.trim($scope.searchParams.from_account) != '') {
                $params.from_account = $scope.searchParams.from_account;
            }

            if ($scope.searchParams.batch_number != null
                    && $.trim($scope.searchParams.batch_number) != '') {
                $params.batch_number = $scope.searchParams.batch_number;
            }

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/qr_payment/get_transaction_list', params: $params})
                    .then(function (response) {
                        app.hideModal();
                        var $result = response.data;
                        $scope.transaction_list = $result.transaction_list;
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


        $scope.sendTrnReference = function (transactionId)
        {
            if (!confirm("Do you really want to send transaction id this transaction?")) {
                return false;
            }

            var $params = {
                transaction_id: transactionId
            };

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/qr_payment/send_trn_reference', params: $params})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success == false) {
                            alert(data.msg);
                            return false;
                        }
                        if (confirm("Your transaction id successfully send")) {
                            window.location.href = app.baseUrl + "qr_payment/#/transaction_list";
                        }
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.upper_range = function () {
            var range = $scope.per_page * $scope.currentPageNumber;
            if (range > $scope.totalCount) {
                return $scope.totalCount;
            }
            return range;
        };

        $scope.createPaymentModal = function ($qrPaymentId) {
            $scope.qrPayementId = $qrPaymentId;
            $('#paymentModal').modal('show');
            return false;
        };

        $scope.confirmQrPayment = function ($qrPaymentId) {

            if (!confirm("Do you really want to paid this amount?")) {
                return false;
            }

            var $params = {
                payment_id: $qrPaymentId,
                remarks: $scope.payment_remarks
            };
            
            app.showModal();
            $http({method: 'post', data: jQuery.param($params), url: app.baseUrl + 'api/qr_payment/confirm_payment/'})
                    .success(function (data) {
                        app.hideModal();
                        if (!data.success) {
                            alert(data.msg);
                            return false;
                        }
                        alert("Transaction process succesfully completed.");
                        $('#paymentModal').modal('hide');
                        window.location.href = app.baseUrl + "qr_payment/"; 
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };


        $scope.init = function () {
            $scope.getResultsPage(1);
        };
        $scope.init();
    }]);

app.addModules("QrModuleApp", "QrModuleApp");