<form class="form" id="" action="<?= base_url() . "admin_login/retriveForgotPassword" ?>" method="post"> 
    <div class="center-block text-center">
        <img src="<?= asset_url() . "images/login_logo.png" ?>" width="200" />
    </div>
    <div class="row" style="margin:0 auto;width:400px">
        <div class="col-md-12 col-xs-12">
            <div id="legend">
                <legend class="">Retrieve Password</legend>
            </div>
            <div class="form-group center-block">
                <span class="small-text text-danger">
                    <?php echo (isset($message) && $message!="")? $message: "";?>
                </span>    
            </div>
            <div class="form-group has-feedback">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required/>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <label>Email</label>
                <input type="text" name="userEmail" class="form-control" required/>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-forward"></i> Proceed</button> 
                <a href="<?php echo base_url(); ?>admin_login/index" class="btn btn-primary"><i class="glyphicon glyphicon-backward"></i> Back</a>              
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