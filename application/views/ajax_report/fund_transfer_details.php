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
                <th>From Account</th>
                <th>To Account</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($result as $i) {
            ?>
                <tr>
                    <td><?= ($sl++) ?></td>
                    <td><?= $i->creationDtTm ?></td>
                    <td><?= get_trn_type($i->trnType) ?></td>
                    <td><?= $i->fromAccNo ?></td>
                    <td><?= $i->toAccNo?></td>
                    <td align="right"><?= $i->amount ?></td>
                    <td><?= ($i->isSuccess == "Y") ? "Success" : "Failed" ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>