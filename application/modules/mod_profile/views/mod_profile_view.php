<br /><br />
<div class="row well">
    <div class="col-md-4 col-sm-4 col-xs-12 well" id="leftPanel">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div>
                    <h2><?= $uinfo['fullName'] ?></h2>
                    <div class="col-sm-12 col-xs-12">
                        <table class="table">
                            <tr>
                                <td class="text-right bold" style="width: 50%">Username</td>
                                <td class="text-left"><?= $uinfo['adminUserName'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right bold" style="width: 50%">Date Of Birth</td>
                                <td class="text-left"><?= $uinfo['dob'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right bold" style="width: 50%">Group</td>
                                <td class="text-left"><?= $uinfo['userGroupName'] ?></td>
                            </tr>
                            <tr>
                                <td class="text-right bold" style="width: 50%">Email</td>
                                <td class="text-left"><?=$this->bocrypter->Decrypt($uinfo['email'])?></td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-sm-8 col-xs-12" id="rightPanel">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h2>Change password</h2>
                <hr class="colorgraph">
                <form class="forms" id="form2" method="post" action="">
                    <div class="row">
                        <div class="form-group col-sm-12 col-xs-12">
                            <label>Old Password</label>
                            <input type="password" name="oldpassword" class="form-control input-lg" />
                        </div>
                        <div class="form-group col-sm-6 col-xs-12">
                            <label>New Password</label>
                            <input type="password" name="newpassword" class="form-control input-lg" />
                        </div>
                        <div class="form-group col-sm-6 col-sm-12">
                            <label>Confirm Password</label>
                            <input type="password" name="confirmpassword" class="form-control input-lg" />
                        </div>

                        <div class="form-group col-sm-12 col-xs-12">
                            <input type="hidden" name="action" value="edit_password" />                    
                            <button type="submit" id="passsubmit" class="btn btn-primary btn-lg pull-right" name="change_password">
                                <i class="glyphicon glyphicon-check"></i> Change Password
                            </button>
                            <br  clear="all"/>

                        </div>
                        <div id="result2" class="col-sm-12 col-xs-12 col-md-12 alert alert-info" style="display: none"></div> 
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>




<script type="text/javascript">
    var app = app || {};
    $(document).ready(function (e) {
        $('#form1').submit(function (e) {
            $("#load").show();
            $("#submit").attr('disabled', 'true');
            var formData = $("#form1").serialize();
            $.ajax({
                type: "POST",
                cache: false,
                url: "<?= $base_url . 'profile/edit_profile' ?>",
                data: formData,
                dataType: 'json',
                timeout: 10000,
                reset: false,
                success: function (data) {
                    if (data.errorState == true) {
                        $("#load").hide();
                        $("#submit").removeAttr('disabled');
                        $("#result").html(data.errors);
                        $("#result").fadeIn("slow");
                        $('html,body').animate({
                            scrollTop: $("#result").offset().top},
                        'slow');
                    } else if (data.success == true) {
                        $("#load").hide();
                        $("#submit").removeAttr('disabled');
                        $("#result").html(data.msg);
                        $("#result").fadeIn("slow");
                        $('html,body').animate({
                            scrollTop: $("#result").offset().top},
                        'slow');
                    }
                },
                error: function (e) {
                    $("#load").hide();
                    $("#submit").removeAttr('disabled');
                    alert("There was an error. Please try again later.");
                    return false;
                }
            });
            return false;
        });

        $('#form2').submit(function (e) {
            $("#result2").html('');
            app.showModal();
            //$("#passsubmit").attr('disabled', 'true');
            var formData = $("#form2").serialize();
            $.ajax({
                type: "POST",
                cache: false,
                url: "<?= $base_url . 'profile/edit_password' ?>",
                data: formData,
                dataType: 'json',
                timeout: 10000,
                reset: false,
                success: function (data) {
                    app.hideModal();
                    if (data.success == false) {
                        $("#result2").html(data.msg);
                        $("#result2").fadeIn("slow");
                        $('html,body').animate({
                            scrollTop: $("#result2").offset().top},
                        'slow');
                        return false;
                    } 
                    
                    $("#result2").html(data.msg);
                    $("#result2").fadeIn("slow");
                    $('#form2')[0].reset();
                    $('html,body').animate({
                        scrollTop: $("#result2").offset().top},
                    'slow');
                },
                error: function (e) {
                    app.hideModal();
                    $("#passsubmit").removeAttr('disabled');
                    alert("There was an error. Please try again later.");
                    return false;
                }
            });
            return false;
        });
    });

</script>    

<style>
    #leftPanel{
        background-color:rgba(43, 125, 52, 0.83);
        color:#fff;
        text-align: center;
        background-image: none;
    }

    #rightPanel{
        min-height:415px;
        padding: 19px;
        margin-bottom: 20px;
    }

    /* Credit to bootsnipp.com for the css for the color graph */
    .colorgraph {
        height: 5px;
        border-top: 0;
        background: #c4e17f;
        border-radius: 5px;
        background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
    }

    .bold {
        font-weight: bold
    }
</style>
