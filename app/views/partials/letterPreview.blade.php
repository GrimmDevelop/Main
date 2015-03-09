<div class="modal-header">
    <h3 class="modal-title">
        Letter <strong>#@{{ letter.id }}</strong>
        <span ng-show="letter.from_id">from <strong>@{{ letter.from.name }}</strong></span>
        <span ng-show="letter.from_id">to <strong>@{{ letter.to.name }}</strong></span>
    </h3>
</div>
<div class="modal-body">
    <h3>Information</h3>
    <p><strong>Code:</strong> @{{ letter.code }}</p>
    <table class="table">
        <thead>
            <tr>
                <th>code</th>
                <th>data</th>
            </tr>
        </thead>
        <tbody>
            <tr ng-repeat="info in letter.information">
                <td>@{{ codes[info.code] }}</td>
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
            <a href person-preview="person.id" class="btn form-control" ng-class="{ 'btn-default': !person.auto_generated, 'btn-warning': person.auto_generated }" ng-repeat="person in letter.senders">
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
            <a href person-preview="person.id" class="btn form-control" ng-class="{ 'btn-default': !person.auto_generated, 'btn-warning': person.auto_generated }" ng-repeat="person in letter.receivers">
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
    <button class="btn btn-warning" letter-edit="letter.id" codes="codes">Edit</button>
@endif
</div>