<?php 
    $this->load->view("ajax_report/render_report_parameters.php",$params); 
?>
<div class="table-responsive">
    <table class="table table-bordered table-condensed table-hover table-striped">
        <thead>
            <tr class="bg-primary">
                <th>SL</th>
                <th>Source Acc No</th>
                <th>Auth</th>
                <th>Type</th>
                <th>RRN</th>
                <th>Transaction Currency</th>
                <th>Transaction Amount</th>
                <th>Destination Mobile no</th>
                <th>Transaction date and time</th>
                <th>Transaction code</th>
                <th>Successful reason code</th>
                <th>Successful reason code - explanation</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $sl = 1;
            foreach($result as $r):
            $type = 'CBS';
            if(strpos($r->sourceAccNo, "**")):
               $type = 'CMS'; 
            endif;    
            ?>
            <tr>
                <td><?=($sl++)?></td>
                <td><?=$r->sourceAccNo?></td>
                <td><?=$r->authCode?></td>
                <td><?=$type?></td>
                <td>N/A</td>
                <td>BDT</td>                
                <td><?=number_format($r->amount,2,'.',',')?></td>
                <td><?=$r->referenceNo?></td>
                <td><?=$r->creationDtTm?></td>
                <td><?=$r->trnReference?></td>
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