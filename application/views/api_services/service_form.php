<h2 class="title-underlined"><?=$serviceInfo->name?></h2>
<form class="form" method="<?=$serviceInfo->method?>" action="<?=$serviceInfo->api_url?>"
      id="serviceForm_<?=$serviceInfo->machine_name?>">
    <?php foreach($serviceFields as $f): ?>
        <?php echo $this->load->view('api_services/service_form_'.$f->field_type, array('field' => $f));?>
    <?php endforeach; ?>
    
    <div class="form-group">
        <input type="hidden" value="<?=$serviceInfo->api_service_id?>" name="api_service_id" />
        <button class="btn btn-primary btn-block" onclick="return app.submitServices()">
            <i class="glyphicon glyphicon-save"></i> Submit                
        </button>
    </div>
    
</form>

<script>
    var app = app || {};
    app.formid = '<?=$serviceInfo->machine_name?>';
    app.serviceInfo = <?= json_encode($serviceInfo)?>;
    app.submitServices = function(){
        if(!confirm('Are you sure?')) 
        {
            return false;
        }
        
        var $formData = $("#serviceForm_" + app.formid).serialize();
        //app.showModal();
        
        alert($formData);
        
        $.ajax({
            url : app.baseUrl + 'api_services/execute_service/' + app.serviceInfo.api_service_id,
            data : $formData,
            dataType : 'json',
            success : function(data){},
            error : function(data){}
        });
        
        return false;
        
    };
    
</script>