<h2 class="title-underlined">Add Biller</h2>
<?php if(trim($message) != ''): ?>
<div class="alert alert-success"><?php echo $message ?></div>
<?php endif; ?>
<form method="post" style="" id="billerForm" name="billerForm" action="<?php echo base_url(); ?>biller_setup_maker/insertNewBiller">

    <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

         <table class="table table-condensed table-bordered table-striped">
            <tr>
                <th width="250" align="left" scope="row">Biller Name*</th>
                <td><input class="form-control input-sm"  type="text" name="billerName" id="billerName" required/></td>
            </tr>
            <tr>
                <th align="left" scope="row">CFID*</th>
                <td><input class="form-control input-sm"  type="text" name="cfId" id="cfId" required/></td>
            </tr>
            <tr>
                <th align="left" scope="row">Biller Code*</th>
                <td><input class="form-control input-sm"  type="text" name="billerCode" id="billerCode" style="text-transform:uppercase" required/></td>
            </tr>

            <tr>
                <th align="left" scope="row">Biller Order*</th>
                <td><input class="form-control input-sm"  type="number" name="billerOrder" id="billerOrder"  required/></td>
            </tr>
            <tr>
                <th align="left" scope="row">Suggestion (Reference No.)</th>
                <td><textarea class="form-control input-sm"  type="text" name="suggestion" id="suggestion" rows="4" cols="50" placeholder="Write suggestion here..." required></textarea></td>
            </tr>
            <tr>
                <th align="left" scope="row">Reference Regex</th>
                <td><input class="form-control input-sm"  type="text" name="referenceRegex" id="referenceRegex" style="width: 500px" /></td>
            </tr>
            <tr>
                <th align="left" scope="row">Reference Flag</th>
                <td><input class="form-control input-sm"  type="number" min="0" max="1" name="referenceMatch" id="referenceMatch"  /></td>
            </tr>
            <tr>
                <th align="left" scope="row">Suggestion (Amount)</th>
                <td><textarea class="form-control input-sm"  type="text" name="amountMessage" id="amountMessage" rows="4" cols="50" placeholder="Write suggestion here..." required></textarea></td>
            </tr>
            <tr>
                <th align="left" scope="row">Amount Regex</th>
                <td><input class="form-control input-sm"  type="text" name="amountRegex" id="amountRegex" style="width: 500px" /></td>
            </tr>
            <tr>
                <th align="left" scope="row">Amount Flag</th>
                <td><input class="form-control input-sm"  type="number" min="0" max="1" name="amountMatch" id="amountMatch"  /></td>
            </tr>
            <tr>
                <th align="left" scope="row">Merchant ID</th>
                <td>
                    <input class="form-control input-sm"  type="text" min="0" max="1" name="merchantId" id="merchantId"  />
                    <small>Please provide merchant id as it will be used in card related debit action</small>
                </td>
            </tr>
            <tr>                       
                <th align="left" scope="">Select Biller Type*</th>
                <td>
                    <select class="form-control input-sm"  id="billTypeCode" name="billTypeCode" required>
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

        </table>
    <button type="submit" form="billerForm" value="Submit" class="btn btn-success">Add</button>
    <a href="<?php echo base_url(); ?>biller_setup_maker" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a> 

</form>
<style>
    @media(min-width:768px){
        #billerForm .form-control{
            max-width: 500px;
        }        
    }
</style>


