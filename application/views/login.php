<form class="form" id="loginform" action="<?= $base_url . "admin_login/checkUserDetails" ?>" method="post"> 
    <div class="center-block text-center">
        <img src="<?= asset_url() . "images/logo.png" ?>" />
    </div>
    <div class="row" style="margin:0 auto;width:400px">
        <div class="col-md-12 col-xs-12">
            <div class="form-group center-block">
                <span class="small-text"><?= $this->my_session->getLoginError() ?></span>    
            </div>
            <div class="form-group has-feedback">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required/>
                <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
            </div>
            <div class="form-group">
                <button type="submit" name="login" class="btn btn-primary btn-block">
                    <i class="glyphicon glyphicon-check"></i> Log In
                </button>
                <a class="pull-right" href="<?php echo base_url(); ?>admin_login/forgotPassword">Forget User Name/Password?</a>
            </div>
        </div>
    </div>
</form>
<style>
    body {
        background: #004d29;
    }
    .form {
        width: 500px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        padding-top: 20px;
        padding-bottom: 20px;
        margin-top: 40px;
    }
</style>