<div class="container">
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
            <h1 class="title-underlined">
                Edit Apps User
                <a href="<?php echo base_url() . 'client_registration/index'; ?>" class="btn btn-primary pull-right btn-sm">
                    <i class="glyphicon glyphicon-plus"></i> Apps User List
                </a>
            </h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row" style="margin-top: 20px;">
        <form name="form_data" id="form_data" ng-submit="saveItems()">
            <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
                <div class="doc_layout">
                    <div class="doc_header"><h4>Apps User Details (<b>{{eblSkyId}}</b>)</h4></div>
                    <div class="well doc_right_panel">
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>CIF ID</label>
                                    <input type="text" name="cfId" class="form-control input-sm" ng-model="cfId" placeholder="ex: 12345..."/>
                                    <input type="hidden" value="{{skyId}}">
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Client ID</label>
                                    <input type="text" name="clientId" class="form-control input-sm" ng-model="clientId" placeholder="ex: 12345..."/>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" name="userName" class="form-control input-sm" ng-model="userName" placeholder="ex: john"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Current Address</label>
                                    <textarea rows="4" class="form-control" ng-model="currAddress"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Permanent Address</label>
                                    <textarea rows="4" class="form-control" ng-model="parmAddress"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                <div class="form-group">
                                    <label>Billing Address</label>
                                    <textarea rows="4" class="form-control" ng-model="billingAddress"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-xs-12" data-ng-show="v_errors.length > 0">
                <br clear ="all" />
                <div class=" alert alert-danger" data-ng-bind-html="v_errors"></div>
                <br clear ="all" />
            </div>
            <div class="col-md-12 col-xs-12 col-sm-12">
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-check"></span> Update</button>
            </div>
        </form>
    </div>
</div>
