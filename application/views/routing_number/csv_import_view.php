<h1 class="title-underlined">
    Routing Number Import
    <a href="<?php echo base_url(); ?>routing_number" class="btn btn-success pull-right"><span>Back to List</span></a>
</h1>
<form class="form-inline" method="post" action="<?php echo base_url() ?>csv_import/importcsv" enctype="multipart/form-data">
    <div class="form-group">
        <input class="form-control" type="file" name="userfile" id="userFile" />
        <button type="button" class="btn btn-primary" onclick="return app.uploadCsv();">
            Upload
        </button>
        <p><small>Only CSV file allowed</small></p>
    </div>
</form>

<script type="text/javascript">
    var app = app || {};
    app.uploadCsv = function(){
        var file_data = $('#userFile').prop('files')[0];
        var form_data = new FormData();
        form_data.append("userfile", file_data);        
        app.showModal();
        $.ajax({
            url: app.baseUrl + 'csv_import/importcsv',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (data) {    
                app.hideModal();
                if(data.success){
                    var $el = $('#userFile');
                    $el.wrap('<form>').closest('form').get(0).reset();
                    $el.unwrap();
                    
                    alert(data.msg);
                    window.location = app.baseUrl + "routing_number"
                }else {
                    alert(data.msg)
                }
            },
            error : function(data){
                app.hideModal();
                console.log(data);
                alert("There was a problem, please try again later");
            }
        });
        return false
    };
</script>