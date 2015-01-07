<div class="modal-header">
    <h3 class="modal-title">Person @{{ person.id }} - @{{ person.name_2013 }}</h3>
</div>
<div class="modal-body">
    <form class="form-horizontal" role="form">
        <div class="form-group">
            <label class="col-sm-2 control-label">name (2013)</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" ng-model="person.name_2013">
            </div>
            <div class="col-sm-2"></div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">auto generated</label>
            <div class="col-sm-8 btn-group">
                <label class="btn btn-primary" ng-model="person.auto_generated" btn-radio="1">Yes</label>
                <label class="btn btn-primary" ng-model="person.auto_generated" btn-radio="0">No</label>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <hr>
        <div class="form-group" ng-repeat="info in person.information|orderObjectBy:'code'" ng-class="info.state == 'add' ? 'info-add' : (info.state == 'remove' ? 'info-remove' : '')">
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
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="save()">Save</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
    <button class="btn btn-warning" ng-click="load(person.id)"><span class="glyphicon glyphicon-refresh"></span></button>
</div>