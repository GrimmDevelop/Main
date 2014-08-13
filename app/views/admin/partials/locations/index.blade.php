
<table class="table">
    <tr ng-repeat="location in locations" ng-click="go('/locations/' + location.id)">
        <td>@{{ location.id }}</td>
        <td>@{{ location.name }}</td>
    </tr>
</table>

<div flow-init="{target: '{{ url() }}/admin/upload'}"
     flow-files-submitted="$flow.upload()"
     flow-file-success="$file.msg = $message">
    
    <span class="btn btn-default" flow-btn>Upload File</span>


    <tr ng-repeat="file in $flow.files">
        <td>@{{ $index + 1 }}</td>
        <td>@{{ file.name }}</td>
    </tr>
</div>
