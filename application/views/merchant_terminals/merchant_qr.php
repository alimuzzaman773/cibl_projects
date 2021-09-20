<div class="container">
    <h3 class="title-underlined">Merchant Account QR</h3>

    <div class="col-md-8 col-md-offset-2">
        <div class="text-center">
            <div style="margin: auto; text-align: center;" id="qrcode"></div>	
        </div>
        <table class="table table-striped table-bordered">
            <thead class="bg-primary">
                <tr><th colspan="2">
                        Merchant Details
                    </th></tr>
            </thead>
            <tr>
                <th>Merchant ID</th>
                <td><?= $merchant_details['merchantId'] ?></td>
            </tr>
            <tr>
                <th>Merchant Name</th>
                <td><?= $merchant_details['merchantName'] ?></td>
            </tr>
            <tr>
                <th>Terminal Name</th>
                <td><?= $merchant_details['terminalName'] ?></td>
            </tr>
            <tr>
                <th>Account No.</th>
                <td><?= $merchant_details['accountNo'] ?></td>
            </tr>
            <tr>
                <th>Currency</th>
                <td><?= $merchant_details['currency'] ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?= $merchant_details['address'] ?></td>
            </tr>
            <tr>
                <th>City</th>
                <td><?= $merchant_details['city'] ?></td>
            </tr>
            <tr>
                <th>District</th>
                <td><?= $merchant_details['district'] ?></td>
            </tr>
            <tr>
                <th>Zip</th>
                <td><?= $merchant_details['zip'] ?></td>
            </tr>
        </table>
    </div>
</div>
<?php ci_add_js(asset_url() . "js/qrcodejs/qrcode.js"); ?>

<script type="text/javascript">
    var app = app || {};
    app.merchantInfo = '<?= $merchant_enc ?>';
    $(document).ready(function () {
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            width: 250,
            height: 250
        });
        qrcode.makeCode(app.merchantInfo);

    });
</script>
