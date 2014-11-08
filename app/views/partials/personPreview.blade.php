<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-user"></span> @{{ person.name_2013 }}</h3>
</div>
<div class="modal-body">
    auto generated: @{{ person.auto_generated }}
    <div ng-show="person.information.length > 0">
        <h3>Information</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>code</th>
                    <th>data</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="info in person.information">
                    <td>@{{ info.code }}</td>
                    <td>@{{ info.data }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
@if(Sentry::check() and Sentry::getUser()->hasAccess('users.edit'))
    <button class="btn btn-warning" person-edit="person.id">Edit</button>
@endif
</div>