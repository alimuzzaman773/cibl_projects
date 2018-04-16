<h2 class="title-underlined">Priority Requests</h2>

<div class="container" style="margin-top:50px">
    <form method="post" id="priorityRequestForm" name="priorityRequestForm" action="<?php echo base_url(); ?>priority_request_process/getRequests">
    <table border="0" cellpadding="5">         
        <tr>
            <th align="left" scope="">Select Service Type</th>
            <td>
                <select id="request" name="request"  onchange="generateList();">
                    <option value="0">All</option>
                    <?php foreach ($service_list as $item) { ?>
                      <option value="<?= $item->serviceTypeCode ?>"><?= $item->serviceName ?></option>
                    <?php } ?>
                   
                </select>
            </td>
        </tr>   
    </table>
    </form>


    <br/>
    <br/>

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
           {  mDataProp:'serviceRequestID' },
           {  mDataProp:'typeCode' },
           {  mDataProp:'serviceName'},
           {  mDataProp:'eblSkyId'},
           {  mDataProp:'referenceNo'},
      	   {  mDataProp:'name'},
      	   {  mDataProp:'contactNo'},
      	   {  mDataProp:'email'},
           {  mDataProp:'requestDtTm'}
           ]}}" class="table table-striped table-bordered" id="referenceTable">
    <thead>
        <tr>
            <th hidden scope="col">ID</th>
            <th style="text-align: center">Action</th>               
            <th hidden scope="col">Type Code</th>
            <th scope="col">Service Name</th>
            <th scope="col">Apps User ID</th>
            <th scope="col">Reference No.</th>
            <th scope="col">Name</th>
            <th scope="col">Contact No.</th>
    	    <th scope="col">Email</th>
            <th scope="col">Request Date</th>
        </tr>
    </thead>

    <tbody>
    </tbody>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden data-bind="text: serviceRequestID"></td>
        <td style="text-align: center">
        <button data-bind="click: $root.sendMail, visible: mail" class="btn btn-warning">Email</button>
        <button data-bind="click: $root.sendMail, visible: mailSent" class="btn btn-success">Mail Sent</button>
        </td> 
        <td hidden data-bind="text: typeCode"></td>
        <td><span data-bind="text: serviceName"></span></td>
        <td><span data-bind="text: eblSkyId"></span></td>
        <td><span data-bind="text: referenceNo"></span></td>
        <td><span data-bind="text: name"></span></td>
    	<td><span data-bind="text: contactNo"></span></td>
    	<td><span data-bind="text: email"></span></td>
        <td><span data-bind="text: requestDtTm"></span></td>   
    </script>

</div>


<script type="text/javascript" charset="utf-8">

    $('#request').val("<?= $TypeCode ?>");
    function generateList()
    {
      $("#priorityRequestForm").submit();
    };

    var vm = function()
    {
      var self = this;
      self.records = ko.observableArray(<?= $requestList ?>);

      $.each(self.records(), function(i, record) {  

            record.mailSent = ko.observable(false);
            record.mail = ko.observable(true);     

            if(record.status === "1"){
              record.mailSent = ko.observable(true);
              record.mail = ko.observable(false);
            }
            else if(record.status === "0"){
              record.mailSent = ko.observable(false);
              record.mail = ko.observable(true);
            }
        })

      self.sendMail = function(item){
        window.location = "<?php echo base_url(); ?>priority_request_process/processRequestById/" + item.serviceRequestID;
      }
    }
    ko.applyBindings(new vm());
    
</script>
