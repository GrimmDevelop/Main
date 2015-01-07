<div class="modal-header">
    <h3 class="modal-title">assign @{{ mode }} for letter - @{{ letter.id }}</h3>
</div>
<div class="modal-body">
    <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>
    <ul>
    <li ng-repeat="info in letter.information | filterCode:['absendeort', 'absort_ers']">
        <input type="text" class="form-control" ng-model="info.data" typeahead="location for location in typeSearch($viewValue)" typeahead-min-length="3">
        <span>
            <a href ng-click="search(info.data)"><span class="glyphicon glyphicon-search"></span></a>
        </span>
        <div ng-repeat="location in resultList">
            <a href ng-click="select(location)">@{{ location.name }} @{{ location.latitude }} @{{ location.longitude }}</a>
            <a href ng-click="show(location)"><span class="glyphicon glyphicon-map-marker"></span></a>
        </div>
    </li>
    </ul>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>