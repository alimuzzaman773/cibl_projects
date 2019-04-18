<?php
$this->load->view("ajax_report/render_report_parameters.php", $params);
?>
<div class="col-md-12">
    <div class="table-responsive">
        <table style="font-size: 12px" class="table table-bordered table-condensed table-hover table-striped tableborder" cellpadding="0" cellspacing="1" border="0" style="width:100%">
            <thead>
                <tr class="bg-primary">
                    <th>SL</th>
                    <th>Date</th>
                    <th>Transaction Reference No.</th>
                    <th>Utility Name</th>
                    <th>From Account</th>
                    <th>BPT Amount</th>
                    <th>Vat Amount</th>
                    <th>Stamp Amount</th>
                    <th>LPC Amount</th>
                    <th>Narration</th>
                    <th>Status</th>
                </tr>
            </thead>
            <?php
            if ($result) {
                $si = 1;
                foreach ($result as $r) {
                    ?>
                    <tr>
                       <td><?= $si++ ?></td>
                        <td><?= $r->created ?></td>
                        <td><?= $r->transaction_id?></td>
                        <td><?= $r->utility_name ?></td>
                        <td><?=$r->from_account ?></td>
                        <td><?=$r->bpt_amount?></td>
                        <td><?= $r->vt_amount ?></td>
                        <td><?= $r->st_amount ?></td>
                        <td><?= $r->lt_amount ?></td>
                        <td><?= $r->bpt_narration ?></td>
                        <td><?= $r->isSuccess ?></td>
                    </tr> 
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="23">No data found.</td>
                </tr>
            <?php } ?>  
        </table>
    </div>
</div>