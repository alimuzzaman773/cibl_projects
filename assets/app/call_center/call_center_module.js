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
        $scope.totalCount = 0;
        $scope.per_page = 7;
        $scope.currentPageNumber = 1;
        $scope.uid = $routeParams.uid;
        $scope.user = {};
        
        $scope.resetSkyId = null;
        $scope.otp_channel_pin = 'sms';

        $scope.pin_sending_url = null;
        $scope.showResetModal = function($skyId, $type) {
            $scope.resetSkyId = $skyId;
            if($type =='pin_send'){
                $scope.pin_sending_url = 'confirm_password_reset';
            }
            else if($type =='pin_resend'){
                $scope.pin_sending_url = 'resend_user_pin';
            }
                    
            $('#resetModal').modal('show');
            return false;
        };

        $scope.pagination = {
            current: 1
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

            app.showModal();
            $http({method: 'get', url: app.baseUrl + 'api/call_center/user_list', params: $params})
                    .then(function (response) {
                        app.hideModal();
                        var $result = response.data;
                        $scope.user_list = $result.user_list;
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
            console.log("asdasdas");
           if(!confirm("Do you really want to activate this user for maker action?")){
                return false;
            }
            
            app.showModal();
            $http({method: 'post', url: app.baseUrl + 'api/call_center/user_approve_checker/' + userId})
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
            if(!confirm("Do you really want to activate this user?")){
                return false;
            }
            
            var $pData = {
                otp_channel : jQuery("#otp_channel").val()
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
        
        $scope.sendPasswordResetPin = function($skyId){
            if($scope.pin_sending_url == null){
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
                data: jQuery.param({'skyId' : $skyId, otp_channel : $scope.otp_channel_pin}), 
                url: app.baseUrl + 'api/call_center/'+$scope.pin_sending_url})
            .success(function (data) {                
                app.hideModal();
                $('#resetModal').modal('hide');
                if (data.success === false) {
                    alert(data.msg);
                    return false;
                }
                
                $scope.pin_sending_url = null;
                alert("Password pin has been sent");
                window.location.href = app.baseUrl + "call_center/#/user_list/";
                
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
            }
            else{
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

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.getResultsPage = function (pageNumber, childId) {
            var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

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