<div class="well group">
    <div class="group-top">
        <button type="button" ng-click="onRemove(group)" class="btn btn-danger btn-xs" tooltip="Remove Group" ng-disabled="notRemovable"><span class="glyphicon glyphicon-remove"></span></button>
    </div>
    <div class="form-group">
        <label for="operator" class="control-label col-md-2">Junction:</label>
        <div class="col-md-10">
            <select class="form-control" name="operator" ng-model="group.properties.operator" ng-options="key as value for (key, value) in operators"></select>
        </div>
    </div>

    <div class="form-group fields" ng-repeat="field in group.fields">
        <field-row field="field" codes="codes" on-remove="onFieldRemove(field)"></field-row>
    </div>
    <div class="form-group">
        <div class="col-md-10 col-md-offset-2">
            <button type="button" class="btn btn-primary" ng-click="addField()" tooltip="add field"><span class="glyphicon glyphicon-plus"></span> Add Filter</button>
            <button type="button" class="btn btn-info" ng-click="addGroup()" tooltip="Add Group"><span class="glyphicon glyphicon-th-large"></span> Add Group</button>
        </div>

    </div>
</div>