
<title>Apps User</title>
<div class="breadcrum">Detail View</div>



<style>

label {
    float:left;
    width:180px;
    clear:left;
    text-align:right;
    padding-right:10px;
    color: #009933;
}

input {
    float:center;
    border: 1px solid #848484; 
    -webkit-border-radius: 30px; 
    -moz-border-radius: 30px; 
    border-radius: 30px; 
    outline:0; 
    height:25px; 
    width: 275px; 
    padding-left:10px; 
    padding-right:10px; 
}
</style>




<input type="hidden" name="skyId" id="skyId" value="<?=$userInfo['skyId']?>" />


<div class="container" style="margin-top:50px">


<h3> General Information <h3>

<table class="table table-striped table-bordered">    
    <tr>
        <th align="left" scope="row">ESB ID</th>
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
        <td><?php if($userInfo['isActive'] == 1) {echo "Active";} else {echo "Inactive";} ?></td>
    </tr>
    
    <tr>
        <th align="left" scope="row">Lock/Unlock</th>
        <td><?php if($userInfo['isLocked'] == 1) {echo "Locked";} else {echo "Unlocked";} ?></td>
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

<br><br>







<h3> Account Information <h3>
<div id="showAccounts" data-bind="visible: accounts().length > 0">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
				<th style="text-align:center">Account Number</th>
                <th style="text-align:center">Account Type</th>
                <th style="text-align:center">Product Name</th>
                <th style="text-align:center">Currency</th>
            </tr>
        </thead>
        <tbody data-bind="foreach: accounts">
            <tr>
				<td style="text-align:center" data-bind="text:accNo"></td>
                <td style="text-align:center" data-bind="text:accType"></td>
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


<div class="container" style="margin-top:50px">

<h3> Device Information <h3>
<div id="showAccounts" data-bind="visible: devices().length > 0">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="text-align:center">ESB ID</th>
                <th style="text-align:center">IMEI No.</th>
                <th style="text-align:center">Is Verified</th>
                <th style="text-align:center">Verify Date</th>
            </tr>
        </thead>
        <tbody data-bind="foreach: devices">
            <tr>
                <td style="text-align:center" data-bind="text:eblSkyId"></td>
                <td style="text-align:center" data-bind="text:imeiNo"></td>
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

    var vm = function() {
        var self = this;
        self.accounts = ko.observableArray(accountInfo);
        self.devices = ko.observableArray(deviceInfo);

        $.each(self.accounts(), function(i, account) {  //build the checkboxes as observable
            account.accNo = account.accNo;
			account.accType = account.accType;
            account.accName = account.accName;
            account.accCurrency = account.accCurrency;
        })

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
        })


        
        self.back = function(item){
            window.location = "<?php echo base_url(); ?>client_registration";
        }



    }
    ko.applyBindings(new vm());
</script>

