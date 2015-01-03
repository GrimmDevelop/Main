
<table class="table">
    <thead>
        <tr>
            <th>id</th>
            <th>date</th>
            <th ng-repeat="field in fields">@{{ field }}</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="item in letters.data">
            <td>@{{ item.id }}</td>
            <td>@{{ item.date }}</td>
            <td ng-repeat="field in fields">@{{ (item.information|filterCodeAndFill:fields)[field].join(', ') }}</td>
        </tr>
    </tbody>
</table>
