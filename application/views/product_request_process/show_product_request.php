<h2 class="title-underlined">Product Requests</h2>

<form method="get" id="productRequestForm" name="productRequestForm" action="<?php echo base_url(); ?>product_request_process/getRequest">
    <div class="container">
        <div class="row">       
            <div class="col-xs-4 col-sm-3">
                <div class="form-group">
                    <label>From Dates</label> 
                    <input type="text" name="fromDate" id="fromDate" class="form-control input-sm" placeholder="Enter From Date"/>
                </div>
            </div>
            <div class="col-xs-4 col-sm-3">
                <div class="form-group">
                    <label>To Date</label> 
                    <input type="text" name="toDate" id="toDate" required class="form-control input-sm" placeholder="Enter To Date"/>
                </div>
            </div>
            <div class="col-xs-4 col-sm-2">
                <div class="form-group">
                    <label style="display:block" class="hidden-xs">&nbsp;&nbsp;</label>
                    <input type="submit"  value="Generate List" class="btn btn-primary btn-sm" />
                </div>
            </div>       
        </div>
    </div>
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
            <th scope="col">Product Name</th>
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

        <script src="<?php echo base_url(); ?>assets/js/jqueryui/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jqueryui/jquery-ui-1.9.2.css"/>
<script>

    $(function () {
    $("#fromDate").datepicker({
    dateFormat: 'yy-mm-dd',
            constrainInput: true
    });
    $("#toDate").datepicker({
    dateFormat: 'yy-mm-dd',
            constrainInput: true
    });
    });
    $('#fromDate').val("<?= $fromDate ?>");
    $('#toDate').val("<?= $toDate ?>");</script>



<script type="text/javascript" charset="utf-8">
    var vm = function()
    {
    var self = this;
    self.records = ko.observableArray(<?= $productRequest ?>);
    $.each(self.records(), function(i, record) {

    record.mailSent = ko.observable(false);
    record.mail = ko.observable(true);
    if (record.status === "1"){
    record.mailSent = ko.observable(true);
    record.mail = ko.observable(false);
    }
    else if (record.status === "0"){
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