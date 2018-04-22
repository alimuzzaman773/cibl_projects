<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Change Password</title>

    <link href="<?php echo base_url(); ?>assets/themes/default/hero_files/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/themes/default/hero_files/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/themes/default/css/custom.css" rel="stylesheet">


    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/themes/default/images/tab_icon.png" type="image/x-icon"/>
    <meta property="og:image" content="<?php echo base_url(); ?>assets/themes/default/images/property_icon.png"/>
    <link rel="image_src" href="<?php echo base_url(); ?>assets/themes/default/images/property_icon.png"/>

</head>

<div class="span7 offset3" id="loginModal" style="margin-top: 70px">
    <div class="modal-body">
        <div style="background-color:" class="well">

            <div class="alert alert-success"><?php echo $message ?></div>  

            <div id="myTabContent" class="tab-content">
                <div class="tab-pane active in" id="login">


                    <form id="changePasswordForm" name="changePasswordForm" class="form-horizontal" action="<?php echo base_url(); ?>admin_login/changePassword" method="POST">

                        <fieldset>

                            <div id="legend">
                                <legend class="">Change Password</legend>
                            </div>    
                            <div class="control-group">
                                <!-- Username -->
                                <label class="control-label"  for="username">User Name</label>
                                <div class="controls">
                                    <input type="text" id="username" name="username" placeholder="" class="input-xlarge" required>
                                </div>
                            </div>

                            <div class="control-group">
                                <!-- Password-->
                                <label class="control-label" for="oldPassword">Old Password</label>
                                <div class="controls">
                                    <input type="password" id="oldPassword" name="oldPassword" placeholder="" class="input-xlarge" required>
                                </div>
                            </div>

                            <div class="control-group">
                                <!-- Password-->
                                <label class="control-label" for="newPassword">New Password</label>
                                <div class="controls">
                                    <input type="password" id="newPassword" name="newPassword" placeholder="" class="input-xlarge" required>
                                </div>
                            </div>

                            <div class="control-group">
                                <!-- Password-->
                                <label class="control-label" for="retypeNewPassword">Retype New Password</label>
                                <div class="controls">
                                    <input type="password" id="retypeNewPassword" name="retypeNewPassword" placeholder="" class="input-xlarge" required>
                                </div>
                            </div>
                            <br>
                            <tr>
                                <th align="left" scope="row">&nbsp;</th>
                                <td>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <a href="<?php echo base_url(); ?>admin_login/index" class="btn btn-success"><i class="icon-remove"></i><span>Cancel</span></a>
                                </td>
                            </tr>
                        </fieldset>
                    </form>                
                </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
