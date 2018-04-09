<title>Approve/Reject</title>
<div class="breadcrum">Approve/Reject Apps Users</div>

<div class="container" style="margin-top:50px">

    <div id="showUsers">
        
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

                        <form method="post" style="" id="appsUserApproveOrReject" name="appsUserApproveOrReject" action="<?php echo base_url(); ?>client_registration_checker/getReason">

                            <input hidden type="text" name="skyId" id="skyId" value="<?= $appsUser['skyId'] ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >

                            <table class="table table-striped table-bordered">    
                                <tr>
                                    <th align="left" scope="row">ESB ID</th>
                                    <td><?php echo text_match($appsUser['eblSkyId'],$appsUser['eblSkyId_c'] );?></td>
                                </tr>
                                
                                <tr>
                                    <th align="left" scope="row">CFID</th>
                                    <td><?php echo text_match($appsUser['cfId'],$appsUser['cfId_c'] );?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Client ID</th>
                                    <td><?php echo text_match($appsUser['clientId'], $appsUser['clientId_c']);?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">User Group Name</th>
                                    <td><?php echo text_match($appsUser['mcUserGroupName'], $appsUser['mainUserGroupName']);?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Date of Birth</th>
                                    <td><?php echo text_match($appsUser['dob'], $appsUser['dob_c']);?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Gender</th>
                                    <td><?php echo text_match($appsUser['gender'], $appsUser['gender_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Father's Name</th>
                                    <td><?php echo text_match($appsUser['fatherName'], $appsUser['fatherName_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Mother's Name</th>
                                    <td><?php echo text_match($appsUser['motherName'], $appsUser['motherName_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Contact Number</th>
                                    <td><?php echo text_match($appsUser['userMobNo1'], $appsUser['userMobNo1_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Alternate Contact Number</th>
                                    <td><?php echo text_match($appsUser['userMobNo2'], $appsUser['userMobNo2_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">User Name</th>
                                    <td><?php echo text_match($appsUser['userName'], $appsUser['userName_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Email</th>
                                    <td><?php echo text_match($appsUser['userEmail'], $appsUser['userEmail_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Current Address</th>
                                    <td><?php text_match($appsUser['currAddress'], $appsUser['currAddress_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Parmanent Address</th>
                                    <td><?php echo text_match($appsUser['parmAddress'], $appsUser['parmAddress_c'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Mailing Address</th>
                                    <td><?php echo text_match($appsUser['billingAddress'], $appsUser['billingAddress_c']);?></td>
                                </tr>

                                 <tr>
                                    <th align="left" scope="row">Home Branch Code</th>
                                    <td><?php echo text_match($appsUser['homeBranchCode'], $appsUser['homeBranchCode_c']);?></td>
                                </tr>

                               <tr>
                                    <th align="left" scope="row">Home Branch Name</th>
                                    <td><?php echo text_match($homeBranchName, $homeBranchName_c);?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Active/Inactive</th>
                                    <td><?php echo text_match($isActive, $isActive_c);?></td>
                                </tr>
                                
                                <tr>
                                    <th align="left" scope="row">Lock/Unlock</th>
                                    <td><?php echo text_match($isLocked, $isLocked_c)?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?php echo text_match($appsUser['makerAction'], $appsUser['makerAction_c']);?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?php echo text_match($makerActionDtTm, $makerActionDtTm_c);?></td>
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


                            <h3 style="color:green" > Cards Information </h3>

                            <div class="alert alert-success"><?php echo $message ?></div>

                            <div style="text-align:center; <?= $cardsModeOfDisplay ?>" >

                                <table class="table table-striped table-bordered">
                                   
                                    <tr>
                                        <th align="left" scope="row">Client ID</th>
                                        <td><?php echo text_match($appsUser['clientId'], $appsUser['clientId_c']);?></td>
                                    </tr>

                                    <tr>
                                        <th align="left" scope="row">Card User Name</th>
                                        <td><?php echo text_match($userNameCard, $userNameCard_c);?></td>
                                    </tr>

                                    <tr>
                                        <th align="left" scope="row">Mother's Name</th>
                                        <td><?php echo text_match($mothersNameCard, $mothersNameCard_c);?></td>
                                    </tr>

                                    <tr>
                                        <th align="left" scope="row">Date of birth</th>
                                        <td><?php echo text_match($dobCard, $dobCard_c)?></td>
                                    </tr>

                                    <tr>
                                        <th align="left" scope="row">Billing Address</th>
                                        <td><?php echo text_match($clientBillingAddress, $clientBillingAddress_c)?></td>
                                    </tr>

                                </table>

                            </div>

                            <div style="<?= $cardsModeOfDisplay?>">
                                <h3 style="color:green" > Card Numbers </h3>
                                <div>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="text-align:center">Card Number</th>
                                            <th style="text-align:center">Card Type</th>
                                            <th style="text-align:center">Currency</th>
                                            <th style="text-align:center">Card Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach($multiCard as $multiCard){?>
                                        <tr>
                                            <td style="text-align:center"><?php echo $multiCard['cardNumber'];?></td>
                                            <td style="text-align:center"><?php echo $multiCard['cardType'];?></td>
                                            <td style="text-align:center"><?php echo $multiCard['cardCurrency'];?></td>
                                            <td style="text-align:center"><?php echo $multiCard['cardStatus'];?></td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <br>

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
                            <td width="100"><button class="btn btn-success" onclick="approve();">Approve</button></td>
                            <td><a href="<?php echo base_url(); ?>client_registration_checker" class="btn btn-warning"><i class="icon-remove"></i><span>Back</span></a></td>
                            <td width="100"><button class="btn btn-danger" onclick="reject();">Reject</button></td>
                        </tr>
                        </table>
                    </td>

          
                    <td style="text-align:center; <?= $publishDataModeOfDisplay_c ?>">

                        <table class="table table-striped table-bordered">    
                            <tr>
                                <th align="left" scope="row">ESB ID</th>
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
                            <h3 style="color:green" > Account Numbers </h3>
                            <div data-bind="visible: publishedAccounts().length > 0">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="text-align:center">Account Number</th>
                                        <th style="text-align:center">Account Type</th>
                                        <th style="text-align:center">Product Name</th>
                                        <th style="text-align:center">Currency</th>
                                    </tr>
                                    </thead>
                                    <tbody data-bind="foreach: publishedAccounts">
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

                        <h3 style="color:green" > Cards Information </h3>
                        <div class="alert alert-success" ><?php echo $message_c ?></div>

                        <div style="text-align:center; <?= $cardsModeOfDisplay_c ?>" >

                            <table class="table table-striped table-bordered">

                                <tr>
                                    <th align="left" scope="row">Client ID</th>
                                    <td><?= $appsUser['clientId_c'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Card User Name</th>
                                    <td><?= $userNameCard_c ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Mother's Name</th>
                                    <td><?= $mothersNameCard_c ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Date of birth</th>
                                    <td><?= $dobCard_c ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Billing Address</th>
                                    <td><?= $clientBillingAddress_c ?></td>
                                </tr>

                            </table>

                        </div>

                        <div style="<?= $cardsModeOfDisplay_c ?>">
                            <h3 style="color:green" > Card Numbers </h3>
                            <div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="text-align:center">Card Number</th>
                                        <th style="text-align:center">Card Type</th>
                                        <th style="text-align:center">Currency</th>
                                        <th style="text-align:center">Card Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($multiCard_c as $multiCard_c){?>
                                    <tr>
                                        <td style="text-align:center"><?php echo $multiCard_c['cardNumber'];?></td>
                                        <td style="text-align:center"><?php echo $multiCard_c['cardType'];?></td>
                                        <td style="text-align:center"><?php echo $multiCard_c['cardCurrency'];?></td>
                                        <td style="text-align:center"><?php echo $multiCard_c['cardStatus'];?></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
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
    };

    function cancelReason()
    {
        document.getElementById("newReasonDiv").style.display = "none"; // div
        $("#newReason").val("");
    };

</script>




<script>
    function approve()
    {
      $("#checkerAction").val("approve");
      $("#appsUserApproveOrReject").submit();
    };

    function reject()
    {
      $("#checkerAction").val("reject");

      var newReason = document.getElementById("newReason").value;
      if(newReason === "" || newReason === " " || newReason === ""){
        alert("Please write new reason.");
      }else{
        $("#appsUserApproveOrReject").submit();
      }
    };
</script>



<script type="text/javascript" charset="utf-8">
    var changedAccountInfo = <?= $accountInfo ?>;

    if(jQuery.isEmptyObject(<?= $accountInfo_c ?>)){
        var publishedAccountInfo = null;
    }

    else{
        var publishedAccountInfo = <?= $accountInfo_c ?>;
    }

    var vm = function() {
        var self = this;
        self.changedAccounts = ko.observableArray(changedAccountInfo);
        self.publishedAccounts = ko.observableArray(publishedAccountInfo);
    }
    ko.applyBindings(new vm());
</script>