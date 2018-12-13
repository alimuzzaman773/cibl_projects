<label>
    <input type="checkbox" id="report_download_option" name="report_download_option" onclick="toggleDownloadOption()"/> Download    
</label>
<input type="hidden" id="report_download_flag" name="report_download_flag" value=""/>
<br />
<select class="form-control input-sm" name="report_download_format" id="report_download_format" style="display: none;" onchange="return downloadFormate()">
    <option>Please select download format</option>
    <option value="excel">Excel</option>
    <option value="zip">Zip</option>
    <!--<option value="pdf" style="display: none">PDF</option>-->
</select>
<br />
<select class="form-control input-sm" name="__layout__" id="__layout__" style="display: none;">
    <option>Please select page layout</option>
    <option value="A4">Portrait</option>
    <option value="A4-L">Landscape</option>
</select>
<br />
<input style="display: none;"  type="button" class="btn btn-primary" id="report_download_btn" name="report_download_btn"  value="Download Report" />

<script>
    $('#report_download_btn').click(function(){        
        if ($("#report_download_format ")[0].selectedIndex <= 0) {
                alert("Not selected");
                return false;
            }
        else
            $(this).attr('type', 'submit');
    });
    
    function downloadFormate() {
        var file = $("#report_download_format").val();
        
        if(file == "pdf") {
            $("#__layout__").show();
        }else{
           $("#__layout__").hide(); 
        }        
    }   
</script>