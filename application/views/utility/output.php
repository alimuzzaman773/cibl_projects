<div id="report-options" class="col-md-12" style="clear:all;width:100%;display: none">        
    <button class="btn btn-primary pull-right btn-xs" onclick="return generateReportOutput('pdf');" type="button">
        <span class="glyphicon glyphicon-book"></span> Get Pdf
    </button>

    <button class="btn btn-primary pull-right btn-xs" onclick="return generateReportOutput('excel');" type="button">
        <span class="glyphicon glyphicon-list-alt"></span> Get Excel
    </button>

    <button class="btn btn-primary pull-right btn-xs" onclick="return generateReportOutput('print');" type="button">
        <span class="glyphicon glyphicon-print"></span> Print
    </button>

    <button class="btn btn-primary pull-right btn-xs" onclick="return mailReport(this.id);" id="mailReportBtn" type="button">
        <span class="glyphicon glyphicon-envelope"></span> Mail
    </button>

    <!-- Modal -->
    <div id="paperSize" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Page Size</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sel1">Select Any One</label>
                        <select class="form-control" id="paperSizeType">
                            <option value="A4">A4 Portrait</option>
                            <option value="A4-L">A4 Landscape</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="return app.reportPageSize()">Get Report</button>
                </div>
            </div>
        </div>
    </div>
</div>

<form style="display: none" id="pdfform" action="<?=base_url().'utility/output?_t='.time()?>" method="post" target="_blank">    
    <input type="hidden" name="outputType" id="outputType" value="pdf" />
    <input type="hidden" name="outputContent" id="outputContent" value="" />  
    <input type="hidden" name="outputPaperSizeType" id="outputPaperSizeType" value=""/>  
</form>
<br clear="all" />
<script type="text/javascript">
    var app = app || {};
    app.reportDivId = '<?=$reportDivId?>';
    
    app.reportPageSize = function() {
        var content = $("#"+app.reportDivId).html();
        $("#outputContent").val(content); 
        $("#outputPaperSizeType").val($("#paperSizeType").val());

        $("#pdfform").submit();
    };

    window.generateReportOutput = function(type)
    {        
        if(type == "print"){
            type == "print";
        }
        else{
            type == "pdf";
        }        
        $("#pdfform #outputType").attr('value',type);
        /* show dialogue **/
        if(type == "pdf") {
            $('#paperSize').modal('show');  
           
            return false;
        }
 
        var content = $("#"+app.reportDivId).html();
        $("#outputContent").val(content); 
        
        $("#pdfform").submit();
    }
    
    window.mailReport = function(id){
        var btnhtml = $("#"+id).attr('disabled','disabled').html();//.;
        $("#"+id).html("Loading...");
        
        $.getJSON( "<?=base_url().'ajax/get_mail_recipients'?>", function() {
        })
        .done(function(data) {
            //console.log(data);
            var uhtml = "";
            $.each(data.userList, function(key,val){
                uhtml += '<option value="'+val.email+'">'+val.first_name+' '+val.last_name+' ('+val.user_name+')'+'</option>'
            });
            $("#reportMailForm #recipients").html(uhtml).chosen({
                width:"50%",
                disable_search_threshold : 10
                
            });
            
            $("#reportMailForm").slideDown();
            $("#"+id).html(btnhtml).removeAttr('disabled');
        })
        .fail(function(data) {
           alert( "There was a problem. Please try again." );
           $("#"+id).html(btnhtml).removeAttr('disabled');
        });
        
        
        
        return false;
    }    
</script>
<script type="text/javascript" src="<?=base_url().ASSETS_FOLDER.'fancybox' ?>/jquery.fancybox.pack.js"></script>
<link rel="stylesheet" href="<?=base_url().ASSETS_FOLDER.'fancybox' ?>/jquery.fancybox.css" type="text/css" media="screen" />
<style>
    #sreportMailForm{margin: 0 auto; margin:200px 100px 200px 100px; left:10%;right:10%;bottom:10%;}
    #sreportMailForm #content{overflow: visible;}
    .fancybox-inner{overflow: visible !important}
    ul.chosen-choices{margin-bottom: 0px !important}
</style>
<script type="text/javascript" src="<?=base_url().ASSETS_FOLDER.'modal' ?>/modal.js"></script>
<link rel="stylesheet" href="<?=base_url().ASSETS_FOLDER.'modal' ?>/modal.css" type="text/css" media="screen" />
<?php
$this->load->view("utility/report_mail_form.php");
?>
<style>
    .ui-datepicker .ui-datepicker-title select{
        color: black !important; /*added this fix*/
    }
    
</style>