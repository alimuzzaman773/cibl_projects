<div class="report-form-container">
    <h1>Product Request Report</h1>
    <div class="report-form">
        <form method="get">
            <table width="100%">
                <tr>
                    <td width="104"><span>From Date</span></td>
                    <td width="282"><input name="from" type="text" id="datepicker1" required/></td>
                    <td width="76"><span>To Date</span></td>
                    <td width="238"><input name="to" type="text" id="datepicker2" required/></td>
                    <td width="593"><input type="submit" value="Generate"/></td>
                    <td width="593"><span style="color:red"><?php echo ($msg=="ok")?"":$msg; ?></span></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="show-report">
    <table id="product_request_table" cellpadding="0">
        <thead>
        <tr>
            <th>SL No</th>
            <th>Customer Name</th>
            <th>Mobile Number</th>
            <th>Email</th>
	    <th>Request Date</th>	
            <th>Location</th>
            <th>Product Name</th>
            <th>Mail Status</th>
            <th>Mail Count Summary</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 0;
        foreach ($rows as $rows) {
            $i++;
            if ($rows["status"] == 1) {
                $mail = "Sent";
            } else if ($rows["status"] == 0) {
                $mail = "Pending";
            }
            ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows["name"]; ?></td>
                <td><?php echo $rows["contactNo"]; ?></td>
                <td><?php echo $rows["email"]; ?></td>
		<td><?php echo $rows["creationDtTm"]; ?></td>
                <td><?php echo $rows["myLocation"]; ?></td>
                <td><?php echo $rows["productName"]; ?></td>
                <td><?php echo $mail; ?></td>
                <td><?php echo $rows["mailCountSummary"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
