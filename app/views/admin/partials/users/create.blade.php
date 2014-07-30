
<form role="form" ng-submit="save()">
    <div class="form-group">
        <label>username</label>
        <input type="text" class="form-control" ng-model="user.username" />
    </div>
    <div class="form-group">
        <label for="active">Can @{{ editData.username }} login?</label>
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group">
                    <button type="button" class="btn btn-default" ng-model="editData.activated" btn-radio="true">Yes</button>
                    <button type="button" class="btn btn-default" ng-model="editData.activated" btn-radio="false">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Speichern</button>
    </div>
</form>