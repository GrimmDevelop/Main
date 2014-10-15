
<div class="row" ng-if="mode == 'index'">
    <div class="col-md-12">
        <div class="col-md-2" style="margin: 20px 0;">
            <select class="form-control" ng-model="itemsPerPage" ng-change="reload(itemsPerPage, currentPage)" ng-options="option for option in itemsPerPageOptions"></select>
        </div>

        <pagination total-items="locations.total" ng-model="currentPage" ng-change="reload(itemsPerPage, currentPage)" items-per-page="locations.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>

        <p>total: @{{ locations.total }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th width="20%">geo id</th>
                    <th>name</th>
                    <th width="10%">country</th>
                </tr>
            </thead>
            <tr ng-repeat="location in locations.data" ng-click="show(location)">
                <td>@{{ location.id }}</td>
                <td>@{{ location.name }}</td>
                <td>@{{ location.country_code }}</td>
            </tr>
        </table>

        <pagination total-items="locations.total" ng-model="currentPage" ng-change="reload(itemsPerPage, currentPage)" items-per-page="locations.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
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

        <google-map center="currentLocation" zoom="zoom">
            <marker idKey="currentLocation.id" coords="currentLocation"></marker>
        </google-map>
    </div>
</div>
