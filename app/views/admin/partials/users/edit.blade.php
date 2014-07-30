
<a onclick="modelHelper.delete('asdf', 1)">Delete</a>


<form role="form" ng-submit="save()">
    <div class="form-group">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" ng-model="user.username">
    </div>
    <div class="form-group">
        <label for="first_name">First Name:</label>
        <input type="text" class="form-control" ng-model="user.first_name">
    </div>
    <div class="form-group">
        <label for="last_name">Last Name:</label>
        <input type="text" class="form-control" ng-model="user.last_name">
    </div>
    <div class="form-group">
        <label for="email">E-Mail:</label>
        <input type="email" class="form-control" ng-model="user.email">
    </div>
    <div class="form-group">
        <label for="email">Password:</label>
        <input type="password" class="form-control" ng-model="user.password">
    </div>
    <div class="form-group">
        <label for="email">Confirm password:</label>
        <input type="password" class="form-control" ng-model="user.password_confirmation">
    </div>
    <div class="form-group">
        <label for="active">Can @{{ user.username }} login?</label>
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group">
                    <button type="button" class="btn btn-default" ng-model="user.activated" btn-radio="true">Yes</button>
                    <button type="button" class="btn btn-default" ng-model="user.activated" btn-radio="false">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Speichern</button>
    </div>
</form>