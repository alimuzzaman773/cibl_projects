<h3 class="title-underlined">Limit Package Setup Checker</h3>

<div class="container" style="margin-top:50px">
    <div id="showUsers">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center; color: red">Changed Data</th>
                    <th style="text-align: center; <?= $publishDataModeOfDisplay_c ?>; color: green">Published Data</th>
                </tr>
            </thead>
            <tbody >
                <tr>
                    <td style="text-align:left">
                        <form method="post" style="" id="limitPackageApproveOrReject" name="limitPackageApproveOrReject" action="<?php echo base_url(); ?>transaction_limit_setup_checker/approveOrReject">
                            <input hidden type="text" name="appsGroupId" id="appsGroupId" value="<?= $appsGroupId ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >

                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th align="left" scope="row">Group Name</th>
                                    <td><?php echo text_match($userGroupName, $userGroupName_c) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?php echo text_match($makerAction, $makerAction_c) ?></td>
                                </tr>
                                <tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?php echo text_match($makerActionDtTm, $makerActionDtTm_c) ?></td>
                                </tr>                                
                            </table>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th hidden >ID</th>
                                        <th>Package Name</th>
                                        <th style="text-align: center" valign="middle">Minimum Transaction Limit</th>
                                        <th style="text-align: center" valign="middle">Maximum Transaction Limit</th>
                                        <th style="text-align: center" valign="middle">Day Transaction Limit</th>
                                        <th style="text-align: center" valign="middle">No. of Transaction Per Day</th>
                                    </tr>
                                </thead>
                                <tbody data-bind="foreach: changes">
                                    <tr>
                                        <td hidden data-bind="text:packageId" > </td>
                                        <td data-bind="text:packageName" > </td>                
                                        <td data-bind="text:min" > </td>
                                        <td data-bind="text:max" > </td>
                                        <td data-bind="text:dayLimit" > </td>
                                        <td data-bind="text:perDay" > </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table width="100" border="0" cellpadding="2">
                                <tr>
                                    <td width="100"><button class="btn btn-success" onclick="writeReason();
                                            return false;">Write New Reason</button></td>
                                    <td width="100"><button class="btn btn-success" onclick="cancelReason();
                                            return false;">Cancel Reason</button></td>
                                </tr>
                            </table>

                            <div id="newReasonDiv" style="display: none" >
                                <h3 style="color:red" >New Reject Reason</h3>
                                <textarea name="newReason" id="newReason" cols="40" rows="5"></textarea>
                                <br><br>
                            </div>
                            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                                <h3 style="color:red">Previous Reject Reason</h3>
                                <textarea name="reason" id="reason" cols="40" rows="5" readonly></textarea>
                                <br><br>
                            </div>
                        </form>
                        <table width="100" border="0" cellpadding="2">
                            <tr>
                                <td width="100"><button class="btn btn-success" onclick="approve();">Approve</button></td>
                                <td><a href="<?php echo base_url(); ?>transaction_limit_setup_checker" class="btn btn-warning"><i class="icon-remove"></i><span>Back</span></a></td>
                                <td width="100"><button class="btn btn-danger" onclick="reject();">Reject</button></td>
                            </tr>
                        </table>
                    </td> 
                    <td style="text-align:left; <?= $publishDataModeOfDisplay_c ?>">

                        <table class="table table-striped table-bordered">
                            <tr>
                                <th align="left" scope="row">Group Name</th>
                                <td><?= $userGroupName_c ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">Maker Action</th>
                                <td><?= $makerAction_c ?></td>
                            </tr>

                            <tr>
                                <th align="left" scope="row">Maker Action Date Time</th>
                                <td><?= $makerActionDtTm_c ?></td>
                            </tr>
                        </table>
                        <br>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th hidden >ID</th>
                                    <th>Package Name</th>
                                    <th style="text-align: center" valign="middle">Minimum Transaction Limit</th>
                                    <th style="text-align: center" valign="middle">Maximum Transaction Limit</th>
                                    <th style="text-align: center" valign="middle">Day Transaction Limit</th>
                                    <th style="text-align: center" valign="middle">No. of Transaction Per Day</th>
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: publishes">
                                <tr>
                                    <td hidden data-bind="text:packageId_c" > </td>
                                    <td data-bind="text:packageName_c" > </td>                
                                    <td data-bind="text:min_c" > </td>
                                    <td data-bind="text:max_c" > </td>
                                    <td data-bind="text:dayLimit_c" > </td>
                                    <td data-bind="text:perDay_c" > </td>
                                </tr>
                            </tbody>
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
        $("#limitPackageApproveOrReject").submit();
    }
    ;

    function reject()
    {
        $("#checkerAction").val("reject");

        var newReason = document.getElementById("newReason").value;
        if (newReason === "" || newReason === " " || newReason === "") {
            alert("Please write new reason.");
        } else {
            $("#limitPackageApproveOrReject").submit();
        }
    }
    ;
