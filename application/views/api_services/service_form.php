<h2 class="title-underlined"><?=$serviceInfo->name?></h2>
<div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-3 col-md-6" id="request_form">
    <form class="form" method="<?=$serviceInfo->method?>" action="<?=base_url().'api_service/execute_service/'.$serviceInfo->api_service_id?>" action-data="<?=$serviceInfo->api_url?>"
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
        <div class="error_msg text-danger"></div>
    </form>    
</div>

<div id="utility_info"></div>

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
        //console.log($formData);
      //  alert($formData);
        
        $.ajax({
            type : 'post',
            url : app.baseUrl + 'api_services/execute_service/' + app.serviceInfo.api_service_id,
            data : $formData,
            dataType : 'json',
            success : function(data){
                if(data.success==false){
                   $(".error_msg").html(data.msg); 
                }
               // $("#request_form").hide();
               // $("#utility_info").show();
               // $("#utility_info").html(data);
            },
            error : function(data){
               alert("Some eeror occured to request bill payment");
            }
        });
        
        return false;
        
    };
    
</script>