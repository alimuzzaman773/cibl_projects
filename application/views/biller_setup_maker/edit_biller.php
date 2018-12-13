<title>Biller Setup</title>
<h2 class="title-underlined">Edit Biller</h2>

<div class="container">

    <?php if (trim($message) != ''): ?>
        <div class="alert alert-success"><?php echo $message ?></div>
    <?php endif; ?>

    <form method="post" style="" id="billerForm" name="billerForm" action="<?php echo base_url(); ?>biller_setup_maker/updateBiller">
        <input hidden type="text" name="billerId" id="billerId" value="<?= $billerData['billerId'] ?>" size="20" />
        <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">
        <table class="table table-condensed table-bordered table-striped">
            <tr>
                <th width="250" align="left" scope="row">Biller Name*</th>
                <th><input class="form-control input-sm" type="text" name="billerName" id="billerName" class="form-control input-sm" value="<?= $billerData['billerName'] ?>" required/></td>
            </tr>
            <tr>
                <th align="left" scope="row">CFID*</th>
                <td><input class="form-control input-sm" type="text" name="cfId" id="cfId" value="<?= $billerData['cfId'] ?>" required/></td>
            </tr>
            <tr>
                <th align="left" scope="row">Biller Code*</th>
                <td><input class="form-control input-sm" type="text" name="billerCode" id="billerCode" style="text-transform:uppercase" value="<?= $billerData['billerCode'] ?>" required/></td>
            </tr>
            <tr>
                <th align="left" scope="row">Biller Order*</th>
                <td><input class="form-control input-sm" type="number" name="billerOrder" id="billerOrder" value="<?= $billerData['billerOrder'] ?>"  required/></td>
            </tr>
            <tr>
                <th align="left" scope="row">Suggestion (Reference No.)</th>
                <td><textarea class="form-control input-sm" type="text" name="suggestion" id="suggestion" rows="4" cols="50" placeholder="Write suggestion here..." required><?= $billerData['suggestion'] ?></textarea></td>
            </tr>

            <tr>
                <th align="left" scope="row">Reference Regex</th>
                <td><input class="form-control input-sm" type="text" name="referenceRegex" id="referenceRegex" value="<?= $billerData['referenceRegex'] ?>" style="width: 500px" /></td>
            </tr>

            <tr>
                <th align="left" scope="row">Reference Flag</th>
                <td><input class="form-control input-sm" type="number" min="0" max="1" name="referenceMatch" id="referenceMatch" value="<?= $billerData['referenceMatch'] ?>" /></td>
            </tr>
            <tr>
                <th align="left" scope="row">Suggestion (Amount)</th>
                <td><textarea class="form-control input-sm" type="text" name="amountMessage" id="amountMessage" rows="4" cols="50" placeholder="Write suggestion here..." required><?= $billerData['amountMessage'] ?></textarea></td>
            </tr>
            <tr>
                <th align="left" scope="row">Amount Regex</th>
                <td><input class="form-control input-sm" type="text" name="amountRegex" id="amountRegex" value="<?= $billerData['amountRegex'] ?>" /></td>
            </tr>
            <tr>
                <th align="left" scope="row">Amount Flag</th>
                <td><input class="form-control input-sm" type="number" min="0" max="1" name="amountMatch" id="amountMatch" value="<?= $billerData['amountMatch'] ?>" /></td>
            </tr>
            <tr>
                <th align="left" scope="row">Merchant ID</th>
                <td>
                    <input class="form-control input-sm"  type="text" min="0" max="1" name="merchantId" id="merchantId" value="<?= $billerData['merchantId'] ?>" />
                    <small>Please provide merchant id as it will be used in card related debit action</small>
                </td>
            </tr>
            <tr>                       
                <th align="left" scope="">Select Biller Type*</th>
                <td>
                    <select id="billTypeCode" class="form-control input-sm" name="billTypeCode" required>
                        <option value="">Select a bill type</option>                      
                        <?php foreach ($billTypes as $item) { ?>
                            <option value="<?= $item->billTypeCode ?>"><?= $item->billTypeName ?></option>
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
                        <textarea class="form-control input-sm" name="reason" id="reason" cols="40" rows="5" readonly> <?= $billerData['checkerActionComment'] ?> </textarea>
                        <br><br>
                        </div>
                        </table>
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
                                if ($("#billTypeCode").val() === "00")
                                {
                                    alert("Please Select A Bill Type");
                                } else
                                {
                                    $("#billerForm").submit();
                                }
                            }
                        </script>

                        <style>
                            @media(min-width:768px){
                                #billerForm .form-control{
                                    max-width: 500px;
                                }        
                            }
                        </style>


