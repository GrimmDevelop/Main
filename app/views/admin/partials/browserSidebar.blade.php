<div class="sidebar-info" ng-if="file">
    <div ng-if="file.isFile">
        <h3>File: @{{ file.filename }}</h3>

        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                <div class="btn-group" style="margin: auto;">
                    <a ng-href="@{{ file.url }}" type="button" class="btn btn-default">{{ trans('admin_default.download_action') }}</a>
                    <button ng-click="deleteFile($event, file.path)" type="button" class="btn btn-default">{{ trans('admin_default.delete_action') }}</button>
                    <button ng-click="rename($event, file)" type="button" class="btn btn-default">{{ trans('admin_default.rename_action') }}</button>
                </div>
            </div>
        </div>

        <div class="row" ng-if="file.type.indexOf('image') === 0">
            <div class="col-md-12 preview">
                <img class="thumbnail" ng-src="@{{file.url}}" style="max-width: 80%; margin: auto;">
            </div>
        </div>
    </div>
    <div ng-if="file.isDir">
        <h3>Directory: @{{ file.filename }}</h3>

        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                <div class="btn-group" style="margin: auto;">
                    <a ng-href="@{{ file.url }}" type="button" class="btn btn-default">{{ trans('admin_default.archive_action') }}</a>
                    <button ng-click="deleteFolder($event, file.path)" type="button" class="btn btn-default">{{ trans('admin_default.delete_action') }}</button>
                    <button ng-click="rename($event, file)" type="button" class="btn btn-default">{{ trans('admin_default.rename_action') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>