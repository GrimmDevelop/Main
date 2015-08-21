
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th sorting-column="'id'" on-sort="reload()">ID</th>
            <th sorting-column="'code'" on-sort="reload()">Date</th>
            <th ng-repeat="field in fields" sorting-column="field" on-sort="reload()">
                @{{ letterInfo.codes[field] }}
            </th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="letter in letters.data" ng-show="!letter.deleted_at">
            <td><a href letter-edit="letter.id" codes="letterInfo.codes">@{{ letter.id }}</a></td>
            <td>@{{ letter.date }}</td>
            <td ng-repeat="field in fields" field-edit="letter" fields="letterInfo.codes" field="field" on-save="editField(letter, field)">
                @{{ (letter.information|filterCodeAndFill:fields)[field].join(', ') }}
            </td>
        </tr>
    </tbody>
</table>
