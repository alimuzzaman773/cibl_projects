var app = app || {};
var AppsUserCheckerModule = angular.module('AppsUserCheckerModule',["angularUtils.directives.dirPagination"])
.controller('AppsUserCheckerController',['$scope','$http',function($scope, $http){
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.apps_user_data = [];
        $scope.totalCount = 0;
        $scope.per_page = 20;
        $scope.currentPageNumber = 1;
        
        $scope.searchParams = {
            isLocked: "",
            isActive: "",
            search: "",
            trOptions: '',
            viewOnlyBool: '1'
        };

        $scope.pagination = {
            current: 1
        };

        $scope.pageChanged = function (newPage) {
            $scope.getResultsPage(newPage);
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                isLocked: "",
                isActive: "",
                search: "",
                trOptions: '',
                viewOnlyBool: '1'
            };
            $scope.getResultsPage(1);
            return false;
        }

        $scope.getResultsPage = function(pageNumber) {
        	app.showModal()
        	var $params = {
                limit: $scope.per_page,
                offset: $scope.per_page * (pageNumber - 1),
                get_count: true
            };

            if ($scope.searchParams.search !== null
                    && $.trim($scope.searchParams.search) !== "") {
                $params.search = $scope.searchParams.search;
            }

            if ($scope.searchParams.isLocked !== null
                && $.trim($scope.searchParams.isLocked) !== "") {
                $params.isLocked = $scope.searchParams.isLocked;
            }

            if ($scope.searchParams.isActive !== null
                && $.trim($scope.searchParams.isActive) !== "") {
                $params.isActive = $scope.searchParams.isActive;
            }

            if ($scope.searchParams.trOptions !== null 
                && $.trim($scope.searchParams.trOptions) !== "") {
                $params.trOptions = $scope.searchParams.trOptions;
            }

            if ($scope.searchParams.viewOnlyBool !== null 
                && $.trim($scope.searchParams.viewOnlyBool) !== "") {
                $params.viewOnlyBool = $scope.searchParams.viewOnlyBool;
            }

            $http({
            	method: "get",
                url: app.baseUrl + "client_registration_checker/get_unapproved_users_list",
                params: $params
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                $scope.apps_user_data = $result.list;
                console.log($scope.apps_user_data);
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

        $scope.init = function(){
            $scope.getResultsPage(1);
        };
        $scope.init();
}]);
app.addModules('AppsUserCheckerModule', 'AppsUserCheckerModule');
