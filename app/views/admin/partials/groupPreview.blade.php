<div class="modal-header">
    <h3 class="modal-title">Group @{{ group.id }} - @{{ group.name }}</h3>
</div>
<div class="modal-body">
    <form class="form-horizontal" role="form">
        <div class="form-group">
            <label class="col-sm-2 control-label">name</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" ng-model="group.name">
            </div>
            <div class="col-sm-2"></div>
        </div>
        <hr>
        <div class="form-group" ng-repeat="user in group.users">
            <div class="col-sm-2">@{{ user.id }}</div>
            <div class="col-sm-8">
                @{{ user.username }}
            </div>
            <div class="col-sm-2">
                <button ng-click="" style="width: 34px;" class="btn btn-danger">x</button>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="save()">Save</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
    <button class="btn btn-warning" ng-click="load(group.id)"><span class="glyphicon glyphicon-refresh"></span></button>
</div>
