<?php
//echo Modules::run("mod_profile/index");
Modules::load("mod_profile");
$profile = new Mod_profile();

echo $profile->index();
?>
