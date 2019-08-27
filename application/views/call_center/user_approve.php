<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">User Approve</h2>
    </div>
</div>

<div class="col-md-12 col-xs-12 col-sm-12" data-ng-if="user.skyId == undefined">
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
                <th style="width:200px;">APPS ID</th>
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
            <tr>
                <th align="left" scope="row">Request Type</th>
                <td>{{user.entityType}}</td>
            </tr>
            <tr>
                <th align="left" scope="row">Request Account/Card</th>
                <td>{{user.entityNumber}}</td>
            </tr>
            <tr>
                <th align="left" scope="row">Request Time</th>
                <td>{{user.created_on}}</td>
            </tr>
        </table>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-primary">
                        <td colspan="7">View Only Customer Registration</td>
                    </tr>
                    <tr>
                        <th>Own Account Transfer</th>
                        <th>PBL Account Transfer</th>
                        <th>Other Account Transfer</th>
                        <th>Account To Card Transfer</th>
                        <th>Card To Account</th>
                        <th>Utility</th>
                        <th>QR Payment</th>
                    </tr>
                </thead>
                <tbody  ng-show="(user.checkerActionBy <= 0 && user.makerActionBy <= 0)" ng-click="approveUserChecker(user.skyId)">
                    <tr>
                        <td align="center">
                            <input type="checkbox" class="checkbox" name="isOwnAccTransfer" id="isOwnAccTransfer" ng-model="trOptions.isOwnAccTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                        </td>
                        <td align="center">
                            <input type="checkbox" class="checkbox" name="isInterAccTransfer" id="isInterAccTransfer" ng-model="trOptions.isInterAccTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                        </td>
                        <td align="center">
                            <input type="checkbox" class="checkbox" name="isOtherAccTransfer" id="isOtherAccTransfer" ng-model="trOptions.isOtherAccTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                        </td>
                        <td align="center">
                            <input type="checkbox" class="checkbox" name="isAccToCardTransfer" id="isAccToCardTransfer" ng-model="trOptions.isAccToCardTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                        </td>
                        <td align="center">
                            <input type="checkbox" class="checkbox" name="isCardToAccTransfer" id="isCardToAccTransfer" ng-model="trOptions.isCardToAccTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                        </td>
                        <td align="center">
                            <input type="checkbox" class="checkbox" name="isUtilityTransfer" id="isUtilityTransfer" ng-model="trOptions.isUtilityTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                        </td>
                        <td align="center">
                            <input type="checkbox" class="checkbox" name="isQrPayment" id="isQrPayment" ng-model="trOptions.isQrPayment" data-ng-true-value="1" data-ng-false-value="0"/>
                        </td>
                    </tr>
                </tbody>
                <tbody  ng-show="user.makerActionBy > 0 && user.checkerActionBy <= 0">
                    <tr>
                        <td align="center">
                            {{(trOptions.isOwnAccTransfer == 1) ? 'Yes' : 'No'}}
                        </td>
                        <td align="center">
                            {{(trOptions.isInterAccTransfer == 1) ? 'Yes' : 'No'}}
                        </td>
                        <td align="center">
                            {{(trOptions.isOtherAccTransfer == 1) ? 'Yes' : 'No'}}
                        </td>
                        <td align="center">
                            {{(trOptions.isAccToCardTransfer == 1) ? 'Yes' : 'No'}}
                        </td>
                        <td align="center">
                            {{(trOptions.isCardToAccTransfer == 1) ? 'Yes' : 'No'}}
                        </td>
                        <td align="center">
                            {{(trOptions.isUtilityTransfer == 1) ? 'Yes' : 'No'}}
                        </td>
                        <td align="center">
                            {{(trOptions.isQrPayment == 1) ? 'Yes' : 'No'}}
                        </td>
                    </tr>
                </tbody>                    
            </table>
        </div>
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
    <div class="col-xs-12 col-sm-3 col-md-3">
        <form name="testform">
            <div class="form-group" ng-if="user.makerActionBy > 0 && user.checkerActionBy <= 0">
                <label>PIN Sending Channel</label>
                <select class="form-control input-sm" id="otp_channel" ng-model="otp_channel">
                    <option value="email">email</option>
                    <option value="sms">sms</option>
                </select>
            </div>
            <?php if (ci_check_permission("callCenterChecker")): ?>
                <div class="btn-group">
                    <button class="btn btn-md btn-primary" id="test" ng-show="user.makerActionBy > 0 && user.checkerActionBy <= 0" data-ng-click="approveUser(user.skyId)">
                        <i class="glyphicon glyphicon-check"></i> Checker Approve
                    </button>         
                </div>
            <?php endif; ?>
            <?php if (ci_check_permission("callCenterMaker")): ?>
                <div class="btn-group">
                    <a class="btn btn-md btn-primary" id="test2" ng-show="(user.checkerActionBy <= 0 && user.makerActionBy <= 0)" ng-click="approveUserChecker(user.skyId)">
                        <i class="glyphicon glyphicon-check"></i> Maker Approve
                    </a>         
                </div> 
            <?php endif; ?>
        </form>
    </div>
</div>