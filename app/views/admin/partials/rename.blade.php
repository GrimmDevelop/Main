<div class="modal-header">
    <h4 class="modal-title"><span class="glyphicon glyphicon-star"></span> {{ trans('admin_default.rename_file') }}</h4>
</div>
<div class="modal-body">
    <ng-form name="nameDialog" novalidate role="form">
        <div class="form-group input-group-lg">
            <label class="control-label" for="course">{{ trans('admin_default.new_name') }}:</label>
            <input type="text" class="form-control" name="destPath" id="destPath" ng-model="file.dest" ng-keyup="hitEnter($event)" required>
            <span class="help-block">{{ trans('admin_default.rename_help') }}</span>
        </div>
    </ng-form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" ng-click="cancel()">{{ trans('admin_default.cancel') }}</button>
    <button type="button" class="btn btn-primary" ng-click="save()">{{ trans('admin_default.submit') }}</button>
</div>