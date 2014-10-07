<div class="modal-header">
    <h3 class="modal-title">@{{ location.name }}</h3>
</div>
<div class="modal-body">
    @{{ location.latitude }} @{{ location.longitude }}

    <div ng-if="showMap">
        <google-map center="center" zoom="zoom">
            <marker idKey="location.id" coords="location"></marker>
        </google-map>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="ok()">OK</button>
</div>