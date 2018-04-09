<title>Admin User Group</title>
<div class="breadcrum">Edit Module</div>

<div class="container" style="margin-top:50px">

    <div class="alert alert-success"><?php echo $message ?></div>   

    <form method="post" style="" id="packageSelection" name="packageSelection" action="<?php echo base_url(); ?>admin_user_group_maker/editAction">
    <div class="container" style="margin-top:50px">

    <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

        <label>Admin Group Name</label>
        <input class="textbox" type="text" name="groupName" id="groupName" value="<?= $adminGroup['userGroupName'] ?>" required><br><br>
        <input hidden type="text" name="userGroupId" id="userGroupId" value="<?= $userGroupId ?>">

        <?php foreach ($modules as $item) { ?>
            <input id="<?= $item->moduleId ?>" style="margin-right: 15px" type="checkbox" name="moduleIds[]" value="<?= $item->moduleId ?>"><label> <?= $item->moduleName ?> </label><br>
        <?php } ?>



        <br><br><br>
        <h4>Authorization Modules<h4>
        <?php foreach ($authorizationModules as $index => $value) { ?>
            <input id="<?= "AM" . $index ?>" style="margin-right: 15px" type="checkbox" name="authorizationModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
        <?php } ?>
        <br><br>



        <br><br><br>
        <h4>Content Setup<h4>
        <?php foreach ($contentSetupModules as $index => $value) { ?>
            <input id="<?= "CMS" . $index ?>" style="margin-right: 15px" type="checkbox" name="contentSetupModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
        <?php } ?>
        <br><br>


        <br><br><br>
        <h4>Service Request<h4>
        <?php foreach ($serviceRequestModules as $index => $value) { ?>
            <input id="<?= "SR" . $index ?>" style="margin-right: 15px" type="checkbox" name="serviceRequestModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
        <?php } ?>
        <br><br>


        <br><br><br>
        <h4>Report Type Modules<h4>
        <?php foreach ($reportTypeModules as $index => $value) { ?>
        <input id="<?= "RTM" . $index ?>" style="margin-right: 15px" type="checkbox" name="reportTypeModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
        <?php } ?>
        <br><br>




        <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
            <h3>Reject Reason<h3>
            <textarea name="reason" id="reason" cols="40" rows="5" readonly></textarea>
            <br><br>
        </div>


        <button type="submit" class="btn btn-success">Next</button>
        <a href="<?php echo base_url(); ?>admin_user_group_maker" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back</span></a>

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
    var moduleIds = jQuery.parseJSON('<?= $moduleIds ?>');//data for building initial table
    moduleIds.forEach(function(entry) {
        document.getElementById(entry.moduleId).checked = true;
    });
    document.getElementById("reason").value = "<?php echo $checkerActionComment ?>";
</script>

<script type="text/javascript">
    var authorizationModuleCodes = <?= $authorizationModuleCodes ?>;//data for building initial table
    if(authorizationModuleCodes != ""){
        authorizationModuleCodes.forEach(function(entry) {
            document.getElementById("AM" + entry).checked = true;
        });
    }
</script>


<script type="text/javascript">
    var contentSetupModulesCodes = <?= $contentSetupModulesCodes ?>;//data for building initial table
    if(contentSetupModulesCodes != ""){
        contentSetupModulesCodes.forEach(function(entry) {
            document.getElementById("CMS" + entry).checked = true;
        });
    }
</script>


<script type="text/javascript">
    var serviceRequestModuleCodes = <?= $serviceRequestModuleCodes ?>;//data for building initial table
    if(serviceRequestModuleCodes != ""){
        serviceRequestModuleCodes.forEach(function(entry) {
            document.getElementById("SR" + entry).checked = true;
        });
    }
</script>


<script type="text/javascript">
 
var reportTypeModuleCodes = <?= $reportTypeModuleCodes ?>;//data for building initial table
    if(reportTypeModuleCodes != ""){
        reportTypeModuleCodes.forEach(function(entry) {
            document.getElementById("RTM" + entry).checked = true;
        });
    }

</script>