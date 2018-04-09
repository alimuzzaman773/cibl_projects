<title>Biller Setup</title>
<div class="breadcrum">Edit Biller</div>


<div class="container" style="margin-top:50px">

    <div class="alert alert-success"><?php echo $message ?></div>

    <form method="post" style="" id="pinRequestForm" name="pinRequestForm" action="<?php echo base_url(); ?>pin_generation/updatePinRequest">

        <input hidden type="text" name="requestId" id="requestId" value="<?=$pinRequestData['requestId'] ?>" size="20" />
        <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

        <fieldset>


            <table width="550" border="0" cellpadding="10">

                <tr>
                    <th width="213" align="left" scope="row">Total Pin*</th>
                    <td width="161"><input type="number" min="1" max="100" name="totalPin" id="totalPin" style="width: 150px" value="<?=$pinRequestData['totalPin'] ?>" required/></td>
                </tr>

                <tr>
                    <th align="left" scope="row">Remark</th>
                    <td><textarea type="text" name="makerActionComment" id="makerActionComment" rows="4" cols="50" required><?=$pinRequestData['makerActionComment'] ?></textarea></td>
                </tr>

                <tr>
                    <th align="left" scope="row">&nbsp;</th>
                    <td></td>
                </tr>


            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                <h3>Reject Reason<h3>
                <textarea name="reason" id="reason" cols="40" rows="5" readonly> <?=$pinRequestData['checkerActionComment']?> </textarea>
                <br><br>
            </div>

            </table>

        </fieldset>
        <button type="submit" form="pinRequestForm" value="Submit" class="btn btn-success">Update</button>
        <a href="<?php echo base_url(); ?>pin_generation/index" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a> 


    </form>

</div>






