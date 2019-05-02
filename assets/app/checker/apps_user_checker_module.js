var app = app || {};
var AppsUserCheckerModule = angular.module('AppsUserCheckerModule',["angularUtils.directives.dirPagination"])
.controller('AppsUserCheckerController',['$scope','$http',function($scope, $http){
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $scope.apps_user_data = [];
        $scope.totalCount = 0;
        $scope.per_page = 20;
        $scope.currentPageNumber = 1;
        
        $scope.searchParams = {
            from_date: "",
            to_date: "",
            eblSkyId: ""
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
                eblSkyId: ""
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

            if ($scope.searchParams.eblSkyId !== null
                    && $.trim($scope.searchParams.eblSkyId) !== "") {
                $params.eblSkyId = $scope.searchParams.eblSkyId;
            }

            $params.from_date = $scope.searchParams.from_date;
            $params.to_date = $scope.searchParams.to_date;

            $http({
            	method: "get",
                url: app.baseUrl + "client_registration_checker/get_unapproved_users_list",
                params: $params
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                $scope.apps_user_data = $result.list;
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
