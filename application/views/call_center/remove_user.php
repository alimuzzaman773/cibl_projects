<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">Request User Remove</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="bg-primary">
                        <td colspan="2">General Information</td>
                    </tr>
                </thead>    
                <tbody >
                    <tr>
                        <th style="width:200px;">ESB ID</th>
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
                </tbody>
                <tr data-ng-show="user.length <= 0">
                    <td colspan="2">No data found</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="bg-primary">
                        <td colspan="5">Account Information</td>
                    </tr>
                </thead>    
                <tr>
                    <th>SI#</th>
                    <th>Account No</th>
                    <th>Account Type</th>
                    <th>Product Name</th>
                    <th>Currency</th>
                </tr>
                <tr data-ng-repeat="ua in user_accounts track by $index">
                    <td>{{$index + 1}}</td>
                    <td>{{ua.accNo}}</td>
                    <td>{{ua.accTypeName}} ({{ua.accTypeCode}})</td>
                    <td>{{ua.accName}}</td>
                    <td>{{ua.accCurrency}}</td>
                </tr>
                <tr data-ng-show="user_accounts.length <= 0">
                    <td colspan="5">No data found</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" data-ng-show="v_errors.length > 0">
            <br clear ="all" />
            <div class=" alert alert-danger" data-ng-bind-html="v_errors"></div>
            <br clear ="all" />
        </div>
    </div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-md-offset-7 col-lg-offset-7">
        <form data-ng-submit="remove_user(skyId)">
            <div class="form-group">
                <label>Reason</label>
                <textarea class="form-control" rows="4" data-ng-model="reason" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary pull-right">Remove</button>
            </div>
        </form>
    </div>
</div>
