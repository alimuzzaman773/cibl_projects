<div class="container">
    <h3 class="title-underlined">Apps User Checker</h3>
    <div id="showUsers">
        <form method="post" style="" id="appsUserApproveOrReject" name="appsUserApproveOrReject" action="<?php echo base_url(); ?>client_registration_checker/getReason">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center; color: red" width="50%">Changed Data</th>
                        <th style="text-align: center; <?= $publishDataModeOfDisplay_c ?>; color: green">Published Data</th>
                    </tr>
                </thead>
                <tbody >
                    <tr>
                        <td style="text-align:center">
                            <input hidden type="text" name="skyId" id="skyId" value="<?= $appsUser['skyId'] ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >

                            <table class="table table-striped table-bordered">    
                                <tr>
                                    <th align="left" scope="row">Apps ID</th>
                                    <td><?php echo text_match($appsUser['eblSkyId'], $appsUser['eblSkyId_c']); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">CFID</th>
                                    <td><?php echo text_match($appsUser['cfId'], $appsUser['cfId_c']); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Client ID</th>
                                    <td><?php echo text_match($appsUser['clientId'], $appsUser['clientId_c']); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">User Group Name</th>
                                    <td><?php echo text_match($appsUser['mcUserGroupName'], $appsUser['mainUserGroupName']); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Date of Birth</th>
                                    <td><?php echo text_match($appsUser['dob'], $appsUser['dob_c']); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Gender</th>
                                    <td><?php echo text_match($appsUser['gender'], $appsUser['gender_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Father's Name</th>
                                    <td><?php echo text_match($appsUser['fatherName'], $appsUser['fatherName_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Mother's Name</th>
                                    <td><?php echo text_match($appsUser['motherName'], $appsUser['motherName_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Contact Number</th>
                                    <td><?php echo text_match($appsUser['userMobNo1'], $appsUser['userMobNo1_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Alternate Contact Number</th>
                                    <td><?php echo text_match($appsUser['userMobNo2'], $appsUser['userMobNo2_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">User Name</th>
                                    <td><?php echo text_match($appsUser['userName'], $appsUser['userName_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Email</th>
                                    <td><?php echo text_match($appsUser['userEmail'], $appsUser['userEmail_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Current Address</th>
                                    <td><?php text_match($appsUser['currAddress'], $appsUser['currAddress_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Parmanent Address</th>
                                    <td><?php echo text_match($appsUser['parmAddress'], $appsUser['parmAddress_c']) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Mailing Address</th>
                                    <td><?php echo text_match($appsUser['billingAddress'], $appsUser['billingAddress_c']); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Home Branch Code</th>
                                    <td><?php echo text_match($appsUser['homeBranchCode'], $appsUser['homeBranchCode_c']); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Home Branch Name</th>
                                    <td><?php echo text_match($homeBranchName, $homeBranchName_c); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Active/Inactive</th>
                                    <td><?php echo text_match($isActive, $isActive_c); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Lock/Unlock</th>
                                    <td><?php echo text_match($isLocked, $isLocked_c) ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?php echo text_match($appsUser['makerAction'], $appsUser['makerAction_c']); ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?php echo text_match($makerActionDtTm, $makerActionDtTm_c); ?></td>
                                </tr>                            
                            </table>

                            <div>
                                <h3 style="color:green" > Account Numbers </h3>
                                <div data-bind="visible: changedAccounts().length > 0">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="text-align:center">Account Number</th>
                                                <th style="text-align:center">Account Type</th>
                                                <th style="text-align:center">Product Name</th>
                                                <th style="text-align:center">Currency</th>
                                            </tr>
                                        </thead>
                                        <tbody data-bind="foreach: changedAccounts">
                                            <tr>
                                                <td style="text-align:center" data-bind="text:accNo"></td>
                                                <td style="text-align:center" data-bind="text:accType"></td>
                                                <td style="text-align:center" data-bind="text:accName"></td>
                                                <td style="text-align:center" data-bind="text:accCurrency"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            
                        </td>


                        <td style="text-align:center; <?= $publishDataModeOfDisplay_c ?>">

                            <table class="table table-striped table-bordered">    
                                <tr>
                                    <th align="left" scope="row">APPS ID</th>
                                    <td><?= $appsUser['eblSkyId_c'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">CFID</th>
                                    <td><?= $appsUser['cfId_c'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Client ID</th>
                                    <td><?= $appsUser['clientId_c'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">User Group Name</th>
                                    <td><?= $appsUser['mainUserGroupName'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Date of Birth</th>
                                    <td><?= $appsUser['dob_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Gender</th>
                                    <td><?= $appsUser['gender_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Father's Name</th>
                                    <td><?= $appsUser['fatherName_c'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Mother's Name</th>
                                    <td><?= $appsUser['motherName_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Contact Number</th>
                                    <td><?= $appsUser['userMobNo1_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Alternate Contact Number</th>
                                    <td><?= $appsUser['userMobNo2_c'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">User Name</th>
                                    <td><?= $appsUser['userName_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Email</th>
                                    <td><?= $appsUser['userEmail_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Current Address</th>
                                    <td><?= $appsUser['currAddress_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Parmanent Address</th>
                                    <td><?= $appsUser['parmAddress_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Mailing Address</th>
                                    <td><?= $appsUser['billingAddress_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Home Branch Code </th>
                                    <td><?= $appsUser['homeBranchCode_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Home Branch Name</th>
                                    <td><?= $homeBranchName_c ?></td>
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
                                    <td><?= $appsUser['makerAction_c'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?= $makerActionDtTm_c ?></td>
                                </tr>

                            </table>

                            <div>
                                <h3>Card Information</h3>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center">Card Number</th>
                                            <th style="text-align:center">Email</th>
                                            <th style="text-align:center">Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody data-bind="foreach: cardList">
                                        <tr>
                                            <td style="text-align:center" data-bind="text:cardNo"></td>
                                            <td style="text-align:center" data-bind="text:email"></td>
                                            <td style="text-align:center" data-bind="text:mobile"></td>
                                        </tr>
                                    </tbody>
                                </table>


                            </div>

                            <br>

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
                    <tr>
                        <td colspan="2">
                            <table width="300" border="0" cellpadding="2">
                                <tr>
                                    <td width="200"><button class="btn btn-success" onclick="writeReason(); return false;">Write New Reason</button></td>
                                    <td width=""><button class="btn btn-success" onclick="cancelReason(); return false;">Cancel Reason</button></td>
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


                            <table width="400" style="margin-top:20px" border="0" cellpadding="2">
                                <tr>
                                    <td width="100">
                                        <button class="btn btn-success" onclick="approve();">Approve</button>
                                    </td>
                                    <td width="100">
                                        <a href="<?php echo base_url(); ?>client_registration_checker" class="btn btn-warning">
                                            <i class="icon-remove"></i><span>Back</span>
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" onclick="reject();">Reject</button>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>    
        </form>
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
        $("#appsUserApproveOrReject").submit();
    }
    ;

    function reject()
    {
        $("#checkerAction").val("reject");

        var newReason = document.getElementById("newReason").value;
        if (newReason === "" || newReason === " " || newReason === "") {
            alert("Please write new reason.");
        } else {
            $("#appsUserApproveOrReject").submit();
        }
    }
    ;
</script>

<script type="text/javascript" charset="utf-8">
    var changedAccountInfo = <?= $accountInfo ?>;
    var cardListArray = <?= json_encode($cardList) ?>;

    if (jQuery.isEmptyObject(<?= $accountInfo_c ?>)) {
        var publishedAccountInfo = null;
    } else {
        var publishedAccountInfo = <?= $accountInfo_c ?>;
    }

    var vm = function () {
        var self = this;
        self.changedAccounts = ko.observableArray(changedAccountInfo);
        self.publishedAccounts = ko.observableArray(publishedAccountInfo);
        self.cardList = ko.observableArray(cardListArray);
    }
    ko.applyBindings(new vm());
</script>