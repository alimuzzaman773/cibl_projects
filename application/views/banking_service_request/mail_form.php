<script>
    function checkValidation() {
        // alert('hello');
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var toMailStr = $("#toMail").val();
        var ccMailStr = $("#ccMail").val();
        var bccMailStr = $("#bccMail").val();

        if (toMailStr === "") {
            alert('To mail is required');
            return false;
        }
        var toMailArr = toMailStr.split(";");
        for (var i = 0; i < toMailArr.length; i++) {

            if (!re.test(toMailArr[i])) {
                alert('Please enter correct To email address');
                return false;
            }
        }

        if (ccMailStr !== "") {
            var ccMailArr = ccMailStr.split(";");
            for (var i = 0; i < ccMailArr.length; i++) {
                if (!re.test(ccMailArr[i])) {
                    alert('Please enter correct Cc email address');
                    return false;
                }
            }
        }

        if (bccMailStr !== "") {
            var bccMailArr = bccMailStr.split(";");
            for (var i = 0; i < bccMailArr.length; i++) {
                if (!re.test(bccMailArr[i])) {
                    alert('Please enter correct Bcc email address');
                    return false;
                }
            }
        }

        document.getElementById('submitBtn').click();
    }
</script>
<h2 class="title-underlined">Send Mail</h2>
<div class="container" style="margin-top:50px">
    <form method="post" style="" id="mailForm" name="mailForm" action="<?php echo base_url(); ?>banking_service_request/sendMail">
        <table class="table table-condensed table-bordered table-striped">
            <input hidden type="text" name="serviceId" value="<?= $serviceId ?>">
            <tr>
                <th align="left" scope="row">To</th>
                <td><input type="text" class="form-control input-sm" id="toMail" name="to" value="<?= $to ?>"></td>
            </tr>
            <tr>
                <th align="left" scope="row">Cc</th>
                <td><input type="text" class="form-control input-sm" id="ccMail" name="cc" value=""></td>
            </tr>
            <tr>
                <th align="left" scope="row">Bcc</th>
                <td><input type="text" class="form-control input-sm" id="bccMail" name="bcc" value=""></td>
            </tr>
            <tr>
                <th align="left" scope="row">Subject</th>
                <td><input type="text" class="form-control input-sm" name="subject" value="<?= $subject ?>" readonly></td>
            </tr>
            <tr>
                <th align="left" scope="row">Body</th>    
                <td><div class="mail_body"><?= $body ?></div></td>
            <input type="text" id="body" class="form-control input-sm" name="body" style="display:none" value="<?= $body ?>">
            </tr>
            <tr>
                <th align="left" scope="row">Instruction<br>(Optional)</th>    
                <td><textarea class="form-control input-sm" rows="8" id="bodyInstruction" name="bodyInstruction" style="width: 700px;"></textarea></td>
            </tr>
            <tr>
                <th align="left" scope="row">&nbsp;</th>
                <td>
                    <input type="submit" id="submitBtn" class="btn btn-success" style="display:none">
                </td>
            </tr>
        </table>
    </form>
    <button class="btn btn-success" onclick="checkValidation()">Send Mail</button>
    <a href="<?php echo base_url(); ?>banking_service_request/getRequests" class="btn btn-success"><i class="icon-remove"></i><span> Cancel</span></a>
</div>

<style>
     @media(min-width:768px){
        #mailForm .form-control{
            max-width: 500px;
        }
        #mailForm .mail_body{
            max-width: 500px;
            border:1px solid #ddd;
            background-color: #fff;
            padding: 3px;
            border-radius: 3px
        }        
    }
</style>
<script>
    /*
    tinymce.init({
        selector: 'textarea',
        readonly: 0
    });
*/
</script>