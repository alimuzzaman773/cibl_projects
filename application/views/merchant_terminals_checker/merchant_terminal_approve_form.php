<div class="container">
    <h3 class="title-underlined">Merchant Terminal Checker</h3>
    <div id="showUsers">
    	<form method="post" style="" id="merchantTmApproveOrReject" name="merchantTmApproveOrReject" action="<?php echo base_url(); ?>merchant_terminals_checker/getReason">
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
                            <input hidden type="text" name="terminalId" id="terminalId" value="<?= $merchantTerminal['terminalId'] ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >

                            <table class="table table-striped table-bordered">
                                <tr>
                                    <th align="left" scope="row">Merchant Id</th>
                                    <td><?= text_match($merchantTerminal['merchantId'], $merchantTerminal['merchantId_c']); ?></td>
                                </tr>
                            	<tr>
                            		<th align="left" scope="row">Terminal Name</th>
                            		<td><?= text_match($merchantTerminal['terminalName'], $merchantTerminal['terminalName_c']); ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">Address</th>
                            		<td><?= text_match($merchantTerminal['address'], $merchantTerminal['address_c']); ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">City</th>
                            		<td><?= text_match($merchantTerminal['city'], $merchantTerminal['city_c']); ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">District</th>
                            		<td><?= text_match($merchantTerminal['district'], $merchantTerminal['district_c']); ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">Zip</th>
                            		<td><?= text_match($merchantTerminal['zip'], $merchantTerminal['zip_c']); ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">Currency</th>
                            		<td><?= text_match($merchantTerminal['currency'], $merchantTerminal['currency_c']); ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">Account No</th>
                            		<td><?= text_match($merchantTerminal['accountNo'], $merchantTerminal['accountNo_c']); ?></td>
                            	</tr>
                            	<tr>
                            		<th align="left" scope="row">Maker Action</th>
                            		<td><?= text_match($merchantTerminal['makerAction'], $merchantTerminal['makerAction_c']); ?></td>
                            	</tr>
                            	<tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?php echo text_match($makerActionDtTm, $makerActionDtTm_c); ?></td>
                                </tr>
                            </table>
                        </td>

                        <td style="text-align:center; <?= $publishDataModeOfDisplay_c ?>">
                        	<table class="table table-striped table-bordered">
                                <tr>
                                    <th align="left" scope="row">Merchant Id</th>
                                    <td><?= $merchantTerminal['merchantId_c']; ?></td>
                                </tr>
                            	<tr>
                            		<th align="left" scope="row">Terminal Name</th>
                            		<td><?= $merchantTerminal['terminalName_c']; ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">Address</th>
                            		<td><?= $merchantTerminal['address_c']; ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">City</th>
                            		<td><?= $merchantTerminal['city_c']; ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">District</th>
                            		<td><?= $merchantTerminal['district_c']; ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">Zip</th>
                            		<td><?= $merchantTerminal['zip_c']; ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">Currency</th>
                            		<td><?= $merchantTerminal['currency_c']; ?></td>
                            	</tr>
                                <tr>
                            		<th align="left" scope="row">Account No</th>
                            		<td><?= $merchantTerminal['accountNo_c']; ?></td>
                            	</tr>
                            	<tr>
                            		<th align="left" scope="row">Maker Action</th>
                            		<td><?= $merchantTerminal['makerAction_c']; ?></td>
                            	</tr>
                            	<tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?php echo $makerActionDtTm_c; ?></td>
                                </tr>
                        	</table>
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
                                        <a href="<?php echo base_url(); ?>merchant_terminals_checker" class="btn btn-warning">
                                            <i class="icon-remove"></i><span>Back</span>
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" onclick="reject(event);">Reject</button>
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
        $("#merchantTmApproveOrReject").submit();
    };

    function reject(event)
    {
        $("#checkerAction").val("reject");

        var newReason = document.getElementById("newReason").value;
        newReason = newReason.trim();
        if (newReason === "" || newReason === undefined) {
			event.preventDefault();
            alert("Please write new reason.");
        } else {
        	console.log('here')
            //$("#merchantAcApproveOrReject").submit();
        }
    };
</script>