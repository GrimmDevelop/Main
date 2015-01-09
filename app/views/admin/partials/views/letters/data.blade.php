
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>id</th>
            <th>date</th>
            <th ng-repeat="field in fields">@{{ field }}</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="item in letters.data">
            <td><a href letter-edit="item.id">@{{ item.id }}</a></td>
            <td>@{{ item.date }}</td>
            <td ng-repeat="field in fields">
                <a href ng-click="editField(item.id, field)" ng-show="display.shortEdit"><span class="glyphicon glyphicon-pencil"></span></a>
                @{{ (item.information|filterCodeAndFill:fields)[field].join(', ') }}
            </td>
        </tr>
    </tbody>
</table>
