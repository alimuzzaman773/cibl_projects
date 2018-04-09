<title>Approve/Reject</title>
<div class="breadcrum">Approve/Reject Admin Users</div>


<div class="container" style="margin-top:50px">
    <div id="showUsers">
        <table class="table table-striped table-bordered">

            <thead>
                <tr>
                    <th style="text-align: center" >Changed Data</th>
                    <th style="text-align: center; <?= $publishDataModeOfDisplay_c ?>" >Published Data</th>
                </tr>
            </thead>

            <tbody >
                <tr>
                    <td style="text-align:left">

                        <form method="post" style="" id="deviceApproveOrReject" name="deviceApproveOrReject" action="<?php echo base_url(); ?>device_info_checker/approveOrRejectDevice">

                            <input hidden type="text" name="deviceId" id="deviceId" value="<?= $deviceInfo['deviceId'] ?>">
                            <input hidden type="text" name="skyId" id="skyId" value="<?= $deviceInfo['skyId'] ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >

                            <table class="table table-striped table-bordered">    
                                                                <tr>
                                    <th align="left" scope="row">User Name</th>
                                    <td><?php echo text_match($deviceInfo['eblSkyId'], $deviceInfo['eblSkyId'])?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">IMEI No</th>
                                    <td><?php echo text_match($deviceInfo['imeiNo'], $deviceInfo['imeiNo_c'])?></td>
                                </tr>
                                

                                <tr>
                                    <th align="left" scope="row">Active/Inactive</th>
                                    <td><?php echo text_match($isActive, $isActive_c)?></td>
                                </tr>
                                

                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?php echo text_match($deviceInfo['makerAction'], $deviceInfo['makerAction_c'])?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?php echo  text_match($makerActionDtTm, $makerActionDtTm_c)?></td>
                                </tr>

                            </table>


                            <table width="100" border="0" cellpadding="2">
                                <tr>
                                    <td width="100"><button class="btn btn-success" onclick="writeReason(); return false;">Write New Reason</button></td>
                                    <td width="100"><button class="btn btn-success" onclick="cancelReason(); return false;">Cancel Reason</button></td>
                                </tr>
                            </table>


                            <div id="newReasonDiv" style="display: none" >
                                <h3 style="color:red" >New Reject Reason<h3>
                                <textarea name="newReason" id="newReason" cols="40" rows="5"></textarea>
                                <br><br>
                            </div>


                            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                                <h3 style="color:red" >Previous Reject Reason<h3>
                                <textarea name="reason" id="reason" cols="40" rows="5" readonly></textarea>
                                <br><br>
                            </div>

                        </form>

                        <table width="100" border="0" cellpadding="2">
                            <tr>
                                <td width="100"><button class="btn btn-success" onclick="approve();">Approve</button></td>
                                <td><a href="<?php echo base_url(); ?>device_info_checker" class="btn btn-warning"><i class="icon-remove"></i><span>Back</span></a></td>
                                <td width="100"><button class="btn btn-danger" onclick="reject();">Reject</button></td>
                            </tr>
                        </table>
                    </td> 
          
                    <td style="text-align:left; <?= $publishDataModeOfDisplay_c ?>">

                            <table class="table table-striped table-bordered">    
                                <tr>
                                    <th align="left" scope="row">User Name</th>
                                    <td><?= $deviceInfo['eblSkyId'] ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">IMEI No</th>
                                    <td><?= $deviceInfo['imeiNo_c'] ?></td>
                                </tr>
                                

                                <tr>
                                    <th align="left" scope="row">Active/Inactive</th>
                                    <td><?= $isActive_c ?></td>
                                </tr>
                                

                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?= $deviceInfo['makerAction_c'] ?></td>
                                </tr>


                                <tr>
                                    <th align="left" scope="row">Maker Action Date Time</th>
                                    <td><?= $makerActionDtTm_c ?></td>
                                </tr>

                            </table>

                        <div id="reasonDiv_c" style="<?= $reasonModeOfDisplay_c ?>" >
                            <h3 style="color:red" >Reject Reason<h3>
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
      $("#deviceApproveOrReject").submit();
    };

    function reject()
    {
      $("#checkerAction").val("reject");

      var newReason = document.getElementById("newReason").value;
      if(newReason === "" || newReason === " " || newReason === ""){
        alert("Please write new reason.");
      }else{
        $("#deviceApproveOrReject").submit();
      }
    };
</script>