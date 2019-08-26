var CallCenterModuleApp = angular.module("CallCenterModuleApp", ['ngRoute', 'ui.autocomplete', 'ui.date', 'ngSanitize', 'angularUtils.directives.dirPagination', 'ui.date']);

CallCenterModuleApp.config(['$routeProvider',
    function ($routeProvider) {
        $routeProvider
                .when('/user_list', {
                    templateUrl: app.baseUrl + 'call_center/user_list',
                    controller: 'CallCenterController'
                })
                .when('/user_approve/:uid?', {
                    templateUrl: app.baseUrl + 'call_center/user_approve',
                    controller: 'CallCenterController'
                })
                .when('/confirmation/:uid?', {
                    templateUrl: app.baseUrl + 'call_center/confirmation',
                    controller: 'CallCenterController'
                })
                .when('/request_account', {
                    templateUrl: app.baseUrl + 'call_center/request_account',
                    controller: 'RequestAccountController'
                })
                .when('/account_details/:uid?', {
                    templateUrl: app.baseUrl + 'call_center/account_details',
                    controller: 'RequestAccountController'
                })
                .when('/remove/:skyId?', {
                    templateUrl: function ($routeParams) {
                        return app.baseUrl + 'call_center/remove_user/' + $routeParams.skyId;
                    },
                    controller: 'AppUsersRemoveController'
                })
                .otherwise({
                    redirectTo: '/user_list'
                });
    }
]);

