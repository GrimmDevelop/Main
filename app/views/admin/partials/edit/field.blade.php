<form class="form-horizontal" role="form" ng-submit="ok()">
    <div class="modal-header">
        <h3 class="modal-title">Edit @{{ fields[field] }}</h3>
    </div>
    <div class="modal-body">
            <div class="form-group" ng-repeat="(index, info) in object.information" ng-if="info.code == field" ng-class="{'has-error': info.data == ''}">
                <div class="col-sm-12">
                    <input class="form-control" ng-model="info.data" focus-on="input.field" ></select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button type="button" class="btn btn-success" ng-click="addField()"><span class="glyphicon glyphicon-plus"></span> Add value</button>
                </div>
            </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Ok</button>
        <button type="button" class="btn btn-default" ng-click="cancel()">Cancel</button>
    </div>
</form>