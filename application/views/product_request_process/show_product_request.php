<title>Product Request</title>
<div class="breadcrum">Product Requests</div>

<div class="container" style="margin-top:50px">

    <form method="get" id="productRequestForm" name="productRequestForm" action="<?php echo base_url(); ?>product_request_process/getRequest">

        <fieldset>
            
            <table width="500" border="0" cellpadding="5">

                <tr>
                    <th align="left" scope="row">Date From</th>
                    <td>
                        <input type="text" name="fromDate" id="fromDate" style="width: 205px" required ></input>
                    </td>
                </tr>

                <tr>
                    <th align="left" scope="row">Date To</th>
                    <td>
                        <input type="text" name="toDate" style="width: 205px" id="toDate" required ></input>
                    </td>
                </tr>
                          

                <tr>

                    <th align="left" scope="row">&nbsp;</th>

                    <td><input type="submit"  value="Generate List" class="btn btn-primary" /></td>

                </tr>

            </table>

        </fieldset>

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
           {  mDataProp:'applyId' },
           {  mDataProp:'productId' },
           {  mDataProp:'name' },
           {  mDataProp:'contactNo'},
           {  mDataProp:'preferredCallDtTm'},
           {  mDataProp:'email'},
           {  mDataProp:'myLocation'},
           {  mDataProp:'productName'},
           {  mDataProp:'creationDtTm'},
           ]}}" class="table table-striped table-bordered" id="referenceTable">
    <thead>
        <tr>
            <th hidden scope="col">Apply ID</th>
            <th style="text-align: center">Action</th>
            <th hidden scope="col">Product ID</th>
            <th scope="col">Name</th>
            <th scope="col">Contact No.</th>
            <th scope="col">Preferred Call Date</th>
            <th scope="col">Email</th>
            <th scope="col">Location</th>
            <th scope="col">ProductName</th>
            <th scope="col">Request Date</th>
        </tr>
    </thead>

    <tbody>
    </tbody>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden data-bind="text: applyId"></td>
        <td style="text-align: center">
        <button data-bind="click: $root.sendMail, visible: mail" class="btn btn-warning">Email</button>
        <button data-bind="click: $root.sendMail, visible: mailSent" class="btn btn-success">Mail Sent</button>
        </td>   
        <td hidden data-bind="text: productId"></td>
        <td><span data-bind="text: name"></span></td>
        <td><span data-bind="text: contactNo"></span></td>
        <td><span data-bind="text: preferredCallDtTm"></span></td>
        <td><span data-bind="text: email"></span></td>
        <td><span data-bind="text: myLocation"></span></td>
        <td><span data-bind="text: productName"></span></td>
        <td><span data-bind="text: creationDtTm"></span></td> 
    </script>

</div>



<script>

    $(function () {
        $( "#fromDate" ).datepicker({ 
          dateFormat: 'yy-mm-dd',
          constrainInput: true
        });

        $( "#toDate" ).datepicker({ 
          dateFormat: 'yy-mm-dd',
          constrainInput: true
        });
    });


    $('#fromDate').val("<?= $fromDate ?>");
    $('#toDate').val("<?= $toDate ?>");

</script>



<script type="text/javascript" charset="utf-8">


    var vm = function()
    {
      var self = this;
      self.records = ko.observableArray(<?= $productRequest ?>);
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
        window.location = "<?php echo base_url(); ?>product_request_process/processRequestById/" + item.applyId + "/" + $('#fromDate').val() + "/" + $('#toDate').val();
      }


    }
    ko.applyBindings(new vm());

    
</script>