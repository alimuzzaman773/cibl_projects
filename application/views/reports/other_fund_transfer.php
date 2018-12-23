<div class="report-form-container">
<script type="text/javascript" language="javascript">
   
</script>
    <h1>Other Fund Transfer Report</h1>
    <div class="report-form">
        <form method="get">
            <table width="100%">
                <tr>
                    <td width="90"><span>From Date</span></td>
		      <td width="50"><input  name="from" type="text" id="datepicker1" required></td>
                    <td width="97"><span>To Date</span></td>
                    <td width="90"><input name="to" type="text" id="datepicker2" required/></td>
                    <td width="100"><input type="submit" value="Generate"/></td>
                    <td width="450"><span style="color:red"><?php echo ($msg=="ok")?"":$msg; ?></span></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="show-report">
    <table id="other_fund_transfer_table" cellpadding="0">
        <thead>
        <tr>
            <th>SL No</th>
            <th>Customer ID</th>
            <th>Apps ID</th>
            <th>Reciever Name</th>
            <th>Bank Name</th>
            <th>Reciver Branch</th>
            <th>Routing No</th>
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
                <td><?php echo $rows->rcvrName; ?></td>
                <td><?php echo $rows->rcvrBankName; ?></td>
                <td><?php echo $rows->rcvrBrunName; ?></td>
                <td><?php echo $rows->rcvrRtNo; ?></td>
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
