<div class="modal-header">
    <h3 class="modal-title">Letter @{{ letter.id }} - @{{ letter.date }}</h3>
</div>
<div class="modal-body">
    <div class="alert" ng-show="message" ng-class="{'alert-danger': message.type=='danger', 'alert-success': message.type=='success'}">
        @{{ message.message }}
    </div>

    <form class="form-horizontal" role="form">
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
    </form>

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
    <button class="btn btn-primary" ng-click="save()">Save</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
    <button class="btn btn-warning" ng-click="load(letter.id)"><span class="glyphicon glyphicon-refresh"></span></button>
</div>