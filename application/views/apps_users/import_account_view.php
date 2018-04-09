<title>Apps User</title>
<div class="breadcrum">Add Apps User</div>

<input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

<!-- Account statement container -->
<div class="container" style="margin-top:50px">

<div class="card_container">
    <h3 style="color: green" class="container_title">Account Related Information
        <h3>

            <div id="showUsers">
                <table class="table table-striped table-bordered">

                    <thead>
                    <tr>
                        <th style="text-align: center; color: green" colspan="2">Accounts Related Information</th>
                    </tr>
                    </thead>


                    <tbody >
                    <tr>
                        <th align="left" scope="row">ESB ID</th>
                        <td id = "esbId"><?= $esbId ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">CFID</th>
                        <td id = "cfId"><?= $cfId ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">Date of Birth</th>
                        <td id = "dob"><?= $dob ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Gender</th>
                        <td id = "sex"><?= $sex ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Father's Name</th>
                        <td id = "fatherName"><?= $fatherName ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">Mother's Name</th>
                        <td id = "motherName"><?= $motherName ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Contact Number</th>
                        <td id = "userMobNo1"><?= $userMobNo1 ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Alternate Contact Number</th>
                        <td id = "userMobNo2"><?= $userMobNo2 ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">User Name</th>
                        <td id = "userName"><?= $userName ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Email</th>
                        <td id = "userEmail"><?= $userEmail ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Current Address</th>
                        <td id = "currAddress"><?= $currAddress ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Parmanent Address</th>
                        <td id = "parmAddress"><?= $parmAddress ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">Mailing Address</th>
                        <td id = "billingAddress"><?= $billingAddress ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">Home Branch Code</th>
                        <td id = "homeBranchCode"><?= $homeBranchCode ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">Home Branch Name</th>
                        <td id = "homeBranchName"><?= $homeBranchName ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>


            <div class="" style="margin-top:30px">

                <div id="userAccounts" data-bind="visible: records().length > 0">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="text-align:center">Account Number</th>
                            <th style="text-align:center">Account Type</th>
                            <th style="text-align:center">Product Name</th>
                            <th style="text-align:center">Currency</th>
                            <th style="text-align:left">
<!--                                
<button style="text-align:left" id="selectAll" data-bind="click :$root.selectAll" class="btn btn-primary">Select All</button>
<button style="text-align:left" id="deselectAll" data-bind="click :$root.deselectAll, visible: false" class="btn btn-primary">Deselect All</button>
-->

                            </th>

                        </tr>
                        </thead>
                        <tbody data-bind="foreach: records">
                        <tr>
                            <td style="text-align:center" data-bind="text:accountNo"></td>
                            <td style="text-align:center" data-bind="text:accountType"></td>
                            <td style="text-align:center" data-bind="text:productName"></td>
                            <td style="text-align:center" data-bind="text:accountCurrency"></td>
                            <td style="text-align:left"><input type="checkbox" data-bind="checked: isProcessed"></td>
                        </tr>
                        </tbody>
                    </table>
                    <br/>

                </div>
            </div>
</div>





