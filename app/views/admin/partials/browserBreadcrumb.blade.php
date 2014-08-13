<ol class="breadcrumb">
    <li><a href="#" ui-on-drop="onDrop({$data: $data, path: {path: '/'}})" ng-click="up($event, -1)">{{ trans('admin_default.breadcrumb_root') }}</a></li>
    <li ng-repeat="dir in cwd track by $index" ng-class="{'active' : $last}">
        <a href="#" ui-on-drop="onDrop({$data: $data, path: {path: buildToIndex($index)}})" ng-if="!$last" ng-click="up($event, $index)">@{{ dir }}</a>
        <span ng-if="$last" ui-on-drop="onDrop({$data: $data, path: {path: buildToIndex($index)}})">@{{ dir }}</span>
    </li>
</ol>