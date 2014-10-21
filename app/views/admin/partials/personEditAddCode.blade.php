<div class="modal-header">
    <h3 class="modal-title">Add code</h3>
</div>
<div class="modal-body">
    <form class="form-horizontal" role="form">
        <div class="form-group">
            <label class="col-sm-2 control-label">code:</label>
            <div class="col-sm-10">
                <select class="form-control" ng-model="code" ng-options="item for item in codes"></select>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">Ok</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>