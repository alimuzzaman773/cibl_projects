<?php
$this->load->view("ajax_report/render_report_parameters.php", $params);
?>
<div class="col-md-12">
    <div class="table-responsive">
        <table style="font-size: 12px" class="table table-bordered table-condensed table-hover table-striped tableborder" cellpadding="0" cellspacing="1" border="0" style="width:100%">
            <thead>
                <tr class="bg-primary">
                    <th>SL</th>
                    <th>APPS ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Date</th>
                    <th>Transaction Reference No.</th>
                    <th>Utility Name</th>
                    <th>From Account</th>
                    <th>To Account</th>
                    <th>Bill Number</th>
                    <th>Zone</th>
                    <th style="text-align: right">Bill Amount</th>
                    <th style="text-align: right">Vat Amount</th>
                    <th style="text-align: right">Stamp Amount</th>
                    <th style="text-align: right">LPC Amount</th>
                    <th style="text-align: right">Total Amount</th>
                    <th>Narration</th>
                    <th>Status</th>
                    <th>Lankabangla File Sent</th>
                </tr>
            </thead>
            <?php
            if ($result) {
                $si = 1;
                $total = 0;
                foreach ($result as $r) {
                    $total += $r->bpt_amount;
                    $requestInfo = json_decode($r->request_data, true);
                    $resInfo = json_decode($r->bill_response);
                    //d($resInfo);
                    ?>
                    <tr>
                        <td><?= $si++ ?></td>
                        <td><?= $r->eblSkyId ?></td>
                        <td><?= $r->userName ?></td>
                        <td><?= $r->mobileNo ?></td>
                        <td><?= $r->created ?></td>
                        <td><?= $r->transaction_id ?></td>
                        <td><?= $r->utility_name ?></td>
                        <td><?= $r->from_account ?></td>
                        <td>
                            <?php
                            if ($r->utility_name == "lankabangla" && isset($requestInfo["cardNo"])):
                                echo $requestInfo["cardNo"];
                            endif;

                            if ($r->utility_name == "top_up"):
                                echo $r->entity_number;
                            endif;
                            
                            if ($r->utility_name == "dpdc" && isset($resInfo->data->data->zone_code)):
                                $acc = @$resInfo->data->data->account_number ? @$resInfo->data->data->account_number : NULL;
                                echo $acc;
                            endif;
                            
                            if ($r->utility_name == "desco" && isset($resInfo->data->data->zone_code)):
                                $acc = @$resInfo->data->data->account_number ? @$resInfo->data->data->account_number : NULL;
                                echo $acc;
                            endif;
                            ?>
                        </td>
                        <?php
                        switch ($r->utility_name) {
                            case "dpdc":
                                //d($resInfo->data->data->zone_code);
                                if (isset($resInfo->data->data->zone_code)):
                                    $bill_number = @$resInfo->data->data->bill_number ? @$resInfo->data->data->bill_number : NULL;
                                    $zone = @$resInfo->data->data->zone_code ? @$resInfo->data->data->zone_code : NULL;
                                    $bill_amount = @$resInfo->data->data->bill_amount ? @$resInfo->data->data->bill_amount : 0.00;
                                    $vat_amount = @$resInfo->data->data->vat_amount ? @$resInfo->data->data->vat_amount : 0.00;
                                    $stamp_amount = @$resInfo->data->data->stamp_amount ? @$resInfo->data->data->stamp_amount : 0.00;

                                    echo "<td>{$bill_number}</td>";
                                    echo "<td style='text-align: right'>{$zone}</td>";
                                    echo "<td style='text-align: right'>{$bill_amount}</td>";
                                    echo "<td style='text-align: right'>{$vat_amount}</td>";
                                    echo "<td style='text-align: right'>{$stamp_amount}</td>";
                                    echo "<td></td>";
                                else:
                                    echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
                                endif;
                                continue;
                            case "desco":
                                if (isset($resInfo->data->data->zone_code)):
                                    $bill_number = @$resInfo->data->data->bill_number ? @$resInfo->data->data->bill_number : NULL;
                                    $zone = @$resInfo->data->data->zone_code ? @$resInfo->data->data->zone_code : NULL;
                                    $bill_amount = @$resInfo->data->data->bill_amount ? @$resInfo->data->data->bill_amount : 0.00;
                                    $vat_amount = @$resInfo->data->data->vat_amount ? @$resInfo->data->data->vat_amount : 0.00;
                                    $stamp_amount = @$resInfo->data->data->stamp_amount ? @$resInfo->data->data->stamp_amount : 0.00;
                                    $lpc_amount = @$resInfo->data->data->lpc_amount ? @$resInfo->data->data->lpc_amount : 0.00;

                                    echo "<td>{$bill_number}</td>";
                                    echo "<td style='text-align: right'>{$zone}</td>";
                                    echo "<td style='text-align: right'>{$bill_amount}</td>";
                                    echo "<td style='text-align: right'>{$vat_amount}</td>";
                                    echo "<td style='text-align: right'>{$stamp_amount}</td>";
                                    echo "<td style='text-align: right'>{$lpc_amount}</td>";
                                else:
                                    echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
                                endif;
                                continue;
                            default:
                                echo "<td></td><td></td><td></td>"
                                . "<td style='text-align: right'>{$r->vt_amount}</td>"
                                . "<td style='text-align: right'>{$r->st_amount}</td>"
                                . "<td style='text-align: right'>{$r->lt_amount}</td>";
                        }
                        ?>
                        <td style="text-align: right"><?= $r->bpt_amount ?></td>
                        <td><?= $r->bpt_narration ?></td>
                        <td><?= $r->isSuccess ?></td>
                        <td><?= $r->utility_name == 'lankabangla' ? $r->file_sent : "" ?></td>
                    </tr> 
                    <?php
                }
                ?>
                <tr>
                    <td colspan="15" style="text-align: right"><b>Total:</b></td>
                    <td colspan="4"><?= number_format($total, 2) ?></td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td colspan="19">No data found.</td>
                </tr>
            <?php } ?>  
        </table>
    </div>
</div>