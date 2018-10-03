<h1 class="title-underlined">Notification Users</h1>
<table class="table table-bordered table-condensed">
    <thead>
        <tr class="bg-primary">
            <th colspan="4">Message Info</th>
        </tr>        
    </thead>
    <tr>
        <td style="width: 120px"><b>Headline</b></td>
        <td><?=$messageInfo->headLine?></td>
        <td style="width: 120px"><b>Body</b></td>
        <td><?=$messageInfo->body?></td>
    </tr>
    <tr>
        <td style="width: 120px"><b>Receivers</b></td>
        <td><?=$messageInfo->receivers?></td>
        <td style="width: 120px"><b>Completed?</b></td>
        <td><?=$messageInfo->completed?></td>
    </tr>
</table>


<div class="table-responsive" id="AppUserModule" data-ng-controller="AppUsersController as AppUsersController">

    <button data-ng-if="app_users.length > 0" data-ng-click="save()" class="btn btn-primary pull-right">
        Save
    </button>
    <table class="table table-striped table-bordered table-condensed" id="referenceTable">
        <thead>
            <tr class="bg-primary">
                <td>
                    Select All
                    <input type="checkbox" data-ng-model="isAllSelected" ng-click="toggleAll()" />
                </td>
                <th>SKY ID</th>
                <th>Apps ID</th>
                <th>User Name</th>
                <th>User Group</th>
                <th>Date of Birth</th>
                <th>Email</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tbody>
            <tr dir-paginate="a in app_users | itemsPerPage: per_page track by $index"
                total-items="totalCount" current-page="pagination.current">
                <td>
                    <input type="checkbox" data-ng-model="a.checked" data-ng-checked="a.checked == true" />
                </td>
                <td>{{a.skyId}}</td>
                <td>{{a.eblSkyId}}</td>
                <td>{{a.userName}}</td>
                <td>{{a.userGroupName}}</td>
                <td>{{a.dob}}</td>
                <td>{{a.userEmail}}</td>
                <td>{{a.gender}}</td>                
            </tr>
        </tbody>
    </table>
    <div class="box-footer clearfix text-center">
        <dir-pagination-controls on-page-change="pageChanged(newPageNumber)"
                                 template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
    </div>  
</div>

<?php
ci_add_js(asset_url()."angularjs/directives/dirPagination.js");  
ci_add_js(asset_url()."app/notification.js")  
?>    
<script>
var app = app || {};
app.messageInfo = <?=json_encode($messageInfo)?>;
</script>