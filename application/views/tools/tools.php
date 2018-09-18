<div class="col-md-12" id='ToolsModuleApp'>
    <div class="row mb">
        <div ng-view></div>
    </div>
</div>


<link rel = "stylesheet" media = "screen" href="<?=$base_url.ASSETS_FOLDER?>css/jqueryui/jquery-ui-1.9.2.custom.min.css" charset="utf-8" />
<link rel = "stylesheet" media = "screen" href="<?=$base_url.ASSETS_FOLDER?>angularjs/ng-tags-input/ng-tags-input.css" charset="utf-8" />

<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
  
<?php

ci_add_js(base_url().ASSETS_FOLDER."js/jqueryui/jquery-ui-1.9.2.custom.min.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/angular-route.min.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/angular-sanitize.min.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/ui/autocomplete.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/ui/date.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/directives/dirPagination.js");
ci_add_js(base_url().ASSETS_FOLDER."angularjs/ng-tags-input/ng-tags-input.min.js");


ci_add_js(base_url().ASSETS_FOLDER."app/tools/tools_module.js");
?>
