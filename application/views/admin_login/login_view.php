<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>Admin Login</title>

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
            <div class="alert alert-success"><?= $this->my_session->getLoginError() ?></div>  
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane active in" id="login">
                    <form class="form-horizontal" action="<?php echo base_url(); ?>admin_login/checkUserDetails" method="POST">
                        <fieldset>
                            <div id="legend">
                                <legend class="">EBL SKYBANKING</legend>
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
                                <label class="control-label" for="password">Password</label>
                                <div class="controls">
                                    <input type="password" id="password" name="password" placeholder="" class="input-xlarge" required>
                                </div>
                            </div>
                            <br>
                            <div class="control-group">
                                <button type="submit" class="btn btn-success">Login</button>
                                <br><br>
                                <div class="controls">
                                    <a href="<?php echo base_url(); ?>admin_login/forgotPassword">Forget User Name/Password?</a>  
                                </div>
                            </div>
                        </fieldset>
                    </form>                
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
