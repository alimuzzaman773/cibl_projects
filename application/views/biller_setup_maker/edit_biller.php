<title>Biller Setup</title>
<div class="breadcrum">Edit Biller</div>


<div class="container" style="margin-top:50px">

    <div class="alert alert-success"><?php echo $message ?></div>

    <form method="post" style="" id="billerForm" name="billerForm" action="<?php echo base_url(); ?>biller_setup_maker/updateBiller">

        <input hidden type="text" name="billerId" id="billerId" value="<?=$billerData['billerId'] ?>" size="20" />
        <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

        <fieldset>


            <table width="800" border="0" cellpadding="10">

                <tr>
                    <th width="213" align="left" scope="row">Biller Name*</th>
                    <td width="161"><input type="text" name="billerName" id="billerName" style="width: 500px" value="<?=$billerData['billerName']?>" required/></td>
                </tr>

                <tr>
                    <th width="213" align="left" scope="row">CFID*</th>
                    <td width="161"><input type="text" name="cfId" id="cfId" value="<?=$billerData['cfId']?>" required/></td>
                </tr>

                <tr>
                    <th align="left" scope="row">Biller Code*</th>
                    <td><input type="text" name="billerCode" id="billerCode" style="text-transform:uppercase" value="<?=$billerData['billerCode']?>" required/></td>
                </tr>

                <tr>
                    <th align="left" scope="row">Biller Order*</th>
                    <td><input type="number" name="billerOrder" id="billerOrder" value="<?=$billerData['billerOrder']?>"  required/></td>
                </tr>


                <tr>
                    <th align="left" scope="row">Suggestion (Reference No.)</th>
                    <td><textarea type="text" name="suggestion" id="suggestion" rows="4" cols="50" placeholder="Write suggestion here..." required><?=$billerData['suggestion']?></textarea></td>
                </tr>

                <tr>
                    <th align="left" scope="row">Reference Regex</th>
                    <td><input type="text" name="referenceRegex" id="referenceRegex" value="<?=$billerData['referenceRegex']?>" style="width: 500px" /></td>
                </tr>

                <tr>
                    <th align="left" scope="row">Reference Flag</th>
                    <td><input type="number" min="0" max="1" name="referenceMatch" id="referenceMatch" value="<?=$billerData['referenceMatch']?>" /></td>
                </tr>

                <tr>
                    <th align="left" scope="row">Suggestion (Amount)</th>
                    <td><textarea type="text" name="amountMessage" id="amountMessage" rows="4" cols="50" placeholder="Write suggestion here..." required><?=$billerData['amountMessage']?></textarea></td>
                </tr>


                <tr>
                    <th align="left" scope="row">Amount Regex</th>
                    <td><input type="text" name="amountRegex" id="amountRegex" value="<?=$billerData['amountRegex']?>" style="width: 500px" /></td>
                </tr>


                <tr>
                    <th align="left" scope="row">Amount Flag</th>
                    <td><input type="number" min="0" max="1" name="amountMatch" id="amountMatch" value="<?=$billerData['amountMatch']?>" /></td>
                </tr>

                <tr>                       
                    <th align="left" scope="">Select Bille Type*</th>
                    <td>
                        <select id="billTypeCode" name="billTypeCode" required>
                            <option value="">Select a bill type</option>                      
                            <?php foreach($billTypes as $item){ ?>
                            <option value="<?=$item->billTypeCode?>"><?= $item->billTypeName ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>              

                <tr>
                    <th align="left" scope="row">&nbsp;</th>
                    <td></td>
                </tr>


            <div id="reasonDiv" style="<?= $reasonModeOfDisplay ?>" >
                <h3>Reject Reason<h3>
                <textarea name="reason" id="reason" cols="40" rows="5" readonly> <?=$billerData['checkerActionComment']?> </textarea>
                <br><br>
            </div>



            </table>

        </fieldset>
        <input type="button"  value="Update" onclick="submitForm()" class="btn btn-success"/>
        <a href="<?php echo base_url(); ?>biller_setup_maker" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a> 

    </form>

</div>



<script type="text/javascript">
    var billTypeCode = <?php echo $billerData['billTypeCode'] ?>;
    document.getElementById("billTypeCode").selectedIndex = billTypeCode;
</script>


<script type="text/javascript">
    function submitForm()
    {
        if($("#billTypeCode").val() === "00")
        {
            alert("Please Select A Bill Type");
        }
        else
        {
            $("#billerForm").submit();
        }
    }
</script>




