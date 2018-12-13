<div class="report-form-container">
    <h2 class="center"><?=$pageTitle?></h2>
    <div class="report-form">
        <form method="get">
            <table class="table table-bordered table-condensed table-hover table-striped" cellpadding="0" cellspacing="1" border="0" style="width:100%">
                <tr class="">
                    <td width="104"><span>Status</span></td>
                    <td width="201">
                        <select name="status" required class="form-control">
                            <option value="">---Select---</option>                               
                            <option value="all">All</option>                            
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="locked">Locked</option>
                            <option value="unlocked">Unlocked</option>
                        </select>
                    </td>
                    <td width="996">
                        <input type="submit" value="Generate"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="show-report">
    <table id="user_status_table" cellpadding="0" class="table table-bordered table-condensed table-hover table-striped">
        <thead>
            <tr class="bg-primary">
                <th>SL No</th>
                <th>Customer ID</th>
                <th>Client ID</th>
                <th><?php echo BANK_NAME; ?> Sky ID</th>
                <th>Customer Name</th>
                <th>Mobile No</th>
                <th>Email ID</th>          
                <th>Is Active</th>
                <th>Is Locked</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach ($rows as $rows) {
                $i++;

                if ($rows->isActive == 1) {
                    $active = "Active";
                } else if ($rows->isActive == 0) {
                    $active = "Inactive";
                }


                if ($rows->isLocked == 1) {
                    $lock = "Locked";
                } else if ($rows->isLocked == 0) {
                    $lock = "Unlocked";
                }
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $rows->cfId; ?></td>
                    <td><?php echo $rows->clientId; ?></td>
                    <td><?php echo $rows->eblSkyId; ?></td>
                    <td><?php echo $rows->userName; ?></td>
                    <td><?php echo $rows->userMobNo1; ?></td>
                    <td><?php echo $rows->userEmail; ?></td>
                    <td><?php echo $active; ?></td>
                    <td><?php echo $lock; ?></td>
                </tr>
<?php } ?>
        </tbody>
    </table>
</div>


<!-- report-->    
<script src="<?php echo base_url(); ?>assets/data_table/data_table.min.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/dataTables.buttons.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/jszip.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/pdfmake.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/buttons.html5.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/buttons.flash.js"></script>
<script src="<?php echo base_url(); ?>assets/data_table/buttons.print.js"></script>
<script src="<?php echo base_url(); ?>assets/js/reports.js"></script>
<script src="<?php echo base_url(); ?>assets/datepicker/jquery-ui.min.js"></script>
<link href="<?php echo base_url(); ?>assets/data_table/data_table.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/reports.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/datepicker/jquery-ui.min.css">
<!--end of report--> 