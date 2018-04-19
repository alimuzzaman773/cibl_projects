<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h3 class="title-underlined ng-scope">
                PIN Create Checker
            </h3>
        </div>
    </div>
</div>

<div class="container" id="PinCreateCheckerModule" data-ng-controller="PinCreateCheckerController">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="table-responsive">        
                <table class="table table-bordered table-condensed table-striped table-hover" >          
                    <thead>
                        <tr class="bg-primary">
                            <th>SL#</th>
                            <th>Total Pin</th>
                            <th>Maker Action</th>
                            <th>Maker Action By</th>
                            <th>Maker Action Date Time</th>
                            <th>Maker Remark</th>
                            <th>Checker Action</th>
                            <th>Checker Action Date Time</th>
                            <th>Checker Remark</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="i in pin_checker_data track by $index">
                            <td class="text-center hidden"><input type="checkbox" id="selectAll" data-ng-model="i.isChecked" data-ng-true-value="true" data-ng-false-value="false" /></td>
                            <td>{{($index + 1)}}</td>
                            <td>{{i.totalPin}}</td>
                            <td>{{i.makerAction}}</td>
                            <td>{{i.fullName}}</td>
                            <td>{{i.makerActionDt}} {{i.makerActionTm}}</td>
                            <td>{{i.makerActionComment}}</td>
                            <td>{{i.checkerAction}}</td>
                            <td>{{i.checkerActionDt}} {{i.checkerActionTm}}</td>
                            <td>{{i.checkerActionComment}}</td>
                            <td>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?= base_url() ?>password_policy_checker/getPasswordPolicyApproval/{{i.validationGroupId}}">
                                                <i class="glyphicon glyphicon-pencil"></i> Approve
                                            </a>
                                        </li> 

                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr data-ng-show="pin_checker_data.length <= 0">
                            <td colspan="10">No data found</td>
                        </tr>
                    </tbody>
                </table>               
            </div>
        </div>
    </div>
</div>


<?php
ci_add_js(asset_url() . 'app/checker/pin_create_checker_module.js');
?>

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.pin_checker_data = <?= $unApprovedRequest ?>;
</script>










<!--
<title>Pin Request</title>
<div class="breadcrum">Pin Request Approve/Reject</div>

<div class="container" style="margin-top:50px">

    <table data-bind="dataTable: { dataSource : records, rowTemplate: 'rowTemplate',
           options: {
           bAutoWidth : false,
           aoColumnDefs: [
           { bSortable: false, aTargets: [] },
           { bSearchable: false, aTargets: [] }
           ],
           aaSorting: [],
           aLengthMenu: [[50, 100, 150, -1], [50, 100, 150, 'All']],
           iDisplayLength: 50,
           aoColumns: [
           {  mDataProp:'requestId' },
           {  mDataProp:'totalPin' },
           {  mDataProp:'makerAction' },
           {  mDataProp:'fullName' },
           {  mDataProp:'makerActionDtTm' },
           {  mDataProp:'makerActionComment' },
           {  mDataProp:'checkerAction' },
           {  mDataProp:'checkerActionDtTm' },
           {  mDataProp:'checkerActionComment' },
           {  mDataProp:'status' }
           ]}}" class="table table-striped table-bordered" id="referenceTable">

        <thead>
            <tr>
                <th hidden style="text-align: center" >ID</th>
                <th style="text-align: center" >Total Pin</th>
                <th style="text-align: center" >Maker Action</th>
                <th style="text-align: center" >Maker Action By</th>
                <th style="text-align: center" >Maker Action Date Time</th>
                <th style="text-align: center" >Maker Remark</th>
                <th style="text-align: center" >Checker Action</th>
                <th style="text-align: center" >Checker Action Date Time</th>
                <th style="text-align: center" >Checker Remark</th>
                <th style="text-align: center" >Status</th>
                <th style="width:30%; text-align: center" colspan="2" >Actions</th>

                
            </tr>
        </thead>

    </table>

    <script id="rowTemplate" type="text/html">
        <td hidden style="text-align:center" data-bind="text:requestId"></td>
        <td style="text-align:center" data-bind="text:totalPin"></td>
        <td style="text-align:center" data-bind="text:makerAction"></td>
        <td style="text-align:center" data-bind="text:fullName"></td>
        <td style="text-align:center" data-bind="text:makerActionDtTm"></td>
        <td style="text-align:center" data-bind="text:makerActionComment"></td>
        <td style="text-align:center" data-bind="text:checkerAction"></td>
        <td style="text-align:center" data-bind="text:checkerActionDtTm"></td>
        <td style="text-align:center" data-bind="text:checkerActionComment"></td>
        <td style="text-align:center" data-bind="text:status, style:{color: statusColor}"></td>
        <td style="text-align:center">
        <button data-bind="click: $root.approve, visible: approve" class="btn btn-primary">Approve</button>
        <button data-bind="click: $root.approved, visible: approved" class="btn btn-success">Approved</button>
        </td>
        <td style="text-align:center"><button data-bind="click: $root.reject, visible: reject" class="btn btn-warning">Reject</button></td>
        

    </script>


