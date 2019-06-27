<h2 class="center">Call Center User Report</h2>
<form name="myform" id="myform" method="get" target="_blank" action="<?= $base_url . 'ajax_report/call_center_report/' ?>">
    <table class="table table-bordered table-condensed table-hover table-striped" cellpadding="0" cellspacing="1" border="0" style="width:100%">
        <thead>
            <tr class="bg-primary">
                <th colspan="2">Parameters</th>
            </tr>
        </thead>    
        <tr>
            <td style="width: 140px;"><b>Filter By</b></td>
            <td>
                <div class="col-sm-3 col-xs-12">
                    <select class="form-control input-sm" required name="filterBy" id="filterBy" onchange="app.toggleRegDate()">
                        <option value="">Please select a filter</option>
                        <option value="activated">Activated Users</option>
                        <option value="activation">Waiting Account Activation</option>
                        <option value="passwordReset">Waiting Password Reset</option>
                        <!--                    <option value="activationPending24">Activation Pending More Than 24 Hours</option>
                                            <option value="passwordResetPending24">Password Reset Request Pending More Than 24 Hours</option>-->
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td><b>Approved By</b></td>
            <td>
                <div class="col-sm-3 col-xs-12">
                    <select class="form-control input-sm" name="approved_by" id="approved_by">
                        <option value="">All</option>
                        <?php foreach ($user_list as $b): ?>
                            <option value="<?= $b->adminUserId ?>"><?= $b->fullName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <td><b>Branch</b></td>
            <td>
                <div class="col-sm-3 col-xs-12">
                    <select class="form-control input-sm" name="branch" id="branch">
                        <option value="">All</option>
                        <?php foreach ($branch_list as $b): ?>
                            <option value="<?= $b->branchCode ?>"><?= $b->ATMName ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </td>
        </tr>
        <tr id="registrationDate" style="display:none">
            <td><b>Registration Date</b><br></td>
            <td>
                <div>
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
                        <label>From</label>
                        <input type="text" class="form-control input-sm" required value="" name="fromdate" id="fromdate" />
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-xs-12">
                        <label>To</label>
                        <input type="text" class="form-control input-sm" required  value="" name="todate" id="todate" />
                    </div>                
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
    var app = app || {};
    var form = null;
    $(document).ready(function (e) {
        app.toggleRegDate = function () {
            var $filterBy = jQuery("#filterBy option:selected").val();
            if ($filterBy == 'activation' || $filterBy == 'activationPending24' || $filterBy == 'activated') {
                jQuery("#registrationDate").show()
                return;
            }

            jQuery("#registrationDate").hide();
            jQuery("#fromdate").val('');
            jQuery("#todate").val('');
            return;
        };


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
            url: '<?= $base_url . 'ajax_report/call_center_report/' ?>',
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