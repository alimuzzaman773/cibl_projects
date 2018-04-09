<title>Notify to user</title>
<div class="breadcrum">Sent Messages</div>

<div class="container" style="margin-top:50px">


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
           {  mDataProp:'messageId' },
           {  mDataProp:'headLine' },
           {  mDataProp:'body' },
           {  mDataProp:'recipient' },
           {  mDataProp:'isActive' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
            <tr>
                <th hidden style="text-align: center" >ID</th>
                <th style="text-align: center" >Headline</th>
                <th hidden style="text-align: center" >Body</th>
                <th hidden style="text-align: center" >Recepient</th>
                <th style="text-align: center" >Active/Inactive</th>
            </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden style="text-align:center" data-bind="text:messageId"></td>
        <td style="text-align:center" data-bind="text:headLine"></td>
        <td hidden style="text-align:center" data-bind="text:body"></td>
        <td hidden style="text-align:center" data-bind="text:recipient"></td>
        <td style="text-align:center" data-bind="text:active, style:{color: activeColor}"></td>
    </script>

  
    <button style="display: block;" id="writeMessage" data-bind="click :$root.writeMessage" class="btn btn-success">Write Message</button>

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



<script type="text/javascript" charset="utf-8">
    var initialData = <?= $sentMessages ?>; //data for building initial table  
    var vm = function() {
        var self = this;
        self.records = ko.observableArray(initialData);

        $.each(self.records(), function(i, record) {  //build the checkboxes checked/unchecked

            record.messageId = record.messageId;
            record.headLine = record.headLine;
            record.body = record.body;
            record.recipient = record.recipient;


            if(record.isActive === "1"){
                record.active = "Active";
                record.activeColor = ko.observable("green");
            }else if(record.isActive === "0"){
                record.active = "Inactive";
                record.activeColor = ko.observable("red");
            }
        })


        self.writeMessage = function(item){
            window.location = "<?php echo base_url(); ?>push_notification/writeMessage";
        }

    }
    ko.applyBindings(new vm());
    
</script>

