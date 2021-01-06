<h2 class="center">Application Settings</h2>
<form class='form' id='settingsform' action='<?= $base_url . "settings/save_settings" ?>' method='post'>
    <table class="table table-bordered table-hover table-condensed table-striped" style="width: 100%" cellpadding="0" cellspacing="1"> 
        <thead>
            <tr class="bg-primary">
                <th colspan="2">
                    App
                    <?php $settings['app'] = (isset($settings['app']) ? $settings['app'] : array()); ?>
                </th>
            </tr>        
        </thead>
        <tr>
            <td style="width: 250px"><b>Application Name</b></td>
            <td>
                <input type="text" required class='form-control' name="app[name]" value="<?= $this->settings_model->getItem('name', $settings['app']) ?>" /> 
            </td>
        </tr>
        <thead>
            <tr class="bg-primary">
                <th colspan="2">
                    Mobile Top-Up
                    <?php $settings['top_up'] = (isset($settings['top_up']) ? $settings['top_up'] : array()); ?>
                </th>
            </tr>        
        </thead>
        <tbody>
            <tr>
                <td><b>Minimum Prepaid Amount</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="top_up[topup_min]" value="<?= $this->settings_model->getItem('topup_min', $settings['top_up']) ?>">
                    <small class=''>Minimum amount per day for prepaid top-up payment</small>
                </td>
            </tr>
            <tr>
                <td><b>Maximum Prepaid Amount</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="top_up[topup_max]" value="<?= $this->settings_model->getItem('topup_max', $settings['top_up']) ?>">
                    <small class=''>Maximum amount per day for Prepaid top-up payment</small>
                </td>
            </tr>
            <tr>
                <td><b>Minimum Postpaid Amount</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="top_up[topup_postpaid_min]" value="<?= $this->settings_model->getItem('topup_postpaid_min', $settings['top_up']) ?>">
                    <small class=''>Minimum amount per day for postpaid top-up payment</small>
                </td>
            </tr>
            <tr>
                <td><b>Maximum Postpaid Amount</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="top_up[topup_postpaid_max]" value="<?= $this->settings_model->getItem('topup_postpaid_max', $settings['top_up']) ?>">
                    <small class=''>Maximum amount per day for postpaid top-up payment</small>
                </td>
            </tr>
            <tr>
                <td><b>Cooling off period</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="top_up[topup_period]" value="<?= $this->settings_model->getItem('topup_period', $settings['top_up']) ?>" placeholder="ex: 10">
                    <small class=''>Time should be minutes</small>
                </td>
            </tr>
        </tbody>
        <thead>
            <tr class="bg-primary">
                <th colspan="2">
                    BEFTN Fund Transfer
                    <?php
                    $settings['beftn'] = (isset($settings['beftn']) ? $settings['beftn'] : array());
                    ?>
                </th>
            </tr>        
        </thead>
        <tbody>
            <tr>
                <td><b>BEFTN Date</b></td>
                <td>
                    <input type="text" class="form-control input-sm" name="beftn[beftn_date]" value="<?= $this->settings_model->getItem('beftn_date', $settings['beftn']) ?>" id="beftnDate" />                
                </td>
            </tr>
        </tbody>
        <thead>
            <tr class="bg-primary">
                <th colspan="2">
                    Nagad Transaction Limit
                    <?php
                    $settings['nagad_payment'] = (isset($settings['nagad_payment']) ? $settings['nagad_payment'] : array());
                    ?>
                </th>
            </tr>        
        </thead>
        <tbody>
            <tr>
                <td><b>Maximum Number of Transaction</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="nagad_payment[max_trn]" value="<?= $this->settings_model->getItem('max_trn', $settings['nagad_payment']) ?>">
                    <small class=''>Maximum transaction per day for Nagad payment</small>
                </td>
            </tr>
            <tr>
                <td><b>Minimum Amount</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="nagad_payment[min_amount]" value="<?= $this->settings_model->getItem('min_amount', $settings['nagad_payment']) ?>">
                    <small class=''>Minimum amount per transaction for Nagad payment</small>
                </td>
            </tr>
            <tr>
                <td><b>Maximum Amount</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="nagad_payment[max_amount]" value="<?= $this->settings_model->getItem('max_amount', $settings['nagad_payment']) ?>">
                    <small class=''>Maximum amount per transaction for Nagad payment</small>
                </td>
            </tr>
            <tr>
                <td><b>Maximum Amount Per Day</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="nagad_payment[max_amount_per_day]" value="<?= $this->settings_model->getItem('max_amount_per_day', $settings['nagad_payment']) ?>">
                    <small class=''>Maximum amount per day for Nagad payment</small>
                </td>
            </tr>
        </tbody>
        <thead>
            <tr class="bg-primary">
                <th colspan="2">
                    RTGS Fund Transfer
                    <?php
                    $settings['rtgs_ft'] = (isset($settings['rtgs_ft']) ? $settings['rtgs_ft'] : array());
                    ?>
                </th>
            </tr>        
        </thead>
        <tbody>
            <tr>    
                <td><b>Minimum Amount Per Transaction</b></td>
                <td>
                    <input type="number" min="0" class='form-control' name="rtgs_ft[rtgs_min]" value="<?= (int) $this->settings_model->getItem('rtgs_min', $settings['rtgs_ft']) ?>" />
                </td>
            </tr>
            <tr>    
                <td><b>Maximum Amount Per Transaction</b></td>
                <td>
                    <input type="number" min="0" class='form-control' name="rtgs_ft[rtgs_max]" value="<?= (int) $this->settings_model->getItem('rtgs_max', $settings['rtgs_ft']) ?>" />
                </td>
            </tr>
            <tr>    
                <td><b>Charge on RTGS</b></td>
                <td>
                    <input type="number" min="0" class='form-control' name="rtgs_ft[rtgs_charge]" value="<?= (int) $this->settings_model->getItem('rtgs_charge', $settings['rtgs_ft']) ?>" />
                </td>
            </tr>
            <tr>    
                <td><b>VAT on Charge</b></td>
                <td>
                    <input type="number" min="0" class='form-control' name="rtgs_ft[rtgs_vat_of_charge]" value="<?= (int) $this->settings_model->getItem('rtgs_vat_of_charge', $settings['rtgs_ft']) ?>" />
                </td>
            </tr>
            <tr>    
                <td><b>RTGS Transaction Time</b></td>
                <td>
                    <label for="from_time">Transaction Time</label>
                    <input type="time" id="from_time" name="rtgs_ft[rtgs_from_time]" value="<?= $this->settings_model->getItem('rtgs_from_time', $settings['rtgs_ft']) ?>">
                    <label for="to_time">To</label>
                    <input type="time" id="to_time" name="rtgs_ft[rtgs_to_time]" value="<?= $this->settings_model->getItem('rtgs_to_time', $settings['rtgs_ft']) ?>">
                </td>
            </tr>
            <tr>    
                <td><b>RTGS Message</b></td>
                <td>
                    <textarea class="form-control" name="rtgs_ft[rtgs_text]" rows="4" cols="50"><?= $this->settings_model->getItem('rtgs_text', $settings['rtgs_ft']) ?></textarea>
                </td>
            </tr>
        </tbody>
        <tr>
            <td></td>
            <td>
                <button class='btn btn-primary pull-right' type='submit' id='save' onclick="return saveData(this);">
                    <i class='glyphicon glyphicon-cog'></i> Save Application Settings
                </button>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript" src="<?= base_url() . ASSETS_FOLDER ?>js/jquery.validate.min.js"></script>
