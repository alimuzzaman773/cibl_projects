<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <h1 class="title-underlined">
                Edit Apps User
                <a href="<?php echo base_url() . 'client_registration/index'; ?>" class="btn btn-primary pull-right btn-sm">
                    <i class="glyphicon glyphicon-plus"></i> Apps User List
                </a>
            </h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <form name="form_data" id="form_data" ng-submit="saveItems()">
            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
                <div class="doc_layout">
                    <div class="doc_header bg-primary"><h4>Apps User Details (<b>{{eblSkyId}}</b>)</h4></div>
                    <div class="well doc_right_panel">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>CIF ID</label>
                                    <input type="text" name="cfId" class="form-control input-sm" ng-model="cfId" placeholder="ex: 12345..."/>
                                    <input type="hidden" value="{{skyId}}">
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Client ID</label>
                                    <input type="text" name="clientId" class="form-control input-sm" ng-model="clientId" placeholder="ex: 12345..."/>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Prepaid ID</label>
                                    <input type="text" name="prepaidId" class="form-control input-sm" ng-model="prepaidId" placeholder="ex: 12345.."/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" name="userName" class="form-control input-sm" ng-model="userName" placeholder="ex: john"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="userEmail" class="form-control input-sm" ng-model="userEmail" placeholder="ex: email@domai.com"/>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Mobile No:1</label>
                                    <input type="text" maxlength="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="userMobNo1" class="form-control input-sm" ng-model="userMobNo1" placeholder="Mobile Number 1"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Mobile No:2</label>
                                    <input type="text" maxlength="11" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" name="userMobNo2" class="form-control input-sm" ng-model="userMobNo2" placeholder="Mobile Number 2"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Current Address</label>
                                    <textarea rows="4" class="form-control" ng-model="currAddress"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Permanent Address</label>
                                    <textarea rows="4" class="form-control" ng-model="parmAddress"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Billing Address</label>
                                    <textarea rows="4" class="form-control" ng-model="billingAddress"></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-primary">
                                <td colspan="8">View Only Customer Registration</td>
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
                        <tbody>
                            <tr>
                                <td align="center">
                                    <input type="checkbox" class="checkbox" name="isOwnAccTransfer" id="isOwnAccTransfer" ng-model="isOwnAccTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                                </td>
                                <td align="center">
                                    <input type="checkbox" class="checkbox" name="isInterAccTransfer" id="isInterAccTransfer" ng-model="isInterAccTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                                </td>
                                <td align="center">
                                    <input type="checkbox" class="checkbox" name="isOtherAccTransfer" id="isOtherAccTransfer" ng-model="isOtherAccTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                                </td>
                                <td align="center">
                                    <input type="checkbox" class="checkbox" name="isAccToCardTransfer" id="isAccToCardTransfer" ng-model="isAccToCardTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                                </td>
                                <td align="center">
                                    <input type="checkbox" class="checkbox" name="isCardToAccTransfer" id="isCardToAccTransfer" ng-model="isCardToAccTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                                </td>
                                <td align="center">
                                    <input type="checkbox" class="checkbox" name="isUtilityTransfer" id="isUtilityTransfer" ng-model="isUtilityTransfer" data-ng-true-value="1" data-ng-false-value="0"/>
                                </td>
                                <td align="center">
                                    <input type="checkbox" class="checkbox" name="isQrPayment" id="isQrPayment" ng-model="isQrPayment" data-ng-true-value="1" data-ng-false-value="0"/>
                                </td>
                            </tr>
                        </tbody>                     
                    </table>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-primary">
                                <td colspan="5">Account Information</td>
                            </tr>
                        </thead>    
                        <tr>
                            <th>SI# Remove</th>
                            <th>Account No</th>
<!--                            <th>Account Type</th>-->
                            <th>Product Name</th>
                            <th>Currency</th>
                        </tr>
                        <tr data-ng-repeat="ua in user_accounts track by $index" class="{{selected_ac == 1 ? 'bg-danger' : ''}}">
                            <td>
                                <div class="form-inline">
                                    <label for="btn{{ua.accountInfoID}}">{{$index + 1}} 
                                        <input  type="checkbox" id="btn{{ua.accountInfoID}}" class="checkbox" data-ng-true-value="1" data-ng-false-value="0" ng-model="selected_ac" ng-click="remove_ac(ua.accountInfoID, selected_ac, $index)"/>
                                    </label>
                                </div>
                            </td>
                            <td>{{ua.accNo}}</td>
<!--                            <td>{{ua.accTypeName}} ({{ua.accTypeCode}})</td>-->
                            <td>{{ua.accName}}</td>
                            <td>{{ua.accCurrency}}</td>
                        </tr>
                        <tr data-ng-show="user_accounts.length <= 0">
                            <td colspan="4">No data found</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-sm-12 col-xs-12" data-ng-show="v_errors.length > 0">
                <br clear ="all" />
                <div class=" alert alert-danger" data-ng-bind-html="v_errors"></div>
                <br clear ="all" />
            </div>
            <div class="col-md-12 col-xs-12 col-sm-12">
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-check"></span> Update</button>
            </div>
        </form>
    </div>
</div>
