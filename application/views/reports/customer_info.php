<div class="report-form-container">
    <h1>Customer Information Report</h1>
    <div class="report-form">
        <form method="get">
            <table width="100%">
                <tr>
                    <td width="200"><span>Creation From Date</span></td>
                    <td width="282"><input name="from" type="text" id="datepicker1" required/></td>
                    <td width="200"><span>Creation To Date</span></td>
                    <td width="238"><input name="to" type="text" id="datepicker2" required/></td>
                    <td width="593"><input type="submit" value="Generate"/></td>
                    <td width="550"><span style="color:red"><?php echo ($msg=="ok")?"":$msg; ?></span></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="show-report">
    <table id="customer_info_table" cellpadding="0">
        <thead>
        <tr>
            <th>SL No</th>
            <th>Customer ID</th>
            <th><?php echo BANK_NAME; ?> SKY ID</th>
            <th>Customer Name</th>
            <th>Email ID</th>
            <th>Mobile No</th>
            <th>Home Branch</th>
            <th>Create Date Time</th>
            <th>Create By</th>
         </tr>
        </thead>
        <tbody>
        <?php $i=0; foreach ($rows as $rows) { $i++; ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->cfId; ?></td>
                <td><?php echo $rows->eblSkyId; ?></td>
                <td><?php echo $rows->userName; ?></td>
                <td><?php echo $rows->userEmail; ?></td>
                <td><?php echo $rows->userMobNo1; ?></td>
                <td><?php echo $rows->ATMName; ?></td>
                <td><?php echo show_date_time($rows->createdDtTm); ?></td>
                <td><?php echo $rows->adminUserName; ?></td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
