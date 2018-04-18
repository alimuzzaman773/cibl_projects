<h2 class="title-underlined"><?= $pageTitle ?></h2>
<?php if (trim($message) != ''): ?>
    <div class="alert alert-success"><?php echo $message ?></div>
<?php endif; ?> 

<form method="post" style="" id="packageSelection" name="packageSelection" action="<?php echo base_url(); ?>admin_user_group_maker/editAction">
    <table class="table">
        <tbody>
            <tr>
                <td></td>
                <td>
                    <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

                    <label>Admin Group Name</label>
                    <input class="form-control" type="text" name="groupName" id="groupName" value="<?= $adminGroup['userGroupName'] ?>" required><br><br>
                    <input hidden type="text" name="userGroupId" id="userGroupId" value="<?= $userGroupId ?>">

                    <?php foreach ($modules as $item) { ?>
                        <input id="<?= $item->moduleId ?>" style="margin-right: 15px" type="checkbox" name="moduleIds[]" value="<?= $item->moduleId ?>"><label> <?= $item->moduleName ?> </label><br>
                    <?php } ?>

                    <br><br>
                    <h4>Authorization Modules</h4>
                    <?php foreach ($authorizationModules as $index => $value) { ?>
                        <input id="<?= "AM" . $index ?>" style="margin-right: 15px" type="checkbox" name="authorizationModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
                    <?php } ?>
                    <br><br>
                    <h4>Content Setup</h4>
                    <?php foreach ($contentSetupModules as $index => $value) { ?>
                        <input id="<?= "CMS" . $index ?>" style="margin-right: 15px" type="checkbox" name="contentSetupModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
                    <?php } ?>
                    <br><br>
                    <h4>Service Request<h4>
                            <?php foreach ($serviceRequestModules as $index => $value) { ?>
                                <input id="<?= "SR" . $index ?>" style="margin-right: 15px" type="checkbox" name="serviceRequestModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
                            <?php } ?>
                            <br><br>
                            <h4>Report Type Modules</h4>
                            <?php foreach ($reportTypeModules as $index => $value) { ?>
                                <input id="<?= "RTM" . $index ?>" style="margin-right: 15px" type="checkbox" name="reportTypeModuleCodes[]" value="<?= $index ?>"><label> <?= $value ?> </label><br>
                            <?php } ?>
                            <br><br>
                            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                                <h3>Reject Reason</h3>
                                <textarea name="reason" id="reason" cols="40" rows="5" readonly></textarea>

                            </div>
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

                            <script type="text/javascript">
                                var moduleIds = jQuery.parseJSON('<?= $moduleIds ?>');//data for building initial table
                                moduleIds.forEach(function (entry) {
                                    document.getElementById(entry.moduleId).checked = true;
                                });
                                document.getElementById("reason").value = "<?php echo $checkerActionComment ?>";
                            </script>

                            <script type="text/javascript">
                                var authorizationModuleCodes = <?= $authorizationModuleCodes ?>;//data for building initial table
                                if (authorizationModuleCodes != "") {
                                    authorizationModuleCodes.forEach(function (entry) {
                                        document.getElementById("AM" + entry).checked = true;
                                    });
                                }
                            </script>


                            <script type="text/javascript">
                                var contentSetupModulesCodes = <?= $contentSetupModulesCodes ?>;//data for building initial table
                                if (contentSetupModulesCodes != "") {
                                    contentSetupModulesCodes.forEach(function (entry) {
                                        document.getElementById("CMS" + entry).checked = true;
                                    });
                                }
                            </script>


                            <script type="text/javascript">
                                var serviceRequestModuleCodes = <?= $serviceRequestModuleCodes ?>;//data for building initial table
                                if (serviceRequestModuleCodes != "") {
                                    serviceRequestModuleCodes.forEach(function (entry) {
                                        document.getElementById("SR" + entry).checked = true;
                                    });
                                }
                            </script>


                            <script type="text/javascript">

                                var reportTypeModuleCodes = <?= $reportTypeModuleCodes ?>;//data for building initial table
                                if (reportTypeModuleCodes != "") {
                                    reportTypeModuleCodes.forEach(function (entry) {
                                        document.getElementById("RTM" + entry).checked = true;
                                    });
                                }

                            </script>