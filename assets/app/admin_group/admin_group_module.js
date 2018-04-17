var app = app || {};
var AdminGroupModule = angular.module('AdminGroupModule',[])
.controller('AdminGroupController',['$scope','$http',function($scope, $http){
        $scope.biller_data = [];
        
        $scope.activate = function($billerId){
            var $postBillerId = [];
            if($billerId == undefined){
                angular.forEach($scope.biller_data,function(v,i){
                    if(v.isChecked == true){
                        $postBillerId.push(v.billerId);
                    }
                });
            }
            else if(app.parseInt($billerId) > 0) {
                $postBillerId.push($billerId);
            }
            
            if($postBillerId.length <= 0){
                alert('Please select a biller id for activating');
                return false;
            }
            app.showModal();
            var dataToSave = {"billerId" : $postBillerId.join("|"), "selectedActionName" : 'Active'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "biller_setup_maker/billerActive",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected Billers are active");
                        window.location = app.baseUrl+ "biller_setup_maker";
                    }
                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = app.baseUrl+"biller_setup_maker";
                    }
                },
                error: function(error) {
                    app.hideModal();
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };
        
        $scope.deactivate = function($billerId){
            var $postBillerId = [];
            if($billerId == undefined){
                angular.forEach($scope.biller_data,function(v,i){
                    if(v.isChecked == true){
                        $postBillerId.push(v.billerId);
                    }
                });
            }
            else if(app.parseInt($billerId) > 0) {
                $postBillerId.push($billerId);
            }
            
            if($postBillerId.length <= 0){
                alert('Please select a biller id for deactivating');
                return false;
            }
            
            app.showModal();
            
            var dataToSave = {"billerId" : $postBillerId.join("|"), "selectedActionName" : 'Inactive'};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: app.baseUrl + "biller_setup_maker/billerInactive",
                success: function(data) {
                    app.hideModal();
                    if (data == 1) {
                        alert("Selected Billers are inactive");
                        window.location = app.baseUrl+ "biller_setup_maker";
                    }
                    else if(data == 2){
                        alert("Don't try like this");
                        window.location = app.baseUrl+"biller_setup_maker";
                    }
                },
                error: function(error) {
                    app.hideModal();
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        };
       
        $scope.init = function(){
            $scope.group_data = app.groupDatas;
        };
        $scope.init();
}]);
app.addModules('AdminGroupModule', 'AdminGroupModule');
