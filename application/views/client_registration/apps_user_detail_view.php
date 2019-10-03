<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">
            Apps User Details
            <a href="<?php echo base_url() . 'client_registration/index'; ?>" class="btn btn-primary pull-right btn-sm">
                <i class="glyphicon glyphicon-plus"></i> Apps User List
            </a>
        </h2>
    </div>
</div>
<input type="hidden" name="skyId" id="skyId" value="<?= $userInfo['skyId'] ?>" />
<div class="clearfix table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr class="bg-primary">
                <td colspan="2">General Information</td>
            </tr>
        </thead>    
        <tr>
            <th style="width:200px;">APPS ID</th>
            <td><?= $userInfo['eblSkyId'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">CFID</th>
            <td><?= $userInfo['cfId'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Client ID</th>
            <td><?= $userInfo['clientId'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Prepaid ID</th>
            <td><?= $userInfo['prepaidId'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">User Group Name</th>
            <td><?= $userInfo['userGroupName'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Date of Birth</th>
            <td><?= $userInfo['dob'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Gender</th>
            <td><?= $userInfo['gender'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Father's Name</th>
            <td><?= $userInfo['fatherName'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Mother's Name</th>
            <td><?= $userInfo['motherName'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Contact Number</th>
            <td><?= $userInfo['userMobNo1'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Alternate Contact Number</th>
            <td><?= $userInfo['userMobNo2'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">User Name</th>
            <td><?= $userInfo['userName'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Email</th>
            <td><?= $userInfo['userEmail'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Current Address</th>
            <td><?= $userInfo['currAddress'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Parmanent Address</th>
            <td><?= $userInfo['parmAddress'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Mailing Address</th>
            <td><?= $userInfo['billingAddress'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Active/Inactive</th>
            <td><?php
                if ($userInfo['isActive'] == 1) {
                    echo "Active";
                } else {
                    echo "Inactive";
                }
                ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Lock/Unlock</th>
            <td><?php
                if ($userInfo['isLocked'] == 1) {
                    echo "Locked";
                } else {
                    echo "Unlocked";
                }
                ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Maker Action</th>
            <td><?= $userInfo['makerAction'] ?></td>
        </tr>
        <tr>
            <th align="left" scope="row">Maker Action Date Time</th>
            <td><?= $userInfo['makerActionDt'] . " " . $userInfo['makerActionTm'] ?></td>
        </tr>
    </table>

    <h3>Account Information</h3>
    <div id="showAccounts" data-bind="visible: accounts().length > 0" class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <th style="text-align:center">Branch Code</th>
                    <th style="text-align:center">Account Number</th>
                    <th style="text-align:center">Product Name</th>
                    <th style="text-align:center">Currency</th>
                </tr>
            </thead>
            <tbody data-bind="foreach: accounts">
                <tr>
                    <td style="text-align:center" data-bind="text:branchCode"></td>
                    <td style="text-align:center" data-bind="text:accNo"></td>
                    <td style="text-align:center" data-bind="text:accName"></td>
                    <td style="text-align:center" data-bind="text:accCurrency"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="well" data-bind="visible: accounts().length == 0">
        <b>No Data Found</b>
    </div>
</div>

<div class="clearfix table-responsive">
    <h3>Device Information</h3>
    <div id="showAccounts" data-bind="visible: devices().length > 0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <th style="text-align:center">APPS ID</th>
                    <th style="text-align:center">Identifier</th>
                    <th style="text-align:center">Is Verified</th>
                    <th style="text-align:center">Verify Date</th>
                </tr>
            </thead>
            <tbody data-bind="foreach: devices">
                <tr>
                    <td style="text-align:center" data-bind="text:eblSkyId"></td>
                    <td style="text-align:center" data-bind="text:identifier"></td>
                    <td style="text-align:center" data-bind="text:isVaryfied, style:{color: verifyColor}"></td>
                    <td style="text-align:center" data-bind="text:varyfiedDtTm"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="well" data-bind="visible: devices().length == 0">
        <h4>No Device Added Yet</h4>
    </div>

    <br>
    <button style="display: block;" id="back" data-bind="click :$root.back" class="btn btn-success">Back</button>
</div>


<script type="text/javascript" charset="utf-8">
    var accountInfo = jQuery.parseJSON('<?= $accountInfo ?>');
    var deviceInfo = jQuery.parseJSON('<?= $deviceInfo ?>');

    var vm = function () {
        var self = this;
        self.accounts = ko.observableArray(accountInfo);
        self.devices = ko.observableArray(deviceInfo);

        $.each(self.accounts(), function (i, account) {  //build the checkboxes as observable
            account.branchCode = account.branchCode;
            account.accNo = account.accNo;
            account.accName = account.accName;
            account.accCurrency = account.accCurrency;
        });

        $.each(self.devices(), function(i, device) { 
            device.eblSkyId = device.eblSkyId;
            device.imeiNo = device.imeiNo;

            if(device.isVaryfied === "1"){
                device.isVaryfied = "YES";
                device.verifyColor = ko.observable("green")
            }else if(device.isVaryfied === "0"){
                device.isVaryfied = "NO";
                device.verifyColor = ko.observable("red")
            }
            device.varyfiedDtTm = device.varyfiedDtTm;
        });
        
        self.back = function(item){
            window.location = "<?php echo base_url(); ?>client_registration";
        };
    };
    ko.applyBindings(new vm());
</script>

