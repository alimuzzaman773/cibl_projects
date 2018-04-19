<h1 class="title-underlined">Apps User</h1>
<div class="clearfix">
    <div class="alert alert-success"><?php echo $message ?></div>
    <form method="post" class="form-inline" id="UserGroupForm" name="UserGroupForm" action="<?php echo base_url(); ?>apps_users/assignUserGroup">
        <input type="hidden" name="skyId" id="skyId" value="<?= $skyId ?>" />
        <input type="hidden" name="action" id="action" value="" />

        <div class="form-group col-md-8 col-sm-8 col-xs-12">
            <label>
                Assign User Group
            </label>
            <select style="max-width: 300px;" class="form-control" id="group" name="group" onchange="dropDown();">
                <?php foreach ($userGroup as $item) { ?>
                    <option value="<?= $item->appsGroupId ?>"><?= $item->userGroupName ?></option>
                <?php } ?>
            </select>
        </div>    

        <div class="clearfix table-responsive" id="assignPackage">
            <table class="table table-striped table-bordered" id="packageTable">
                <thead>
                    <tr>
                        <th hidden >ID</th>
                        <th style="text-align: center" valign="middle">Package Name</th>
                        <th style="text-align: center" valign="middle">Minmum Transaction Limit</th>
                        <th style="text-align: center" valign="middle">Maximum Transaction Limit</th>
                        <th style="text-align: center" valign="middle">Day Transaction Limit</th>
                        <th style="text-align: center" valign="middle">No. of Transaction Per Day</th>
                    </tr>
                </thead>
                <tbody data-bind="foreach: records">
                    <tr>
                        <td hidden data-bind="text:packageId" > </td>
                        <td style="text-align: center" valign="middle" data-bind="text:packageName"> </td>                
                        <td style="text-align: center" valign="middle" data-bind="text:min"> </td>
                        <td style="text-align: center" valign="middle" data-bind="text:max"> </td>
                        <td style="text-align: center" valign="middle" data-bind="text:dayLimit"> </td>
                        <td style="text-align: center" valign="middle" data-bind="text:perDay"> </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <button class="btn btn-success" onclick="button();">Assign</button>
        <a href='<?php echo base_url(); ?>client_registration' class="btn btn-danger">
            <i class="icon-plus icon-white"></i> Cancel
        </a>  
    </form>
</div>





<script type="text/javascript">

    function dropDown()
    {
        $("#action").val("d");
        $("#UserGroupForm").submit();
    }
    ;

    function button()
    {
        $("#action").val("b");
        $("#UserGroupForm").submit();
    }
    ;

    var packages = jQuery.parseJSON('<?= $packages ?>');//data for building initial table
    var group = jQuery.parseJSON('<?= $group ?>');


    $('#group').val(group.appsGroupId);

    var vm = function () {

        var self = this;
        self.records = ko.observableArray(packages);

        $.each(self.records(), function (i, record) {

            record.packageId = record.packageId;
            record.packageName = record.packageName;

            if (record.packageId == 1) {
                record.min = group.oatMinTxnLim;
                record.max = group.oatMaxTxnLim;
                record.dayLimit = group.oatDayTxnLim;
              record.perDay = group.oatNoOfTxn;
            }

            if(record.packageId == 2){
              record.min = group.eatMinTxnLim;
              record.max = group.eatMaxTxnLim;
              record.dayLimit = group.eatDayTxnLim;
              record.perDay = group.eatNoOfTxn;
            }

            if(record.packageId == 3){
              record.min = group.obtMinTxnLim;
              record.max = group.obtMaxTxnLim;
              record.dayLimit = group.obtDayTxnLim;
              record.perDay = group.obtNoOfTxn;
            }

            if(record.packageId == 4){
              record.min = group.pbMinTxnLim;
              record.max = group.pbMaxTxnLim;
              record.dayLimit = group.pbDayTxnLim;
              record.perDay = group.pbNoOfTxn;
            }
        })
    }
    ko.applyBindings(new vm());

</script>




