var app = app || {};
var AdminUserGroupModule = angular.module('AdminUserGroupModule', ["angularUtils.directives.dirPagination"])
        .controller('AdminUserGroupController', ['$scope', '$http', function ($scope, $http) {
                $scope.data = [];
                $scope.group_list = [];
                $scope.totalCount = 0;
                $scope.per_page = 10;
                $scope.currentPageNumber = 1;

                $scope.searchParams = {
                    group_name: "",
                    lock_status: 0
                };

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

            if ($scope.searchParams.group_name !== null
                    && $.trim($scope.searchParams.group_name) !== "") {
                $params.group_name = $scope.searchParams.group_name;
            }

            if ($scope.searchParams.lock_status !== null
                    && $.trim($scope.searchParams.lock_status) !== "") {
                $params.lock_status = $scope.searchParams.lock_status;
            }

            app.showModal();
            $http({
                method: "get",
                url: app.baseUrl + "admin_user_group_maker/ajax_get_admin_group",
                params: $params
            }).then(function (response) {
                app.hideModal();
                var $result = response.data;
                $scope.group_list = $result.group_list;
                $scope.totalCount = $result.total;
                $scope.currentPageNumber = pageNumber;
                $scope.pagination.current = $scope.currentPageNumber;

            }, function (response) {
                app.hideModal();
                alert("There was a problem, please try again later");
                $scope.loading = false;
            });
        };

        $scope.resetSearch = function () {
            $scope.searchParams = {
                pbl_id: "",
                cif_id: "",
                customer_name: "",
                mobile_no: "",
                lock_status: 0
            };

            $scope.getResultsPage(1);
            return false;
        };

        $scope.upper_range = function () {
            var range = $scope.per_page * $scope.currentPageNumber;
            if (range > $scope.totalCount) {
                return $scope.totalCount;
            }
            return range;
        };


                $scope.activate = function ($id) {
                    if (!confirm("Are you sure?")) {
                        return false;
                    }

                    var $postId = [];
                    if ($id == undefined) {
                        angular.forEach($scope.data, function (v, i) {
                            if (v.isChecked == true) {
                                $postId.push(v.userGroupId);
                            }
                        });
                    } else if (app.parseInt($id) > 0) {
                        $postId.push($id);
                    }

                    if ($postId.length <= 0) {
                        alert('Please select a item for activating');
                        return false;
                    }
                    app.showModal();
                    var dataToSave = {"userGroupId": $postId.join("|"), "selectedActionName": 'Active'};
                    $.ajax({
                        type: "POST",
                        data: dataToSave,
                        url: app.baseUrl + "admin_user_group_maker/groupActive",
                        success: function (data) {
                            app.hideModal();
                            if (data == 1) {
                                alert("Selected item are active");
                                window.location = app.baseUrl + "admin_user_group_maker";
                            } else if (data == 2) {
                                alert("Don't try like this");
                                window.location = app.baseUrl + "admin_user_group_maker";
                            }
                        },
                        error: function (error) {
                            app.hideModal();
                            alert(error.status + "<--and--> " + error.statusText);
                        }
                    });
                };

                $scope.deactivate = function ($id) {
                    if (!confirm("Are you sure?")) {
                        return false;
                    }

                    var $postId = [];
                    if ($id == undefined) {
                        angular.forEach($scope.data, function (v, i) {
                            if (v.isChecked == true) {
                                $postId.push(v.userGroupId);
                            }
                        });
                    } else if (app.parseInt($id) > 0) {
                        $postId.push($id);
                    }

                    if ($postId.length <= 0) {
                        alert('Please select a item id for deactivating');
                        return false;
                    }

                    app.showModal();

                    var dataToSave = {"userGroupId": $postId.join("|"), "selectedActionName": 'Inactive'};
                    $.ajax({
                        type: "POST",
                        data: dataToSave,
                        url: app.baseUrl + "admin_user_group_maker/groupInactive",
                        success: function (data) {
                            app.hideModal();
                            if (data == 1) {
                                alert("Selected item are inactive");
                                window.location = app.baseUrl + "admin_user_group_maker";
                            } else if (data == 2) {
                                alert("Don't try like this");
                                window.location = app.baseUrl + "admin_user_group_maker";
                            }
                        },
                        error: function (error) {
                            app.hideModal();
                            alert(error.status + "<--and--> " + error.statusText);
                        }
                    });
                };

                $scope.lock = function ($id) {
                    if (!confirm("Are you sure?")) {
                        return false;
                    }

                    var $postId = [];
                    if ($id == undefined) {
                        angular.forEach($scope.data, function (v, i) {
                            if (v.isChecked == true) {
                                $postId.push(v.userGroupId);
                            }
                        });
                    } else if (app.parseInt($id) > 0) {
                        $postId.push($id);
                    }

                    if ($postId.length <= 0) {
                        alert('Please select a item for lock');
                        return false;
                    }
                    app.showModal();
                    var dataToSave = {"userGroupId": $postId.join("|"), "selectedActionName": 'Lock'};
                    $.ajax({
                        type: "POST",
                        data: dataToSave,
                        url: app.baseUrl + "admin_user_group_maker/groupLock",
                        success: function (data) {
                            app.hideModal();
                            if (data == 1) {
                                alert("Selected item is lock");
                                window.location = app.baseUrl + "admin_user_group_maker";
                            } else if (data == 2) {
                                alert("Don't try like this");
                                window.location = app.baseUrl + "admin_user_group_maker";
                            }
                        },
                        error: function (error) {
                            app.hideModal();
                            alert(error.status + "<--and--> " + error.statusText);
                        }
                    });
                };

                $scope.unlock = function ($id) {
                    if (!confirm("Are you sure?")) {
                        return false;
                    }

                    var $postId = [];
                    if ($id == undefined) {
                        angular.forEach($scope.data, function (v, i) {
                            if (v.isChecked == true) {
                                $postId.push(v.userGroupId);
                            }
                        });
                    } else if (app.parseInt($id) > 0) {
                        $postId.push($id);
                    }

                    if ($postId.length <= 0) {
                        alert('Please select a item id for unlock');
                        return false;
                    }

                    app.showModal();

                    var dataToSave = {"userGroupId": $postId.join("|"), "selectedActionName": 'Unlock'};
                    $.ajax({
                        type: "POST",
                        data: dataToSave,
                        url: app.baseUrl + "admin_user_group_maker/groupUnlock",
                        success: function (data) {
                            app.hideModal();
                            if (data == 1) {
                                alert("Selected item are unlock");
                                window.location = app.baseUrl + "admin_user_group_maker";
                            } else if (data == 2) {
                                alert("Don't try like this");
                                window.location = app.baseUrl + "admin_user_group_maker";
                            }
                        },
                        error: function (error) {
                            app.hideModal();
                            alert(error.status + "<--and--> " + error.statusText);
                        }
                    });
                };

                $scope.init = function () {
                   // $scope.data = app.adminGroups;
                    $scope.getResultsPage(1);
                };
                $scope.init();
            }]);
app.addModules('AdminUserGroupModule', 'AdminUserGroupModule');
