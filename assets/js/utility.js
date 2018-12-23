var populateUserList = function(dom,domIdToPopulate)
{
    var crntDom = $(dom);
    var branchId = $("#"+crntDom.attr('id')+" option:selected").val();

    var html = '<option value="">Select a User</otpion>';
    if(branchId == "" || branchId == null || branchId <= 0)
    {
        $("#"+domIdToPopulate).html("");
        return true;
    }

    $.each(userList,function(index,c){
        if(c.branchId == branchId)
        {
            html += '<option value="'+c.userId+'">'+c.firstName.toString()+' ('+c.lastName.toString()+')</option>';
        }            
    });

    $("#"+domIdToPopulate).html(html);
}

var populateServiceList = function(dom,domIdToPopulate)
{
    var crntDom = $(dom);
    var branchId = $("#"+crntDom.attr('id')+" option:selected").val();

    var html = '<option value="">Select a Service</otpion>';
    if(branchId == "" || branchId == null || branchId <= 0)
    {
        $("#"+domIdToPopulate).html("");
        return true;
    }

    $.each(categoryList,function(index,c){
        if(c.branchId == branchId)
        {
            html += '<option value="'+c.categoryId+'">'+c.categoryName+' ('+c.categoryCode+')</option>';
        }            
    });

    $("#"+domIdToPopulate).html(html);
}

var populateCounterList = function(dom,domIdToPopulate)
{
    var crntDom = $(dom);
    var branchId = $("#"+crntDom.attr('id')+" option:selected").val();

    var html = '<option value="">Select a Counter</otpion>';
    if(branchId == "" || branchId == null || branchId <= 0)
    {
        $("#"+domIdToPopulate).html("");
        return true;
    }

    $.each(counterList,function(index,c){
        if(c.branchId == branchId)
        {
            html += '<option value="'+c.counterId+'">'+c.counterCode+'</option>';
        }            
    });

    $("#"+domIdToPopulate).html(html);
}

var populateBranchList = function(domId)
{
    var html = "<option value=''>Select a Branch</option>";
    $.each(branchList,function(index,b){
        html += '<option value="'+b.branchId+'">'+b.branchName+'</option>';
    });

    $("#"+domId).html(html);
}

var ini_autocomplete = function(autocompleteId, id_to_set, url){
    
};

var toggleDownloadOption = function()
{
    var value = $('#report_download_option:checked').val();

    $('#report_download_format').hide();
    $('#report_download_btn').hide();
    $('#__layout__').hide();
    $('#genReport').show();
    $('#report_download_flag').val('');

    if(value == "on")
    {
        $('#report_download_flag').val(1);
        $('#report_download_format').show();        
        $('#genReport').hide();
        $('#report_download_btn').show();
    }
};

var app = app || {};
    
app.renderFuelPumpDD = function($this, $fueldomId){
    var $region_id = app.parseInt($($this).val());
    $("#"+$fueldomId+" option").each(function(e){
        $(this).hide();
        if($region_id > 0){
            var $optionRid = app.parseInt($(this).attr("data-region-id"));
            if($optionRid == $region_id){
                $(this).show();
            }
        }
        else {
            $(this).show();
        }
    });
};

