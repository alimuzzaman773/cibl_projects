<h2 class="title-underlined">
<?= $pageTitle ?>
</h2>
<?php if (trim($message) != ''): ?>
    <div class="alert alert-success"><?php echo $message ?></div>
<?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            
        </div>
    </div>
<form method="post" style="" id="userForm" name="userForm" action="<?php echo base_url(); ?>admin_users_maker/insertNewUser">
    <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">
    <fieldset>
        <table class="table table-condensed table-bordered table-striped">
            <tr>
                <th width="213" align="left" scope="row">Search Domain User</th>
                <td>
                    <div class="form-inline form-group">
                        <input type="text" id="searchUser" name="searchUser" class="form-control" placeholder="Search by username" autocomplete="off" />
                            <a href='#' type="button" class="btn btn-info" onclick="app.searchUser();">Domain Search</a>
                    </div>
                </td>
            </tr>
            <tr>
                <th width="150" align="left" scope="row">Full Name</th>
                <td><input class="form-control input-sm" type="text" name="fullName" id="fullName"/></td>
            </tr>
            <tr>
                <th align="left" scope="row">User ID</th>
                <td>
                    <input class="form-control input-sm" type="text" name="userId" id="userId"/>
                    <small class="important">Application user name</small>
                </td>
            </tr>
            <tr>
                <th align="left" scope="row">AD UserName</th>
                <td><input class="form-control input-sm" type="text" name="adUserName" id="adUserName"/></td>
            </tr>
            <tr>
                <th align="left" scope="row">Password</th>
                <td><input class="form-control input-sm" type="password" name="password1" id="pass1" size="20" /></td>
            </tr>
            <tr>
                <th align="left" scope="row">Retype Password</th>
                <td><input class="form-control input-sm" type="password" name="password2" id="pass2" size="20" /></td>
            </tr>
            <tr>                       
                <th align="left" scope="">Select Group Name</th>
                <td>
                    <select id="group" name="group"  class="form-control">
                        <option value="">Select a Group</option>                      
                        <?php foreach ($userGroups as $item) { ?>
                            <option value="<?= $item->userGroupId ?>"><?= $item->userGroupName ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th align="left" scope="row">Email</th>
                <td><input class="form-control input-sm" type="text" name="email" id="email" size="20" /></td>
            </tr>
        </table>
    </fieldset>
    <input type="button"  value="Create" class="btn btn-success" onclick="submitForm()"/>
    <a href="<?php echo base_url(); ?>admin_users_maker" class="btn btn-success">Cancel</a> 
</form>
<style>
    @media(min-width:768px){
        .form-control{
            max-width: 50%;
        }        
    }
</style>
<link rel = "stylesheet" media = "screen" href="<?= base_url() . ASSETS_FOLDER ?>css/jqueryui/jquery-ui-1.9.2.custom.min.css" charset="utf-8" />
<script type="text/javascript" src="<?= base_url() . ASSETS_FOLDER ?>js/jqueryui/jquery-ui-1.9.2.custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= base_url() . ASSETS_FOLDER ?>js/jquery.validate.min.js"></script>


<script type="text/javascript">
        var app = app || {};
        $(function () {
            
            app.searchUser = function()
            {
                if($.trim($("#searchUser").val()) == ''){
                    return false;
                }

                var $user = $.trim($("#searchUser").val());
                app.showModal();

                $.ajax({
                    type: 'POST',
                    url: app.baseUrl+ 'api/client_registration/get_ad_user',
                    data: {
                        user: $user
                    },
                    dataType: 'json',
                    success : function(data){
                        app.hideModal();
                        console.log(data);
                        if(!data.success){
                            alert("No data found. "+data.msg);
                            return false;
                        }

                        $("#fullName").val(data.name);
                        $("#email").val(data.email);
                        $("#userId").val($user);
                    },
                    error : function(data){
                        app.hideModal();
                        console.log("errere");
                    }
                });
                return false;
            }


            $("#addUser").addClass("active");
            $("#usersCollape").addClass("btn-primary");

            $.validator.addMethod("userId", function (value, element)
            {
                return this.optional(element) || /^[a-zA-Z0-9\x21-\x7E]{3,40}$/i.test(value);
            }, "User ID are 3-40 characters, Space not Supported");


            $.validator.addMethod("fullName", function (value, element)
            {
                return this.optional(element) || /[a-z A-Z 0-9]/.test(value);
            }, "User ID are 3-40 characters");



            /**
             * @todo check password regex
             */
            /*$.validator.addMethod("password", function(value, element)
             {
             return this.optional(element) ||
             /^(?=[\x21-\x7E]*[0-9])(?=[\x21-\x7E]*[A-Z])(?=[\x21-\x7E]*[a-z])(?=[\x21-\x7E]*[\x21-\x2F|\x3A-\x40|\x5B-\x60|\x7B-\x7E])[\x21-\x7E]{8}$/.test(value);
             }, "Password should be exactly 8 characters with at least one upper case and special character and numeric.");
             */
        });

         

        function submitForm() {

            $("#userForm").validate({

                rules: {

                    fullName: "required fullName",
                    userId: "required userId",
                    //password1: "required password",
                    //password2: "required password",
                    //password1: "required",
                    //password2: "required",
                    email: {
                        required: true,
                        email: true,
                        maxlength: 100
                    },
                },

                messages: {
                    email: {
                        required: "* Email ID is required",
                        email: "* Invalid Email Id"
                    },
                },

                errorElement: "div",
                wrapper: "div", // a wrapper around the error message
                errorPlacement: function (error, element) {
                    offset = element.offset();
                    error.insertBefore(element);
                    error.addClass('message');  // add a class to the wrapper
                    error.css('position', 'absolute');
                    error.css('left', offset.left + element.outerWidth());
                    error.css('top', offset.top);
                    error.css('color', 'red');
                }

            });

            var isValid = $("#userForm").valid();

            /*if (isValid) {
                var pass1 = document.getElementById("pass1").value;
                var pass2 = document.getElementById("pass2").value;

                if (pass1 == pass2 && pass1 != "") {

                    if ($('#group').val() == "") {
                        alert('Please select a group');
                    } else {
                        $("#userForm").submit();
                    }
                } else {
                    alert("Password Mismatch");
                }
            }*/
        }

</script>