<!-- Card statement container -->
<div class="card_container" style="<?= $cardsModeOfDisplay;?>">
    <h3 style="color: green" class="container_title">Card Related Information
        <h3>


            <div id="showUsers">
                <table class="table table-striped table-bordered">

                    <thead>
                    <tr>
                        <th colspan="2" style="text-align: center; color: green">General Information</th>
                    </tr>
                    </thead>


                    <tbody>

                    <tr>
                        <th align="left" scope="row">Clinet ID</th>
                        <td id = "clientId"><?= $clientId ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">Card User Name</th>
                        <td><?= $userNameCard ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Mother's Name</th>
                        <td><?= $mothersNameCard ?></td>
                    </tr>


                    <tr>
                        <th align="left" scope="row">Date of birth</th>
                        <td><?= $dobCard ?></td>
                    </tr>

                    <tr>
                        <th align="left" scope="row">Billing Address</th>
                        <td><?= $clientBillingAddress ?></td>
                    </tr>

                    </tbody>
                </table>
            </div>


            <div class="" style="margin-top:50px">

                <div id="showAccounts">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th colspan="5" style="text-align: center; color: green">Card Information</th>
                        </tr>
                        <tr>
                            <th style="text-align:center">Card Number</th>
                            <th style="text-align:center">Card Type</th>
                            <th style="text-align:center">Currency</th>
                            <th style="text-align:center">Card Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($multiCard as $multiCard){?>
                            <tr>
                                <td style="text-align:center"><?php echo $multiCard['cardNumber'];?></td>
                                <td style="text-align:center"><?php echo $multiCard['cardType'];?></td>
                                <td style="text-align:center"><?php echo $multiCard['cardCurrency'];?></td>
                                <td style="text-align:center"><?php echo $multiCard['cardStatus'];?></td>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
</div>
</div>


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




<div class="container" style="margin-top:50px">

    <button data-bind="click :$root.save" class="btn btn-success">Create User</button>
    <a href="<?php echo base_url(); ?>apps_users/addAppsUser/<?= $selectedActionName ?>" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back</span></a>

</div>


<script type="text/javascript" charset="utf-8">
    var initialData = jQuery.parseJSON('<?= $accountInfo ?>');//data for building initial table
    var vm = function() {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function(i, record) {   //build the checkboxes as observable
            record.isProcessed = ko.observable(false);
            record.accountNo = record.accountNo;
            record.accountType = record.accountType;
            record.productName = record.productName;
            record.accountCurrency = record.accountCurrency;
        });


        self.selectAll = function(){
            document.getElementById("selectAll").style.display = "none";
            document.getElementById("deselectAll").style.display = "block";
            $.each(self.records(), function(i, record){
                record.isProcessed(true);
            })
        };


        self.deselectAll = function(){
            document.getElementById("selectAll").style.display = "block";
            document.getElementById("deselectAll").style.display = "none";
            $.each(self.records(), function(i, record){
                record.isProcessed(false);
            })
        };



        self.save = function()
        {
            var acc_no = "";
            var acc_type = "";
            var product_name = "";
            var account_currency = "";

            $.each(self.records(), function (i, record) {
                if(record.isProcessed()==true){

                    selected_action_name = selectedActionName.value;
                    esb_id = $("#esbId").text();
                    cf_id = $("#cfId").text();
                    client_id = $("#clientId").text();
                    birth_date = $("#dob").text();
                    gender = $("#sex").text();
                    father_name = $("#fatherName").text();
                    mother_name = $("#motherName").text();
                    mob1 = $("#userMobNo1").text();
                    mob2 = $("#userMobNo2").text();
                    user_name = $("#userName").text();
                    user_email = $("#userEmail").text();
                    curr_address = $("#currAddress").text();
                    parm_address = $("#parmAddress").text();
                    billing_address = $("#billingAddress").text();

                    home_branch_code = $("#homeBranchCode").text();
                    home_branch_name = $("#homeBranchName").text();


                    acc_no = acc_no+"|"+record.accountNo;
                    acc_type = acc_type+"|"+record.accountType;
                    product_name = product_name+"|"+record.productName;
                    account_currency = account_currency+"|"+record.accountCurrency;

                    // acc_no = acc_no.substring(1); acc_type = acc_type.substring(1);
                    // product_name = product_name.substring(1); account_currency = account_currency.substring(1);


                }
            });

            if(acc_no==""){
                alert("Error: No Account is Selected");
                return false;
            }

            var dataToSave = {"selectedActionName" : selected_action_name,
                "esbid" : esb_id,
                "cfid" : cf_id,
                "clientId" : client_id,
                "dob" : birth_date,
                "sex" : gender,
                "fatherName" : father_name,
                "motherName" : mother_name,
                "mob1" : mob1,
                "mob2" : mob2,
                "username" : user_name,
                "email" : user_email,
                "currAddress" : curr_address,
                "parmAddress" : parm_address,
                "billingAddress" : billing_address,

                "homeBranchCode" : home_branch_code,
                "homeBranchName" : home_branch_name,

                "accNo" : acc_no,
                "accType" : acc_type,
                "productName" : product_name,
                "accCurrency" : account_currency};

            console.log(dataToSave);
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>apps_users/insertUserInfo",
                success: function(data) {
                    if (data > 0) {
                        alert("User created successfully");
                        window.location = "<?= base_url() ?>apps_users/userGroupSelection?skyId=" + data + "&action=add";
                    }

                    else{
                        alert("This ESB ID is Used");
                        window.location = "<?= base_url() ?>apps_users/addAppsUser";
                    }
                },
                error: function(error) {
                    // alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }
    };

    ko.applyBindings(new vm());


</script>
