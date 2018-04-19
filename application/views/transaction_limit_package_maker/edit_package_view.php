<h1 class="title-underlined">Limit Package >> Edit</h1>
<div class="clearfix table-responsive">

    <?php if($message != ""): ?>
    <div class="alert alert-success"><?php echo $message ?></div>
    <?php endif; ?>
    
    <form method="post" style="" id="packageSelection" name="packageSelection" action="<?php echo base_url(); ?>transaction_limit_setup_maker/editGroup">
    <div class="container" style="margin-top:50px">


        <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
            <h3>Reject Reason<h3>
            <textarea name="reason" id="reason" cols="40" rows="5" readonly></textarea>
            <br><br>
        </div>



        <label>Group Name</label>
        <input class="textbox" type="text" name="groupName" id="groupName" value=""><br><br>

        <input hidden type="text" name="appsGroupId" id="appsGroupId" value="<?= $appsGroupId ?>">
        <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">


        <input style="margin-right: 15px" type="checkbox" id="own" name="packageName[]" value="1"><label> Own Account Transfer</label><br>
        <input style="margin-right: 15px" type="checkbox" id="ebl" name="packageName[]" value="2"><label> EBL Account Transfer</label><br>
        <input style="margin-right: 15px" type="checkbox" id="other" name="packageName[]" value="3"><label> Other Bank Transfer</label><br>
        <input style="margin-right: 15px" type="checkbox" id="bill" name="packageName[]" value="4"><label> Bills Pay</label><br>

        <br><br>
        <button type="submit" class="btn btn-success">Next</button>
        <a href="<?php echo base_url(); ?>transaction_limit_setup_maker" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back</span></a> 

    </div>
    </form>

</div>


<style> 
.textbox { 
    border: 1px solid #848484; 
    -webkit-border-radius: 30px; 
    -moz-border-radius: 30px; 
    border-radius: 30px; 
    outline:0;
    height:25px; 
    width: 275px; 
    padding-left:10px; 
    padding-right:10px; 
  } 
</style>


<style>

label {
    vertical-align:middle;
}

input {
    float:center;
    border: 1px solid #848484; 
    -webkit-border-radius: 30px; 
    -moz-border-radius: 30px; 
    border-radius: 30px; 
    outline:0; 
    height:25px; 
    width: 100px; 
    padding-left:10px; 
    padding-right:10px; 
}
</style>


<script type="text/javascript">

    var initialData = jQuery.parseJSON('<?= $packages ?>');//data for building initial table
    $('#groupName').val(initialData.userGroupName);
    $('#appsGroupId').val(initialData.appsGroupId);

    if(initialData.eatMinTxnLim > 0){
        document.getElementById("ebl").checked = true;
    }

    if(initialData.oatMinTxnLim > 0){
        document.getElementById("own").checked = true;
    }

    if(initialData.pbMinTxnLim > 0){
        document.getElementById("bill").checked = true;
    }

    if(initialData.obtMinTxnLim > 0){
        document.getElementById("other").checked = true;
    }

    document.getElementById("reason").value = "<?php echo $checkerActionComment ?>";

</script>



