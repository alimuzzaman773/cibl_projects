<title>Limit Package</title>
<div class="breadcrum">Add Package</div>

<div class="container" style="margin-top:50px">

    <div class="alert alert-success"><?php echo $message ?></div>   

    <form method="post" style="" id="packageSelection" name="packageSelection" action="<?php echo base_url(); ?>transaction_limit_setup_maker/assignGroup">
        <div class="container" style="margin-top:50px">

            <input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">

            <label>Group Name</label>
            <input class="textbox" type="text" name="groupName" id="groupName" required><br><br>

            <input style="margin-right: 15px" type="checkbox" name="packageName[]" value="1"><label> Own Account Transfer</label><br>
            <input style="margin-right: 15px" type="checkbox" name="packageName[]" value="2"><label> EBL Account Transfer</label><br>
            <input style="margin-right: 15px" type="checkbox" name="packageName[]" value="3"><label> Other Bank Transfer</label><br>
            <input style="margin-right: 15px" type="checkbox" name="packageName[]" value="4"><label> Bills Pay</label><br>

            <br><br>
            <button type="submit" class="btn btn-success">Next</button>
            <a href="<?php echo base_url(); ?>transaction_limit_setup_maker" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back</span></a> 

        </div>
    </form>

</div>


<style> 
    .textbox { 
        border: 1px solid #848484; 
        -webkit-border-radius: 30px; 
        -moz-border-radius: 30px; 
        border-radius: 30px; 
        outline:0;
        height:25px; 
        width: 275px; 
        padding-left:10px; 
        padding-right:10px; 
    } 
</style>


<style>

    label {
        vertical-align:middle;
    }

    input {
        float:center;
        border: 1px solid #848484; 
        -webkit-border-radius: 30px; 
        -moz-border-radius: 30px; 
        border-radius: 30px; 
        outline:0; 
        height:25px; 
        width: 100px; 
        padding-left:10px; 
        padding-right:10px; 
    }
</style>



