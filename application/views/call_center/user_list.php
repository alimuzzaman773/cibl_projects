<div class="row">
    <div class="col-sm-12">
        <h2 class="title-underlined ng-scope">User List </h2>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" id="resetModal" role="dialog" aria-labelledby="resetModal">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
      <div class="modal-header bg-primary">
          Please select the PIN Sending Channel
      </div>
          <div class="modal-body">
              <select class="form-control" data-ng-model="otp_channel_pin">
                  <option value="sms">SMS</option>
                  <option value="email">EMAIL</option>
              </select>
              
          </div>
          
      <div class="modal-footer">
          <button class="btn btn-primary" data-ng-click="sendPasswordResetPin(resetSkyId);">
              Confirm and send PIN
          </button>
      </div>
      </div>
  </div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="row">
        <div class="form-group col-sm-4 col-xs-6">
                <label>Search</label>
                <input type="text" class="form-control" data-ng-model="searchParams.search" placeholder="Search by skyid, esbid, name, cfid, clientid, prepaidid" />
            </div>
        <div class="form-group col-sm-4 col-xs-6">
            <label>Filter By:</label>
            <select class="form-control" data-ng-model="searchParams.filter_by">
                <option value="activation">Waiting Account Activation</option>
                <option value="passwordReset">Waiting Password Reset</option>
                <option value="activationPending24">Activation Pending More Than 24 Hours</option>
                <option value="passwordResetPending24">Password Reset Request Pending More Than 24 Hours</option>
            </select>
        </div>
        <div class="form-group col-xs-6 col-sm-2">
            <label style="display:block" class="hidden-xs">&nbsp;&nbsp;</label>
            <button class="btn btn-primary btn-sm" data-ng-click="getResultsPage(1)">
                <i class="glyphicon glyphicon-search"></i>
            </button>

            <button class="btn btn-primary btn-sm" data-ng-click="resetSearch();">
                <i class="glyphicon glyphicon-refresh"></i> Reset
            </button>
        </div>
    </div>
</div>

<div class="col-md-12 col-sm-12 text-right" data-ng-show="totalCount > 0">        
    <span class="label label-primary"> Displaying: {{ ((per_page * currentPageNumber) - (per_page - 1))}} to {{ upper_range()}} of {{ totalCount}}</span>            
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="table-responsive">        
            <table class="table table-bordered table-condensed table-striped table-hover" style="font-size: 12px">          
                <thead>
                    <tr class="bg-primary">
                        <th>SI#</th>
                        <th>Apps ID</th>
                        <th>CIF ID</th>
                        <th>Client ID</th>
                        <th>Prepaid ID</th>
                        <th>User Name</th>
                        <th>Father Name</th>
                        <th>User Email</th>
                        <th>User Mobile</th>
                        <th>Current Address</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th class="hidden">Remarks</th>
                        <th>Password Reset Request</th>
                        <th>Password Reset Wait Time</th>
                        <th>Activation Wait Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr dir-paginate="i in user_list | itemsPerPage: per_page track by $index" total-items="totalCount" current-page="pagination.current">
                        <td>{{(per_page * (currentPageNumber - 1)) + ($index + 1)}}</td>
                        <td>{{i.eblSkyId}}</td>
                        <td>{{i.cfId}}</td>
                        <td>{{i.clientId}}</td>
                        <td>{{i.prepaidId}}</td>
                        <td>{{i.userName}}</td>
                        <td>{{i.fatherName}}</td>
                        <td>{{i.userEmail}}</td>
                        <td>{{i.userMobNo1}}</td>
                        <td>{{i.currAddress}}</td>
                        <td>{{i.gender}}</td>
                        <td>{{i.dob}}</td>
                        <td class="hidden">{{i.remarks}}</td>
                        <td>
                            {{i.passwordReset > 0 ? 'Yes' : ''}}
                        </td>
                        <td>{{i.passwordReset > 0 ? convertToHour(i.passwordResetDiff) : ''}}</td>
                        <td>{{convertToHour(i.activationDiff)}}</td>
                        <td ng-class="{'bg-success' : (i.skyIdOriginal > 0), 'bg-primary' : (i.skyIdOriginal <= 0 || i.skyIdOriginal == null)}">
                            {{i.skyIdOriginal > 0 ? 'Activated' : 'Waiting Activation'}}
                        </td>
                        <td>
                            <div class="dropdown pull-right">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li data-ng-hide="i.skyIdOriginal > 0">
                                        <a href="<?= base_url() . "call_center/#/user_approve/" ?>{{i.skyId}}">
                                            <i class="glyphicon glyphicon-flash"></i> View Details and Activate
                                        </a>
                                    </li>
                                    <li data-ng-if="i.passwordReset == '1'">
                                        <a style="cursor: pointer" ng-click="showResetModal(i.skyId, 'pin_send');">
                                            <i class="glyphicon glyphicon-send"></i> Send Password Reset Pin
                                        </a>
                                    </li>                                    
                                    <li>
                                        <a style="cursor: pointer" ng-click="showResetModal(i.skyId, 'pin_resend');">
                                            <i class="glyphicon glyphicon-send"></i> Resend PIN
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr data-ng-show="user_list.length <= 0">
                        <td colspan="12">No data found</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <dir-pagination-controls on-page-change="pageChanged(newPageNumber)" template-url="<?= base_url() ?>assets/angularjs/directives/dirPagination.tpl.html"></dir-pagination-controls>
            </div>                
        </div>
    </div>
</div>