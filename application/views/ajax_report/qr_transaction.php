<?php
$this->load->view("ajax_report/render_report_parameters.php", $params);
?>
<div class="table-responsive">        
    <table class="table bg_white table-striped table-bordered table-hover" style="font-size: 11px">          
        <thead>
            <tr class="bg-primary">
                <th>SI</th>
                <th>Transaction Date</th>
                <th>Apps ID</th>
                <th>Username</th>
                <th>CIF ID</th>
                <th>Client ID</th>
                <th>Merchant Name</th>
                <th>Merchant Address</th>
                <th>Transaction Mode</th>
                <th>From Account</th>
                <th>To Account</th>
                <th>Narration</th>
                <th>Batch Number</th>
                <th>Warning</th>
                <th>Transaction ID</th>
                <th>Amount</th>
                <th>Paid By</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            foreach ($result as $i) {
                $from_acc = $i->fromAccNo;
                $to_acc = $i->toAccNo;

                if ($i->transactionMode == "cbs_to_cms"):
                    $to_acc = mask_number(MASK, $i->toAccNo);
                endif;

                if ($i->transactionMode == "cms_to_cbs"):
                    $from_acc = mask_number(MASK, $i->fromAccNo);
                endif;
                ?>
                <tr>
                    <td><?= ($sl++) ?></td>
                    <td><?= $i->creationDtTm ?></td>
                    <td><?= $i->eblSkyId ?></td>
                    <td><?= $i->userName ?></td>
                    <td><?= $i->cfId ?></td>
                    <td><?= $i->clientId ?></td>
                    <td><?= $i->merchantName ?></td>
                    <td><?= $i->merchantAddress ?></td>
                    <td><?= $i->transactionMode ?></td>
                    <td><?= $from_acc ?></td>
                    <td><?= $to_acc ?></td>
                    <td><?= $i->narration ?></td>
                    <td><?= $i->crossRefNo ?></td>
                    <td><?= $i->warning ?></td>
                    <td><?= $i->transferId ?></td>
                    <td align="right"><?= $i->amount ?></td>
                    <td><?= $i->adminUserName ?></td>
                    <td><?= $i->isSuccess == 'Y' ? 'Success' : 'Failed' ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>