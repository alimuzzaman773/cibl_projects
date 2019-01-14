<div id='AppUsersModuleApp'>
    <div ng-view></div>
</div>

<link rel="stylesheet" media="screen" href="<?= $base_url . ASSETS_FOLDER ?>css/jqueryui/jquery-ui-1.9.2.custom.min.css" charset="utf-8" />
<script>
    var app = app || {};
</script>

<?php
ci_add_js(base_url() . ASSETS_FOLDER . "js/jqueryui/jquery-ui-1.9.2.custom.min.js");
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/angular-route.min.js");
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/angular-sanitize.min.js");
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/ui/autocomplete.js");
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/ui/date.js");
ci_add_js(base_url() . ASSETS_FOLDER . "angularjs/directives/dirPagination.js");

ci_add_js(base_url() . ASSETS_FOLDER . "app/client_registration/app_users.js");
?>