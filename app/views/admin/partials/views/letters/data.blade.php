
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th sorting-column="'id'" on-sort="reload()">ID</th>
            <th sorting-column="'code'" on-sort="reload()">Date</th>
            <th ng-repeat="field in fields" sorting-column="field" on-sort="reload()">
                <a href ng-click="editColumn(field, $event)" ng-show="display.shortEdit"><span class="glyphicon glyphicon-pencil"></span></a>
                @{{ letterInfo.codes[field] }}
            </th>
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
