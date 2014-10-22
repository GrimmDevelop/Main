<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-mail"></span> @{{ letter.id }} (date code: @{{ letter.code }})</h3>
</div>
<div class="modal-body">
    <h3>Informations</h3>
    <table class="table">
        <thead>
            <tr>
                <th>code</th>
                <th>data</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="info in letter.information">
                <td>@{{ info.code }}</td>
                <td>@{{ info.data }}</td>
            </tr>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-2">
            <a href location-preview="letter.from_id" class="btn btn-default form-control" ng-show="letter.from_id">
                 <span class="glyphicon glyphicon-map-marker"></span>
                 <span class="glyphicon glyphicon-arrow-right"></span>
                 <span class="glyphicon glyphicon-envelope"></span>
            </a>
            <a href person-preview="person.id" class="btn btn-default form-control" ng-repeat="person in letter.senders">
                <span class="glyphicon glyphicon-user"></span>
                <span class="glyphicon glyphicon-arrow-right"></span>
                <span class="glyphicon glyphicon-envelope"></span>
            </a>
        </div>
        <div class="col-md-2">
            <a href location-preview="letter.to_id" class="btn btn-default form-control" ng-show="letter.to_id">
                <span class="glyphicon glyphicon-envelope"></span>
                <span class="glyphicon glyphicon-arrow-right"></span>
                <span class="glyphicon glyphicon-map-marker"></span>
            </a>
            <a href person-preview="person.id" class="btn btn-default form-control" ng-repeat="person in letter.receivers">
                <span class="glyphicon glyphicon-envelope"></span>
                <span class="glyphicon glyphicon-arrow-right"></span>
                <span class="glyphicon glyphicon-user"></span>
            </a>
        </div>
        <div class="col-md-8"></div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
@if(Sentry::check() and Sentry::getUser()->hasAccess('users.edit'))
    <button class="btn btn-warning" letter-edit="letter.id">Edit</button>
@endif
</div>