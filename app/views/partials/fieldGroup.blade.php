<div class="well">
    @{{ group.properties.operator }}
    <select class="form-control" ng-model="group.properties.operator" ng-options="key as value for (key, value) in operators"></select>
    <div class="form-group row fields" ng-repeat="field in group.fields">
        <field-row field="field" codes="codes" on-remove="onRemove(field)"></field-row>
        <!--<field-group ng-if="field.type == 'group'" group="field" codes="codes"></field-group>-->
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary" ng-click="addField()" tooltip="add field"><span class="glyphicon glyphicon-plus"></span> Add Filter</button>
        <button type="button" class="btn btn-info" ng-click="addGroup()" tooltip="Add Group"><span class="glyphicon glyphicon-th-large"></span> Add Group</button>
    </div>
</div>