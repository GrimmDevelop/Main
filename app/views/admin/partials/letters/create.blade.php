<form class="form-horizontal" name="letterCreateForm" role="form" ng-submit="save()">
    <div class="modal-header">
        <h3 class="modal-title">Creating new letter</h3>
    </div>
    <div class="modal-body">
        <div class="alert" ng-show="message" ng-class="{'alert-danger': message.type=='danger', 'alert-success': message.type=='success'}">
            @{{ message.message }}
        </div>

        <div class="form-group" ng-class="{'has-error': !letterCreateForm.letterCode.$valid}">
            <label class="col-sm-2 control-label">Code</label>
            <div class="col-sm-8">
                <input type="text" ng-pattern="/^[0-9]{8}\.[0-9]{2}$/" ng-model="letter.code" name="letterCode" class="form-control ng-dirty ng-valid ng-valid-pattern">
                <span ng-show="!letterCreateForm.letterCode.$valid" class="help-block">Invalid format of the given letter code!</span>
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
                <button type="button" ng-click="removeInformation(info)" style="width: 34px;" class="btn btn-danger">-</button>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-10 col-sm-1">
                <button type="button" ng-click="addCode()" style="width: 34px;" class="btn btn-success">+</button>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" ng-click="cancel()">Cancel</button>
    </div>
</form>