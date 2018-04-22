<div class="container">
    <h3 class="title">Approve/Reject Admin User Group</h3>
    <div id="showGroups">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center" >Changed Data</th>
                    <th style="text-align: center; <?= $publishDataOfDisplay_c ?>" >Published data</th>
                </tr>
            </thead>
            <tbody >
                <tr>
                    <td style="text-align:left">
                        <form method="post" style="" id="adminUserGroupApproveOrReject" name="adminUserGroupApproveOrReject" action="<?php echo base_url(); ?>admin_user_group_checker/getReason">
                            <label>User Group Name </label>
                            <input class="textbox" type="text" name="groupName" id="groupName" value="<?= $userGroup['userGroupName'] ?>" readonly>
                            <input hidden type="text" name="userGroupId" id="userGroupId" value="<?= $userGroup['userGroupId'] ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >
                            <br><br>

                            <?php
                            foreach ($moduleIds as $index => $value) {
                                $var = 0;
                                ?>
                                <div style="margin-top:50px" id="<?= $value . "dv" ?>">
                                    <?php
                                    foreach ($modulesActions as $item) {
                                        if ($value == $item['moduleId']) {
                                            if ($var == 0) {
                                                ?>
                                                <div> <input class="textbox" type="text" name="moduleCode" id="<?= $value . "mName" ?>" value="<?= $item['moduleName'] ?>" readonly> </div>
                                                <?php
                                                $var = 1;
                                            }
                                            ?>
                                            <input hidden class="textbox" type="text" name="moduleId" id="<?= $item['moduleActionId'] . "maId" ?>" value="<?= $item['moduleId'] ?>">
                                            <input hidden class="textbox" type="text" name="moduleCode" id="<?= $item['moduleActionId'] . "mCode" ?>" value="<?= $item['moduleCode'] ?>">
                                            <input hidden class="textbox" type="text" name="actionCode" id="<?= $item['moduleActionId'] . "aCode" ?>" value="<?= $item['actionCode'] ?>">
                                            <input id="<?= $item['moduleActionId'] ?>" class="actionCheckbox" style="margin-right: 15px" type="checkbox" name="actionCode[]" value="<?= $item['moduleActionId'] ?>" onclick="return false"><label> <?= $item['actionName'] ?> </label><br>
                                        <?php } ?>      
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <br><br>

                            <h4>Authorization Modules</h4>
                            <br><br>
                            <?php foreach ($authorizationModules as $index => $value) { ?>
                                <input id="<?= "AM" . $index ?>" style="margin-right: 15px" type="checkbox" name="authorizationModuleCodes[]" value="<?= $index ?>" onclick="return false"><label> <?= $value ?> </label><br>
                            <?php } ?>
                            <br><br>


                            <br><br>
                            <h4>Content Setup</h4>
                            <br><br>
                            <?php foreach ($contentSetupModules as $index => $value) { ?>
                                <input id="<?= "CMS" . $index ?>" style="margin-right: 15px" type="checkbox" name="contentSetupModuleCodes[]" value="<?= $index ?>" onclick="return false"><label> <?= $value ?> </label><br>
                            <?php } ?>
                            <br><br>


                            <br><br>
                            <h4>Service Request</h4>
                            <br><br>
                            <?php foreach ($serviceRequestModules as $index => $value) { ?>
                                <input id="<?= "SR" . $index ?>" style="margin-right: 15px" type="checkbox" name="serviceRequestModuleCodes[]" value="<?= $index ?>" onclick="return false"><label> <?= $value ?> </label><br>
                            <?php } ?>
                            <br><br>
                            <h4>Report Type Modules</h4>
                            <br><br>
                            <?php foreach ($reportTypeModules as $index => $value) { ?>
                                <input id="<?= "RTM" . $index ?>" style="margin-right: 15px" type="checkbox" name="reportTypeModuleCodes[]" value="<?= $index ?>" onclick="return false"><label> <?= $value ?> </label><br>
                            <?php } ?>
                            <br><br>
                            <table class="table table-striped table-bordered">    
                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?= $userGroup['makerAction'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?= $makerActionDtTm ?></td>
                                </tr>
                            </table>
                            <table width="100" border="0" cellpadding="2">
                                <tr>
                                    <td width="100"><button class="btn btn-success" onclick="writeReason(); return false;">Write New Reason</button></td>
                                    <td width="100"><button class="btn btn-success" onclick="cancelReason(); return false;">Cancel Reason</button></td>
                                </tr>
                            </table>
                            <div id="newReasonDiv" style="display: none" >
                                <h3 style="color:red" >New Reject Reason</h3>
                                <textarea name="newReason" id="newReason" cols="40" rows="5"></textarea>
                                <br><br>
                            </div>
                            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                                <h3 style="color:red" >Previous Reject Reason</h3>
                                <textarea name="reason" id="reason" cols="40" rows="5" readonly></textarea>
                                <br><br>
                            </div>
                        </form>
                        <table width="100" border="0" cellpadding="2">
                            <tr>
                                <td width="100"><button class="btn btn-success" onclick="approve(); return false;">Approve</button></td>
                                <td><a href="<?php echo base_url(); ?>admin_user_group_checker" class="btn btn-warning"><i class="icon-remove"></i><span>Back</span></a></td>
                                <td width="100"><button class="btn btn-danger" onclick="reject(); return false;">Reject</button></td>
                            </tr>
                        </table>
                    </td> 

                    <td style="text-align:left; <?= $publishDataOfDisplay_c ?>">

                        <input class="textbox" type="text" name="groupName_c" id="groupName_c" value="<?= $userGroup['userGroupName_c'] ?>" readonly>
                        <br><br>
                        <?php
                        foreach ($moduleIds as $index => $value) {
                            $var = 0;
                            ?>
                            <div style="margin-top:50px" id="<?= $value . "dv_c" ?>">
                                <?php
                                foreach ($modulesActions as $item) {
                                    if ($value == $item['moduleId']) {
                                        if ($var == 0) {
                                            ?>
                                            <div> <input class="textbox" type="text" name="moduleCode_c" id="<?= $value . "mName_c" ?>" value="<?= $item['moduleName'] ?>" readonly> </div>
                                            <?php
                                            $var = 1;
                                        }
                                        ?>
                                        <input hidden class="textbox" type="text" name="moduleId_c" id="<?= $item['moduleActionId'] . "maId_c" ?>" value="<?= $item['moduleId'] ?>">
                                        <input hidden class="textbox" type="text" name="moduleCode_c" id="<?= $item['moduleActionId'] . "mCode_c" ?>" value="<?= $item['moduleCode'] ?>">
                                        <input hidden class="textbox" type="text" name="actionCode_c" id="<?= $item['moduleActionId'] . "aCode_c" ?>" value="<?= $item['actionCode'] ?>">
                                        <input id="<?= $item['moduleActionId'] . "_c" ?>" class="actionCheckbox" style="margin-right: 15px" type="checkbox" name="actionCode[]_c" value="<?= $item['moduleActionId'] ?>" onclick="return false"><label> <?= $item['actionName'] ?> </label><br>
                                    <?php } ?>      
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <br><br>

                        <br><br>
                        <h4>Authorization Modules</h4>
                        <br><br>
                        <?php foreach ($authorizationModules as $index => $value) { ?>
                            <input id="<?= "AM" . $index . "_c" ?>" style="margin-right: 15px" type="checkbox" name="authorizationModuleCodes_c[]" value="<?= $index ?>" onclick="return false"><label> <?= $value ?> </label><br>
                        <?php } ?>

                        <br><br>
                        <h4>Content Setup</h4>
                        <br><br>
                        <?php foreach ($contentSetupModules as $index => $value) { ?>
                            <input id="<?= "CMS" . $index . "_c" ?>" style="margin-right: 15px" type="checkbox" name="contentSetupModuleCodes_c[]" value="<?= $index ?>" onclick="return false"><label> <?= $value ?> </label><br>
                        <?php } ?>
                        <br><br>
                        <h4>Service Request</h4>
                        <br><br>
                        <?php foreach ($serviceRequestModules as $index => $value) { ?>
                            <input id="<?= "SR" . $index . "_c" ?>" style="margin-right: 15px" type="checkbox" name="serviceRequestModuleCodes_c[]" value="<?= $index ?>" onclick="return false"><label> <?= $value ?> </label><br>
                        <?php } ?>
                        <br><br>
                        <h4>Report Type Modules</h4>
                        <br><br>
                        <?php foreach ($reportTypeModules as $index => $value) { ?>
                            <input id="<?= "RTM" . $index . "_c" ?>" style="margin-right: 15px" type="checkbox" name="reportTypeModuleCodes_c[]" value="<?= $index ?>" onclick="return false"><label> <?= $value ?> </label><br>
                        <?php } ?>
                        <br><br>

                        <table class="table table-striped table-bordered">    
                            <tr>
                                <th align="left" scope="row">Maker Action</th>
                                <td><?= $userGroup['makerAction_c'] ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">Maker Action Date Time</th>
                                <td><?= $makerActionDtTm_c ?></td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </tbody>
        </table>    
    </div>
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
    var maIds = jQuery.parseJSON('<?= $moduleActionIds ?>');
    maIds.forEach(function (entry) {
        if (document.getElementById(entry)) {
            document.getElementById(entry).checked = true;
        }
    });
    document.getElementById("reason").value = "<?php echo $checkerActionComment ?>";
</script>


<script type="text/javascript">
    var maIds = jQuery.parseJSON('<?= $moduleActionIds_c ?>');
    var cntry_c = "";
    maIds.forEach(function (entry) {
        cntry_c = entry + "_c";
        if (document.getElementById(cntry_c)) {
            document.getElementById(cntry_c).checked = true;
        }
    });
    document.getElementById("reason_c").value = "<?php echo $checkerActionComment_c ?>";
</script>

<script type="text/javascript">

    document.getElementById("reason").value = "<?php echo $checkerActionComment ?>";
    document.getElementById("reason_c").value = "<?php echo $checkerActionComment ?>";

</script>

<script>
    function writeReason()
    {
        document.getElementById("newReasonDiv").style.display = "block"; // div
    }
    ;

    function cancelReason()
    {
        document.getElementById("newReasonDiv").style.display = "none"; // div
        $("#newReason").val("");
    }
    ;

</script>

<script>

    function approve()
    {
        $("#checkerAction").val("approve");
        $("#adminUserGroupApproveOrReject").submit();
    }
    ;

    function reject()
    {
        $("#checkerAction").val("reject");
        var newReason = document.getElementById("newReason").value;
        if (newReason === "" || newReason === " " || newReason === "") {
            alert("Please write new reason.");

        } else {
            $("#adminUserGroupApproveOrReject").submit();
        }
    }
    ;

</script>

<script type="text/javascript">
    var authorizationModuleCodes = jQuery.parseJSON('<?= $authorizationModuleCodes ?>');//data for building initial table
    if (authorizationModuleCodes !== "") {
        authorizationModuleCodes.forEach(function (entry) {
            document.getElementById("AM" + entry).checked = true;
        });
    }
</script>

<script type="text/javascript">
    var authorizationModuleCodes_c = jQuery.parseJSON('<?= $authorizationModuleCodes_c ?>');//data for building initial table
    var entry_c = "";
    if (authorizationModuleCodes_c !== "") {
        authorizationModuleCodes_c.forEach(function (entry) {
            entry_c = entry + "_c";
            document.getElementById("AM" + entry_c).checked = true;
        });
    }
</script>

<script type="text/javascript">
    var contentSetupModuleCodes = jQuery.parseJSON('<?= $contentSetupModuleCodes ?>');//data for building initial table
    if (contentSetupModuleCodes !== "") {
        contentSetupModuleCodes.forEach(function (entry) {
            document.getElementById("CMS" + entry).checked = true;
        });
    }
</script>

<script type="text/javascript">
    var contentSetupModuleCodes_c = jQuery.parseJSON('<?= $contentSetupModuleCodes_c ?>');//data for building initial table
    var entry_c = "";
    if (contentSetupModuleCodes_c !== "") {
        contentSetupModuleCodes_c.forEach(function (entry) {
            entry_c = entry + "_c";
            document.getElementById("CMS" + entry_c).checked = true;
        });
    }
</script>

<script type="text/javascript">
    var serviceRequestModuleCodes = jQuery.parseJSON('<?= $serviceRequestModuleCodes ?>');//data for building initial table
    if (serviceRequestModuleCodes !== "") {
        serviceRequestModuleCodes.forEach(function (entry) {
            document.getElementById("SR" + entry).checked = true;
        });
    }
</script>

<script type="text/javascript">
    var serviceRequestModuleCodes_c = jQuery.parseJSON('<?= $serviceRequestModuleCodes_c ?>');//data for building initial table
    var entry_c = "";
    if (serviceRequestModuleCodes_c !== "") {
        serviceRequestModuleCodes_c.forEach(function (entry) {
            entry_c = entry + "_c";
            document.getElementById("SR" + entry_c).checked = true;
        });
    }
</script>

<script type="text/javascript">

    var reportTypeModuleCodes = jQuery.parseJSON('<?= $reportTypeModuleCodes ?>');//data for building initial table
    if (reportTypeModuleCodes !== "") {
        reportTypeModuleCodes.forEach(function (entry) {
            document.getElementById("RTM" + entry).checked = true;
        });
    }


</script>

<script type="text/javascript">

    var reportTypeModuleCodes_c = jQuery.parseJSON('<?= $reportTypeModuleCodes_c ?>');//data for building initial table
    var entry_c = "";
    if (reportTypeModuleCodes_c !== "") {
        reportTypeModuleCodes_c.forEach(function (entry) {
            entry_c = entry + "_c";
            document.getElementById("RTM" + entry_c).checked = true;
        });
    }
</script>