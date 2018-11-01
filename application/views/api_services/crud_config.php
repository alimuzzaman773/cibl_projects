<div class="col-sm-12 col-xs-12" ng-controller="ConfigDataController" id="config_data_module">
    
    <button data-ng-click="addItem()" role="button" type="button" class="btn btn-sm pull-right">
        <i class="glyphicon glyphicon-plus-sign"></i> Add
    </button>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-primary">
                        <th></th>
                        <th>Type</th>
                        <th style="width: 100px">Key</th>
                        <th>Value</th> 
                    </tr>
                </thead>
                <tbody>
                    <tr data-ng-repeat="i in config_data track by $index">
                        <td>
                            <button role="button" type="button" class="btn btn-xs btn-danger" data-ng-click="removeItem($index)">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                        </td>
                        <td>
                            <select name="config_data_type[]" class="form-control" data-ng-model="i.type">
                                <option value="">Select a type</option>
                                <option value="header">Header</option>
                                <option value="body">Body</option>
                            </select>
                        </td>
                        <td style="width: 100px">
                            <input type="text" name="config_data_key[]" placeholder="Provide a key in small caps only" class="form-control" data-ng-model="i.key" />
                        </td>
                        <td>
                            <input type="text" name="config_data_val[]" placeholder="Provide a value" class="form-control" data-ng-model="i.val" />
                        </td>
                    </tr>
                    <tr data-ng-show="config_data.length <= 0">
                        <td colspan="3">No items found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    #config_data_input_box{
        width: 900px;
        max-width: 900px;
        overflow-x: scroll;
    }
</style>
<script>
    var app = app || {};
    app.api_config_data = <?=json_encode($config_data)?>;
</script>
<?php ci_add_js(base_url().'assets/app/api_sevices.js'); ?>
