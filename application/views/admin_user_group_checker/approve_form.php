<div class="container">
    <h3 class="title title-underlined">Approve/Reject Admin User Group</h3>
    <div class="row" id="showGroups" style="margin-top: 30px;">
        <div class="col-md-12 col-xs-12 col-sm-12">
            <form method="post" style="" id="adminUserGroupApproveOrReject" name="adminUserGroupApproveOrReject" action="<?php echo base_url(); ?>admin_user_group_checker/getReason">
                <label>User Group Name </label>
                <input class="textbox" type="text" name="groupName" id="groupName" value="<?= $userGroup['userGroupName'] ?>" readonly>
                <input hidden type="text" name="userGroupId" id="userGroupId" value="<?= $userGroup['userGroupId'] ?>">
                <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                <input hidden type="text" name="checkerAction" id="checkerAction" value="" >
                <br><br>
                <table width="100" border="0" cellpadding="2">
                    <tr>
                        <td width="100"><button class="btn btn-success" onclick="writeReason(); return false;">Write New Reason</button></td>
                        <td width="100"><button class="btn btn-success" onclick="cancelReason(); return false;">Cancel Reason</button></td>
                    </tr>
                </table>
                <br><br>
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-12 col-sm-12">
            <h3 class="title-underlined">Previous Info</h3>
            <table style="width:100%;" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Admin User Group</th>
                        <th>Maker Action</th>
                        <th>Lock/Unlock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        if (!empty($ugInfo)):
                            ?>

                            <td><?= $ugInfo->userGroupName ?></td>
                            <td><?= $ugInfo->makerAction ?></td>
                            <td>
                                <?php
                                if ($ugInfo->isLocked == 1):
                                    echo '<span class="text-danger">Locked</span>';
                                else:
                                    echo '<span class="text-success">Unlocked</span>';
                                endif;
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($ugInfo->mcStatus == 1):
                                    echo '<span class="text-success">Approved</span>';
                                else:
                                    echo '<span class="text-danger">Wait for approve</span>';
                                endif;
                                ?>
                            </td>

                            <?php
                        else:
                            echo '<td colspan="4">No data found!</td>';
                        endif;
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-12">
            <h3 class="title-underlined">New Info</h3>
            <table style="width:100%;" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Admin User Group</th>
                        <th>Maker Action</th>
                        <th>Lock/Unlock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $userGroup['userGroupName'] ?></td>
                        <td><?= $userGroup['makerAction'] ?></td>
                        <td>
                            <?php
                            if ($userGroup['isLocked'] == 1):
                                echo '<span class="text-danger">Locked</span>';
                            else:
                                echo '<span class="text-success">Unlocked</span>';
                            endif;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($userGroup['mcStatus'] == 1):
                                echo '<span class="text-success">Approved</span>';
                            else:
                                echo '<span class="text-danger">Wait for approve</span>';
                            endif;
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-12 col-sm-12">
            <h3 class="title-underlined">Previous Permissions</h3>
            <div style="overflow-y: scroll;max-height: 300px;">
                <table style="width:100%;" class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td class="td form-inline">
                                <?php
                                //d($ugPermission);
                                if (!empty($ugPermission)):
                                    $sl = 1;
                                    foreach ($permissions['category'] as $k => $p):
                                        ?>
                                        <h6 class="title-underlined hidden" style="font-size: 14px;font-weight: bold;clear:both;margin-top:10px"><?= $k ?></h6>
                                        <?php
                                        foreach ($permissions['category'][$k] as $v):
                                            //        $checked = (isset($v->name)) ? "checked" : "";
                                            if (isset($ugPermission[$v->permissionId])):
                                                ?>
                                                <div class="form-group col-xs-12 col-md-6 col-sm-6">
                                                    <label>
                                                        <?= ($sl++) . '. ' . $v->name ?> 
                                                    </label>                
                                                    <small><i><?= $v->description ?></i></small>
                                                </div>    
                                                <?php
                                            endif;
                                        endforeach;
                                    endforeach;
                                else:
                                    echo "No data found!";
                                endif;
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-12">
            <h3 class="title-underlined">New Permissions</h3>
            <div style="overflow-y: scroll;max-height: 300px;">
                <table style="width:100%;" class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td class="td form-inline">
                                <?php
                                if (!empty($existingPermission)):
                                    $sl = 1;
                                    foreach ($permissions['category'] as $k => $p):
                                        ?>
                                        <h6 class="title-underlined hidden" style="font-size: 14px;font-weight: bold;clear:both;margin-top:10px"><?= $k ?></h6>
                                        <?php
                                        foreach ($permissions['category'][$k] as $v):
                                            //        $checked = (isset($v->name)) ? "checked" : "";
                                            if (isset($existingPermission[$v->permissionId])):
                                                //$checked = (isset($existingPermission[$v->permissionId])) ? "checked" : "";
                                                ?>
                                                <div class="form-group col-xs-12 col-md-6 col-sm-6">
                                                    <label>
                                                        <?= ($sl++) . '. ' . $v->name ?> 
                                                    </label>                
                                                    <small><i><?= $v->description ?></i></small>
                                                </div>    
                                                <?php
                                            endif;
                                        endforeach;
                                    endforeach;
                                else:
                                    echo "No data found!";
                                endif;
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 30px;">
        <div class="col-md-12 col-xs-12 col-sm-12">
            <div class="form-group">
                <button class="btn btn-success" onclick="approve(); return false;">Approve</button>
                <a href="<?php echo base_url(); ?>admin_user_group_checker" class="btn btn-warning"><i class="icon-remove"></i><span>Back</span></a>
                <button class="btn btn-danger" onclick="reject(); return false;">Reject</button>
            </div>
        </div>
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