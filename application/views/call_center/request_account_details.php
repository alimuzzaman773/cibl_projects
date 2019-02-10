<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">Request Account/Card Details</h2>
    </div>
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
        </table>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12" data-ng-if="user_accounts.length !== 0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <td colspan="6">Account Information</td>
                </tr>
            </thead>    
            <tr>
                <th>Account No</th>
                <th>Customer ID</th>
                <th>Account Type</th>
                <th>Account Status</th> 
                <th>Product Name</th>
                <th>Currency</th>
            </tr>

            <tr>
                <td>{{user_accounts.ACCOUNT_NUMBER}}</td>
                <td>{{user_accounts.CUSTOMER_ID}}</td>
                <td>{{user_accounts.ACCOUNT_TYPE}}</td>
                <td>{{user_accounts.ACC_STATUS_NM}}</td>
                <td>{{user_accounts.PRODUCT_NM}}</td>
                <td>{{user_accounts.CURRENCY_NM}}</td>
            </tr>
        </table>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12" data-ng-if="user_cards.length !== 0">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <td colspan="7">Request Card Information</td>
                </tr>
            </thead>    
            <tr>
                <th>Client ID</th>
                <th>Account No</th>
                <th>Currency</th>
                <th>Card Holder Name</th>
                <th>Expiry Date</th>
                <th>Email</th>
                <th>Mobile</th>
            </tr>
            <tr>
                <td>{{user_cards.clientId}}</td>
                <td>{{user_cards.accNo}}</td>
                <td>{{user_cards.currency}}</td>
                <td>{{user_cards.name}}</td>
                <td>{{user_cards.expiry}}</td>
                <td>{{user_cards.email}}</td>
                <td>{{user_cards.mobile_no}}</td>
            </tr>
        </table>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-3">
        <form name="testform">    
            <div class="btn-group">
                <button class="btn btn-md btn-primary" id="test" data-ng-click="accountApprove(user.skyId)">
                    <i class="glyphicon glyphicon-check"></i> Approve
                </button>         
            </div>
        </form>
    </div>
</div>