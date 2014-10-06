<div class="modal-header">
    <h3 class="modal-title">@{{ location.name }}</h3>
</div>
<div class="modal-body">
    @{{ location.latitude }} @{{ location.longitude }}

    <google-map center="center" zoom="zoom">
        <marker idKey="location.id" coords="location"></marker>
    </google-map>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
</div>