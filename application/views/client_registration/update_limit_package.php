<h1 class="title-underlined">
    Apps User Limit Package Update
    
</h1>
<div class="table-responsive" id="AppUserModule" data-ng-controller="LimitPackageController as LimitPackageController">
    <table class="table table-bordered table-condensed table-striped">
        <thead>
            <tr class="bg-primary">
                <th colspan="4">Apps user Information</th>
            </tr>
        </thead>
        <tr>
            <td>SKY ID</td>
            <td>{{skyInfo.skyId}}</td>
            <td>ESB ID</td>
            <td>{{skyInfo.eblSkyId}}</td>
        </tr>
        <tr>
            <td>Name</td>
            <td colspan="3">{{skyInfo.userName}}</td>            
        </tr>
        <tr>
            <td>Existing Limit Package</td>
            <td>{{getLimitPackage(skyInfo.appsGroupId)}}</td>
            <td>Requested Limit Package</td>
            <td>
                <div data-ng-if="requestedGroupInfo.appsGroupId > 0">
                    {{requestedGroupInfo.userGroupName}} <small>{{requestedGroupInfo.groupDescription}}</small>                    
                </div>
            </td>
        </tr>
        <thead>
            <tr class="bg-primary">
                <th colspan="4">Choose New Limit Package</th>
            </tr>
        </thead>
        <tr>
            <td colspan="4">
                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control" data-ng-model="appsGroupId" data-ng-change="setAppsGroupId()">
                        <option value="">Select A Limit Package</option>
                        <option data-ng-repeat="u in userGroups" value="{{u.appsGroupId}}">{{u.userGroupName}} - {{u.groupDescription}}</option>
                    </select>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12" data-ng-show="appsGroupId > 0">
                    <!--<b>oatMinTxnLim:</b> {{selectedGroup.oatMinTxnLim}}, <b>oatMaxTxnLim:</b> {{selectedGroup.oatMaxTxnLim}}, <b>oatDayTxnLim:</b> {{selectedGroup.oatDayTxnLim}}, <b>oatNoOfTxn:</b> {{selectedGroup.oatNoOfTxn}} <br />
                    <b>eatMinTxnLim:</b> {{selectedGroup.eatMinTxnLim}}, <b>eatMaxTxnLim:</b> {{selectedGroup.eatMaxTxnLim}}, <b>eatDayTxnLim:</b> {{selectedGroup.eatDayTxnLim}}, <b>eatNoOfTxn:</b> {{selectedGroup.eatNoOfTxn}} <br />
                    <b>obtMinTxnLim:</b> {{selectedGroup.obtMinTxnLim}}, <b>obtMaxTxnLim:</b> {{selectedGroup.obtMaxTxnLim}}, <b>obtDayTxnLim:</b> {{selectedGroup.obtDayTxnLim}}, <b>obtNoOfTxn:</b> {{selectedGroup.obtNoOfTxn}} <br />
                    <b>pbMinTxnLim:</b> {{selectedGroup.pbMinTxnLim}}, <b>pbMaxTxnLim:</b> {{selectedGroup.pbMaxTxnLim}}, <b>pbDayTxnLim:</b> {{selectedGroup.pbDayTxnLim}}, <b>pbNoOfTxn:</b> {{selectedGroup.pbNoOfTxn}}-->
                    <group-info appsgroupinfo="selectedGroup"></group-info>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div class="alert alert-danger" data-ng-show="appsGroupId > 0">
                    Kindly Change/Adjust the Internet Banking Package Before updating this request 
                </div>
                <button class="btn btn-primary" data-ng-click="updatePackage()">
                    <i class="glyphicon glyphicon-check"></i> Update Limit Package
                </button>
            </td>
        </tr>
    </table>    
    
</div>

<?php  
ci_add_js(asset_url()."angularjs/directives/dirPagination.js");
ci_add_js(asset_url()."app/directives/custom_directives.js");
ci_add_js(asset_url()."app/app_users.js");  
?>    

<script type="text/javascript" charset="utf-8">
    var app = app || {};
    app.userGroups = <?=json_encode($userGroups)?>;
    app.skyInfo = <?= json_encode($skyInfo)?>;
    app.requestedGroupInfo = <?= json_encode($requestedGroupInfo)?>;
    app.serviceId = <?=(int)$serviceId?>;
</script>




