
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
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-tasks"></span> Running Tasks</h3></div>
                <div class="panel-body" ng-if="tasks.length == 0">
                    <h3 class="text-center">No Running Tasks!</h3>
                </div>
                <table class="table" ng-if="tasks.length > 0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Progress</th>
                            <th>Starter</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="task in tasks">
                            <td><a href="" ng-click="openTaskDetails(task)">@{{ task.title }}</a></td>
                            <td job-progress the-progress="task.progress"></td>
                            <td>@{{ task.starter.username }}</td>
                            <td><progressbar ng-if="task.status == 1" class="progress-striped active" value="task.percentage" type="success"></progressbar>
                                <progressbar ng-if="task.status == 2" class="progress-striped active" value="task.percentage" type="warning"></progressbar>
                                <progressbar ng-if="task.status == 0" class="progress-striped active" value="task.percentage" type="info"></progressbar>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/ng-template" id="progress-view-modal-content.html">
    <div class="modal-header">
        <h3 class="modal-title">Task: @{{ task.title }}</h3>
    </div>
    <div class="modal-body">
        <ul>
            <li ng-repeat="progress in task.progress">
                <strong>@{{ progress[0] }}</strong>
                <code>@{{ progress[1] }}</code>
            </li>
        </ul>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" ng-click="close()">Close</button>
    </div>
</script>