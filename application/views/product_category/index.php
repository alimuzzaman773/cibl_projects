<h2 class="title-underlined">Category Setup</h2>
<?= $output ?>
<script>
    var app = app || {};
    app.crudState = '<?=$crudState?>';

    app.categories = <?= json_encode($categories) ?>;
    app.categoryInfo = <?= json_encode($categoryInfo)?>;
    
    app.getOptions = function(type, categories) {
        var $html = '<option></option>';
        $.each(categories, function(i, v){
            if (type !== undefined && type == v.type){
                $html += '<option value="' + v.pc_id + '">' + v.name + '</option>';
            }
        });
        return $html;
    }

    $(document).ready(function(e){
        if(app.crudState == 'add' || app.crudState == 'edit'){
            //var $optionHtml = app.getOptions($('#field-type').val(), app.categories);
            //$('#field-parent_id').html($optionHtml);
            if(app.crudState == 'add'){
                var $optionHtml = app.getOptions($(this).val(), app.categories);
                //$("#field-parent_id").chosen('destroy');
                $('#field-parent_id').html($optionHtml);
            }
            $("#field-type").on('change', function(e){
                var $optionHtml = app.getOptions($(this).val(), app.categories);
                //$("#field-parent_id").chosen('destroy');
                $('#field-parent_id').html($optionHtml);
               // $("#field-parent_id").chosen();
            });        
            
            if(app.crudState == 'edit'){
                //$("#field-parent_id").chosen('destroy');
                //$('#field-parent_id').val(app.categoryInfo.parent_id);
                //$("#field-parent_id").chosen();
            }
            
        }
        
        
    });
</script>