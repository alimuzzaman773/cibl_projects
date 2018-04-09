<title>Reject Reason</title>
<div class="breadcrum">Reject Reason</div>


<div class="container" style="margin-top:50px">

    <form method="post" style="" id="reasonForm" name="reasonForm" action="<?php echo base_url(); ?>pin_create_checker/checkerReject">



        <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
        <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">


        <div class="alert alert-success"><?php echo $message ?></div>

        <input hidden class="textbox" type="text" name="requestId" id="requestId" value="<?= $requestId ?>">

        <fieldset>

            <table width="550" border="0" cellpadding="10">

                <tr>
                    <th align="left" scope="row">Reject Reason</th>
                    <td><textarea type="text" name="checkerActionComment" id="checkerActionComment" rows="4" cols="50" placeholder="Write reason here..." required></textarea></td>
                </tr>

                <tr>
                    <th align="left" scope="row">&nbsp;</th>
                    <td></td>
                </tr>

            </table>

        </fieldset>


        <button type="submit" form="reasonForm" value="Submit" class="btn btn-success">Submit</button>
        <a href="<?php echo base_url(); ?>pin_create_checker" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a> 

    </form>

</div>



