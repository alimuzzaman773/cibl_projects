<h1 class="title-underlined">Notifications</h1>
<?php echo $output; ?>
<?php

//ci_add_css(base_url()."assets/jqueryui/jquery-ui.min.css");
ci_add_css(base_url()."assets/timepicker/jquery.timepicker.min.css");

//ci_add_js(base_url()."assets/jqueryui/jquery-ui.min.js");
ci_add_js(base_url()."assets/timepicker/jquery.timepicker.min.js");


?>

<script>
    $(document).ready(function(e){
        $("#field-executionTimeField").timepicker({
            timeFormat: 'HH:mm:ss',
            //dateFormat: 'yy-mm-dd'
            dropdown: true,
            scrollbar: true,
            interval: 10,
            dynamic:true
        });
        
        $("#field-executionTime").datepicker({            
            dateFormat: 'yy-mm-dd'
        });
    });
</script>