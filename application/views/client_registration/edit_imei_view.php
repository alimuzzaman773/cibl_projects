<h1 class="title-underlined">Apps User</h1>
<div class="clearfix table-responsive">
    <div class="alert alert-info" id="message" style="display:none"><?php echo $message ?></div>

    <form method="post" style="" id="imeiEditForm" name="imeiEditForm" action="<?php echo base_url(); ?>client_registration/updateDevice">
        <input hidden type="text" name="skyId" id="skyId" value="<?= $skyId ?>" size="20" />
        <input hidden type="text" name="deviceId" id="deviceId" value="<?= $deviceId ?>" size="20" />
        <input hidden type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>" size="20" />

        <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>">
            <h3>Reject Reason</h3>
            <?=$checkerActionComment?>        
        </div>

        <table class="table table-bordered table-bordered table-striped">
            <tr>
                <th style="width:200px;">ESB ID</th>
                <td><input type="text" class="form-control" name="eblSkyId" id="eblSkyId" value="<?= $eblSkyId ?>" size="20" readonly /></td>                
            </tr>
            <tr>
                <th>IMEI No.</th>
                <td><input type="text" class="form-control" name="imeiNo" id="imeiNo" maxlength="15" value="<?= $imeiNo ?>"/></td>
            </tr>

            <tr>
                <th>&nbsp;</th>
                <td>
                    <button class="btn btn-primary" type="button" onclick="return app.saveImei();">
                        Update
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
           url : app.baseUrl+'client_registration/updateDevice',
           type: 'post',
           data : $("#imeiEditForm").serialize(),
           dataType : 'json',
           success : function(data){
               app.hideModal();
               if(data.success == false){
                   console.log(data);
                   $("#message").html(data.msg);
                   $("#message").fadeIn();
                   return false;
               }
               
               alert("IMEI NO saved successfully");
               $("#reasonDiv").hide();
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

    $(document).ready(function () {
        $('#backButton').click(function () {
            var skyId = $('#skyId').val();
            var eblSkyId = $('#eblSkyId').val();
            window.location = "<?php echo base_url(); ?>client_registration/deviceInfo?skyId=" + skyId + "&eblSkyId=" + eblSkyId;
        });
    });

</script>