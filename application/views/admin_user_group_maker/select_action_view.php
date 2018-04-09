<title>Admin User Group</title>
<div class="breadcrum">Add Action</div>

<div class="container" style="margin-top:50px">

    <form method="post" style="" id="actionSelection" name="actionSelection" action="<?php echo base_url(); ?>admin_user_group_maker/createAdminUserGroup">

    <label>User Group Name </label>
    <input class="textbox" type="text" name="groupName" id="groupName" value="<?= $groupName ?>" readonly>
    <input hidden class="textbox" type="text" name="moduleCodes" id="moduleCodes" value="">
    <input hidden class="textbox" type="text" name="actionCodes" id="actionCodes" value="">
    <input hidden class="textbox" type="text" name="moduleActionId" id="moduleActionId" value="">
    <input hidden class="textbox" type="text" name="authorizationModuleCodes" id="authorizationModuleCodes" value="<?= $authorizationModuleCodes ?>">
    <input hidden class="textbox" type="text" name="contentSetupModuleCodes" id="contentSetupModuleCodes" value="<?= $contentSetupModuleCodes ?>">
    <input hidden class="textbox" type="text" name="serviceRequestModuleCodes" id="serviceRequestModuleCodes" value="<?= $serviceRequestModuleCodes ?>">
    <input hidden class="textbox" type="text" name="reportTypeModuleCodes" id="reportTypeModuleCodes" value="<?= $reportTypeModuleCodes ?>">
    <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">


    <?php foreach ($moduleIds as $index => $value) { $var = 0; ?>
        <div class="container" style="margin-top:50px" id="<?= $value. "dv" ?>">
            <?php foreach ($modulesActions as $item) {
                if($value == $item['moduleId']){ 
                    if($var == 0){ ?>
                        <div> <input class="textbox" type="text" name="moduleCode" id="<?= $value."mName" ?>" value="<?= $item['moduleName'] ?>" readonly> </div>
                   <?php $var = 1; }?>
                    <input hidden class="textbox" type="text" name="moduleId" id="<?= $item['moduleActionId'] . "maId" ?>" value="<?= $item['moduleId'] ?>">
                    <input hidden class="textbox" type="text" name="moduleCode" id="<?= $item['moduleActionId'] . "mCode" ?>" value="<?= $item['moduleCode'] ?>">
                    <input hidden class="textbox" type="text" name="actionCode" id="<?= $item['moduleActionId'] . "aCode" ?>" value="<?= $item['actionCode'] ?>">
                    <input class="actionCheckbox" style="margin-right: 15px" type="checkbox" name="actionCode[]" value="<?= $item['moduleActionId'] ?>"><label> <?= $item['actionName'] ?> </label><br>
             <?php } ?>      
            <?php } ?>
        </div>
    <?php } ?>
    <br><br>    
    </form>

    <button class="btn btn-success" onclick="getModuleid();">Next</button>
    <a href="<?php echo base_url(); ?>admin_user_group_maker/selectModule" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back</span></a> 
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


    var obj = <?php echo json_encode($moduleIds); ?>

    function getModuleid()
    {
        var moduleId = "";
        var selectMid = "";
        var moduleCode ="";
        var actionCode = "";
        var moduleActionId = "";

        $(".actionCheckbox:checked").each(function() {

            moduleActionId += "," + $(this).val();
            var id = $(this).val();
            var m = $("#"+id+"maId").val();
            if(selectMid != m){
                moduleId += $("#"+id+"maId").val() + "|";
                selectMid = m;
                moduleCode += $("#"+id+"mCode").val() + "|";
                actionCode += "#" + $("#"+id+"aCode").val();
            }

            else{
                actionCode += "," + $("#"+id+"aCode").val();
            }
        });

        actionCode = actionCode.substring(1);
        moduleActionId = moduleActionId.substring(1);
        moduleCode = moduleCode.substring(0, moduleCode.length - 1);
        var sap = (actionCode.match(/#/g) || []).length;
        var moduleNumber = obj.length;

        if(sap === moduleNumber - 1){

            $("#moduleCodes").val(moduleCode);
            $("#actionCodes").val(actionCode);
            $("#moduleActionId").val(moduleActionId);
            $("#actionSelection").submit();
        }

        else{
            alert("At least one action must be selected from each module");
        }
    };


</script>



