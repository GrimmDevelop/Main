<form class="form-horizontal" name="editForm" role="form" ng-submit="save()">
    <div class="modal-header">
        <h3 class="modal-title">Letter #@{{ letter.id }} - @{{ letter.date }}</h3>
    </div>
    <div class="modal-body">
        <div class="alert" ng-show="message" ng-class="{'alert-danger': message.type=='danger', 'alert-success': message.type=='success'}">
            @{{ message.message }}
        </div>

        <div class="form-group" ng-class="{'has-error': !editForm.code.$valid}">
            <label class="col-sm-2 control-label">Code</label>
            <div class="col-sm-8">
                <input class="form-control" name="code" type="text" ng-model="letter.code" ng-pattern="/^[0-9]{8}\.[0-9]{2}$/">
            </div>
            <div class="col-sm-2"></div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Date</label>
            <div class="col-sm-8">
                <input class="form-control" name="date" type="text" ng-model="letter.date">
            </div>
            <div class="col-sm-2"></div>
        </div>

        <hr>

        <div class="form-group" ng-repeat="info in letter.information|orderObjectBy:'code'" ng-class="info.state == 'add' ? 'info-add' : (info.state == 'remove' ? 'info-remove' : '')">
            <label class="col-sm-2 control-label">@{{ info.code }}</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" ng-model="info.data" ng-readonly="info.state == 'remove'">
            </div>
            <div class="col-sm-2">
                <button type="button" ng-click="removeInformation(info)" class="btn btn-danger"><span class="glyphicon glyphicon-minus"></span></button>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-10 col-sm-1">
                <button type="button" ng-click="addCode()" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span></button>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <div ng-repeat="sender in letter.senders">
                    <a href person-preview="sender.id">@{{ sender.name_2013 }}</a>
                </div>
            </div>
            <div class="col-md-6">
                <div ng-repeat="receiver in letter.receivers">
                    <a href person-preview="receiver.id">@{{ receiver.name_2013 }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-default" ng-click="cancel()">Cancel</button>
        <button type="button" class="btn btn-danger" ng-click="delete()">Delete</button>
        <button type="button" class="btn btn-warning" ng-click="load(letter.id)"><span class="glyphicon glyphicon-refresh"></span></button>
    </div>
</form>