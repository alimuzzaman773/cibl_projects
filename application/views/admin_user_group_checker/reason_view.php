<title>Reason</title>
<div class="breadcrum">Write Reason</div>


<div class="container" style="margin-top:50px">

    <form method="post" style="" id="reasonFrom" name="reasonForm" action="<?php echo base_url(); ?>admin_user_group_checker/checkerRejectWithReason">

        <input hidden  type="text" name="userGroupId" id="userGroupId" value="<?= $userGroupId ?>">
        <input hidden  type="text" name="checkerAction" id="checkerAction" value="<?= $checkerAction ?>" >
        <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
        <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">


        <h3>Reason<h3>
        <textarea name="reason" id="reason" placeholder="Write reason here..." cols="40" rows="5" required></textarea>
        <br><br>


        <table width="50" border="0" cellpadding="2">
            <tr>
                <td width="100"><button type="submit" class="btn btn-success" >Submit</button></td>
                <td><a href="<?php echo base_url(); ?>admin_user_group_checker" class="btn btn-success"><i class="icon-remove"></i><span> Cancle </span></a></td>
            </tr>
        </table>

        
    </form>

</div>
