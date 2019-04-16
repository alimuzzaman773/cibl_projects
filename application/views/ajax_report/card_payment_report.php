<?php 
    $this->load->view("ajax_report/render_report_parameters.php",$params); 
?>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-hover table-striped">
        <thead>
            <tr class="bg-primary">
                <th>SL</th>
                <th>Source CASA no</th>                
                <th>Destination Card no</th>
                <th>Transaction Currency</th>
                <th>Billing Currency</th>
                <th>Billing Amount</th>
                <th>UBS transaction ref</th>
                <th>GL / A/C number (auto credit entry)</th>
                <th>Transaction date and time</th>
                <th>Transaction code</th>
                <!-- <th>Auth code</th> -->
                <th>Successful reason code</th>
                <th>Successful reason code - explanation</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $sl = 1;
            foreach($result as $r):             
        ?>
            <tr>
                <td><?=($sl++)?></td>
                <td><?=$r->sourceAccNo?></td>
                <td><?=$r->referenceNo?></td>
                <td>BDT</td>
                <td>
                <?php 
                $billerName = $r->billerName;
                $billingCurrency = 'BDT';
                if(strpos(strtoupper($billerName), 'FCY PAYMENT') !== FALSE):
                    $billingCurrency = 'USD';
                endif;
                
                echo $billingCurrency;
                ?>
                </td>
                <td><?=number_format($r->amount,2,'.',',')?></td>
                <td>N/A</td>
                <td>N/A</td>
                <td><?=$r->creationDtTm?></td>
                <td><?=$r->trnReference?></td>
                <!-- <td><?=$r->authCode?></td> -->
                <td><?=$r->isSuccess?></td>
                <td><?=$r->warning?></td>                
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