<div class="modal-header">
    <h3 class="modal-title">search person</h3>
</div>
<div class="modal-body">
    <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>

    <input type="text" class="form-control" ng-model="personName" typeahead="location for location in typeSearch($viewValue)" typeahead-min-length="3">
    <span>
        <a href ng-click="search(personName)"><span class="glyphicon glyphicon-search"></span></a>
    </span>
    <div ng-repeat="person in resultList">
        <a href ng-click="select(person)">@{{ person.name }}</a>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-warning" ng-show="showCreateButton" ng-click="autoGenerate()">+</button>
    <button class="btn btn-primary" ng-click="ok()">OK</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>