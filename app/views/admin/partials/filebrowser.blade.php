<div collapse="toggle">
    <div class="row">
        <div class="col-md-12 col-lg-12" browser-breadcrumb on-change="cd(path)"></div>
    </div>
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 15px;">
    <div class="filelist">
        <div class="row">
            <div class="col-sm-6 col-md-2 file" ng-repeat="file in files |bytype:types">
                <div ui-draggable="enableDragDrop" drag="file" ui-on-drop="move($data, file)" class="thumbnail" ng-class="{'selected': selected == file.path}" ng-if="file.isDir" ng-click="preview(file)" ng-dblclick="cd(file.path)">
                    <p>
                        <i style="font-size: 300%" class="glyphicon glyphicon-folder-open"></i>
                    </p>
                    <p>@{{ file.filename }}</p>
                </div>
                <div ui-draggable="enableDragDrop" drag="file" class="thumbnail" ng-class="{'selected': selected == file.path}" ng-if="file.isFile" ng-click="preview(file)">
                    <p>
                        <i style="font-size: 300%" class="glyphicon glyphicon-picture"></i>
                    </p>
                    <p>@{{ file.filename }}</p>
                </div>
            </div>
            <div class="no-files" ng-if="files.length === 0">
                <h3>{{ trans('admin_default.no_files') }}</h3>
                <p ng-if="enableDrapDrop">{{ trans('admin_default.no_files_desc') }}</p>
                <p ng-if="!enableDrapDrop">{{ trans('admin_default.no_files_and_drag_drop_desc') }}</p>
            </div>
        </div>
    </div>
    </div>
    </div>
</div>