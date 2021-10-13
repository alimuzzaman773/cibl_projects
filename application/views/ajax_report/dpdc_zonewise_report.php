<?php 
    $this->load->view("ajax_report/render_report_parameters.php",$params); 
?>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-hover table-striped">
        <thead>
            <tr class="bg-primary">
                <th>SL</th>        
                <th>Location Code</th>
                <th>Quantity</th>
                <th style="text-align: right">Bill Amount</th>
                <th style="text-align: right">Vat Amount</th>
                <th style="text-align: right">Total Amount</th>
                <th style="text-align: right">Stamp Charge</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $sl = 1;
            foreach($result as $r):             
        ?>
            <tr>
                <td><?=($sl++)?></td>
                <td><?=$r->zone_code?></td>
                <td><?=$r->totalItem?></td>
                <td style="text-align: right"><?=number_format($r->totalBillAmount + $r->totalStampAmount,2)?></td>
                <td style="text-align: right"><?=number_format($r->totalVatAmount,2)?></td>
                <td style="text-align: right"><?=number_format($r->totalTotalAmount,2)?></td>
                <td style="text-align: right"><?=number_format($r->totalStampAmount,2)?></td>                
            </tr>
        <?php endforeach; ?>
        <?php if(count($result) <= 0): ?>
            <tr>
                <td colspan="15">No data found</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>