
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                    <th>module</th>
                    <th>description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href ng-click="go('/files')">{{ trans('admin_default.files_nav') }}</a></td>
                    <td>file browser used to upload files like location tables or dbf files</td>
                </tr>
                <tr>
                    <td><a href ng-click="go('/users')">{{ trans('admin_default.users_nav') }}</a></td>
                    <td>user browser used to create, update and delete users and thier permissions</td>
                </tr>
                <tr>
                    <td><a href ng-click="go('/letters')">{{ trans('admin_default.letters_nav') }}</a></td>
                    <td>letter browser used to edit importet letters and assign persons and locations</td>
                </tr>
                <tr>
                    <td><a href ng-click="go('/locations')">{{ trans('admin_default.locations_nav') }}</a></td>
                    <td>location browser used to look through imported locations and thier google map position</td>
                </tr>
                <tr>
                    <td><a href ng-click="go('/persons')">{{ trans('admin_default.persons_nav') }}</a></td>
                    <td>person browser used to look through imported persons and thier written letters</td>
                </tr>
                <tr>
                    <td><a href ng-click="go('/import')">{{ trans('admin_default.import_nav') }}</a></td>
                    <td>importer used to import letters or persons from dbf files or locations from txt files</td>
                </tr>
                <tr>
                    <td><a href ng-click="go('/export')">{{ trans('admin_default.export_nav') }}</a></td>
                    <td>exporter used to export letters, locations or persons from database to given formats</td>
                </tr>
                <tr>
                    <td><a href ng-click="go('/assign')">{{ trans('admin_default.assign_locations') }}</a></td>
                    <td>mass assigner used to assign locations and persons to mutiple letters at once automated</td>
                </tr>
                <tr>
                    <td><a href ng-click="go('/mailing')">{{ trans('admin_default.mailing') }}</a></td>
                    <td>mailing functions used to inform about changes</td>
                </tr>
            </tbody>
            <tfoot></tfoot>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="dashboard-statistics">
            <div class="panel panel-default">
                <div class="panel-heading">Running Tasks</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Progress</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="task in tasks">
                            <td>@{{ task.title }}</td>
                            <td job-progress the-progress="task.progress"></td>
                            <td><progressbar ng-if="task.status == 1" class="progress-striped active" value="100" type="success"></progressbar>
                                <progressbar ng-if="task.status == 2" class="progress-striped active" value="100" type="warning"></progressbar>
                                <progressbar ng-if="task.status == 0" class="progress-striped active" value="100" type="info"></progressbar>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