<script type="text/javascript">
                    var saveData = function ($this) {
                        if (!$("#settingsform").valid()) {
                            return false;
                        }

                        $btnhtml = $($this).html();
                        $($this).attr("disabled", "disabled");
                        $($this).html("Saving...");
                        var formData = $("#settingsform").serialize();
                        $.ajax({
                            url: app.baseUrl + "settings/save_settings",
                            data: formData,
                            type: 'post',
                            dataType: 'json',
                            success: function (data) {
                                $($this).html($btnhtml);
                                $($this).removeAttr("disabled");

                                if (data.success) {
                                    alert("Data has been saved");
                                    window.location = app.baseUrl + "settings";
                                    return false;
                                }

                                alert(data.msg);
                            },
                            error: function (data) {
                                $($this).html($btnhtml);
                                $($this).removeAttr("disabled");
                                alert("There was a problem. Please try again later.");
                            }
                        });

                        return false;
                    };

                    $(document).ready(function () {
                        $.validator.addMethod("time", function (value, element) {
                            return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);
                        }, "Please enter a valid time. Format - 10:25");
                        $("#settingsform").validate();


                        $("#beftnDate").datepicker({yearRange: 'c-10:c+10', dateFormat: 'dd-MM-yy', changeMonth: true, changeYear: true}).on('focusin', function () {
                            //$(this).prop("readonly", true);
                        }).on('focusout', function () {
                            //$(this).prop("readonly", false);
                        });


                    });
</script>