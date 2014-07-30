
<table class="table">
    <tr ng-repeat="location in locations" ng-click="go('/locations/' + location.id)">
        <td>@{{ location.id }}</td>
        <td>@{{ location.name }}</td>
    </tr>
</table>

