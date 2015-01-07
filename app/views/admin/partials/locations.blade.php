
<div class="row">
    <div class="col-md-2" style="margin: 20px 0;">
        <select class="form-control" ng-model="itemsPerPage" ng-change="reload(itemsPerPage, currentPage)" ng-options="option for option in itemsPerPageOptions"></select>
    </div>
    <div class="col-md-10">
        <pagination total-items="locations.total" ng-model="currentPage" ng-change="reload(itemsPerPage, currentPage)" items-per-page="locations.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <p>total: @{{ locations.total }}</p>
        <p>countries: @{{ locations.countries.join(', ') }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th width="20%">geo id</th>
                    <th>name</th>
                    <th width="10%">country</th>
                </tr>
            </thead>
            <tr ng-repeat="location in locations.data">
                <td><a href location-preview="location.id">@{{ location.id }}</a></td>
                <td>@{{ location.name }}</td>
                <td>@{{ location.country_code }}</td>
            </tr>
        </table>

        <pagination total-items="locations.total" ng-model="currentPage" ng-change="reload(itemsPerPage, currentPage)" items-per-page="locations.per_page"
            max-size="7" previous-text="&lsaquo;" next-text="&rsaquo;" first-text="&laquo;" last-text="&raquo;" boundary-links="true"></pagination>
    </div>
</div>
