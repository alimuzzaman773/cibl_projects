<h2 class="center"><?= $pageTitle ?></h2>
<form name="myform" id="myform" method="get" target="_blank" action="<?= $base_url . 'ajax_report/utility_bills_report/' ?>">
    <table class="table table-bordered table-condensed table-hover table-striped apps_transaction" cellpadding="0" cellspacing="1" border="0" style="width:100%">
        <thead>
            <tr class="bg-primary">
                <th colspan="2">Parameters</th>
            </tr>
        </thead>
        <tr>
            <td style="width: 150px;"><b>From</b><br></td>
            <td>
                <div class="col-md-3">
                    <input type="text" class="form-control input-sm" required value="" name="fromdate" id="fromdate" />
                </div>
            </td>
        </tr>
        <tr>
            <td><b>To</b></td>
            <td>
                <div class="col-md-3">
                    <input type="text" class="form-control input-sm" required  value="" name="todate" id="todate" />
                </div>
            </td>
        </tr>
        <tr>
            <td><b>Status</b></td>
            <td>
                <div class="col-md-3">
                    <select class="form-control input-sm" name="status">
                        <option value="">Show All</option>
                        <option value="Y">Success</option>
                        <option value="N">Failed</option>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td><b>Utility Name</b></td>
            <td>
                <div class="col-md-3">
                    <select class="form-control form-control-sm" name="utility">
                        <option value="">Please select</option>
                        <option value="dpdc">DPDC</option>
                        <option value="desco">DESCO</option>
                        <option value="top_up">Mobile Recharge</option>
                        <option value="wasa">WASA</option>
                        <option value="ois">OIS</option>
                        <option value="buft">BUFT</option>
                        <option value="lankabangla">Lankabangla</option>
                        <option value="wasa_new_connection">WASA New Connection</option>
                        <option value="wasa_demand_note">WASA Demand Note</option>
                        <option value="wasa_general_bill">WASA General Bill</option>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <div class="col-sm-3 col-xs-12">
                    <input type="submit" class="btn btn-primary" id="genReport" name="genReport" onclick="return generateReport(this);" value="Generate Report" />
                    <?php
                    $this->load->view("utility/report_generators.php");
                    ?>
                </div>
            </td>
        </tr>
    </table>
</form>

<br clear="all" />
<?php
$this->load->view("utility/output.php", array("reportDivId" => "reportDiv"))
?>
<div style="display: none" class="row" id="reportDiv"></div>


<link rel = "stylesheet" media = "screen" href="<?= $base_url . ASSETS_FOLDER ?>css/jqueryui/jquery-ui-1.9.2.custom.min.css" charset="utf-8" />
<script type="text/javascript" src="<?= $base_url . ASSETS_FOLDER ?>js/jqueryui/jquery-ui-1.9.2.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $base_url . ASSETS_FOLDER ?>js/utility.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $base_url . ASSETS_FOLDER ?>js/jquery.validation/jquery.validate.min.js"></script>
<script type="text/javascript">
    var form = null;
    $(document).ready(function (e) {
        //populateBranchList("branchId");
        $("#myform").validate();

        $("#fromdate").datepicker({yearRange: 'c-10:c+10', dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true}).on('focusin', function () {
            $(this).prop("readonly", true);
        }).on('focusout', function () {
            $(this).prop("readonly", false);
        });
        $("#todate").datepicker({yearRange: 'c-10:c+10', dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true}).on('focusin', function () {
            $(this).prop("readonly", true);
        }).on('focusout', function () {
            $(this).prop("readonly", false);
        });
    });

    var checkForm = function ()
    {
        if ($("#myform").valid()) {
            return true;
        }
        return false;
    };

    var generateReport = function (dom)
    {
        if (!checkForm()) {
            return false;
        }

        $("#report-options").hide();

        var btn = $(dom);
        var btnhtml = btn.attr("value");
        btn.attr("disabled", "disabled").attr("value", "Generating...");
        var formData = $("#myform").serialize();
        $.ajax({
            url: '<?= $base_url . 'ajax_report/utility_bills_report/' ?>',
            type: 'post',
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                btn.removeAttr("disabled").attr("value", btnhtml);
                $("#reportDiv").html(data.msg).show();
                $("#report-options").show();
            },
            error: function (data) {
                btn.removeAttr("disabled").attr("value", btnhtml);
                alert("There was a problem. Please try again later.");
            }
        });
        return false;
    };
</script>
