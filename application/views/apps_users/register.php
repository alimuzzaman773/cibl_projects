<h2 class="title-underlined">Apps User Registration</h2>
<form class="form" id="form" method="get" action="">
    <div class="form-group">
        <label>Account Number</label>
        <input type="text" value="" id="account_number" name="account_number" class="form-control" />
    </div>
    <div class="form-group">
        <button class="btn btn-primary" onclick="return app.search();">
            <i class="glyphicon glyphicon-search"></i> Search
        </button>
    </div>    
    <div id="response">
    </div>    
</form>
<script>
    var app = app || {};
    
    app.search = function(){
        app.showModal();
        
        var $data = {
            account_number : $("#account_number").val()
        };
        
        $.ajax({
            type : 'get',
            url : app.baseUrl + 'apps_users/get_customer_info',
            data : $data,
            dataType : 'json',
            success : function(data){
                app.hideModal();
                console.log(data);
                if(data.success){
                    $("#response").html(data.view);
                    return;
                }
                alert(data.msg);
                
            },
            error : function(data){
                app.hideModal();
                alert("There was a problem. Please try again later");
                console.table(data);
            }
        });
        return false;
    };
    
    app.confirmRegistration = function(){
        if(!confirm('Do you really want to confirm this registration?')){
            return false;
        }
        app.showModal();
        
        var $data = {
            account_number : $("#account_number").val()
        };
        
        $.ajax({
            type : 'post',
            url : app.baseUrl + 'apps_users/confirm_registration',
            data : $data,
            dataType : 'json',
            success : function(data){
                app.hideModal();
                console.log(data);
                if(data.success){
                    alert("Registration is complete");
                    return;
                }
                alert(data.msg);
                
            },
            error : function(data){
                app.hideModal();
                alert("There was a problem. Please try again later");
                console.table(data);
            }
        });
        return false;
    };

</script>

