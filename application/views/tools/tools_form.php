<div class="container">
    <div class="col-md-8 col-sm-8 col-md-offset-2 col-sm-offset-2 form">
        <div class="row">
            <div class="form-group col-md-12 col-sm-12">
                <label>Request URL</label>
                <input type="text" class="form-control input-sm" ng-model="tools.url"/>
            </div>
            <div class="col-md-12 col-sm-12 form-group">
                <a href="" class="btn btn-sm btn-info" data-ng-click="addRow()"><i class="fa fa-plus"></i> Add Row</a>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="table table-responsive">
                    <table class="table table-sm custom_table">
                        <thead>
                            <tr class="bg-primary">
                                <th class="fit">Parameter Name</th>
                                <th class="fit">Parameter Value</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-ng-repeat="r in form_row">
                                <td> <input type="text" class="form-control input-sm" ng-model="r.key"/></td>
                                <td><input type="text" class="form-control input-sm" ng-model="r.value"/></td>
                                <td class="text-right"><a href="" class="btn btn-sm btn-danger" data-ng-click="removeRow($index)" data-ng-disabled="form_row.length <= 1"><i class="fa fa-trash"></i> Remove</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <a href="" class="btn btn-sm btn-success" data-ng-click="toolsSubmit()">Submit</a>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="form_result" data-ng-bind-html="tools.result"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .form{
        margin-top: 20px;
    }
    
    .custom_table{
        border: 1px solid #ddd;
    }
    
    .custom_table .fit{
        width: 50%;
    }
    
    .custom_table thead{
        border-bottom: none !important;
    }

    .form_result{
        border: 1px solid #DDDDDD;
        width: 100%;
        height: 250px;
        margin-top: 15px;
        border-radius: 5px;
        overflow: hidden;
        padding: 5px;
        word-wrap: break-word;  
        background-color: #f4f4f4;
        overflow-y: scroll;
    }
</style>