</script>

<script type="text/javascript" charset="utf-8">

    var changedPackages = <?= $changedPackages ?>;
    var publishedPackages = <?= $publishedPackages ?>;
    var group = <?= $group ?>;

    var vm = function () {

        var self = this;
        self.changes = ko.observableArray(changedPackages);
        self.publishes = ko.observableArray(publishedPackages);


        $.each(self.changes(), function (i, change)
        {
            change.packageId = change.packageId;
            change.packageName = change.packageName;

            if (change.packageId == 1) {
                change.min = group.oatMinTxnLim;
                change.max = group.oatMaxTxnLim;
                change.dayLimit = group.oatDayTxnLim;
                change.perDay = group.oatNoOfTxn;
            }

            if (change.packageId == 2) {
                change.min = group.eatMinTxnLim;
                change.max = group.eatMaxTxnLim;
                change.dayLimit = group.eatDayTxnLim;
                change.perDay = group.eatNoOfTxn;
            }

            if (change.packageId == 3) {
                change.min = group.obtMinTxnLim;
                change.max = group.obtMaxTxnLim;
                change.dayLimit = group.obtDayTxnLim;
                change.perDay = group.obtNoOfTxn;
            }

            if (change.packageId == 4) {
                change.min = group.pbMinTxnLim;
                change.max = group.pbMaxTxnLim;
                change.dayLimit = group.pbDayTxnLim;
                change.perDay = group.pbNoOfTxn;
            }
            if (change.packageId == 5) {
                change.min = group.ccMinTxnLim;
                change.max = group.ccMaxTxnLim;
                change.dayLimit = group.ccDayTxnLim;
                change.perDay = group.ccNoOfTxn;
            }
        })

        $.each(self.publishes(), function (i, publish)
        {
            publish.packageId_c = publish.packageId_c;
            publish.packageName_c = publish.packageName_c;

            if (publish.packageId_c == 1) {
                publish.min_c = group.oatMinTxnLim_c;
                publish.max_c = group.oatMaxTxnLim_c;
                publish.dayLimit_c = group.oatDayTxnLim_c;
                publish.perDay_c = group.oatNoOfTxn_c;
            }

            if (publish.packageId_c == 2) {
                publish.min_c = group.eatMinTxnLim_c;
                publish.max_c = group.eatMaxTxnLim_c;
                publish.dayLimit_c = group.eatDayTxnLim_c;
                publish.perDay_c = group.eatNoOfTxn_c;
            }

            if (publish.packageId_c == 3) {
                publish.min_c = group.obtMinTxnLim_c;
                publish.max_c = group.obtMaxTxnLim_c;
                publish.dayLimit_c = group.obtDayTxnLim_c;
                publish.perDay_c = group.obtNoOfTxn_c;
            }

            if (publish.packageId_c == 4) {
                publish.min_c = group.pbMinTxnLim_c;
                publish.max_c = group.pbMaxTxnLim_c;
                publish.dayLimit_c = group.pbDayTxnLim_c;
                publish.perDay_c = group.pbNoOfTxn_c;
            }

            if (publish.packageId_c == 5) {
                publish.min_c = group.ccMinTxnLim_c;
                publish.max_c = group.ccMaxTxnLim_c;
                publish.dayLimit_c = group.ccDayTxnLim_c;
                publish.perDay_c = group.ccNoOfTxn_c;
            }
        })
    }
    ko.applyBindings(new vm());
</script>
