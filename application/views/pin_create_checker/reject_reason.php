<h3 class="title-underlined">Reject Reason</h3>

<div class="container" style="margin-top:50px">

    <form method="post" style="" id="reasonForm" name="reasonForm" action="<?php echo base_url(); ?>pin_create_checker/checkerReject">
        <input hidden type="text" name="makerActionDtTm" id="makerActionDtTm" value="<?= $makerActionDtTm ?>">
        <input hidden type="text" name="checkerActionDtTm" id="checkerActionDtTm" value="<?= $checkerActionDtTm ?>">
        <?php if ($message != "") { ?>
            <div class="alert alert-success"><?php echo $message ?></div>
        <?php } ?>
        <input hidden class="textbox" type="text" name="requestId" id="requestId" value="<?= $requestId ?>">

        <div class="form-group">
            <label>Type your reject reason</label>
            <textarea class="form-control" type="text" name="checkerActionComment" id="checkerActionComment" rows="4" cols="50" placeholder="Write reason here..." required style="width: 500px"></textarea>
        </div>
        <div class="btn-group">
            <button type="submit" form="reasonForm" value="Submit" class="btn btn-success">Submit</button>
            <a href="<?php echo base_url(); ?>pin_create_checker" class="btn btn-danger"><i class="icon-plus icon-white"></i><span>Cancel</span></a> 
        </div>
    </form>
</div>



