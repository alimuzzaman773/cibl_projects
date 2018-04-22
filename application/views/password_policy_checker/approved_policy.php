
<div class="container">
    <h3 class="title">Approve/Reject Policy</h3>
    <div id="showUsers">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center; color: red">Changed Data</th>
                    <th style="text-align: center; <?= $publishDataModeOfDisplay_ck ?>; color: green" >Published Data</th>
                </tr>
            </thead>
            <tbody >
                <tr>
                    <td style="text-align:left">
                        <form method="post" style="" id="passwordApproveOrReject" name="passwordApproveOrReject" action="<?php echo base_url(); ?>password_policy_checker/getReason">
                            <input hidden type="text" name="policyId" id="policyId" value="<?= $policy['validationGroupId'] ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >

                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th align="left" scope="row">Policy Name</th>
                                    <td><?php echo text_match($policy['validationGroupName'], $policy['validationGroupName_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Messages</th>
                                    <td><?php echo text_match($policy['message'], $policy['message_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Example</th>
                                    <td><?php echo text_match($policy['example'], $policy['example_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">VG Code</th>
                                    <td><?php echo text_match($policy['vgCode'], $policy['vgCode_ck']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Version Code</th>
                                    <td><?php echo text_match($policy['vCodes'], $policy['vCodes_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Wrong Attempt</th>
                                    <td><?php echo text_match($policy['wrongAttempts'], $policy['wrongAttempts_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">History Size</th>
                                    <td><?php echo text_match($policy['passHistorySize'], $policy['passHistorySize_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Pass Expiry Period</th>
                                    <td><?php echo text_match($policy['passExpiryPeriod'], $policy['passExpiryPeriod_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Warning Period</th>
                                    <td><?php echo text_match($policy['warningPeriod'], $policy['warningPeriod_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Hibernation Period</th>
                                    <td><?php echo text_match($policy['hibernationPeriod'], $policy['hibernationPeriod_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">PIN Expiry Period</th>
                                    <td><?php echo text_match($policy['pinExpiryPeriod'], $policy['pinExpiryPeriod_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?php echo text_match($policy['makerAction'], $policy['makerAction_ck']) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Maker Action Date</th>
                                    <td><?php echo text_match($makerActionDtTm, $makerActionDtTm_ck) ?></td>
                                </tr>
                            </table>

                            <div class="btn-group pull-left">
                                <button class="btn btn-info" onclick="writeReason(); return false;">Write New Reason</button>                        
                                <button class="btn btn-warning" onclick="cancelReason(); return false;">Cancel Reason</button>
                            </div>

                            <div id="newReasonDiv" style="display: none">
                                <br>
                                <h3 style="color:red" >New Reject Reason</h3>
                                <textarea class="form-control" name="newReason" id="newReason" cols="40" rows="5"></textarea>
                            </div>
                            <br>
                            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                                <h3 style="color:red" >Previous Reject Reason</h3>
                                <textarea name="reason" id="reason" cols="40" rows="5" readonly></textarea>
                                <br><br>
                            </div>

                        </form>
                        <div class="btn-group pull-right">
                            <button class="btn btn-success" onclick="approve();">Approve</button>
                            <button class="btn btn-danger" onclick="reject();">Reject</button>
                            <a href="<?php echo base_url(); ?>password_policy_checker" class="btn btn-warning">Back</a>
                        </div>
                    </td>

                    <td style="text-align:left; <?= $publishDataModeOfDisplay_ck ?>">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th align="left" scope="row">Policy Name</th>
                                <td><?= $policy['validationGroupName_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Messages</th>
                                <td><?= $policy['message_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Example</th>
                                <td><?= $policy['example_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">VG Code</th>
                                <td><?= $policy['vgCode_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Version Code</th>
                                <td><?= $policy['vCodes'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Wrong Attempt</th>
                                <td><?= $policy['wrongAttempts_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">History Size</th>
                                <td><?= $policy['passHistorySize_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Pass Expiry Period</th>
                                <td><?= $policy['passExpiryPeriod_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Warning Period</th>
                                <td><?= $policy['warningPeriod_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Hibernation Period</th>
                                <td><?= $policy['hibernationPeriod_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">PIN Expiry Period</th>
                                <td><?= $policy['pinExpiryPeriod_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Maker Action</th>
                                <td><?= $policy['makerAction_ck'] ?></td>
                            </tr>
                            <tr>
                                <th align="left" scope="row">Maker Action Date</th>
                                <td><?= $makerActionDtTm_ck ?></td>
                            </tr>
                        </table>

                        <div id="reasonDiv_c" style="<?= $reasonModeOfDisplay_ck ?>" >
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

</script>

<script>

    function writeReason()
    {
        document.getElementById("newReasonDiv").style.display = "block"; // div
    }

    function cancelReason()
    {
        document.getElementById("newReasonDiv").style.display = "none"; // div
        $("#newReason").val("");
    }

</script>

<script>
    function approve()
    {
        $("#checkerAction").val("approve");
        $("#passwordApproveOrReject").submit();
    }
    function reject()
    {
        $("#checkerAction").val("reject");
        var newReason = document.getElementById("newReason").value;
        if (newReason === "" || newReason === " " || newReason === "") {
            alert("Please write new reason.");
        } else {
            $("#passwordApproveOrReject").submit();
        }
    }
</script>