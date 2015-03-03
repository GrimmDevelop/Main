<form class="form-horizontal" role="form" ng-submit="save()">
    <div class="modal-header">
        <h3 class="modal-title">Creating new letter</h3>
    </div>
    <div class="modal-body">
        <div class="alert" ng-show="message" ng-class="{'alert-danger': message.type=='danger', 'alert-success': message.type=='success'}">
            @{{ message.message }}
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Code</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" ng-model="letter.code" ng-pattern="/\d\d\d\d\d\d\d\d\.\d\d/">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Date</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" ng-model="letter.date" placeholder="e.g. around May 4th, 1810">
            </div>
        </div>
        <div class="form-group" ng-repeat="info in letter.information|orderObjectBy:'code'" ng-class="info.state == 'add' ? 'info-add' : (info.state == 'remove' ? 'info-remove' : '')">
            <label class="col-sm-2 control-label">@{{ info.code }}</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" ng-model="info.data" ng-readonly="info.state == 'remove'">
            </div>
            <div class="col-sm-2">
                <button ng-click="removeInformation(info)" style="width: 34px;" class="btn btn-danger">-</button>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-10 col-sm-1">
                <button ng-click="addCode()" style="width: 34px;" class="btn btn-success">+</button>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Save</button>
        <button class="btn btn-default" ng-click="cancel()">Cancel</button>
    </div>
</form>