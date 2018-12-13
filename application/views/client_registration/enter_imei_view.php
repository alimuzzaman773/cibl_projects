<h1 class="title-underlined">Apps User</h1>
<div class="clearfix table-responsive">
    <div class="alert alert-info" style="display:none" id="message"><?php echo $message ?></div>
    <form method="post" style="" id="imeiAddForm" name="imeiAddForm" action="<?php echo base_url(); ?>client_registration/insertDevice">
        <input hidden type="text" name="skyId" id="skyId" value="<?=$skyId ?>" size="20" />
        <input hidden type="text" name="selectedActionName" id="selectedActionName" value="<?=@$selectedActionName ?>" size="20" />
        <input type="hidden" name="skyId" id="skyId" value="<?=$skyId ?>" size="20" readonly />
        
        <table class="table table-bordered table-striped">
            <tr>
                <th style="width:200px;" align="left" scope="row">ESB ID</th>
                <td><input class="form-control" type="text" name="eblSkyId" id="eblSkyId" value="<?=$eblSkyId ?>" size="20" readonly /></td>                    
            </tr>
            <tr>
                <th align="left" scope="row">IMEI No.</th>
                <td><input class="form-control" type="text" name="imeiNo" id="imeiNo" maxlength="15"/></td>
            </tr>
            <tr>
                <th align="left" scope="row">&nbsp;</th>
                <td>
                    <button class="btn btn-primary" type="button" onclick="return app.saveImei();">
                        Add
                    </button>
                    <input type="button" value="Back" class="btn btn-success" id="backButton"/>
                </td>
            </tr>
        </table>
    </form>
</div>


<script>
    var app = app || {};
    
    app.saveImei = function()
    {
        var result = app.checkImei();
        if(!result.success){
            $("#message").html(result.msg);
            $("#message").fadeIn();
            return false;
        }
        
        $("#message").html("");
        $("#message").hide();
        app.showModal();
        $.ajax({
           url : app.baseUrl+'client_registration/insertDevice',
           type: 'post',
           data : $("#imeiAddForm").serialize(),
           dataType : 'json',
           success : function(data){
               app.hideModal();
               if(data.success == false){
                   console.log(data);
                   $("#message").html(data.msg);
                   $("#message").fadeIn();
                   return false;
               }
               
               alert("IMEI NO added successfully");
               $("#imeiAddForm")[0].reset();
           },
           error : function(data){
               app.hideModal();
               alert("There was a problem, please try again later.");
           }
        });
        return false;
    };
    
    app.checkImei = function(){
        var imeiNo = $('#imeiNo').val();
        if(imeiNo.match(/\s|\./g)){
            return {
                success : false,
                msg : "IMEI field cannot contain spaces"
            }
        }
        else if(imeiNo === null || imeiNo === ""){
            return {
                success : false,
                msg : 'IMEI field cannot be empty'
            }
        }
        return {
            success: true
        }
    };
  
</script>



<script>

  $(document).ready(function(){
    $('#backButton').click(function(){
       var skyId = $('#skyId').val();
       var eblSkyId = $('#eblSkyId').val();
       window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
    });
  });

</script>