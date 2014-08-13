<div flow-init="{target:'{{ url() }}/admin/files/upload', 'query':queryBuild}"
     flow-name="upload.flow"
     flow-file-added="fileAdded($event, $file)"
     flow-files-submitted="startUpload($event, $files)"
     flow-complete="uploadComplete()">
    <div class="row toolbar">
        <div class="col-md-3 col-lg-2">
            <div class="btn-group">
                <button class="btn btn-default" title="{{ trans('admin_default.upload_action') }}" flow-btn>
                    <span class="glyphicon glyphicon-arrow-up"></span>
                </button>
                <button class="btn btn-default" title="{{ trans('admin_default.new_dir_action') }}" ng-click="open_mkdir()">
                    <span class="glyphicon glyphicon-folder-close"></span>
                </button>
                <button class="btn btn-default" title="{{ trans('admin_default.archive_action') }}">
                    <span class="glyphicon glyphicon-envelope"></span>
                </button>
            </div>
            <div class="btn-group pull-right">
                <button type="button" class="btn btn-default" title="{{ trans('admin_default.up') }}" ng-click="cd('..')">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                </button>
            </div>
        </div>
        <div class="col-md-9 col-lg-10" browser-breadcrumb on-change="cd(path)" on-drop="move($data, path)">

        </div>
    </div>
    <div class="row">
        <div class="col-md-8 filelist" ng-class="{'dragging': dragging}" flow-drop flow-drag-enter="dragging=true" flow-drag-leave="dragging=false">
            <div class="row">
                <div class="col-sm-6 col-md-2 file" ng-repeat="file in files">
                    <div ui-draggable="true" drag="file" ui-on-drop="move($data, file)" class="thumbnail" ng-class="{'selected': selected == file.path}" ng-if="file.isDir" ng-click="preview(file)" ng-dblclick="cd(file.path)">
                        <p>
                            <i style="font-size: 300%" class="glyphicon glyphicon-folder-open"></i>
                        </p>
                        <p>@{{ file.filename }}</p>
                    </div>
                    <div ui-draggable="true" drag="file" class="thumbnail" ng-class="{'selected': selected == file.path}" ng-if="file.isFile" ng-click="preview(file)">
                        <p>
                            <i style="font-size: 300%" class="glyphicon glyphicon-picture"></i>
                        </p>
                        <p>@{{ file.filename }}</p>
                    </div>
                </div>
                <div class="no-files" ng-if="files.length === 0">
                    <h3>{{ trans('admin_default.no_files') }}</h3>
                    <p>{{ trans('admin_default.no_files_desc') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4" browser-sidebar
            delete-modal-title="{{ trans('admin_default.are_you_sure') }}"
            delete-file-text="{{ trans('admin_default.delete_file_text') }}"
            delete-directory-text="{{ trans('admin_default.delete_directory_text') }}">

        </div>
    </div>
    <div class="row uploads">
        <div class="col-md-12">
            <h3>{{ trans('admin_default.uploads') }}</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('admin_default.tbl_filename') }}</th>
                        <th>{{ trans('admin_default.tbl_path') }}</th>
                        <th>{{ trans('admin_default.tbl_filesize') }}</th>
                        <th>{{ trans('admin_default.tbl_filetype') }}</th>
                        <th>{{ trans('admin_default.tbl_upload_status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="file in upload.flow.files" ng-class="{'error': file.error, 'completed': file.isComplete()}">
                        <td>@{{file.name}}</td>
                        <td>@{{ file.virtualPath }}</td>
                        <td>@{{file.size | bytes }}</td>
                        <td>@{{file.getType()}}</td>
                        <td ng-switch="file.isComplete()">
                            <span ng-switch-when="false">
                                <progressbar max="100" value="file.progress() * 100" type="warning">@{{ file.progress() | percentage }} %</progressbar>
                            </span>
                            <span ng-switch-default>{{ trans('admin_default.done') }}</span>
                        </td>
                    </tr>
                    <tr ng-if="upload.flow.files.length === 0">
                        <td colspan="5" class="no-files">{{ trans('admin_default.no_uploads') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>