<div class="report-form-container">
<script type="text/javascript" language="javascript">
   
</script>
    <h1>Fund Transfer Report</h1>
    <div class="report-form">
        <form method="get">
            <table width="100%">
                <tr>
                    <td width="143"><span>Report Types</span></td>
                    <td width="281">
                        <select name="type" required>
                            <option value="06">Own Account</option>
                            <option value="07"><?php echo BANK_NAME; ?> Account</option>
                        </select>
                    </td>
                    <td width="124"><span>From Date</span></td>
			<td width="259"><input  name="from" type="text" id="datepicker1" required></td>
                    <td width="97"><span>To Date</span></td>
                    <td width="198"><input name="to" type="text" id="datepicker2" required/></td>
                    <td width="183"><input type="submit" value="Generate"/></td>
                    <td width="550"><span style="color:red"><?php echo ($msg=="ok")?"":$msg; ?></span></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="show-report">
    <table id="fund_transfer_table" cellpadding="0">
        <thead>
        <tr>
            <th>SL No</th>
            <th>Customer ID</th>
            <th>Apps ID</th>
            <th>Mobile No</th>
            <th>Email ID</th>
            <th>Transaction Date</th>
            <th>From Account</th>
            <th>To Account</th>
            <th>Amount</th>
            <th>Is Success</th>
            <th>Ref No</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=0; foreach ($rows as $rows) { $i++; ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->cfid; ?></td>
                <td><?php echo $rows->eblSkyId; ?></td>
                <td><?php echo $rows->userMobNo1; ?></td>
                <td><?php echo $rows->userEmail; ?></td>
                <td><?php echo show_date_time($rows->creationDtTm); ?></td>
                <td><?php echo $rows->fromAccNo; ?></td>
                <td><?php echo $rows->toAccNo; ?></td>
                <td><?php echo $rows->amount; ?></td>
                <td><?php echo $rows->isSuccess; ?></td>
                <td><?php echo $rows->trnReference; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
