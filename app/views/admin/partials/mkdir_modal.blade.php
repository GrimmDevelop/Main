<div class="modal-header">
            <h3>{{ trans('admin_default.create_folder') }}</h3>
        </div>
        <div class="modal-body">
            <p>{{ trans('admin_default.create_folder_desc') }}</p>
            <input type="text" class="form-control" ng-keyup="hitEnter($event)" placeholder="{{ trans('admin_default.folder_name_placeholder') }}" ng-model="fields.dirname" />
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" ng-click="ok()">{{ trans('admin_default.ok') }}</button>
            <button class="btn btn-warning" ng-click="cancel()">{{ trans('admin_default.cancel') }}</button>
        </div>