<div class="col-md-2"><select ng-change="onChange()" class="form-control code-selector" ng-model="theField.code" ng-options="key as value for (key, value) in codes"></select></div>
<div class="col-md-2"><select ng-change="onChange()" class="form-control" ng-model="theField.compare">
        <option>equals</option>
        <option>contains</option>
        <option>starts with</option>
        <option>ends with</option>
    </select></div>
<div class="col-md-7">
    <input type="text" ng-change="onChange()" class="form-control" ng-model="theField.value" />
</div>
<div class="col-md-1">
    <button type="button" class="btn btn-danger" ng-click="removeHandler(theField)" tooltip="Remove Field"><span class="glyphicon glyphicon-minus"></span></button>
</div>