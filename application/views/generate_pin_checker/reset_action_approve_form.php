<h3 class="title-underlined">PIN Reset Checker</h3>
<div class="container" style="margin-top:50px">
    <div id="showUsers">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center" >Changed Data</th>
                </tr>
            </thead>
            <tbody >
                <tr>
                    <td style="text-align:left">
                        <form method="post" style="" id="resetActionApproveOrReject" name="resetActionApproveOrReject" action="<?php echo base_url(); ?>pin_generation_checker/approveOrReject">
                            <input hidden type="text" name="eblSkyId" id="eblSkyId" value="<?= $resetPin['eblSkyId'] ?>">
                            <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
                            <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
                            <input hidden type="text" name="checkerAction" id="checkerAction" value="" >
                            <table class="table table-striped table-bordered">    
                                <tr>
                                    <th align="left" scope="row">APPS ID</th>
                                    <td><?= $resetPin['eblSkyId'] ?></td>
                                </tr>
                                
                                <tr>
                                    <th align="left" scope="row">Printed/Not</th>
                                    <td><?= $isPrinted ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Used/Not</th>
                                    <td><?= $isUsed ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Active/Destroyed</th>
                                    <td><?= $isActive ?></td>
                                </tr>
                                
                                <tr>
                                    <th align="left" scope="row">Reset/Not</th>
                                    <td><?= $isReset ?></td>
                                </tr>

                                <tr>
                                    <th align="left" scope="row">Maker Action</th>
                                    <td><?= $resetPin['makerAction'] ?></td>
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
                                <h3 style="color:red" >New Reject Reason<h3>
                                <textarea name="newReason" id="newReason" cols="40" rows="5"></textarea>
                                <br><br>
                            </div>

                            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                                <h3 style="color:red" >Previous Reject Reason<h3>
                                        <textarea class="form-control" name="reason" id="reason" cols="40" rows="5" readonly></textarea>
                                <br><br>
                            </div>
                        </form>

                        <table width="100" border="0" cellpadding="2">
                            <tr>
                                <td width="100"><button class="btn btn-success" onclick="approve();">Approve</button></td>
                                <td><a href="<?php echo base_url(); ?>pin_generation_checker" class="btn btn-warning"><i class="icon-remove"></i><span>Back</span></a></td>
                                <td width="100"><button class="btn btn-danger" onclick="reject();">Reject</button></td>
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
      $("#resetActionApproveOrReject").submit();
    };

    function reject()
    {
      $("#checkerAction").val("reject");

      var newReason = document.getElementById("newReason").value;
      if(newReason === "" || newReason === " " || newReason === ""){
        alert("Please write new reason.");
      }else{
        $("#resetActionApproveOrReject").submit();
      }
    };
</script>