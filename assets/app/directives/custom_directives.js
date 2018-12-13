var app = app || {};
app.CustomDirective = {};

app.CustomDirective.groupInfo = function () {
    return {
        restrict: "E",
        scope: {
            appsGroupInfo: '=appsgroupinfo',
        },
        link: function ($scope, element, attrs) { 
            //$scope.siteInfo = $rootScope.siteInfo
        },
        templateUrl : app.baseUrl + 'assets/app/directives/templates/group_info.html'
        //template : "<b>{{siteinfo}}</b>"
    };
};

app.CustomDirective.fileModel = function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;
            
            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
};

app.CustomDirective.googleDocs = function () {
    return {
        restrict: "E",
        scope: {
            fileInfo: '=fileinformation'
        },
        link: function ($scope, element, attrs) { 
            /*element.bind('click', function () {
                console.log("directive link starts");
                console.log($scope);
                console.log(element);
                console.log(attrs);
                console.log("directive link ends");
            });*/
        },
        templateUrl : app.baseUrl + 'assets/app/directives/templates/google_docs.html'
    };
};