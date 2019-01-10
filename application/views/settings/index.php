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
                <td><b>Minimum Amount</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="top_up[topup_min]" value="<?= $this->settings_model->getItem('topup_min', $settings['top_up']) ?>">
                    <small class=''>Minimum amount per day for top-up</small>
                </td>
            </tr>
            <tr>
                <td><b>Maximum Amount</b></td>
                <td>
                    <input type="number" min="0" class="form-control input-sm" name="top_up[topup_max]" value="<?= $this->settings_model->getItem('topup_max', $settings['top_up']) ?>">
                    <small class=''>Minimum amount per day for top-up</small>
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
                    window.location = app.baseUrl+"settings";
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

    });
</script>