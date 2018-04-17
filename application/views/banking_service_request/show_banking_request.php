<h2 class="title-underlined">Banking Requests</h2>

<div class="container" style="margin-top:50px">
    <form method="post" id="bankingRequestForm" name="bankingRequestForm" action="<?php echo base_url(); ?>banking_service_request/getRequests">
    <table border="0" cellpadding="5">         
        <tr>
            <th align="left" scope="">Select Service Type</th>
            <td>
                <select id="Prequest" name="Prequest"  onchange="generateDropDown();" class="form-control">
                    <option value="0">Select Service Type</option>
                    <?php foreach ($parentServiceList as $item) { ?>
                      <option value="<?= $item->serviceTypeCode ?>"><?= $item->serviceName ?></option>
                    <?php } ?>
                   
                </select>
            </td>
            <td>
                <select id="Crequest" name="Crequest"  onchange="generateList();" class="form-control">
                    <option value="0">Select Service Type</option>
                    <?php foreach ($childServiceList as $item) { ?>
                      <option value="<?= $item->serviceTypeCode ?>"><?= $item->serviceName ?></option>
                    <?php } ?>
                   
                </select>
            </td>
        </tr>   
    </table>
    </form>

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
           {  mDataProp:'serviceId' },
           {  mDataProp:'skyId' },
           {  mDataProp:'typeCode' },
           {  mDataProp:'serviceName'},
           {  mDataProp:'eblSkyId'},
           {  mDataProp:'referenceNo'},
           {  mDataProp:'userName'},
           {  mDataProp:'mobileNo'},
           {  mDataProp:'reason'},
           {  mDataProp:'requestDtTm'}
           ]}}" class="table table-striped table-bordered" id="referenceTable">
    <thead>
        <tr>
            <th hidden scope="col">ID</th>
            <th style="text-align: center">Action</th>
            <th hidden scope="col">Sky ID</th>                  
            <th hidden scope="col">Type Code</th>
            <th scope="col">Service Name</th>
            <th scope="col">Apps User ID</th>
            <th scope="col">Reference No.</th>
            <th scope="col">User Name</th>
            <th scope="col">Contact No.</th>
            <th scope="col">Reason</th>
            <th scope="col">Request Date</th>
        </tr>
    </thead>

    <tbody>
    </tbody>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden data-bind="text: serviceId"></td>
	<td style="text-align: center">
        <button data-bind="click: $root.sendMail, visible: mail" class="btn btn-warning">Email</button>
        <button data-bind="click: $root.sendMail, visible: mailSent" class="btn btn-success">Mail Sent</button>
        </td>
        <td hidden data-bind="text: skyId"></td>
        <td hidden data-bind="text: typeCode"></td>
        <td><span data-bind="text: serviceName"></span></td>
        <td><span data-bind="text: eblSkyId"></span></td>
        <td><span data-bind="text: referenceNo"></span></td>
        <td><span data-bind="text: userName"></span></td>
        <td><span data-bind="text: mobileNo"></span></td>
        <td><span data-bind="text: reason"></span></td>
        <td><span data-bind="text: requestDtTm"></span></td>    
    </script>

</div>



<script type="text/javascript" charset="utf-8">

    $('#Prequest').val("<?= $cServiceTypeCode ?>");
    $('#Crequest').val("<?= $pServiceTypeCode ?>");
    
    function generateDropDown()
    {
      $("#bankingRequestForm").submit();
    };

    function generateList()
    {
      $("#bankingRequestForm").submit();
    };



    var vm = function()
    {
      var self = this;
      self.records = ko.observableArray(<?= $BankingRequestList ?>);
      $.each(self.records(), function(i, record) { 

            record.mailSent = ko.observable(false);
            record.mail = ko.observable(true);      

            if(record.status1 === "1"){
              record.mailSent = ko.observable(true);
              record.mail = ko.observable(false);
            }
            else if(record.status1 === "0"){
              record.mailSent = ko.observable(false);
              record.mail = ko.observable(true);
            }
        })


      self.sendMail = function(item){
        window.location = "<?php echo base_url(); ?>banking_service_request/processRequestById/" + item.serviceId;
      }


    }
    ko.applyBindings(new vm());
    
</script>