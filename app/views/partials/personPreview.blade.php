<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-user"></span> @{{ person.name_2013 }}</h3>
</div>
<div class="modal-body">
    auto generated: @{{ person.auto_generated }}
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
@if(Sentry::check() && Sentry::getUser()->hasAccess('users.edit'))
    <button class="btn btn-warning" ng-click="edit()">Edit</button>
@endif
</div>