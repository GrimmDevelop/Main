<div class="modal-header">
    <h3 class="modal-title">
        <div class="row"><div class="col-md-12">Person @{{ person.id }}</div></div>
        <div class="row">
            <div class="col-sm-11"><input type="text" class="form-control" ng-model="person.name_2013"></div>
            <div class="col-sm-1"><a href ng-click="saveName()" class="btn btn-default"><span class="glyphicon glyphicon-floppy-save"></span></a></div>
        </div>
    </h3>
</div>
<div class="modal-body">
    <form class="form-horizontal" role="form">
        <div class="form-group" ng-repeat="information in person.informations|orderObjectBy:'code'" ng-class="information.state == 'add' ? 'info-add' : (information.state == 'remove' ? 'info-remove' : '')">
            <label class="col-sm-2 control-label">@{{ information.code }}</label>
            <div class="col-sm-8">
                <input class="form-control" type="text" ng-model="information.data" ng-readonly="information.state == 'remove'">
            </div>
            <div class="col-sm-2">
                <button ng-click="removeInformation(information)" style="width: 34px;" class="btn btn-danger">-</button>
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