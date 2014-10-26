
<div class="row" ng-if="message">
    <div class="col-md-12">
        <div class="messages">
            <alert ng-if="message.error" type="danger" close="closeMessage()">@{{ message.error.message }}</alert>
            <alert ng-if="message.success" type="success" close="closeMessage()">@{{ message.success.message }}</alert>
        </div>
    </div>
</div>

<div class="row" ng-if="mode == 'index'">
    <div class="col-md-12">
        <button class="btn btn-default" ng-click="create()">{{ trans('admin_default.users.create') }}</button>
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="30%">username</th>
                    <th></th>
                    <th>Gruppen</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="user in users" ng-class="{ 'active': !user.activated }">
                    <td><a href ng-click="edit(user)">@{{ user.id }}</a></td>
                    <td>@{{ user.username }}</td>
                    <td>@{{ user.activated }}</td>
                    <td>
                        <span ng-repeat="group in user.groups | orderObjectBy:'name'">
                            <a href group-preview="group.id">@{{ group.name }}</a>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row" ng-if="mode == 'edit' || mode == 'create'">
    <div class="col-md-12">
        <form role="form" ng-submit="save()">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" ng-model="currentUser.username">
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" ng-model="currentUser.first_name">
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" ng-model="currentUser.last_name">
            </div>
            <div class="form-group">
                <label for="email">E-Mail:</label>
                <input type="email" class="form-control" ng-model="currentUser.email">
            </div>
            <div class="form-group">
                <label for="email">Password:</label>
                <input type="password" class="form-control" ng-model="currentUser.password">
            </div>
            <div class="form-group">
                <label for="email">Confirm password:</label>
                <input type="password" class="form-control" ng-model="currentUser.password_confirmation">
            </div>
            <div class="form-group">
                <label for="active">Can @{{ currentUser.username }} login?</label>
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default" ng-model="currentUser.activated" btn-radio="true">Yes</button>
                            <button type="button" class="btn btn-default" ng-model="currentUser.activated" btn-radio="false">No</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ trans('admin_default.submit') }}</button>
                <button class="btn btn-default" ng-click="cancel($event)">{{ trans('admin_default.cancel') }}</button>
                <button class="btn btn-danger" ng-click="delete($event)" ng-if="mode == 'edit'">{{ trans('admin_default.delete_action') }}</button>
            </div>
        </form>
    </div>
</div>
