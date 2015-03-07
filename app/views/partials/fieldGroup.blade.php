<div class="well">
    @{{ group.properties.operator }}
    <div class="form-group row" ng-repeat="field in group.fields">
        <field-row ng-if="field.type == 'field'" field="field" codes="codes" on-remove="onRemove(field)"></field-row>
        <!--<field-group ng-if="field.type == 'group'" group="field" codes="codes"></field-group>-->
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary" ng-click="addField()" tooltip="add field"><span class="glyphicon glyphicon-plus"></span> Add Filter</button>
        <button type="button" class="btn btn-info" ng-click="addGroup()" tooltip="Add Group"><span class="glyphicon glyphicon-th-large"></span> Add Group</button>
    </div>
</div>