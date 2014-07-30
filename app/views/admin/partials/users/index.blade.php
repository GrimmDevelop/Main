
<table class="table">
    <tr ng-repeat="user in users" ng-click="go('/users/' + user.id + '/edit')">
        <td>@{{ user.username }}</td>
        <td>@{{ user.activated }}</td>
    </tr>
</table>
