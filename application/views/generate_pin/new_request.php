<h2 class="title-underlined"><?= $pageTitle ?></h2>
<?php if (trim($message) != ''): ?>
    <div class="alert alert-success"><?php echo $message ?></div>
<?php endif; ?>

<form method="post" style="" id="pinRequestForm" name="pinRequestForm" action="<?php echo base_url(); ?>pin_generation/insertNewRequest">
    <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">
    <fieldset>
        <table class="table table-condensed table-bordered table-striped">
            <tr>
                <th width="150" align="left" scope="row">Total Pin*</th>
                <td>
                    <input type="number" min="1" max="100" name="totalPin" id="totalPin" style="width: 33.33%;" class="form-control" required/>
                </td>
            </tr>
            <tr>
                <th align="left" scope="row">Remark</th>
                <td>
                    <textarea type="text" name="makerActionComment" id="makerActionComment" rows="4" cols="50" placeholder="Write remark here..." style="width: 33.33%;" class="form-control" required></textarea>
                </td>
            </tr>
        </table>
    </fieldset>
    <button type="submit" form="pinRequestForm" value="Submit" class="btn btn-success">Submit</button>
    <a href="<?php echo base_url(); ?>pin_generation/index" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a> 
</form>
