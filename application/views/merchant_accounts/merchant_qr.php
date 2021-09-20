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
                <th>Merchant Code</th>
                <td><?= $merchant_details['merchantCode'] ?></td>
            </tr>
            <tr>
                <th>Merchant Category</th>
                <td><?= $merchant_details['merchantCategory'] ?></td>
            </tr>
             <tr>
                <th>Merchant Email</th>
                <td><?= $merchant_details['merchantEmail'] ?></td>
            </tr>
            <tr>
                <th>Merchant Name</th>
                <td><?= $merchant_details['merchantName'] ?></td>
            </tr>
            <tr>
                <th>Merchant Account No.</th>
                <td><?= $merchant_details['merchantAccountNo'] ?></td>
            </tr>
            <tr>
                <th>Merchant Phone</th>
                <td><?= $merchant_details['merchantPhone'] ?></td>
            </tr>
            <tr>
                <th>Merchant Address</th>
                <td><?= $merchant_details['merchantAddress'] ?></td>
            </tr>
            <tr>
                <th>Merchant Website</th>
                <td><?= $merchant_details['merchantWebsite'] ?></td>
            </tr>
            <tr>
                <th>Merchant Logo</th>
                <td><?= $merchant_details['merchantLogo'] ?></td>
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
