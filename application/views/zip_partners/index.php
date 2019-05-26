<h2 class="title-underlined"><?= $pageTitle ?></h2>
<?= $output ?>

<script>
    var app = app || {};

    app.categories = <?= json_encode($categories) ?>;
    app.productInfo = <?= json_encode($zipInfo) ?>;

//    app.getChildOptions = function (categories, parent_id) {
//        var $html = '<option></option>';
//        $.each(categories, function (i, v) {
//            //console.log(parent_id, v.parent_id, "child")
//            if (parent_id !== undefined && v.parent_id !== null && v.name !== undefined) {
//                $html += '<option value="' + v.pc_id + '">' + v.name + '</option>';
//            }
//        });
//        return $html;
//    };

    app.getOptions = function (categories, type) {
        var $html = '<option></option>';
        $.each(categories, function (i, v) {
            //console.log(v,i)
            if (type !== undefined && type == v.type && app.parseInt(v.parent_id, 0) <= 0) {
                $html += '<option value="' + v.pc_id + '">' + v.name + '</option>';
            }
        });
        return $html;
    };

    app.crudState = '<?= $crudState ?>';
    $(document).ready(function (e) {
        if (app.crudState == 'add' || app.crudState == 'edit') {
            var $optionHtml = app.getOptions(app.categories, 'partner');
            //console.log($optionHtml);
            $("#field-pc_id").html($optionHtml);
            $("#field-pc_id").val(app.productInfo.pc_id);

//            $("#field-parentName").html($optionHtml);
//            $("#field-parentName").val(app.productInfo.parentName);

            setTimeout(function () {
//                var $childOption = app.getChildOptions(app.categories, app.productInfo.parentName);
//                console.log($childOption);
//                $("#field-pc_id").chosen('destroy');
//                $("#field-pc_id").html($childOption);
//                $("#field-pc_id").val(app.productInfo.pc_id);
//                $("#field-pc_id").chosen();

            }, 1000);

//            $("#field-parentName").on('change', function (e) {
//               // console.log("parent value", $(this).val());
//                var $childOption = app.getChildOptions(app.categories, $(this).val());
//               // console.log($childOption);
//                $("#field-pc_id").chosen('destroy');
//                $("#field-pc_id").html($childOption);
//                $("#field-pc_id").chosen();
//                $("#field-pc_id").val(app.productInfo.pc_id);
//            });
        }

        $('#field-offerType').change(function () {
            var val = $('#field-offerType option:selected').val();

            //console.log(val);

            if (val == "1") {
                $('#fromDate_field_box').show();
                $('#toDate_field_box').show();
            } else {
                $('#fromDate_field_box').hide();
                $('#toDate_field_box').hide();
            }
        });
    });
</script>
