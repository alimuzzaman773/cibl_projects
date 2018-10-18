<table class="table table-bordered table-condensed">
    <tr class="bg-primary">
        <th colspan="4">Customer Information</th>
    </tr>
    <tr>
        <td class="bold">ACC Title</td>
        <td><?=$aInfo->ACC_TITLE?></td>
        <td class="bold">Customer Full Name</td>
        <td><?=$aInfo->CUSTOMER_FULL_NM?></td>
    </tr>
    <tr>
        <td class="bold">Address</td>
        <td><?=$aInfo->ADDRESS?></td>
        <td class="bold">Home Branch Name</td>
        <td><?=$aInfo->HOME_BRANCH_NM?></td>
    </tr>
    <tr>
        <td class="bold">EMAIL</td>
        <td><?=$aInfo->EMAIL?></td>
        <td class="bold">PHONE</td>
        <td><?=$aInfo->PHONE?></td>
    </tr>
    <tr>
        <td class="bold">PRODUCT NAME</td>
        <td><?=$aInfo->PRODUCT_NM?></td>
        <td class="bold">Account Number</td>
        <td><?=$aInfo->ACCOUNT_NUMBER?></td>
    </tr>
</table>

<div class="form-group col-sm-3 col-xs-12">
    <label>Notification Channel</label>
    <select class="form-control" name="otp_channel" id="otp_channel">
        <option value="email">Email</option>
        <option value="sms">SMS</option>
    </select>
</div>
<button class="btn btn-primary" onclick="return app.confirmRegistration()">
    <i class="glyphicon glyphicon-check"></i> Confirm Registration
</button>