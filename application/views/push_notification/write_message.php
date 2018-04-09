
<title>Notify to user</title>
<div class="breadcrum">Write Message</div>

<div class="container" style="margin-top:50px">

    <form method="post" style="" id="messageForm" name="messageForm" action="<?php echo base_url(); ?>push_notification/sendMessage">

        <input hidden type="text" name="skyIds" id="skyIds" value="">
        <fieldset>
            <table width="500" border="0" cellpadding="5">
                <tr>
                    <th align="left" scope="row">Headline</th>
                    <td><input type="text" name="headLine" id="headLine"></td>
                </tr>

                
                <tr>
                    <th align="left" scope="row">Body</th>
                    <td><textarea name="body" id="body" class="texteditor" form="messageForm" ></textarea></td>
                </tr>
            </table>
        </fieldset>

    </form>

    <br><br>

    <table data-bind="dataTable: { dataSource : records, rowTemplate: 'rowTemplate',
           options: {
           bAutoWidth : false,
           aoColumnDefs: [
           { bSortable: false, aTargets: [] },
           { bSearchable: false, aTargets: [] }
           ],
           aaSorting: [],
           aLengthMenu: [[50, 100, 150, -1], [50, 100, 150, 'All']],
           iDisplayLength: 50,
           aoColumns: [
           {  mDataProp:'skyId' },
           {  mDataProp:'eblSkyId' },
           {  mDataProp:'cfId' },
           {  mDataProp:'gender' },
           {  mDataProp:'dob' },
           {  mDataProp:'userName' },
           {  mDataProp:'userEmail' },
           {  mDataProp:'userGroupName' },
           {  mDataProp:'isLocked' },
           {  mDataProp:'isActive' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
            <tr>
                <th style="text-align:left">                 
                    <button style="text-align:left" id="selectAll" data-bind="click :$root.selectAll" class="btn btn-primary">Select All</button>
                    <button style="text-align:left" id="deselectAll" data-bind="click :$root.deselectAll, visible: false" class="btn btn-primary">Deselect All</button>
                </th>
                <th hidden style="text-align:center" >SKY ID</th>
                <th style="text-align:center" >ESB ID</th>
                <th style="text-align:center" >CFID</th>
                <th style="text-align:center" >Gender</th>
                <th style="text-align:center" >Date of Birth</th>
                <th style="text-align:center" >User Name</th>
                <th style="text-align:center" >Email</th>
                <th style="text-align:center" >User Group</th>
                <th style="text-align:center" >Lock/Unlock</th>
                <th style="text-align:center" >Active/Inactive</th>
            </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td style="text-align:center"><input type="checkbox" data-bind="checked: isProcessed"></td>   
        <td hidden data-bind="text:skyId"></td>
        <td data-bind="text:eblSkyId"></td>
        <td data-bind="text:cfId"></td>
        <td style="text-align:center" data-bind="text:gender"></td>
        <td style="text-align:center" data-bind="text:dob"></td>
        <td style="text-align:center" data-bind="text:userName"></td>
        <td style="text-align:center" data-bind="text:userEmail"></td>
        <td style="text-align:center" data-bind="text:userGroupName"></td>
        <td style="text-align:center" data-bind="text:lock, style:{color: lockColor}"></td>
        <td style="text-align:center" data-bind="text:active, style:{color: activeColor}"></td>      
    </script>



    <table width="50" border="0" cellpadding="2">
        <tr>
            <td width="100"><button style="display: block;" id="sendMessage" data-bind="click :$root.sendMessage" class="btn btn-success">Send Message</button></td>
            <td width="100"><button style="display: block;" id="back" data-bind="click :$root.back" class="btn btn-success">Back</button></td>
        </tr>
    </table>

</div>







<script type="text/javascript" charset="utf-8">
    var initialData = <?= $appsUsers ?>; //data for building initial table
    var vm = function() {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function(i, record) {   //build the checkboxes as observable
            record.isProcessed = ko.observable(false);

            record.skyId = record.skyId;
            record.eblSkyId = record.eblSkyId;
            record.cfId = record.cfId;
            record.gender = record.gender;
            record.dob = record.dob;
            record.userName = record.userName;
            record.userEmail = record.userEmail;
            record.userGroupName = record.userGroupName;

            if(record.isActive == 1){
                record.active = "Active";
                record.activeColor = ko.observable("green");
            }
            else if(record.isActive == 0){
                record.active = "Inactive";
                record.activeColor = ko.observable("red");
            }

            if(record.isLocked == 1){
                record.lock = "Locked";
                record.lockColor = ko.observable("red");
            }
            else if(record.isLocked == 0){
                record.lock = "Unlocked";
                record.lockColor = ko.observable("green");
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

        self.back = function(){
            window.location = "<?php echo base_url(); ?>push_notification";
        }


        self.sendMessage = function(){
            var sky_ids = "";
            $.each(self.records(), function (i, record) {
                if(record.isProcessed() == true){
                    sky_ids = sky_ids+","+record.skyId;
                }
            })
            if($("#headLine").val() === "" || $("#headLine").val() === null){
                alert("Headline is required");
                return false;
            }

            
            // if($("#body").val() === "" || $("#body").val() === null){
            //     alert("Body is required");
            //     return false;
            // }


            if(sky_ids == ""){
                alert("Error: No User Is Selected");
                return false;
            }
            sky_ids = sky_ids.substring(1);
            $("#skyIds").val(sky_ids);
            $("#messageForm").submit();
        }
    }
    ko.applyBindings(new vm());
</script>


<script>tinymce.init({selector:'textarea'});</script>


</div>