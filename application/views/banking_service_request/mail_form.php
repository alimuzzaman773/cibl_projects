<title>Banking Request</title>
<script>
    function checkValidation(){
       // alert('hello');
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var toMailStr = $("#toMail").val();
        var ccMailStr = $("#ccMail").val();
        var bccMailStr = $("#bccMail").val();
        
        if(toMailStr === ""){
            alert('To mail is required');
            return false;
        }
        var toMailArr = toMailStr.split(";");
        for(var i=0;i<toMailArr.length;i++){
            
            if(!re.test(toMailArr[i])){
                alert('Please enter correct To email address');
                return false;
            }
        }
        
        if(ccMailStr !== ""){
            var ccMailArr = ccMailStr.split(";");
            for(var i=0;i<ccMailArr.length;i++){
                if(!re.test(ccMailArr[i])){
                    alert('Please enter correct Cc email address');
                    return false;
                }
            }
        }
        
        if(bccMailStr !== ""){
            var bccMailArr = bccMailStr.split(";");
            for(var i=0;i<bccMailArr.length;i++){
                if(!re.test(bccMailArr[i])){
                    alert('Please enter correct Bcc email address');
                    return false;
                }
            }
        }

        document.getElementById('submitBtn').click();
    }
</script>
<div class="breadcrum">Send Mail</div>


<div class="container" style="margin-top:50px">

    <form method="post" style="" id="mailForm" name="mailForm" action="<?php echo base_url(); ?>banking_service_request/sendMail">
        <fieldset>

            <table border="0" cellpadding="5">

                <input hidden type="text" name="serviceId" value="<?=$serviceId?>" style="width: 700px">

                <tr>
                    <th align="left" scope="row">To</th>
                    <td><input type="text" id="toMail" name="to" value="<?=$to?>" style="width: 700px"></td>
                </tr>
                <tr>
                    <th align="left" scope="row">Cc</th>
                    <td><input type="text" id="ccMail" name="cc" value="" style="width: 700px"></td>
                </tr>
                 <tr>
                    <th align="left" scope="row">Bcc</th>
                    <td><input type="text" id="bccMail" name="bcc" value="" style="width: 700px"></td>
                </tr>
                
                <tr>
                    <th align="left" scope="row">Subject</th>
                    <td><input type="text" name="subject" value="<?=$subject?>" style="width: 700px" readonly></td>
                </tr>
                 <tr>
                    <th align="left" scope="row">Body</th>    
                    <td><div style="border:1px solid #A9A9A9;font-size: 12px;padding:3px;"><?=$body?></div></td>
                    <input type="text" id="body" name="body" style="display:none" value="<?=$body?>">
                </tr>
                <tr>
                    <th align="left" scope="row">Instruction<br>(Optional)</th>    
                    <td><textarea rows="8" id="bodyInstruction" name="bodyInstruction" style="width: 700px;"></textarea></td>
                </tr>
<!--
                <tr>
                    <th align="left" scope="row">Body</th>    
                    <td><textarea rows="20" id="body" name="body" style="width: 700px;"><?=$body?></textarea></td>
                </tr>-->


                <tr>
                    <th align="left" scope="row">&nbsp;</th>
                    <td>
                        <input type="submit" id="submitBtn" class="btn btn-success" style="display:none">
                    </td>
<!--                    <td><button type="submit" class="btn btn-success">Send Mail</button> <a href="<?php echo base_url(); ?>banking_service_request/getRequests" class="btn btn-success"><i class="icon-remove"></i><span> Cancel</span></a></td>-->
                </tr>

            </table>
        </fieldset>

    </form>
    <button class="btn btn-success" onclick="checkValidation()">Send Mail</button>
    <a href="<?php echo base_url(); ?>banking_service_request/getRequests" class="btn btn-success"><i class="icon-remove"></i><span> Cancel</span></a>
</div>

<script>

tinymce.init({

    selector: 'textarea',
    readonly: 0

});

</script>