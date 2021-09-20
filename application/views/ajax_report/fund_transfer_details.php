<?php
$this->load->view("ajax_report/render_report_parameters.php", $params);
?>
<div class="table-responsive">        
    <table class="table bg_white table-striped table-bordered table-hover" style="font-size: 11px">          
        <thead>
            <tr class="bg-primary">
                <th>SI</th>
                <th>Transaction Date</th>
                <th>Transaction Type</th>
                <th>Currency</th>
                <th>From Account</th>
                <th>To Account</th>
                <th>Routing Number</th>
                <th>Reference Number</th>
                <th>File Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Source</th>
                <th>Status Log</th>
                <th>IsReverse</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result) {
                $si = 1;
                $total = 0;
                foreach ($result as $i) {
                    $total += $i->amount;
                    ?>
                    <tr>
                        <td><?= ($si++) ?></td>
                        <td><?= $i->creationDtTm ?></td>
                        <td><?= get_trn_type($i->trnType) ?></td>
                        <td><?= $i->currency ?></td>
                        <td><?= $i->fromAccNo ?></td>
                        <td><?= $i->toAccNo ?></td>
                        <td><?= $i->rcvrRtNo ?></td>
                        <td><?= $i->crossRefNo > 0 ? $i->crossRefNo : '' ?></td>
                        <td><?= $i->fileName ?></td>
                        <td align="right"><?= $i->amount ?></td>
                        <td><?= ($i->isSuccess == "Y") ? "Success" : "Failed" ?></td>
                        <td><?= $i->source ?></td>
                        <td><?= json_display_html($i->data) ?></td>
                        <td><?= $i->isReverse ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="9" align="right"><b>Total Amount:</b></td>
                    <td align="right"><?= number_format($total, 2); ?></td>
                    <td colspan="5"></td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td colspan="14">No data found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>