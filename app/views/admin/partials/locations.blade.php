
<div class="row" ng-if="mode == 'index'">
    <div class="col-md-12">
        <table class="table">
            <tr ng-repeat="location in locations" ng-click="show(location)">
                <td>@{{ location.id }}</td>
                <td>@{{ location.name }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="row" ng-if="mode == 'show'">
    <div class="col-md-12">
        <a ng-click="index()" class="btn btn-default">Back</a>
        <table>
            <tr>
                <td>@{{ currentLocation.name }}</td>
                <td>@{{ currentLocation.latitude }}</td>
                <td>@{{ currentLocation.longitude }}</td>
            </tr>
        </table>

        <google-map center="currentLocation" zoom="zoom"></google-map>
    </div>
</div>
