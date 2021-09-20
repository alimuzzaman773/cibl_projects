<h2 class="title-underlined">Merchant Accounts</h2>
<div class="row">
    <div class="col-md-12 col-sm-12" id="MerchantModule" data-ng-controller="MerchantController as MerchantController">
        <?= $output ?>        
    </div>
</div>

<script>
    var app = app || {};
    app.sessions = <?=trim($sessions) == '' ? '[]' : $sessions?>;
    app.terms = <?=trim($terms) == '' ? '[]' : $terms?>; 
    $(document).ready(function () {
        if ($('#field-merchantCategory option:selected').val() !== "school") {
            $('#schoolSession_field_box').hide();
            $('#schoolTerms_field_box').hide();
        }
        $('#field-merchantCategory').change(function () {
            var val = $('#field-merchantCategory option:selected').val();
            if (val === "school") {
                $('#schoolSession_field_box').show();
                $('#schoolTerms_field_box').show();

            } else {
                $('#schoolSession_field_box').hide();
                $('#schoolTerms_field_box').hide();
            }
        });
        
        var MerchantModule = angular.module("MerchantModule", []);
        MerchantModule.controller("MerchantController", ["$scope", "$http", function ($scope, $http) {
            $scope.sessions = [];
            $scope.addSession = function(){
                $scope.sessions.push({sessionId : '', sessionName: ''});
                return false;
            };
            
            $scope.removeSession = function($index){
                $scope.sessions.splice($index, 1);
                return false;
            };
            
            $scope.addTerm = function(){
                $scope.terms.push({sessionId : '', termId: '', termName : ''});
                return false;
            };
            
            $scope.removeTerm = function($index){
                $scope.terms.splice($index, 1);
                return false;
            };
            
            $scope.init = function () {
                console.log("mechant controller initialized");
                $scope.sessions = app.sessions;
                $scope.terms = app.terms;
            };
            $scope.init();

        }]);

        app.addModules("MerchantModule","MerchantModule");
        
    });
    
</script>