<h2 class="title-underlined">Discount Partners</h2>
<?= $output ?>

<script>
    var app = app || {};

    app.categories = <?= json_encode($categories) ?>;
    app.productInfo = <?= json_encode($discountInfo) ?>;

    app.getChildOptions = function (categories, parent_id) {
        var $html = '<option></option>';
        $.each(categories, function (i, v) {
            console.log(parent_id, v.parent_id, "child")
            if (parent_id !== undefined && parent_id == v.parent_id) {
                $html += '<option value="' + v.pc_id + '">' + v.name + '</option>';
            }
        });
        return $html;
    };

    app.getOptions = function (categories, parent_id) {
        var $html = '<option></option>';
        $.each(categories, function (i, v) {
            //console.log(v,i)
            $html += '<option value="' + v.pc_id + '">' + v.name + '</option>';
        });
        return $html;
    };

    app.crudState = '<?=$crudState?>';
    $(document).ready(function (e) {
        if(app.crudState == 'add' || app.crudState == 'edit'){
            var $optionHtml = app.getOptions(app.categories);
            //console.log($optionHtml);
            $("#field-parentName").html($optionHtml);
            $("#field-parentName").val(app.productInfo.parentName);

            setTimeout(function () {
                var $childOption = app.getChildOptions(app.categories, app.productInfo.parentName);
                console.log($childOption);
                $("#field-childName").chosen('destroy');
                $("#field-childName").html($childOption);
                $("#field-childName").val(app.productInfo.childName);
                $("#field-childName").chosen();

            }, 1000);

            $("#field-parentName").on('change', function (e) {
                console.log("parent value", $(this).val());
                var $childOption = app.getChildOptions(app.categories, $(this).val());
                console.log($childOption);
                $("#field-childName").chosen('destroy');
                $("#field-childName").html($childOption);
                $("#field-childName").chosen();
                $("#field-childName").val(app.productInfo.childName);
            });            
        }
    });
</script>
