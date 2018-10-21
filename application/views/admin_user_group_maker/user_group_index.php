<h2 class="title-underlined"><?= $pageTitle ?></h2>
<?= $output ?>

<script>
    var crstate = "<?= $crudState ?>";
    $(document).ready(function (e) {
        $("#field-machine_name").attr("readonly", "readonly").css("background-color", "#ccc");

        //if (crstate == "add") {
            $("#machine_name_input_box").append("<a href='#' onclick='return removeReadonly();'>[ change ]</a>");
        //}
        $("#field-userGroupName").change(function () {
            //if (crstate == "add") {
                var value = $(this).val().toLowerCase().trim().split(" ").join("_")
                $("#field-machine_name").val(value);
            //}
        });


    });

    function removeReadonly() {
        $("#field-machine_name").removeAttr("readonly").css("background-color", "");
        ;
        return false;
    }
</script>