</div>



<style>
input {
    float:center;
    border: 1px solid #848484; 
    -webkit-border-radius: 30px; 
    -moz-border-radius: 30px; 
    border-radius: 30px; 
    outline:0; 
    height:25px; 
    width: 100px; 
    padding-left:10px; 
    padding-right:10px; 
}
</style>



<script type="text/javascript" charset="utf-8">

  var initialData = <?= $unApprovedRequest ?>;
  var vm = function() {
    
    var self = this;
    self.records = ko.observableArray(initialData);

    $.each(self.records(), function(i, record) {  //build the checkboxes checked/unchecked
      record.makerActionDtTm = record.makerActionDt + " " + record.makerActionTm;
      record.checkerActionDtTm = record.checkerActionDt + " " + record.checkerActionTm;
      record.approve = ko.observable(false);
      record.approved = ko.observable(false);
      record.reject = ko.observable(false);


      if(record.mcStatus === "1"){
        record.approved = ko.observable(true);
        record.status = "Approved";
        record.statusColor = "Green";
      }
      if(record.mcStatus === "0"){
        record.approve = ko.observable(true);
        record.reject = ko.observable(true);
        record.status = "Wait for approve";
        record.statusColor = "Red";
      }
      if(record.mcStatus === "2"){
        record.status = "Rejected";
        record.statusColor = "Red";
      }



    })



    self.approved = function(item){

      alert("Already Approved.");

    }




    self.approve = function(item){

      var dataToSave = {"requestId" : item.requestId,
                        "totalPin" : item.totalPin,
                        "makerActionDtTm" : item.makerActionDtTm, 
                        "checkerActionDtTm" : item.checkerActionDtTm};
      $.ajax({
        type: "POST",
        data: dataToSave,
        url: "<?= base_url() ?>pin_create_checker/pinCreateApprove",
        success: function(data) {

            if (data == 1) {
                alert("The pin generation request is approved");
                window.location = "<?= base_url() ?>pin_create_checker";
            }

            else if(data == 2) {
                alert("Authorization Module Not given");
                window.location = "<?= base_url() ?>pin_create_checker";
            }

            else if(data == 3) {
              alert("Data cheanged");
              window.location = "<?= base_url() ?>pin_create_checker";
            }
        },
        error: function(error) {
            alert(error.status + "--" + error.statusText);
        }
      });
    }


    self.reject = function(item){
      window.location = "<?= base_url() ?>pin_create_checker/getRejectReason?requestId=" + item.requestId + "&makerActionDtTm=" + item.makerActionDtTm + "&checkerActionDtTm=" + item.checkerActionDtTm;
    }





  }
  ko.applyBindings(new vm());
    
</script>




-->