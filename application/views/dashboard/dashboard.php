<h1 class="title-underlined">
    <i class="glyphicon glyphicon-blackboard"></i>
    Dashboard
</h1>
<div id="result">
    <div id="get_apps_registration_stats" class="row"></div>
    <div id="get_apps_user_stats" class="row"></div>
</div>
<script>
    var app = app || {};
    app.get_apps_registration_stats = function(){
        $("#get_apps_registration_stats").html('Loading content please wait...');
        $.ajax({
            url : app.baseUrl + 'api/dashboard/get_apps_registration_stats',
            type : 'get',
            dataType : 'json',
            success : function(data){
                if(data.result){
                    $("#get_apps_registration_stats").html(data.result);
                }
                else {
                    $("#get_apps_registration_stats").html('could not load content');
                }
            },
            error : function(data){
                $("#get_apps_registration_stats").html('error loading content');
                console.log(data);
            }
        });
    };
    app.get_apps_user_stats = function(){
        $("#get_apps_user_stats").html('Loading content please wait...');
        $.ajax({
            url : app.baseUrl + 'api/dashboard/get_apps_user_stats',
            type : 'get',
            dataType : 'json',
            success : function(data){
                if(data.result){
                    $("#get_apps_user_stats").html(data.result);
                }
                else {
                    $("#get_apps_user_stats").html('could not load content');
                }
            },
            error : function(data){
                $("#get_apps_user_stats").html('error loading content');
                console.log(data);
            }
        });
    };
    $(document).ready(function(){
        app.get_apps_registration_stats();
        app.get_apps_user_stats();        
    });
</script>