CallCenterModuleApp.controller('CallCenterController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.user_list = [];
        $scope.branch_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 10;
        $scope.currentPageNumber = 1;
        $scope.uid = $routeParams.uid;
        $scope.eblSkyId = '';
/*        $scope.isOwnAccTransfer = "1";
        $scope.isInterAccTransfer = "1";
        $scope.isOtherAccTransfer = "1";
        $scope.isAccToCardTransfer = "1";
        $scope.isCardToAccTransfer = "1";
        $scope.isUtilityTransfer = "1";
        $scope.isQrPayment = "1";*/
        $scope.user = {};
        $scope.trOptions = {}

        $scope.resetSkyId = null;
        $scope.otp_channel_pin = 'sms';

        $scope.pin_sending_url = null;
        $scope.showResetModal = function ($skyId, $type) {
            $scope.resetSkyId = $skyId;
            if ($type == 'pin_send') {
                $scope.pin_sending_url = 'confirm_password_reset';
            } else if ($type == 'pin_resend') {
                $scope.pin_sending_url = 'resend_user_pin';
            }

            $('#resetModal').modal('show');
            return false;
        };

        $scope.searchParams = {
            from_date: "",
            to_date: "",
            search: '',
            status: '',
            branch: '',
            is_regester: '',
            password_reset: ''
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
                search: '',
                status: '',
                branch: '',
                is_regester: '',
                password_reset: ''
            };

            $scope.getResultsPage(1);
            return false;
        };

        $scope.getResultsPage = function (pageNumber, childId) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            if ($scope.searchParams.search !== null && $.trim($scope.searchParams.search) != '') {
                $params.search = $scope.searchParams.search;
            }

            if ($scope.searchParams.branch !== null && $.trim($scope.searchParams.branch) != '') {
                $params.branch = $scope.searchParams.branch;
            }

            if ($scope.searchParams.status !== null && $.trim($scope.searchParams.status) != '') {
                $params.status = $scope.searchParams.status;
            }

            if ($scope.searchParams.is_regester !== null && $.trim($scope.searchParams.is_regester) != '') {
                $params.is_regester = $scope.searchParams.is_regester;
            }

            if ($scope.searchParams.password_reset !== null && $.trim($scope.searchParams.password_reset) != '') {
                $params.password_reset = $scope.searchParams.password_reset;
            }

            if (($scope.searchParams.from_date !== null
                    && $.trim($scope.searchParams.from_date) !== "") &&
                    ($scope.searchParams.to_date !== null
                            && $.trim($scope.searchParams.to_date) !== "")) {
                $params.from_date = $scope.searchParams.from_date;
                $params.to_date = $scope.searchParams.to_date;
            }

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/user_list', params: $params})
                    .then(function (response) {
                        app.hideModal();
                        var $result = response.data;
                        $scope.user_list = $result.user_list;
                        $scope.branch_list = $result.branch_list;
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

        $scope.user_accounts = [];
        $scope.user_cards = [];
        $scope.get_user_info = function ()
        {
            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/get_user_info/' + $scope.uid})
                    .success(function (data) {
                        $scope.user = data.user_info;
                        $scope.user_accounts = data.user_accounts;
                        $scope.eblSkyId = data.user_info.eblSkyId;
                        $scope.trOptions.isOwnAccTransfer = app.parseInt(data.user_info.isOwnAccTransfer);
                        $scope.trOptions.isInterAccTransfer = app.parseInt(data.user_info.isInterAccTransfer);
                        $scope.trOptions.isOtherAccTransfer = app.parseInt(data.user_info.isOtherAccTransfer);
                        $scope.trOptions.isAccToCardTransfer = app.parseInt(data.user_info.isAccToCardTransfer);
                        $scope.trOptions.isCardToAccTransfer = app.parseInt(data.user_info.isCardToAccTransfer);
                        $scope.trOptions.isUtilityTransfer = app.parseInt(data.user_info.isUtilityTransfer);
                        $scope.trOptions.isQrPayment = app.parseInt(data.user_info.isQrPayment);
                        console.log(data.user_info);
                        app.hideModal();
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.approveUserChecker = function (userId)
        {
            if (!confirm("Do you really want to activate this user for maker action?")) {
                return false;
            }
            
            var $pData = {
                isOwnAccTransfer: $scope.trOptions.isOwnAccTransfer,
                isInterAccTransfer: $scope.trOptions.isInterAccTransfer,
                isOtherAccTransfer: $scope.trOptions.isOtherAccTransfer,
                isAccToCardTransfer: $scope.trOptions.isAccToCardTransfer,
                isCardToAccTransfer: $scope.trOptions.isCardToAccTransfer,
                isUtilityTransfer: $scope.trOptions.isUtilityTransfer,
                isQrPayment: $scope.trOptions.isQrPayment
            };

            app.showModal();
            $http({method: 'post', url: app.baseUrl + 'api/call_center/user_approve_checker/' + userId, data: jQuery.param($pData)})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success == false) {
                            alert(data.msg);
                            return false;
                        }
                        window.location.href = app.baseUrl + "call_center/#/user_list";
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.otp_channel = 'email';
        $scope.approveUser = function (userId)
        {
            if (!confirm("Do you really want to activate this user?")) {
                return false;
            }

            var $pData = {
                otp_channel: jQuery("#otp_channel").val(),
            };

            app.showModal();
            $http({method: 'post', url: app.baseUrl + 'api/call_center/user_approve/' + userId, data: jQuery.param($pData)})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success == false) {
                            alert(data.msg);
                            return false;
                        }
                        window.location.href = app.baseUrl + "call_center/#/user_list";
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.approvedConfirmation = function () {
            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/approve_confirmation/' + $scope.uid})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success === false) {
                            alert(data.msg);
                            return false;
                        }
                        if (confirm("Successfully approved!")) {
                            window.location.href = app.baseUrl + "call_center/#/user_list/";
                        }
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.approveStatusMessage = function (userInfo) {

            var msg = "Approved";
            if (userInfo.makerActionBy > 0 && userInfo.checkerActionBy <= 0) {
                msg = "Waiting for approval";
            }
            if (userInfo.isRejected > 0) {
                msg = "Rejected";
            }
            if (userInfo.isRejected === '0' && userInfo.isPublished === '1' && userInfo.passwordReset === '1') {
                msg = "Password Reset Request";
            }
            return msg;
        };

        $scope.sendPasswordResetPin = function ($skyId) {
            if ($scope.pin_sending_url == null) {
                alert('PIN sending URL is not defined');
                return false;
            }

            if (!confirm("Do you really want to send the new password pin?")) {
                $('#resetModal').modal('hide');
                return false;
            }
            $scope.resetSkyId = null;
            $('#resetModal').modal('hide');
            app.showModal();
            $http({
                method: 'post',
                data: jQuery.param({'skyId': $skyId, otp_channel: $scope.otp_channel_pin}),
                url: app.baseUrl + 'api/call_center/' + $scope.pin_sending_url})
                    .success(function (data) {
                        app.hideModal();
                        $('#resetModal').modal('hide');
                        if (data.success === false) {
                            alert(data.msg);
                            return false;
                        }

                        $scope.pin_sending_url = null;
                        alert("Successfully PIN sent to user");
                        window.location.href = app.baseUrl + "call_center";
                    })
                    .error(function () {
                        app.hideModal();
                        $('#resetModal').modal('hide');
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.rejectRequest = function (userId)
        {
            if (!confirm("Do you really want to reject this user?")) {
                return false;
            }

            var $remarks = prompt("Please provide a rejection reason");
            if ($remarks == null || $.trim($remarks) == '') {
                alert("Please provide a rejection reason");
                return false;
            }

            var $pData = {
                skyId: userId,
                remarks: $remarks
            };

            app.showModal();
            $http({method: 'post', url: app.baseUrl + 'api/call_center/reject_user/', data: jQuery.param($pData)})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success == false) {
                            alert(data.msg);
                            return false;
                        }
                        alert("User has been rejected successfully");
                        window.location.href = app.baseUrl + "call_center";
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.init = function () {
            if ($routeParams.uid !== undefined) {
                $scope.get_user_info();
            } else {
                $scope.getResultsPage(1);
            }
        };
        $scope.init();
    }]);

CallCenterModuleApp.controller('RequestAccountController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.account_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 10;
        $scope.currentPageNumber = 1;
        $scope.uid = $routeParams.uid;
        $scope.user = {};

        $scope.pagination = {
            current: 1
        };

        $scope.searchParams = {
            from_date: "",
            to_date: "",
            search: '',
            approved_status: '',
            request_type: ''
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                from_date: "",
                to_date: "",
                search: '',
                approved_status: '',
                request_type: ''
            };

            $scope.getResultsPage(1);
            return false;
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.getResultsPage = function (pageNumber, childId) {
            
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            if ($scope.searchParams.search !== null && $.trim($scope.searchParams.search) != '') {
                $params.search = $scope.searchParams.search;
            }

            if ($scope.searchParams.approved_status !== null && $.trim($scope.searchParams.approved_status) != '') {
                $params.approved_status = $scope.searchParams.approved_status;
            }

            if ($scope.searchParams.request_type !== null && $.trim($scope.searchParams.request_type) != '') {
                $params.request_type = $scope.searchParams.request_type;
            }

            if (($scope.searchParams.from_date !== null
                    && $.trim($scope.searchParams.from_date) !== "") &&
                    ($scope.searchParams.to_date !== null
                            && $.trim($scope.searchParams.to_date) !== "")) {
                $params.from_date = $scope.searchParams.from_date;
                $params.to_date = $scope.searchParams.to_date;
            }

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/request_account_list', params: $params})
                    .then(function (response) {
                        app.hideModal();
                        var $result = response.data;
                        $scope.account_list = $result.account_list;
                        $scope.totalCount = $result.total;
                        $scope.currentPageNumber = pageNumber;
                        $scope.pagination.current = $scope.currentPageNumber;
                    }, function () {
                        app.hideModal();
                        alert("There was a problem, please try again later")
                        $scope.loading = false;
                    });
        };

        $scope.user = [];
        $scope.user_accounts = {};
        $scope.user_cards = {};
        $scope.get_request_account_info = function ()
        {
            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/get_request_account_info/' + $scope.uid})
                    .success(function (data) {
                        $scope.user = data.user_info;
                        $scope.user_accounts = data.user_accounts;
                        $scope.user_cards = data.user_cards;
                        app.hideModal();
                    })
                    .error(function () {
                        app.hideModal();
                        alert("There was a problem, please try again.")
                    });
            return false;
        };

        $scope.accountApprove = function (userId) {
            if (!confirm("Do you really want to activate this user for account?")) {
                return false;
            }

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/account_approve/' + userId})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success === false) {
                            alert(data.msg);
                            return false;
                        }
                        if (confirm("Successfully approved!")) {
                            window.location.href = app.baseUrl + "call_center/#/request_account/";
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

        $scope.init = function () {
            if ($routeParams.uid !== undefined) {
                $scope.get_request_account_info();
            } else {
                $scope.getResultsPage(1);
            }
        };
        $scope.init();
    }]);

CallCenterModuleApp.controller('AppUsersRemoveController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
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

            $http({method: 'POST', url: app.baseUrl + 'api/call_center/remove_user/', data: jQuery.param(iData)})
                    .success(function (data) {
                        app.hideModal();
                        if (data.success == false) {
                            $scope.v_errors = data.msg;
                            return false;
                        }
                        window.location = app.baseUrl + "call_center/#/user_list";
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

app.addModules("CallCenterModuleApp", "CallCenterModuleApp");