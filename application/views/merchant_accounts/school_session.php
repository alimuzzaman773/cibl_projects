<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="table-responsive">
            <button type="button" class="btn btn-success btn-xs " data-ng-click="addSession()">
                <i class="glyphicon glyphicon-plus"></i> Add Session
            </button>
            <table class="table table-bordered table-hover table-striped" data-ng-show="sessions.length > 0" style="font-size: 11px;margin-top: 10px">
                <thead>
                <tr class="bg-primary">
                    <th></th> 
                    <th>Session Code</th> 
                    <th>Session Name</th> 
                </tr>
                </thead>
                <tr data-ng-repeat="s in sessions track by $index">
                    <td>
                        <button type="button" class="btn btn-danger btn-xs" data-ng-click="removeSession($index)">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>                        
                    </td>
                    <td>
                        <input type="text" name="schoolSession[sessionId][{{$index + 1}}]" data-ng-model="s.sessionId" class="form-control" />
                    </td>
                    <td>
                        <input type="text" name="schoolSession[sessionName][{{$index + 1}}]" data-ng-model="s.sessionName" class="form-control" />
                    </td>
                </tr>
            </table>
        </div>
    </div>    
</div>

