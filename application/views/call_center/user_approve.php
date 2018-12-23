<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">User Approve</h2>
    </div>
</div>
<div class="col-md-12 col-xs-12 col-sm-12" data-ng-if="user.skyId <= 0">
    User is already approved
</div>
<div class="row" data-ng-if="user.skyId != undefined">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <td colspan="2">General Information</td>
                </tr>
            </thead>    
            <tr>
                <th style="width:200px;">PBL ID</th>
                <td>{{user.eblSkyId}}</td>
            </tr>

            <tr>
                <th align="left" scope="row">CFID</th>
                <td>{{user.cfId}}</td>
            </tr>

            <tr>
                <th align="left" scope="row">Client ID</th>
                <td>{{user.clientId}}</td>
            </tr>

            <tr>
                <th align="left" scope="row">Date of Birth</th>
                <td>{{user.dob}}</td>
            </tr>

            <tr>
                <th align="left" scope="row">Gender</th>
                <td>{{user.gender}}</td>
            </tr>

            <tr>
                <th align="left" scope="row">Father's Name</th>
                <td>{{user.fatherName}}</td>
            </tr>

            <tr>
                <th align="left" scope="row">Mother's Name</th>
                <td>{{user.motherName}}</td>
            </tr>
            <tr>
                <th align="left" scope="row">Contact Number</th>
                <td>{{user.userMobNo1}}</td>
            </tr>
            <tr>
                <th align="left" scope="row">Alternate Contact Number</th>
                <td>{{user.userMobNo2}}</td>
            </tr>

            <tr>
                <th align="left" scope="row">User Name</th>
                <td>{{user.userName}}</td>
            </tr>
            <tr>
                <th align="left" scope="row">Email</th>
                <td>{{user.userEmail}}</td>
            </tr>
            <tr>
                <th align="left" scope="row">Current Address</th>
                <td>{{user.currentAddress}}</td>
            </tr>
            <tr>
                <th align="left" scope="row">Permanent Address</th>
                <td>{{user.parmAddress}}</td>
            </tr>
            <tr>
                <th align="left" scope="row">Mailing Address</th>
                <td>{{user.billingAddress}}</td>
            </tr>
        </table>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12" data-ng-if="user_accounts.length > 0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <td colspan="4">Account Information</td>
                </tr>
            </thead>    
            <tr>
                <th>Account No</th>
                <th>Account Type</th>
                <th>Product Name</th>
                <th>Currency</th>
            </tr>

            <tr data-ng-repeat="ua in user_accounts track by $index">
                <td>{{ua.accNo}}</td>
                <td>{{ua.accTypeName}} ({{ua.accTypeCode}})</td>
                <td>{{ua.accName}}</td>
                <td>{{ua.accCurrency}}</td>
            </tr>
        </table>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12" data-ng-if="user_cards.length > 0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <td colspan="4">Card Information</td>
                </tr>
            </thead>    
            <tr>
                <th>Card Number</th>
                <th>Card Type</th>
                <th>Currency</th>
                <th>Card Status</th>
            </tr>
            <tr>
                <td>467922******3019</td>
                <td>VISA</td>
                <td>BDT</td>
                <td>Normal</td>
            </tr>
        </table>
    </div>
    <div class="col-xs-12 col-sm-12">
        <div class="btn-group row">
            <div class="form-group col-sm-12 col-xs-12 hidden">
                <label>Limit Package</label>
                <select class="form-control input-sm" id="appsGroupId" ng-model="user.appsGroupId" data-ng-change="showAppsGroupInfo()">
                    <option value="">Select Limit Package</option>
                    <option ng-repeat="i in appsGroupList" data-ng-if="i.isActive == 1" value="{{i.appsGroupId}}">{{i.userGroupName}} - {{i.groupDescription}}</option>
                </select>
                <div class="alert alert-danger" data-ng-show="user.appsGroupId > 0">
                    Kindly Change/Adjust the Internet Banking Package Before updating this request 
                </div>
            </div>
            <div class="form-group col-sm-12 col-xs-12">
                <label>PIN Sending Channel</label>
                <select class="form-control input-sm" id="otp_channel" ng-model="otp_channel">
                    <option value="email">email</option>
                    <option value="sms">sms</option>
                </select>
            </div> 
            <div class="col-sm-12 col-xs-12">
                <button class="btn btn-lg btn-primary" data-ng-click="approveUser(user.skyId)">
                    <i class="glyphicon glyphicon-check"></i> Approve and Update Limit Package
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var app = app || {};
    app.appsUserGroups = <?=json_encode($appUserGroupInfo)?>;
</script>