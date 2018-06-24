<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">User Approve</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="bg-primary">
                    <td colspan="2">General Information</td>
                </tr>
            </thead>    
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
        </table>
    </div>
    <div class="col-xs-12 col-sm-12">
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

            <tr>
                <td>1223131</td>
                <td>C</td>
                <td>High Performance Account (HPA)</td>
                <td>BDT</td>
            </tr>
        </table>
    </div>
    <div class="col-xs-12 col-sm-12">
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
        <div class="btn-group">
            <a href="#" class="btn btn-sm btn-primary" data-ng-click="approveUser(user.skyId)">Approve</a>
            <a href="<?php echo base_url(); ?>call_center/#/user_list" class="btn btn-sm btn-danger">Cancel</a>
        </div>
    </div>
</div>

