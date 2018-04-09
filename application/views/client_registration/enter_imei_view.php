<title>Apps User</title>
<div class="breadcrum">Add Device</div>
<div class="container" style="margin-top:50px">

    <div class="alert alert-success"><?php echo $message ?></div>

    <form method="post" style="" id="imeiAddForm" name="imeiAddForm" action="<?php echo base_url(); ?>client_registration/insertDevice">
        <input hidden type="text" name="skyId" id="skyId" value="<?=$skyId ?>" size="20" />
        <input hidden type="text" name="selectedActionName" id="selectedActionName" value="<?=$selectedActionName ?>" size="20" />

        <fieldset>
            <table width="500" border="0" cellpadding="5">
                <tr>
                    <th width="213" align="left" scope="row">ESB ID</th>
                    <td width="161"><input type="text" name="eblSkyId" id="eblSkyId" value="<?=$eblSkyId ?>" size="20" readonly /></td>
                    <td width="161"><input hidden type="text" name="skyId" id="skyId" value="<?=$skyId ?>" size="20" readonly /></td>
                </tr>

  
                <tr>
                    <th align="left" scope="row">IMEI No.</th>
                    <td><input type="text" name="imeiNo" id="imeiNo" maxlength="15"/></td>
                </tr>
                
                <tr>
                    <th align="left" scope="row">&nbsp;</th>
                    <td></td>
                </tr>

            </table>

        </fieldset>


<table width="50" border="0" cellpadding="2">
    <tr>
        <td width="100"><input type="button" value="Add" class="btn btn-success" id="addButton"/></td>
        <td width="100"><input type="button" value="Back" class="btn btn-success" id="backButton"/></td>
    </tr>
</table>



    </form>

</div>


<script>

  $(document).ready(function(){
    $('#addButton').click(function(){
       var imeiNo = $('#imeiNo').val();
       if(imeiNo.match(/\s|\./g)){
        alert("IMEI field can't contain space");
       }
       else if(imeiNo === null || imeiNo === ""){
        alert("IMEI field can't be empty");
       }
       /*
	else if(isNaN(imeiNo)){
        alert("Only numbers are allowed for IMEI");
       }
	*/
       else{
        $('#imeiAddForm').submit()
       }
    });
  });

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