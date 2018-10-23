<div class="report-form-container">
    <h1>Last User ID Modification Report</h1>
    <div class="report-form">
        <form method="get">
            <table width="100%">
                <tr>
                    <td width="104"><span>From Date</span></td>
                    <td width="282"><input name="from" type="text" id="datepicker1" required/></td>
                    <td width="76"><span>To Date</span></td>
                    <td width="238"><input name="to" type="text" id="datepicker2" required/></td>
                    <td width="593"><input type="submit" value="Generate"/></td>
                    <td width="550"><span style="color:red"><?php echo ($msg=="ok")?"":$msg; ?></span></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="show-report">
    <table id="id_modification_table" cellpadding="0">
        <thead>
        <tr>
            <th>SL No</th>
            <th>Customer ID</th>
            <th>Client ID</th>
            <th>Apps ID</th>
            <th>Customer Name</th>
            <th>Mobile No</th>
            <th>Email ID</th>
            <th>Last Mod Date</th>
            <th>Last Action</th>
            <th>Maker ID</th>
            <th>Checker ID</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i=0;
        foreach ($rows as $data) {
                        $i++;
            ?>
            <tr>
                <td><?php echo $i;?></td>
                <td><?php echo $data->cfId; ?></td>
                <td><?php echo $data->clientId; ?></td>
                <td><?php echo $data->eblSkyId; ?></td>
                <td><?php echo $data->userName; ?></td>
                <td><?php echo $data->userMobNo1; ?></td>
                <td><?php echo $data->userEmail; ?></td>
                <td><?php echo show_date($data->makerActionDt); ?></td>
                <td><?php echo $data->makerAction; ?></td>
                <td><?php echo admin_name($data->makerActionBy); ?></td>
                <td><?php echo admin_name($data->checkerActionBy); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
