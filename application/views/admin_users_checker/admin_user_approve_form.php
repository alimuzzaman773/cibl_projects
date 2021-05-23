<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                <?= $pageTitle ?>
                <a href="<?php echo base_url(); ?>admin_users_checker" class="btn btn-primary btn-xs pull-right">
                    <i class="fa fa-backward"></i> Back to Admin User Checker List
                </a>
            </h3>
        </div>
    </div>
</div>

<div class="container">
    <div id="showUsers">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <th style="text-align: center; color: red">Changed Data</th>
                    <th style="text-align: center; <?= $publishDataModeOfDisplay_c ?>; color: green" >Published Data</th>
                </tr>
            </thead>
            <tbody >
                <tr>
                    <td style="text-align:left">

                        <form method="post" style="" id="adminUserApproveOrReject" name="adminUserApproveOrReject" action="<?php echo base_url(); ?>admin_users_checker/getReason">

                            <input hidden type="text" name="adminUserId" id="adminUserId" value="<?= $adminUser['adminUserId'] ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >

                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th align="left" scope="row">Full Name</th>
                                    <td><?php echo text_match($adminUser['fullName'], $adminUser['fullName_c']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">User ID</th>
                                    <td><?php echo text_match($adminUser['adminUserName'], $adminUser['adminUserName_c']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">AD User Name</th>
                                    <td><?php echo text_match($adminUser['adUserName'], $adminUser['adUserName_c']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">User Group Name</th>
                                    <td><?php echo text_match($adminUser['mcAdminUserGroupName'], $adminUser['mainAdminUserGroupName']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Email</th>
                                    <td><?php echo text_match($adminUser['email'], $adminUser['email_c']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Active/Inactive</th>
                                    <td><?php echo text_match($isActive, $isActive_c) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Lock/Unlock</th>
                                    <td><?php echo text_match($isLocked, $isLocked_c) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?php echo text_match($adminUser['makerAction'], $adminUser['makerAction_c']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?php echo text_match($makerActionDtTm, $makerActionDtTm_c) ?></td>
                                </tr>
                            </table>
                            <table width="100" border="0" cellpadding="2">
                                <tr>
                                    <td width="100"><button class="btn btn-success" onclick="writeReason(); return false;">Write New Reason</button></td>
                                    <td width="100"><button class="btn btn-success" onclick="cancelReason(); return false;">Cancel Reason</button></td>
                                </tr>
                            </table>
                            <br>
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
                                <td width="100"><button class="btn btn-success" onclick="approve();">Approve</button></td>
                                <td><a href="<?php echo base_url(); ?>admin_users_checker" class="btn btn-warning"><i class="icon-remove"></i><span>Back</span></a></td>
                                <td width="100"><button class="btn btn-danger" onclick="reject();">Reject</button></td>
                            </tr>
                        </table>
                    </td> 

                    <td style="text-align:left; <?= $publishDataModeOfDisplay_c ?>">

                        <table class="table table-striped table-bordered">


                            <tr>
                                <th align="left" scope="row">Full Name</th>
                                <td><?= $adminUser['fullName_c'] ?></td>
                            </tr>


                            <tr>
                                <th align="left" scope="row">User ID</th>
                                <td><?= $adminUser['adminUserName_c'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">AD User Name</th>
                                <td><?= $adminUser['adUserName_c'] ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">User Group Name</th>
                                <td><?= $adminUser['mainAdminUserGroupName'] ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">Email</th>
                                <td><?= $adminUser['email_c'] ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">Active/Inactive</th>
                                <td><?= $isActive_c ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">Lock/Unlock</th>
                                <td><?= $isLocked_c ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">Maker Action</th>
                                <td><?= $adminUser['makerAction_c'] ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">Maker Action Date Time</th>
                                <td><?= $makerActionDtTm_c ?></td>
                            </tr>   
                        </table>

                        <div id="reasonDiv_c" style="<?= $reasonModeOfDisplay_c ?>" >
                            <h3 style="color:red" >Reject Reason</h3>
                            <textarea name="reason_c" id="reason_c" cols="40" rows="5" readonly></textarea>
                            <br><br>
                        </div>

                        <table width="100" border="0" cellpadding="2">
                            <tr>
                                <td width="100"></td>
                                <td width="100"></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>    
    </div>
</div>

<script type="text/javascript">

    document.getElementById("reason").value = "<?php echo $checkerActionComment ?>";
    document.getElementById("reason_c").value = "<?php echo $checkerActionComment ?>";

    function writeReason()
    {
        document.getElementById("newReasonDiv").style.display = "block"; // div
    }

    function cancelReason()
    {
        document.getElementById("newReasonDiv").style.display = "none"; // div
        $("#newReason").val("");
    }

    function approve()
    {
        $("#checkerAction").val("approve");
        $("#adminUserApproveOrReject").submit();
    }

    function reject()
    {
        $("#checkerAction").val("reject");
        var newReason = document.getElementById("newReason").value;
        if (newReason === "" || newReason === " " || newReason === "") {
            alert("Please write new reason.");
        } else {
            $("#adminUserApproveOrReject").submit();
        }
    }

</script>