<div class="modal-header">
    <h3 class="modal-title">Export letters</h3>
</div>
<div class="modal-body">
    <alert ng-if="message" type="@{{ message.type }}" close="closeMessage()">@{{ message.message }}</alert>

    <form class="form-horizontal" role="form">
        <tabset>
            <tab heading="Format">
                <p>Choose the output format type.</p>
                <div class="form-group">
                    <label class="col-sm-2">export format</label>
                    <div class="col-sm-10">
                        <select class="form-control">
                            <option>csv</option>
                        </select>
                    </div>
                </div>
            </tab>
            <tab heading="Fields">
                <div ng-repeat="code in codes" class="checkbox">
                    <label><input type="checkbox"> @{{ code }}</label>
                </div>
            </tab>
        </tabset>
    </form>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">Export</button>
    <button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>