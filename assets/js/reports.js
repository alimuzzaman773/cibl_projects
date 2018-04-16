$(document).ready(function () {

    //Report function call by view table

    data_table("user_status_table", 9);
    data_table("id_modification_table", 11);
    data_table("fund_transfer_table", 11);
    data_table("other_fund_transfer_table", 13);
    data_table("customer_info_table", 9); 
    data_table("user_login_table", 11);
    data_table("product_request_table", 9);
    data_table("priority_request_table", 12);
    data_table("banking_request_table", 12);



    //Data table Global Function for every report
    function data_table(table, columnNo) {
        var column_no = [];
        for (var i = 0; i < columnNo; i++) {
            column_no.push(i)
        }
        $('#' + table).DataTable({
            stateSave: true, 
            autoWidth: true,
            aLengthMenu:[[25,50,100,200,-1],[25,50,100,200,"All"]],
            isDisplayLength:-1,
            dom: '<"toolbar">l<"toolbar2">Bftr<"bottom"p>i',
            buttons: [{
                extend: 'print',
		orientation:'landscape',
                className: 'btn btn-primary btn-sm',
                exportOptions: {modifier: {page: "current"}, columns: column_no}
            }, {
                extend: 'pdf',
                orientation:'landscape',
                className: 'btn btn-primary btn-sm',
                exportOptions: {modifier: {page: "current"}, columns: column_no}
            }, {
                extend: 'excel',
                className: 'btn btn-primary btn-sm',
                exportOptions: {modifier: {page: "current"}, columns: column_no}
            }]
        });
    }

    //Date picker function
    $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
    $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});

});


