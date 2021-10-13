<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="table-responsive">
            <button type="button" class="btn btn-success btn-xs " data-ng-click="addTerm()" data-ng-show="sessions.length > 0">
                <i class="glyphicon glyphicon-plus"></i> Add Term
            </button>
            <table class="table table-bordered table-hover table-striped" style="font-size: 11px;margin-top: 10px">
                <thead>
                <tr class="bg-primary">
                    <th></th> 
                    <th>Session</th> 
                    <th>Term ID</th> 
                    <th>Term Name</th> 
                </tr>
                </thead>
                <tr data-ng-repeat="t in terms track by $index">
                    <td>
                        <button type="button" class="btn btn-danger btn-xs" data-ng-click="removeTerm($index)">
                            <i class="glyphicon glyphicon-trash"></i>
                        </button>                        
                    </td>
                    <td>
                        <select class="form-control" data-ng-model="t.sessionId" name="schoolTerms[sessionId][{{$index + 1}}]">
                            <option data-ng-repeat="s in sessions" value="{{s.sessionId}}">{{s.sessionName}}</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="schoolTerms[termId][{{$index + 1}}]" data-ng-model="t.termId" class="form-control" />
                    </td>
                    <td>
                        <input type="text" name="schoolTerms[termName][{{$index + 1}}]" data-ng-model="t.termName" class="form-control" />
                    </td>
                </tr>
            </table>
        </div>
    </div>    
</div>

