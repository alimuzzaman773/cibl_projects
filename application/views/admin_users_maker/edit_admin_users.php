<title>Admin User</title>
<div class="breadcrum">Edit Admin Users</div>


<div class="container" style="margin-top:50px">

    <div class="alert alert-success"><?php echo $message ?></div>

    <form method="post" style="" id="userForm" name="userForm" action="<?php echo base_url(); ?>admin_users_maker/updateAdminUser">

        <input hidden type="text" name="adminUserId" id="adminUserId" value="<?=$adminUserData['adminUserId'] ?>" size="20" />
        <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

        <fieldset>
            <table width="500" border="0" cellpadding="5">


                <tr>
                    <th width="213" align="left" scope="row">Full Name</th>
                    <td width="161"><input type="text" name="fullName" id="fullName" value="<?=$adminUserData['fullName'] ?>" size="20" /></td>
                </tr>



                <tr>
                    <th width="213" align="left" scope="row">User ID</th>
                    <td width="161"><input type="text" name="userId" id="userId" value="<?=$adminUserData['adminUserName'] ?>" size="20" /></td>
                </tr>


                <tr>                       
                    <th align="left" scope="">Select Group Name</th>
                    <td>
                        <select id="group" name="group">
                            <option value="">Select a Group</option>                      
                            <?php foreach($userGroups as $item){ ?>
                            <option value="<?=$item->userGroupId?>"><?= $item->userGroupName ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th align="left" scope="row">Email</th>
                    <td><input type="text" name="email" id="email" value="<?=$adminUserData['email'] ?>" size="20" /></td>
                </tr>
                
                <tr>
                    <th align="left" scope="row">&nbsp;</th>
                    <td></td>
                </tr>


            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                <h3>Reject Reason<h3>
                <textarea name="reason" id="reason" cols="40" rows="5" readonly></textarea>
                <br><br>
            </div>


            </table>

        </fieldset>
        <input type="button"  value="Update" onclick="submitForm()" class="btn btn-success"/>
        <a href="<?php echo base_url(); ?>admin_users_maker" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a> 

    </form>

</div>




<script type="text/javascript">


    $(function() {
             
        $("#addUser").addClass("active");
        $("#usersCollape").addClass("btn-primary");
        
        $.validator.addMethod("userId", function(value, element)
        {
            return this.optional(element) || /^[a-zA-Z0-9\x21-\x7E]{3,40}$/i.test(value);
        }, "User ID are 3-40 characters, Space not Supported");


        $.validator.addMethod("fullName", function(value, element)
        {
            return this.optional(element) || /[a-z A-Z 0-9]/.test(value);
        }, "User ID are 3-40 characters");



        $.validator.addMethod("password", function(value, element)
        {
            return this.optional(element) ||
                    /^(?=[\x21-\x7E]*[0-9])(?=[\x21-\x7E]*[A-Z])(?=[\x21-\x7E]*[a-z])(?=[\x21-\x7E]*[\x21-\x2F|\x3A-\x40|\x5B-\x60|\x7B-\x7E])[\x21-\x7E]{8}$/.test(value);
        }, "Passwords are 8 characters and Alphanumeric with special character and case sensitive");
    }); 



    function submitForm() {

        $("#userForm").validate({

            rules: {

                fullName: "required fullName",
                userId: "required userId",
            
                email: {
                    required: true,
                    email: true,
                    maxlength: 100
                },

                dob: {
                    required: true
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
            errorPlacement: function(error, element) {
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

        if (isValid) {

            if ($('#group').val() == "") {
                alert('Please select a group');
            }

            else {
                $("#userForm").submit();
            }
        }
    }

</script>



<script type="text/javascript">

    var userGroupId = <?php echo $adminUserData['adminUserGroup'] ?>
    
    $('#group').val(userGroupId);

    document.getElementById("reason").value = "<?php echo $checkerActionComment ?>";


</script>

