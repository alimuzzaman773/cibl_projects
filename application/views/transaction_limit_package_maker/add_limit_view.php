<title>Limit Package</title>
<div class="breadcrum">Add Limit</div>

<div class="container" style="margin-top:50px">
    <label>Group Name</label>
    <input class="textbox" type="text" name="groupName" id="groupName" value="<?= $groupName ?>" readonly><br><br>
</div>

<input hidden class="textbox" type="text" name="selectedActionName" id="selectedActionName" value="<?= $selectedActionName ?>">


<div class="container" id="assignPackage">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
            <th hidden >ID</th>
            <th>Package Name</th>
            <th style="text-align: center" valign="middle">Minimum Transaction Limit</th>
            <th style="text-align: center" valign="middle">Maximum Transaction Limit</th>
            <th style="text-align: center" valign="middle">Day Transaction Limit</th>
            <th style="text-align: center" valign="middle">No. of Transaction Per Day</th>
            </tr>
      </thead>
      <tbody data-bind="foreach: records">
          <tr>
            <td  hidden data-bind="text:packageId"></td>
            <td data-bind="text:packageName"></td>                
            <td contenteditable > </td>
            <td contenteditable > </td>
            <td contenteditable > </td>
            <td contenteditable > </td>
         </tr>
    </tbody>
  </table>    
<br><br>
 <button data-bind="click :$root.create" class="btn btn-success">Create Group</button>
<a href="<?php echo base_url(); ?>transaction_limit_setup_maker/createGroup/<?= $selectedActionName ?>" class="btn btn-success"><i class="icon-plus icon-white"></i><span>Back</span></a> 
</div>


<style scoped>
  #example-one { margin-bottom: 1px; }
  [contenteditable="true"] { padding: 1px; outline: 1px dashed #CCC; }
  [contenteditable="true"]:hover { outline: 1px dashed #0090D2; }
</style>

 
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



<script type="text/javascript" charset="utf-8">
  var initialData = jQuery.parseJSON('<?= $packages ?>');//data for building initial table
  var vm = function() {
    var self = this;
    self.records = ko.observableArray(initialData);
    $.each(self.records(), function(i, record){
        record.packageId = record.packageId;
        record.packageName = record.packageName;
    })

    
    self.create = function()
    {
      $(function() {
        var ownAccount = [];
        var eblAccount = [];
        var otherBank = [];
        var billsPay = [];
        var obj = {
          "1": ownAccount,
          "2": eblAccount,
          "3": otherBank,
          "4": billsPay
        };
        $("#assignPackage tr").each(function(){
          var tableData = $(this).find('td');
          var firstCellVal = $.trim(tableData.eq(0).text());
          if (tableData.length > 0) {
              tableData.each(function(){
                obj[firstCellVal].push($(this).text());
            });
          }
        });

        var GroupName = "";
        var SelectedActionName = "";
        GroupName = groupName.value;
        SelectedActionName = selectedActionName.value;
        var dataToSave = {"group_name" : GroupName,
                          "selected_action_name" : SelectedActionName,
                          "own_acc_transfer" : ownAccount,
                          "ebl_acc_transfer" : eblAccount,
                          "other_bank_transfer" : otherBank,
                          "bills_pay" : billsPay};

        var own = true;
        var ebl = true;
        var ob = true;
        var bill = true;
        var type = "";

        for(var i = 2; i < ownAccount.length; i++){
          type = "Own Account Transfer";
          var ownCheck = dataValidate(ownAccount[i], type, i);
          if(ownCheck){
            alert(ownCheck);
            //sweetAlert("", ownCheck, "error");
            own = false;
            break;
          }
          else{
            if(own === false){
              own = false;
            }
            else{
              own = true;
            }
          }
        }


        for(var i = 2; i < eblAccount.length; i++){
          type = "EBL Account Transfer";
          var eblCheck = dataValidate(eblAccount[i], type, i);
          if(eblCheck){
            alert(eblCheck);
            //sweetAlert("", eblCheck, "error");
            ebl = false;
            break;
          }
          else{
            if(ebl === false){
              ebl = false;
            }
            else{
              ebl = true;
            }
          }
        }

        for(var i = 2; i < otherBank.length; i++){
          type = "Other Bank Transfer";
          var otherCheck = dataValidate(otherBank[i], type, i);
          if(otherCheck){
            alert(otherCheck);
            //sweetAlert("", otherCheck, "error");
            ob = false;
            break;
          }
          else{
            if(ob === false){
              ob = false;
            }
            else{
              ob = true;
            }
          }
        }

        for(var i = 2; i < billsPay.length; i++){
          type = "Bills pay";
          var billCheck = dataValidate(billsPay[i], type, i);
          if(billCheck){
            alert(billCheck);
            //sweetAlert("", billCheck, "error");
            bill = false;
            break;
          }
          else{
            if(bill === false){
              bill = false;
            }
            else{
              bill = true;
            }
          }
        }


        if(own === true && ebl === true && ob === true && bill === true){

          $.ajax({
              type: "POST",
              data: dataToSave,
              url: "<?= base_url() ?>transaction_limit_setup_maker/saveGroup",
              success: function(data) {
                if (data > 0) {
                    alert("Group Successfully Created");
                    window.location = "<?= base_url() ?>transaction_limit_setup_maker";
                }

                else {
                    alert("This Group Already Exists");
                    window.location = "<?= base_url() ?>transaction_limit_setup_maker/createGroup";
                }
              },
              error: function(error) {
                alert(error.status + "<----> " + error.statusText);
              }
          });
        }
      });
    }



    var dataValidate = function(data, type, i){
      
      switch(i){

        case 2:
            if(data == " " || data == "" || data == 0.00){
              return "Minimum Transaction limit for " + type + " can't be empty or zero";
            }
            else if(isNaN(data)){ 
              return "Only numbers are allowed as input";
            }

            else{
              return false;
            }

        case 3:
            if(data == " " || data == "" || data == 0.00){
              return "Maximum Transaction limit for " + type + " can't be empty or zero";
            }
            else if(isNaN(data)){ 
              return "Only numbers are allowed as input";
            }

            else{
              return false;
            }

        case 4:
            if(data == " " || data == "" || data == 0.00){
              return "Day Transaction limit for " + type + " can't be empty or zero";
            }
            else if(isNaN(data)){ 
              return "Only numbers are allowed as input";
            }

            else{
              return false;
            }

        case 5:
            if(data == " " || data == "" || data == 0){
              return "No. of Transaction for " + type + " can't be empty or zero";
            }
            else if(isNaN(data)){ 
              return "Only numbers are allowed as input";
            }

            else{
              return false;
            }

        default:
            return false;
      }
    }
  }
  ko.applyBindings(new vm());
</script>