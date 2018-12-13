<script src="<?php echo base_url(); ?>assets/js/jquery.excel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/js_pdf.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/html2canvas.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.print.js"></script>


<div class="report-form-container">
    <h1>Bill Pay Report</h1>

    <div class="report-form">
        <form method="get">
            <table width="100%">
                <tr>
                    <td width="112"><span>Filter Type</span></td>
                    <td width="202">
                        <select name="type" required>
                            <option value="1">By Company</option>
                            <option value="2">By User</option>
                        </select>                    </td>
                    <td width="106"><span>From Date</span></td>
                    <td width="157"><input name="from" type="text" id="datepicker1" required></td>
                    <td width="99"><span>To Date</span></td>
                    <td width="153"><input name="to" type="text" id="datepicker2" required/></td>
                    <td width="89"><input type="submit" value="Generate"/></td>
                    <td width="450"><span style="color:red"><?php echo ($msg=="ok")?"":$msg; ?></span></td>
                </tr>
            </table>
        </form>
    </div>
</div>


<div id="show-report">
    <div class="report-btn">
       <a href="#" class="btn btn-primary btn-sm" id="bill_excel">Excel</a>
       <a href="#" class="btn btn-primary btn-sm" id="bill_pdf">PDF</a>
       <a href="#" class="btn btn-primary btn-sm" id="bill_print">Print</a>
    </div>
    <table id="bill_pay_table" cellpadding="0">
        <?php
        if(isset($type)){
        if ($type == 1) {
            ?>
            <thead>
            <tr>
                <th width="55">SL No</th>
                <th width="71">SKY ID</th>
                <th width="115">Date Time</th>
                <th width="132">Customer ID</th>
                <th width="87">Customer Name</th>
                <th width="104">Source Account</th>
                <th width="65">Success</th>
                <th width="65">Bill Type</th>
                <th width="52">Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($rows as $rows) { ?>
                <tr>
                    <td colspan="9" class="data-title">Biller Name: <?php echo $rows['billerName'] ?>, Biller
                        Code: <?php echo $rows['billCode'] ?></td>
                </tr>
                <?php
                $i = 0;
                $total = 0;
                foreach ($rows['billerCode'] as $data) {
                    $i++;
                    $amount = $data['amount'];
                    $total += $amount;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data['eblSkyId']; ?></td>
                        <td><?php echo show_date_time($data['creationDtTm']); ?></td>
                        <td><?php echo $data['cfId']; ?></td>
                        <td><?php echo $data['userName']; ?></td>
                        <td><?php echo $data['sourceAccNo']; ?></td>
                        <td><?php echo $data['isSuccess']; ?></td>
                        <td><?php echo $data['billTypeName']; ?></td>
                        <td><?php echo $data['amount']; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="8" class="data-title">Total</td>
                    <td class="data-title data-bottom"><?php echo number_format($total,2);?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        <?php } ?>


        <?php
        if ($type == 2) {
            ?>
            <thead>
            <tr>
                <th width="55">SL No</th>
                <th width="71">Date Time</th>
                <th width="115">Biller Name</th>
                <th width="132">Biller Code</th>
                <th width="87">Source Account</th>
                <th width="104">Success</th>
                <th width="65">Bill Type</th>
                <th width="65">Amount</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($rows as $rows) { ?>
                <tr>
                    <td colspan="8" class="data-title">Customer Name: <?php echo $rows['userName'] ?>, SKY ID
                        : <?php echo $rows['eblSkyId'] ?>, CF ID
                        : <?php echo $rows['cfId'] ?></td>
                </tr>
                <?php
                $i = 0;
                $total = 0;
                foreach ($rows['skyId'] as $data) {
                    $i++;
                    $amount = $data['amount'];
                    $total += $amount;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo show_date_time($data['creationDtTm']); ?></td>
                        <td><?php echo $data['billerName']; ?></td>
                        <td><?php echo $data['billerCode']; ?></td>
                        <td><?php echo $data['sourceAccNo']; ?></td>
                        <td><?php echo $data['isSuccess']; ?></td>
                        <td><?php echo $data['billTypeName']; ?></td>
                        <td><?php echo $data['amount']; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="7" class="data-title">Total</td>
                    <td class="data-title data-bottom"><?php echo number_format($total,2);?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        <?php }
        }else{?>
        <thead>
        <tr>
            <th width="55">SL No</th>
            <th width="71">SKY ID</th>
            <th width="115">Date Time</th>
            <th width="132">Customer ID</th>
            <th width="87">Customer Name</th>
            <th width="104">Source Account</th>
            <th width="65">Success</th>
            <th width="65">Bill Type</th>
            <th width="52">Amount</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
            <?php
        } ?>
    </table>
</div>


<script>
    $("#bill_excel").click(function () {
        $("#bill_pay_table").table2excel({
            exclude: ".noExl",
            filename: "Bill Pay Report"
        });
    });

    $("#bill_print").click(function () {
        $("#bill_pay_table").print({
            globalStyles: true,
            mediaPrint: false,
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 250,
            title: 'Bill Pay',
            doctype: '<!doctype html>'
        });
    });

    $("#bill_pdf").click(function () {
        html2canvas($("#bill_pay_table"), {
            onrendered: function (canvas) {
                var imgData = canvas.toDataURL('image/png');
                var doc = new jsPDF("l", "pt", [500, window.innerWidth]);
                doc.addImage(imgData, 'JPG', 10, 10);
                doc.save('bill_pay_report.pdf');
            }
        });
    });


</script>

