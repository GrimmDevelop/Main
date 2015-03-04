<div class="col-md-2 control-label"><select class="form-control code-selector" ng-model="theField.code" ng-options="key as value for (key, value) in codes"></select></div>
<div class="col-md-2"><select class="form-control" ng-model="theField.compare">
        <option>equals</option>
        <option>contains</option>
        <option>starts with</option>
        <option>ends with</option>
    </select></div>
<div class="col-md-7">
    <input type="text" class="form-control" ng-model="theField.value" typeahead="value for value in fieldTypeahead($viewValue, theField)" />
</div>
<div class="col-md-1">
    <button type="button" class="btn btn-danger" ng-click="onRemove(theField)" tooltip="remove field"><span class="glyphicon glyphicon-minus"></span></button>
</div>