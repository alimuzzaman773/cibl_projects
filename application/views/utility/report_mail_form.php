<div class="col-md-12">
    <div id="reportMailForm" style="display: none">
        <div id="content" style="margin:0;padding:0">
            <form id="mailform" action="<?= base_url() . 'utility/mail_report' ?>" method="post" target="_blank">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th colspan="2">Mail Report</th>
                    </tr>
                    <tr>
                        <td width="130px"><b>Recipients</b></td>
                        <td>
                            <div class="col-md-12">
                                <select multiple class="chosen-select form-control" id="recipients" name="recipients[]" data-placeholder="Select Recipients">                        
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Message</b> (optional)</td>
                        <td>
                            <div class="col-md-12">
                                <textarea id="message" name="message" class="form-control" rows="7"></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="outputContent" id="outputContent" value="" />
                            <button type="button" id="mailReport" name="mailReport" class="btn btn-primary" onclick="return sendMailReport(this.id);">
                                 Mail Report
                            </button>    
                            <button type="button" role="button" id="cancelMailReport" name="cancelMailReport" class="btn btn-primary" onclick="return $('#reportMailForm').slideUp();">
                                Cancel 
                            </button>
                        </td>
                    </tr>
                </table>    
            </form>
        </div>   
    </div>  
</div>
<script type="text/javascript" src="<?= base_url() . ASSETS_FOLDER . 'chosen' ?>/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="<?= base_url() . ASSETS_FOLDER . 'chosen' ?>/chosen.css" type="text/css" media="screen" />
<script>
    var sendMailReport = function(id) {
        var recipients = $("#recipients").val();
        if(recipients == null){
            alert("Please select a recipient.")
                    return false;
        }
        
        if(!confirm('Are you sure you want to mail the report?')){
            return false;
        }
        
        
        var btnhtml = $("#"+id).attr('disabled','disabled').html();//.;
        $("#"+id).html("Mailing...");
        
        fdata = {};
        fdata.recipients = recipients;
        fdata.outputContent = ""+$("#<?=$reportDivId?>").html();
        fdata.message = $("#message").val();
        $.ajax({
            type:'post',
            cache:false,
            url: '<?=base_url().'utility/mail_report'?>',
            data: fdata,
            dataType:'json',
            success:function(data){
                $("#"+id).html(btnhtml).removeAttr('disabled');
                alert(""+data.msg);
                if(data.success == true){
                    $("#reportMailForm").slideUp();
                }
            },
            error:function(data){
                alert( "There was a problem. Please try again." );
                $("#"+id).html(btnhtml).removeAttr('disabled');
            }
        });
        return false;
    }
</script>
