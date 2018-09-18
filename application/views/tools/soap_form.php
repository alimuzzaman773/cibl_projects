<div class="container">
    <div class="col-md-8 col-sm-8 col-md-offset-2 col-sm-offset-2 form">
        <div class="row">
            <div class="form-group col-md-12 col-sm-12">
                <label>Soap Object</label>
                <textarea class="form-control input-sm" ng-model="soap.soap_obj" rows="10"></textarea>
            </div>
            <div class="col-md-12 col-sm-12">
                <a href="" class="btn btn-sm btn-success" data-ng-click="soapSubmit()">Submit</a>
            </div>
            <div class="col-md-12 col-sm-12">
                <div class="form_result">{{soap.result}}</div>
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