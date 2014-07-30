asdf

<table class="table">
    <tr ng-repeat="letter in letters" ng-click="go('/letters/' + letter.id + '/edit')">
        <td>@{{ letter.id }}</td>
        <td>@{{ letter.code }}</td>
    </tr>
</table>
