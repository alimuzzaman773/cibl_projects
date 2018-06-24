<div class="col-md-12" id='CallCenterModuleApp'>
    <div class="row mb">
        <div ng-view></div>
    </div>
</div>

<link rel = "stylesheet" media = "screen" href="<?=$base_url.ASSETS_FOLDER?>css/jqueryui/jquery-ui-1.9.2.custom.min.css" charset="utf-8" />
<link rel = "stylesheet" media = "screen" href="<?=$base_url.ASSETS_FOLDER?>angularjs/ng-tags-input/ng-tags-input.css" charset="utf-8" />
<script src="<?php echo asset_url() ?>js/jqueryui/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo asset_url() ?>css/jqueryui/jquery-ui-1.9.2.css"/>
<?php
ci_add_js(base_url().ASSETS_FOLDER."angularjs/angular-route.min.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/angular-sanitize.min.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/ui/autocomplete.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/ui/date.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/directives/dirPagination.js");


ci_add_js(base_url().ASSETS_FOLDER."app/call_center/call_center_module.js");

?>