<title>Generate Pin</title>
<div class="breadcrum">Pin Numbers</div>

<div class="container" style="margin-top:50px">

    <br><br>

    <div>
    <form method="post" id="actionSelectForm" name="actionForm" action="<?php echo base_url(); ?>pin_generation/viewPinByAction">
    <table width="300" border="0" cellpadding="5">         
        <tr>
            <th align="left" scope="">Select Action</th>
            <td>
                <select id="actionSelect" name="action" data-bind="event: {change: $root.generateList}">
                    <option value="all">View All</option>

                </select>
            </td>
        </tr>   
    </table>
    </form>
    </div>

    <br/><br/>


    <button style="display: none;" id="destroy" data-bind="click :$root.destroy" class="btn btn-success">Destroy</button>
    <button style="display: none;" id="reset" data-bind="click :$root.reset" class="btn btn-success">Reset</button>   
    <button style="display: none;" id="print" data-bind="click :$root.print" class="btn btn-success">Print</button>
    <button style="display: none;" id="create" data-bind="click :$root.create" class="btn btn-success">Create</button>

    <br><br>


    <table data-bind="dataTable: { dataSource : records, rowTemplate: 'rowTemplate',
           options: {
           bAutoWidth : false,
           aoColumnDefs: [
           { bSortable: false, aTargets: [] },
           { bSearchable: false, aTargets: [] }
           ],
           aaSorting: [],
           aLengthMenu: [[5, 10, 15, -1], [5, 10, 15, 'All']],
           iDisplayLength: 50,
           aoColumns: [
           {  mDataProp:'eblSkyId' },
           {  mDataProp:'pin' },
           {  mDataProp:'active' },
           {  mDataProp:'used' },
           {  mDataProp:'printed' },
           {  mDataProp:'reset' },
           {  mDataProp:'status' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
            <tr>
                <th style="text-align: center" valign="middle">ESB ID</th>
                <th style="text-align: center" valign="middle">Pin Number</th>
                <th style="text-align: center" valign="middle">Destroyed/Active</th>
                <th style="text-align: center" valign="middle">Used/Unused</th>
                <th style="text-align: center" valign="middle">Printed/Not</th>
                <th style="text-align: center" valign="middle">Reset/Not</th>
                <th style="text-align: center" valign="middle">Status</th>
                <th style="text-align:left">
<!--                    
<button style="text-align:left" id="selectAll" data-bind="click :$root.selectAll" class="btn btn-primary">Select All</button>
<button style="text-align:left" id="deselectAll" data-bind="click :$root.deselectAll, visible: false" class="btn btn-primary">Deselect All</button>
-->
                </th>
            </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td data-bind="text:eblSkyId"></td>
        <td data-bind="text:pin"></td>
        <td style="text-align:center" data-bind="text:active"></td>
        <td style="text-align:center" data-bind="text:used"></td>
        <td style="text-align:center" data-bind="text:printed"></td>
        <td style="text-align:center" data-bind="text:reset"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
        <td style="text-align:center"><input type="checkbox" data-bind="checked: isProcessed"></td>
    </script>

</div>



    <div style="display: none" id="printDiv">





    </div>



<style>

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



<script type="text/javascript">

    $(function() {
        $('#actionSelect').val("<?= $selectedValue ?>");
        if($('#actionSelect :selected').val() === "destroy"){
            document.getElementById("destroy").style.display = "block";
        }
        else if($('#actionSelect :selected').val() === "reset"){
            document.getElementById("reset").style.display = "block";
        }
        else if($('#actionSelect :selected').val() === "print"){
            document.getElementById("print").style.display = "block";
        }
        else if($('#actionSelect :selected').val() === "create"){
            document.getElementById("create").style.display = "block";
        }
    });

</script>


<script type="text/javascript" charset="utf-8">
    var initialData = jQuery.parseJSON('<?= $pinNumbers ?>'); //data for building initial table
    var vm = function() {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function(i, record) {   //build the checkboxes as observable
            record.isProcessed = ko.observable(false);



            if(record.isActive == 1){
                record.active = "Active";
            }else if(record.isActive == 0){
                record.active = "Destroyed";
            }


            if(record.isUsed == 1){
                record.used = "Used";
            }else if(record.isUsed == 0){
                record.used = "Not Used";
            }


            if(record.isPrinted == 1){
                record.printed = "Printed";
            }else if(record.isPrinted == 0){
                record.printed = "Not Printed";
            }

            if(record.isReset == 1){
                record.reset = "Reset";
            }else if(record.isReset == 0){
                record.reset = "Not Reset";
            }


            if(record.mcStatus === "1"){
                record.status = "Approved";
                record.statusColor = ko.observable("green");
            }else if(record.mcStatus === "0"){
                record.status = "Wait for approve";
                record.statusColor = ko.observable("red");
            }else if(record.mcStatus === "2"){
                record.status = "Rejected";
                record.statusColor = ko.observable("red");
            }
        })


        self.selectAll = function(){
            document.getElementById("selectAll").style.display = "none";
            document.getElementById("deselectAll").style.display = "block";
            $.each(self.records(), function(i, record){
                record.isProcessed(true);
            })
        }


        self.deselectAll = function(){
            document.getElementById("selectAll").style.display = "block";
            document.getElementById("deselectAll").style.display = "none";
            $.each(self.records(), function(i, record){
                record.isProcessed(false);
            })
        }



        self.print = function(){

            var esb_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();

            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    esb_id = esb_id+"|"+record.eblSkyId;
                }
            })

            if(esb_id == ""){
                alert("Error: No Pin Is Selected");
                return false;
            }

            var dataToSave = {"esbid" : esb_id, "selectedActionName" : selected_action_name};

            $.ajax({
                type: "POST",
                data: dataToSave,
                dataType: 'json',
                url: "<?= base_url() ?>pin_generation/pinPrint",
                success: function(data) {

                    if(data.responseCode === 1) {

                        for(var i = 0; i < data.printData.length; i++) {

                            var br1 = document.createElement("br");
                            var br2 = document.createElement("br");
                            var br3 = document.createElement("br");
                            var br4 = document.createElement("br");
                            var br5 = document.createElement("br");
                            var br6 = document.createElement("br");
                            

                            var innerDiv = document.createElement("div");
                            innerDiv.style.height = "200px"

                            var h2 = document.createElement("h2");
                            h2.style.marginLeft = "150px";

                            var h3 = document.createElement("h4");
                            h3.style.marginLeft = "70px";


                            h2.appendChild(document.createTextNode("ID: " + data.printData[i].eblSkyId));
                            h3.appendChild(document.createTextNode("ID: " + data.printData[i].eblSkyId + " Password: " + data.printData[i].pin));

                            innerDiv.appendChild(h2);
                            innerDiv.appendChild(br1);
                            innerDiv.appendChild(br2);
                            innerDiv.appendChild(br3);
                            innerDiv.appendChild(br4);
                            innerDiv.appendChild(br5);
                            
                            innerDiv.appendChild(h3);

                            outerDiv = document.getElementById("printDiv");
                            outerDiv.appendChild(br6);
                            outerDiv.appendChild(innerDiv);
                        }

                        var printContents = document.getElementById('printDiv').innerHTML;
                        var originalContents = document.body.innerHTML;
                        document.body.innerHTML = printContents;
                        window.print();
                        document.body.innerHTML = originalContents;
                    }

                    alert(data.message);
                    window.location = "<?= base_url() ?>pin_generation/viewPinByAction";

                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }



        self.destroy = function()
        {

            alert("Pin destroy action is not allowed.");
            window.location = "<?= base_url() ?>pin_generation/viewPinByAction";


            // var esb_id = "";
            // var selected_action_name = $("#actionSelect option:selected").text();
            // $.each(self.records(), function (i, record) {
            //     if(record.isProcessed() == true){
            //         esb_id = esb_id+"|"+record.eblSkyId;
            //     }
            // })

            // if(esb_id == ""){
            //     alert("Error: No Pin Is Selected");
            //     return false;
            // }

            // var dataToSave = {"esbid" : esb_id, "selectedActionName" : selected_action_name};
            // $.ajax({
            //     type: "POST",
            //     data: dataToSave,
            //     url: "<?= base_url() ?>pin_generation/pinDestroy",
            //     success: function(data) {
            //         if (data == 1) {
            //             alert("Successfully Destroyed");
            //             window.location = "<?= base_url() ?>pin_generation/viewPinByAction";
            //         }
            //     },
            //     error: function(error) {
            //         alert(error.status + "<--and--> " + error.statusText);
            //     }
            // });
        }



        self.reset = function()
        {
            var esb_id = "";
            var selected_action_name = $("#actionSelect option:selected").text();
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    esb_id = esb_id+"|"+record.eblSkyId;
                }
            })

            if(esb_id == ""){
                alert("Error: No Record is Selected");
                return false;
            }

            var dataToSave = {"esbid" : esb_id, "selectedActionName" : selected_action_name};
            $.ajax({
                type: "POST",
                data: dataToSave,
                url: "<?= base_url() ?>pin_generation/pinReset",
                success: function(data) {
                    if (data == 1) {
                        alert("Successfully sent to checker for approval");
                        window.location = "<?= base_url() ?>pin_generation/viewPinByAction";
                    }
                },
                error: function(error) {
                    alert(error.status + "<--and--> " + error.statusText);
                }
            });
        }

        self.create = function()
        {
            window.location = "<?= base_url() ?>pin_generation/index/" + $("#actionSelect option:selected").text();
        }



        self.testPrint = function()
        {
            var printContents = document.getElementById('testDiv').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }


        self.generateList = function()
        {
            $("#actionSelectForm").submit();
        }
    }

    ko.applyBindings(new vm());
</script>


<script type="text/javascript" charset="utf-8">

varactionCodes= <?php echo $actionCodes?>;
varactionNames= <?php echo $actionNames?>;
varsel= document.getElementById('actionSelect');
for(vari= 0; i<actionCodes.length; i++) {
varopt = document.createElement('option');
if(actionCodes[i] !=="print"){
opt.innerHTML= actionNames[i];
opt.value= actionCodes[i];
sel.appendChild(opt);
    }
}

</script>