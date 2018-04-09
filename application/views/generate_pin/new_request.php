<title>Pin Request</title>
<div class="breadcrum">New Request</div>


<div class="container" style="margin-top:50px">

    <div class="alert alert-success"><?php echo $message ?></div>

    <form method="post" style="" id="pinRequestForm" name="pinRequestForm" action="<?php echo base_url(); ?>pin_generation/insertNewRequest">

        <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

        <fieldset>


            <table width="550" border="0" cellpadding="10">

                <tr>
                    <th width="213" align="left" scope="row">Total Pin*</th>
                    <td width="161"><input type="number" min="1" max="100" name="totalPin" id="totalPin" style="width: 150px" required/></td>
                </tr>

                <tr>
                    <th align="left" scope="row">Remark</th>
                    <td><textarea type="text" name="makerActionComment" id="makerActionComment" rows="4" cols="50" placeholder="Write remark here..." required></textarea></td>
                </tr>

                <tr>
                    <th align="left" scope="row">&nbsp;</th>
                    <td></td>
                </tr>

            </table>

        </fieldset>
        <button type="submit" form="pinRequestForm" value="Submit" class="btn btn-success">Submit</button>
        <a href="<?php echo base_url(); ?>pin_generation/index" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Cancel</span></a> 

    </form>

</div>



