<h2 class="title-underlined"><?= $pageTitle ?></h2>
<?php if (trim($message) != ''): ?>
    <div class="alert alert-success"><?php echo $message ?></div>
<?php endif; ?> 

<form method="post" style="" id="packageSelection" name="packageSelection" action="<?php echo base_url(); ?>admin_user_group_maker/selectAction">
    <table class="table">
        <tbody>
            <tr>
                <td></td>
                <td>
                    <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">
                    <label>Admin Group Name</label>
                    <input class="form-control" type="text" name="groupName" id="groupName" required style="width: 33.33%;"><br>
                    <?php foreach ($modules as $item) { ?>
                        <input type="checkbox" name="moduleIds[]" value="<?= $item->moduleId ?>"><label> <?= $item->moduleName ?> </label><br>
                    <?php } ?>


                    <br><br>
                    <label>Authorization</label><br>
                    <?php foreach ($authorizationModules as $index => $value) { ?>
                        <input type="checkbox" name="authorizationModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
                    <?php } ?>


                    <br><br>
                    <label>Content Setup</label><br>
                    <?php foreach ($contentSetupModules as $index => $value) { ?>
                        <input style="margin-right: 15px" type="checkbox" name="contentSetupModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
                    <?php } ?>


                    <br><br>
                    <label>Service Request</label><br>
                    <?php foreach ($serviceRequestModules as $index => $value) { ?>
                        <input style="margin-right: 15px" type="checkbox" name="serviceRequestModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
                    <?php } ?>


                    <br><br>
                    <label>Report Type Modules</label><br>
                    <?php foreach ($reportTypeModules as $index => $value) { ?>
                        <input style="margin-right: 15px" type="checkbox" name="reportTypeModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
                    <?php } ?>

                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-success">Next</button>
                    <a href="<?php echo base_url(); ?>admin_user_group_maker" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back</span></a> 
                </td>               
            </tr>
        </tbody>
    </table>
</form>