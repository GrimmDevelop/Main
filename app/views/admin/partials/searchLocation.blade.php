<div class="modal-header">
    <h3 class="modal-title">search location</h3>
</div>
<div class="modal-body">
    <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>
    <ul>
    <li>
        <input type="text" ng-model="locationName" typeahead="location for location in typeSearch($viewValue)" typeahead-min-length="3">
        <span>
            <a href ng-click="search(locationName)"><span class="glyphicon glyphicon-search"></span></a>
        </span>

        <table class="table" ng-show="resultList.length > 0">
            <thead>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>coords.</th>
                    <th>Map</th>
                    <th>popul.</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="location in resultList | orderObjectBy:'population':1:'float'">
                    <td>@{{ location.id }}</td>
                    <td><a href ng-click="select(location)">@{{ location.name }}</a></td>
                    <td>@{{ location.latitude }} @{{ location.longitude }}</td>
                    <td><a href ng-click="show(location)"><span class="glyphicon glyphicon-map-marker"></span></a></td>
                    <td>@{{ location.population | population }}</td>
                </tr>
            </tbody>
        </table>
    </li>
    </ul>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>