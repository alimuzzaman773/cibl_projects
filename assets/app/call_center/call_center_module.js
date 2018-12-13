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
                .otherwise({
                    redirectTo: '/user_list'
                });
    }
]);

CallCenterModuleApp.controller('CallCenterController', ['$scope', '$http', '$routeParams', function ($scope, $http, $routeParams) {
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.user_list = [];
        $scope.totalCount = 0;
        $scope.per_page = 20;
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
        
        $scope.searchParams = {
            filter_by: "",
            search : ''
        };
        
        $scope.resetSearch = function () {
            $scope.searchParams = {
                filter_by: "",
                search : ''
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
            
            if($scope.searchParams.filter_by != '')
            {
                $params.filter_by = $scope.searchParams.filter_by;
            }

            if($scope.searchParams.search != '')
            {
                $params.search = $scope.searchParams.search;
            }
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

        $scope.user = {
            appsGroupId : 0
        };
        $scope.otp_channel = 'email';
        $scope.approveUser = function (userId) 
        {
            console.log($scope.user.appsGroupId);
            if($scope.user.appsGroupId <= 0){
                //alert("Please select a limit package");
                //return false;
            }
            
            if(!confirm("Do you really want to activate this user?")){
                return false;
            }
            
            var $pData = {
                otp_channel : jQuery("#otp_channel").val(),
                appsGroupId : $scope.user.appsGroupId
            };
            
            app.showModal();
            $http({method: 'post', url: app.baseUrl + 'api/call_center/user_approve/' + userId, data: jQuery.param($pData)})
            .success(function (data) {
                app.hideModal();
                if (data.success == false) {
                    alert(data.msg);
                    return false;
                }
                window.location.href = app.baseUrl + "client_registration/update_limit_package/" + $scope.user.skyId;
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
        
        $scope.appsGroupId = 0;
        $scope.appsGroupList = [];

        $scope.init = function () {
            $scope.appsGroupList = app.appsUserGroups;
            $scope.getResultsPage(1);
            if ($routeParams.uid !== undefined) {
                $scope.get_user_info();
            }
        };
        $scope.init();
    }]);

app.addModules("CallCenterModuleApp", "CallCenterModuleApp");