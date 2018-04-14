<?php
if(isset($permissions['canViewHomeMenu'])){
    include("menu_templates/home.php");
}
if(isset($permissions['canViewSetupMenu'])){
    include("menu_templates/setup.php");
}
if(isset($permissions['canViewModulesMenu'])){
    include("menu_templates/modules.php");
}
if(isset($permissions['canViewReportMenu'])){
    include("menu_templates/report.php");
}
if(isset($permissions['canViewCounterUserMenu'])){
    include("menu_templates/counter_user.php");
}
if(isset($permissions['canViewKioskMenu'])){
    include("menu_templates/kiosk_menu.php");
}
?>

    