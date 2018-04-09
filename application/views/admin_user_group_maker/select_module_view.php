<title>Admin User Group</title>
<div class="breadcrum">Add Module</div>

<div class="container" style="margin-top:50px">

    <div class="alert alert-success"><?php echo $message ?></div>   

    <form method="post" style="" id="packageSelection" name="packageSelection" action="<?php echo base_url(); ?>admin_user_group_maker/selectAction">
    <div class="container" style="margin-top:50px">

        <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

        <label>Admin Group Name</label>
        <input class="textbox" type="text" name="groupName" id="groupName" required><br><br>

        <?php foreach ($modules as $item) { ?>
            <input style="margin-right: 15px" type="checkbox" name="moduleIds[]" value="<?= $item->moduleId ?>"><label> <?= $item->moduleName ?> </label><br>
        <?php } ?>


        <br><br>
        <label>Authorization</label>
        <br><br>
        <?php foreach ($authorizationModules as $index => $value) { ?>
            <input style="margin-right: 15px" type="checkbox" name="authorizationModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
        <?php } ?>


        <br><br>
        <label>Content Setup</label>
        <br><br>
        <?php foreach ($contentSetupModules as $index => $value) { ?>
            <input style="margin-right: 15px" type="checkbox" name="contentSetupModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
        <?php } ?>


        <br><br>
        <label>Service Request</label>
        <br><br>
        <?php foreach ($serviceRequestModules as $index => $value) { ?>
            <input style="margin-right: 15px" type="checkbox" name="serviceRequestModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
        <?php } ?>


        <br><br>
        <label>Report Type Modules</label>
        <br><br>
        <?php foreach ($reportTypeModules as $index => $value) { ?>
            <input style="margin-right: 15px" type="checkbox" name="reportTypeModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
        <?php } ?>
        <br><br>


        <br><br>
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



